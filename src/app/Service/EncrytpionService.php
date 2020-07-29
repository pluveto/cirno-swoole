<?php
namespace App\Service;

use Hyperf\Config\Annotation\Value;



/**
 * 参考 https://8gwifi.org/rsafunctions.jsp
 */
class EncrytpionService{

    /**
     * 公钥
     * @Value("keys.public")
     */
    private string $publicKey;

    /**
     * 私钥
     * @Value("keys.private")
     */
    private string $privateKey;


    /**
     * 解码，失败返回 null
     *
     * @param string $subject
     * @return object|null
     */
    public function decryptBase64(string $subject):?object
    {
        return json_decode(self::decrypt($subject, $this->privateKey));
    }

    public function encryptBase64($subject):string
    {
        return self::encrypt($subject, $this->publicKey);
    }

    public function privateEncryptBase64($subject):string
    {
        return self::privateEncrypt($subject, $this->privateKey);
    }

    /**
     * Set the value of privateKey
     *
     * @return  self
     */
    public function setPrivateKey(string $privateKey): EncrytpionService
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Set the value of publicKey
     *
     * @return  self
     */
    public function setPublicKey(string $publicKey): EncrytpionService
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    private static function decrypt(string $subject, string $privateKey): string
    {
        $decrypted  = '';
        $subject = base64_decode($subject);
        $ret = openssl_private_decrypt($subject, $decrypted, $privateKey);
        if (!$ret) {
            echo "error: " . openssl_error_string() . "\n";
        }
        return $decrypted;
    }

    private static function encrypt(string $subject, string $publicKey): string
    {
        $encrypted = '';
        openssl_public_encrypt($subject, $encrypted, $publicKey);
        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }

    private static function privateEncrypt(string $subject, string $privateKey): string
    {
        $encrypted = '';
        openssl_private_encrypt($subject, $encrypted, $privateKey);
        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }
}