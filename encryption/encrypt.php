<?php 
$key = 'password to (en/de)crypt';
$string = 'test string to be encrypted / seconde'; // note the spaces

$siv = mcrypt_create_iv(
    mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC),
    MCRYPT_DEV_URANDOM
);


$encrypted = base64_encode(
    $siv .
    mcrypt_encrypt(
        MCRYPT_RIJNDAEL_256,
        hash('sha256', $key, true),
        $string,
        MCRYPT_MODE_CBC,
        $siv
    )
);

$data = base64_decode($encrypted);

$eiv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC));

$decrypted = rtrim(
    mcrypt_decrypt(
        MCRYPT_RIJNDAEL_256,
        hash('sha256', $key, true),
        substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)),
        MCRYPT_MODE_CBC,
        $eiv
    ),
    "\0"
);

echo 'Encrypted:' .$siv. "\n";
var_dump($encrypted); // "ey7zu5zBqJB0rGtIn5UB1xG03efyCp+KSNR4/GAv14w="

echo "\n";

echo 'Decrypted:' .$eiv. "\n";
var_dump($decrypted); // " string to be encrypted "

?>