<?php

namespace helpers;

use RuntimeException;

/**
 * This class provides a simple wrapper around the Blowfish cipher.
 *
 * This class will throw (immediately on load) on PHP versions prior to 5.3.7, which
 * had a broken implementation of the Blowfish algorithm (and/or would fall back to DES.)
 *
 * http://www.php.net/security/crypt_blowfish.php
 */
class Bcrypt
{
    /** @type int salt length required for Blowfish algorithm */
    const SALT_LENGTH = 16;

    /** @type string path to the dev/urandom device on Linux */
    const DEV_URANDOM = '/dev/urandom';

    /** @type string minimum PHP version with proper Blowfish support */
    const MIN_PHP_VERSION = '5.3.7';

    /**
     * @var int cryptographic cost of the Blowfish algorithm
     */
    private $_cost;

    /**
     * @param int $cost cost (iteration count) for the underlying Blowfish-based hashing algorithm (range 4 to 31)
     *
     * @throws RuntimeException for invalid $cost
     */
    public function __construct($cost = 10)
    {
        if ($cost < 4 || $cost > 31) {
            throw new RuntimeException("invalid strength - please use a number between 4 and 31");
        }

        $this->_cost = $cost;

        if (@CRYPT_BLOWFISH != 1) {
            if (!function_exists('crypt')) {
                throw new RuntimeException("Blowfish encryption is unavailable on this system");
            }
        }

        if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION) < 0) {
            throw new RuntimeException(
                "Blowfish encryption is broken or unavailable on php versions prior to " . self::MIN_PHP_VERSION
            );
        }
    }

    /**
     * @param string $value value (for example, a password in plain text)
     *
     * @return string salted value hash (which can be checked with {@see check()})
     */
    public function hash($value)
    {
        return crypt($value, $this->getSalt());
    }

    /**
     * @param string $value value (for example, password in plain text)
     * @param string $hash  salted value hash (previously created with {@see hash()})
     *
     * @return bool true, if the value matches the hash
     */
    public function check($value, $hash)
    {
        return $hash === crypt($value, $hash);
    }

    /**
     * @return string a salt compatible with crypt() - configured for the Blowfish algorithm
     */
    private function getSalt()
    {
        return '$2y$' . sprintf('%02d', $this->_cost)
        . '$' . strtr(substr(base64_encode($this->getEntropy(self::SALT_LENGTH)), 0, 22), '+', '.');
    }

    /**
     * @param int $length entropy length (in bytes)
     *
     * @return string entropy
     * @throws RuntimeException
     */
    private function getEntropy($length)
    {
        // mcrypt provides the best/safest entropy on systems when available:

        if (function_exists('mcrypt_create_iv')) {
            return mcrypt_create_iv($length);
        }

        // on Linux, the dev/urandom device provides good entropy:

        if (@is_readable(self::DEV_URANDOM)) {
            $random = fopen(self::DEV_URANDOM, 'rb');

            if ($random) {
                $entropy = fread($random, $length);
                fclose($random);
                return $entropy;
            }
        }

        // on other systems, we'll make do with pseudo-random numbers and PID:

        $entropy = '';

        $seed = mt_rand();

        if (function_exists('getmypid')) {
            $seed .= getmypid();
        }

        while (strlen($entropy) < $length) {
            $seed = sha1(mt_rand() . $seed, true);
            $entropy .= sha1($seed, true);
        }

        return substr($entropy, 0, $length);
    }
}