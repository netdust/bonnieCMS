<?php
/**
 * Created by netdust.be.
 * Date: 29/01/14
 * Time: 19:13
 
 |         ,--.           |        . . .         |                   |    
|,---.    |   |.   .,---.|---     | | |,---.    |--- ,---..   .,---.|--- 
||   |    |   ||   |`---.|        | | ||---'    |    |    |   |`---.|    
``   '    `--' `---'`---'`---'    `-'-'`---'    `---'`    `---'`---'`---'

 */


namespace services;


class Upload {

    protected static $options = array(
        "maxsize"   => 30000000,
        "allowed"   => array(
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/png'
        ),
        "hash"      =>true,
        "override"  =>true
    );

    protected static function uploadDir($directoryName)
    {
        if (!file_exists($directoryName) && !is_dir($directoryName)) {
            $createFolder = mkdir("" . $directoryName, 0666, true);
            if (!$createFolder) {
                throw new \Exception("Folder " . $directoryName . " could not be created");
            }
        }
        return $directoryName;
    }

    protected  static function validType( $file, $options ) {
        //check type
        return ( in_array($file["type"], $options['allowed']) && !empty($file["type"]) );
    }

    protected  static function validSize( $file, $options ) {
        // check size
        return ( $file["size"] < $options['maxsize'] && $file["size"] != 0  );
    }

    protected  static function validImage( $file ) {
        // check size to see if image is real
        return getimagesize($file['tmp_name']);
    }

    protected  static function validUpload( $file ) {
        // check upload
        return is_uploaded_file($file['tmp_name']);
    }

    protected  static function rename( $file, $options ) {

    }

    public static function send($file, $folder, $options=array()) {

        $options = array_merge( self::$options, $options );

        //Сheck that we have a file
        if( !empty($file) && $file['error'] == 0 )
        {
            if( !self::validSize($file, $options) )
            {
                throw new \FileUploadException( "Filesize " .$file["size"]. " not accepted for upload" );
            }

            if( !self::validType($file, $options) )
            {
                throw new \FileUploadException( "Filetype " .$file["type"]. " not accepted for upload" );
            }

            if (strpos($file["type"], 'image') !== false) {
                if (!self::validImage($file)) {
                    throw new \FileUploadException( "Image not accepted for upload" );
                }
            }

            // check if it a real upload
            if( !self::validUpload($file) ) {
                throw new \FileUploadException( "File is not accepted for upload" );
            }

            // move file
            $filename = basename($file['name']);
            $hashname = $filename;
            if( $options['hash'] ) {
                $ext = substr($filename, strrpos($filename, '.') + 1);
                $hashname = md5($filename) . '.'.$ext;
            }

            $folder  = self::uploadDir( $options['folder'].$folder );
            $newname = $folder.$hashname;

            if (!file_exists($newname) || $options['override'] ) {
                if ((move_uploaded_file($file['tmp_name'],$newname))){
                    return array(
                        'label' => $filename,
                        'name' => $hashname,
                        'file' => $newname
                    );
                } else {
                    throw new \FileUploadException( "A problem occurred during upload!" );
                }
            } else {
                throw new \FileUploadException( "File already exists" );
            }

        } else {
            throw new \FileUploadException( 'No file uploaded.' );
        }
    }

}