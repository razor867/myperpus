<?php

if (!function_exists('encode')) {

    function encode($string)
    {
        $encrypter = service('encrypter');
        return bin2hex($encrypter->encrypt($string));
    }
}

if (!function_exists('decode')) {

    function decode($string)
    {
        $encrypter = service('encrypter');
        return $encrypter->decrypt(hex2bin($string));
    }
}
