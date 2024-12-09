<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    const secretKey = '@#12$%&dW';
//    private $secretKey = '';
    public function successResponse(){
        return response()->json(
            [

            ]
        );
    }

    public static function encryptData($data_set){
        $data = $data_set;
        $encryptionMethod = "AES-256-CBC"; // You can choose other encryption algorithms like AES-128-CBC, AES-192-CBC, etc.
        $secretKey = self::secretKey; // Replace with your secret key (keep this secret)
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encryptionMethod));
        $encryptedData = openssl_encrypt($data, $encryptionMethod, $secretKey, 0, $iv);

        return base64_encode($iv . $encryptedData);
    }

    public static function decryptData($encrypted_pass_data){

        $encryptedDataWithIV = $encrypted_pass_data; // Replace with your encrypted data
        $encryptionMethod = "AES-256-CBC"; // Same as used for encryption
        $secretKey = self::secretKey; // Same as used for encryption
        $encryptedData = base64_decode($encryptedDataWithIV);
        $ivSize = openssl_cipher_iv_length($encryptionMethod);
        $iv = substr($encryptedData, 0, $ivSize);
        $encryptedText = substr($encryptedData, $ivSize);

        return openssl_decrypt($encryptedText, $encryptionMethod, $secretKey, 0, $iv);
    }
}
