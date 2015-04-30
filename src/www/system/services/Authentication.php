<?php
/*
 | -------------------------------------------------------------------------
 | Hash Method (sha1 or bcrypt)
 | -------------------------------------------------------------------------
 | Bcrypt is available in PHP 5.3+
 |
 | IMPORTANT: Based on the recommendation by many professionals, it is highly recommended to use
 | bcrypt instead of sha1.
 |
 | NOTE: If you use bcrypt you will need to increase your password column character limit to (80)
 |
 | Below there is "default_rounds" setting.  This defines how strong the encryption will be,
 | but remember the more rounds you set the longer it will take to hash (CPU usage) So adjust
 | this based on your server hardware.
 |
 | If you are using Bcrypt the Admin password field also needs to be changed in order login as admin:
 | $2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36
 |
 | Becareful how high you set max_rounds, I would do your own testing on how long it takes
 | to encrypt with x rounds.
 */

namespace services;

class AuthenticationException extends \Exception{}

class Authentication {

    private $identity;
    private $password;
	private $remember;
	private $hash_method;				// IMPORTANT: Make sure this is set to either sha1 or bcrypt
	private $default_rounds = 8;		// This does not apply if random_rounds is set to true
	private $random_rounds  = FALSE;
	private $min_rounds     = 5;
	private $max_rounds     = 9;
	private $rounds;
	private $bcrypt;
	private $salt_length  	= 10;

	public function __construct($identity = '', $password = '', $remember=false, $hash_method='bcrypt') {
        //if identity is the email.. implementing only email for now`
		if (!empty($identity)) {
			$this->identity = filter_var(stripslashes(trim($identity)), FILTER_SANITIZE_EMAIL);
        }
		if (!empty($password)) {
            $this->password= $password;
        }
        $this->remember= $remember;
		$this->hash_method = $hash_method;
		if ($this->hash_method == 'bcrypt') {
			if ($this->random_rounds){
				$rand = rand($this->min_rounds,$this->max_rounds);
				$this->rounds = array('rounds' => $rand);
			}else{
				$this->rounds = array('rounds' => $this->default_rounds);
			}
		}
		$this->bcrypt = new \helpers\Bcrypt($this->rounds);
    }

    public function getUser(){

        if(!filter_var($this->identity, FILTER_VALIDATE_EMAIL)){
            return FALSE;
        }

        $user = \Model::factory('User')->where('email', $this->identity); //\ORM::for_table('cms_user')->where('email',$this->identity);
        if ($user->count()==0){
            return FALSE;
        }

        return $user->find_one();
    }

	public function getIdentity(){
		return $this->identity;
	}

	public function getPassword(){
		return $this->password;
	}

	public function isEmpty(){
		if(empty($this->identity) || empty($this->password))
			return true;
		return false;
	}

    public function logout()
    {
        unset($_SESSION['user'] );
        setcookie("login_remember", "", time()-3600);
    }

	/**
		Login function checks against user database for username then
		checks password wish encryption
	*/
	public function login()
	{
        if( isset($_COOKIE['login_remember']) )
        {
            $credentials = explode( '|', $_COOKIE['login_remember'] );
            $this->identity = $credentials[0];
            $user = $this->getUser();

            if( $user && $user->remember_code == $credentials[1] ) {
                $user->last_login = time();
                $user->remember_code = $this->key();
                setcookie('login_remember', implode('|', array($this->identity,$user->remember_code)), time()+60*60*24*365 );
                $user->save();
                $_SESSION['user'] =  $user->as_array();
                return $user;
            }
        }

        $user = $this->getUser();
        if( !$user ) {
            throw new AuthenticationException("User not found");
        }

        if( $user->active == 0 ) {
            throw new AuthenticationException("Account is not active");
        }

		if( !$this->validate_hash_password($user->password, $user->salt) ) {
            throw new AuthenticationException("Unvalid password");
		}

        //Success!
        $user->last_login = time();

        if( $this->remember ) {
            $user->remember_code = $this->key();
            setcookie('login_remember', implode('|', array($this->identity,$user->remember_code)), time()+60*60*24*365 );
        }

        $user->save();
        $_SESSION['user'] =  $user->as_array();

        return $user;

	}

    /**
     * This function takes a password and validates it
     * against an entry in the users table.
     *
     * @return bool
     **/
    public function validate_hash_password($hashed_password){
        if (empty($this->password) || empty($hashed_password)){
            return FALSE;
        }
        if ($this->bcrypt->verify($this->password,$hashed_password)){
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Hashes the password to be stored in the database.
     *
     * @return void
     * @author Mathew
     **/
    public function hash_password($password)
    {
        if (empty($password)){
            return FALSE;
        }
        //bcrypt
        return $this->bcrypt->hash($password);
    }

    /**
     * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
     *
     * @return void
     * @author Mathew
     **/
    public function hash_sha1($key, $salt)
    {
        if (empty($key)){
            return FALSE;
        }
        return  $salt . substr(sha1($salt . $key), 0, -$this->salt_length);
    }

    public function key()
    {
        $activation_code_part = openssl_random_pseudo_bytes(128);  //php >= 5.3
        for($i=0;$i<1024;$i++) {
            $activation_code_part = sha1($activation_code_part . mt_rand() . microtime());
        }
        return $this->hash_sha1($activation_code_part.$this->identity, $this->salt());
    }

	/**
	 * Generates a random salt value.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function salt()
	{
		return substr(md5(uniqid(rand(), true)), 0, 10); //10 is salt length
	}

    /**
    This function checks to see if the email is already in the database
    If email is not in the database, it is available and function returns true
     */
    public function email_available()
    {
        $user = \ORM::for_table('cms_user')->where('email',$this->identity);
        return ($user->count()==0);
    }

	/**
		This function creates a user 
	*/
	public function createUser($first_name, $last_name, $company = '', $phone = '', $group='admin'){
		if(!filter_var($this->identity, FILTER_VALIDATE_EMAIL)){
            throw new AuthenticationException("Email is not valid");
		}
		if(!filter_var($first_name, FILTER_SANITIZE_STRING)){
            throw new AuthenticationException("First name is not valid");
		}
		if(!filter_var($last_name, FILTER_SANITIZE_STRING)){
            throw new AuthenticationException("Last name is not valid");
		}
		if(!empty($company) && !filter_var($company, FILTER_SANITIZE_STRING)){
            throw new AuthenticationException("Company name is not valid");
		}
		if(!empty($phone) && !filter_var($phone, FILTER_SANITIZE_STRING)){
            throw new AuthenticationException("Phone is not valid");
		}
		if(!$this->email_available()){
            throw new AuthenticationException("Email is already in use!");
		}
		
		$newUser = \ORM::for_table('cms_user')->create();
		$newUser->ip_address = $_SERVER['REMOTE_ADDR'];
		$newUser->username = $this->identity;
		$newUser->password = $this->hash_password($this->password);
		$newUser->salt = $this->salt();
		$newUser->email = $this->identity;
		$newUser->created_on = time();
		$newUser->last_login = time();
		$newUser->first_name = $first_name;
		$newUser->last_name = $last_name;
		$newUser->phone = $phone;
		$newUser->company = $company;
		$newUser->role = $group;
		$newUser->active = 1;
        $newUser->save();

		return TRUE;
	}

	public function fogottenPassword() {

		if($this->email_available()){
            throw new AuthenticationException("Email does not exist");
		}

        $key = $this->key();

		$user = $this->getUser();
		$user->forgotten_password_code = $key;
		$user->forgotten_password_time = time();
        $user->save();

		$message = "password recovery\r\ncode: $key\r\nLine 3";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");
		
		// Send
        return mail($this->identity, 'password recovery', $message);
	}

}