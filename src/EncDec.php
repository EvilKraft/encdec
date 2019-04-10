<?php
/**
 * Created by PhpStorm.
 * User: Kraft
 * Date: 10.04.2019
 * Time: 12:17
 */

namespace EvilKraft\encdec;


class EncDec
{
    private static $method  = 'aes-256-cbc';
    private static $hashAlg = 'sha3-512';

    // Create The First Key
    public static function genFirstKey(){
        return base64_encode(openssl_random_pseudo_bytes(32));
    }

    // Create The Second Key
    public static function genSecondKey(){
        return base64_encode(openssl_random_pseudo_bytes(64));
    }

    public static function securedEncrypt($data)
    {
        $first_key = base64_decode(FIRSTKEY);
        $second_key = base64_decode(SECONDKEY);

        $iv_length = openssl_cipher_iv_length(self::$method);
        $iv = openssl_random_pseudo_bytes($iv_length);

        $first_encrypted = openssl_encrypt($data, self::$method, $first_key, OPENSSL_RAW_DATA , $iv);
        $second_encrypted = hash_hmac(self::$hashAlg, $first_encrypted, $second_key, true);

        $output = base64_encode($iv.$second_encrypted.$first_encrypted);
        return $output;
    }

    public static function securedDecrypt($input)
    {
        $first_key = base64_decode(FIRSTKEY);
        $second_key = base64_decode(SECONDKEY);
        $mix = base64_decode($input);

        $iv_length = openssl_cipher_iv_length(self::$method);

        $iv = substr($mix,0,$iv_length);
        $second_encrypted = substr($mix,$iv_length,64);
        $first_encrypted = substr($mix,$iv_length+64);

        $data = openssl_decrypt($first_encrypted, self::$method, $first_key,OPENSSL_RAW_DATA, $iv);
        $second_encrypted_new = hash_hmac(self::$hashAlg, $first_encrypted, $second_key, true);

        if (hash_equals($second_encrypted,$second_encrypted_new)){
            return $data;
        }

        return false;
    }

    public static function obfus($filename_in = 'test.php', $filename_out = 'test_out.php', $codeName = 'Class/Code NAME'){
        $filename_in  = realpath($filename_in);
        $filename_out = realpath($filename_out);


        $sData = file_get_contents($filename_in);
        $sData = str_replace(array('<?php', '<?', '?>'), '', $sData); // Strip PHP open/close tags

        $sObfusationData = new \Obfuscator($sData, $codeName);

        echo '<pre>'.print_r($filename_in, true).'</pre>';
        echo '<pre>'.print_r($filename_out, true).'</pre>';
        echo '<pre>'.print_r($sObfusationData, true).'</pre>';

        file_put_contents($filename_out, '<?php ' . "\r\n" . $sObfusationData);
    }
}