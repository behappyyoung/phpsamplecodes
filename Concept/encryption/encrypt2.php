<?php
/**
 * Created by PhpStorm.
 * User: young
 * Date: 3/25/14
 * Time: 11:12 AM
 */

$security_questions = array(
    '1'=>' What is your first car ?',
    '2' =>'What is your favorite color ?',
    '3' =>'What is your favorite vacation place  ?',
    '4' =>'What is your pet\'s name ?'
);

$passkey  = 'young honda encrypt key';

function getEncrypt($string){
    $iv = mcrypt_create_iv(  mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC),  MCRYPT_DEV_URANDOM);
    $encrypted = base64_encode($iv .mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256,
            hash('sha256', $GLOBALS['passkey'], true),
            $string, MCRYPT_MODE_CBC, $iv )
    );
    return $encrypted;
}

function getDecrypt($string){
    $data = base64_decode($string);

    $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC));

    $decrypted = rtrim(
        mcrypt_decrypt(
            MCRYPT_RIJNDAEL_256,
            hash('sha256', $GLOBALS['passkey'], true),
            substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)),
            MCRYPT_MODE_CBC,
            $iv
        ),
        "\0"
    );
    return $decrypted;

}

$code = getEncrypt('young1');
echo $code;
$return = getDecrypt($code);
echo $return;

