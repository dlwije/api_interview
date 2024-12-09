<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class FileImport extends Model
{
    const secretKey = '@#12$%&dW';

    use HasFactory, SoftDeletes;

    protected $fillable = ['email', 'first_name', 'last_name', 'tags', 'ip'];

    public function getEmailAttribute()
    {
        return self::decryptData($this->attributes['email']);
    }

    public function setEmailAttribute()
    {
        return self::encryptData($this->attributes['email']);
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
