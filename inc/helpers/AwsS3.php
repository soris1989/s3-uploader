<?php

namespace Inc\Helpers;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Symfony\Component\Dotenv\Dotenv;

class AwsS3 extends CodeMessage
{
    private ?string $access_key_id;
    private ?string $secret_access_key;
    private ?string $region;
    private ?string $version;
    private ?string $bucket;
    private S3Client $s3;

    public function __construct()
    {
        $this->access_key_id = $_ENV['S3_ACCESS_KEY_ID'];
        $this->secret_access_key = $_ENV['S3_SECRET_ACCESS_KEY'];
        $this->version = $_ENV['S3_VERSION'];
        $this->region = $_ENV['S3_REGION'];
        $this->bucket = $_ENV['S3_BUCKET'];

        // Instantiate an Amazon S3 client 
        $this->s3 = new S3Client([
            'credentials' => [
                'key'    => $this->access_key_id,
                'secret' => $this->secret_access_key,
            ],
            'version' => $this->version,
            'region'  => $this->region
        ]);
    }

    public function getObjectUrl(string $key)
    {
        return $this->s3->getObjectUrl($this->bucket, $key);
    }

    public function getPresignedObjectUrl(string $key, string $expiry = '+6 hours')
    {
        $file_name = basename($key);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $cmd = $this->s3->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key,
            'ResponseContentType' => ext2mime($file_ext),
            // 'ResponseContentDisposition' => 'inline; filename="' . $this->safe_urlencode($file_name) . '"',
        ]);

        $request = $this->s3->createPresignedRequest($cmd, $expiry);

        // Get the actual presigned-url
        $presigned_url = (string)$request->getUri();
        return $presigned_url;
    }

    public function listObjects(string $folder = '', $max_keys = null)
    {
        $params = [
            'Bucket' => $this->bucket,
            'Prefix' => $folder,
            'Delimiter' => '/',
        ];

        if ($max_keys) {
            $params['MaxKeys'] = $max_keys;
        }

        try {
            return $this->s3->listObjectsV2($params);
        } catch (S3Exception $e) {
            $this->setCodeMessage(0, $e->getMessage());
            return false;
        }
    }

    public function putObject(string $key, string $file_path)
    {
        $this->resetCodeMessage();

        try {
            $result = $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $key,
                'SourceFile' => $file_path
            ]);

            return $result;
        } catch (S3Exception $e) {
            $this->setCodeMessage(0, $e->getMessage());
            return false;
        }
    }

    public function putBase64Object(string $key, string $file_base64, string $file_mime)
    {
        $this->resetCodeMessage();

        try {
            $result = $this->s3->putObject([
                'Body' => $file_base64,
                'Bucket' => $this->bucket,
                'Key' => $key,
                'ContentType' => $file_mime,
            ]);

            return $result;
        } catch (S3Exception $e) {
            $this->setCodeMessage(0, $e->getMessage());
            return false;
        }
    }

    public function deleteObject(string $key)
    {
        try {
            $result = $this->s3->deleteObject(array(
                'Bucket' => $this->bucket,
                'Key'    => $key
            ));

            return $result;
        } catch (S3Exception $e) {
            $this->setCodeMessage(0, $e->getMessage());
            return false;
        }
    }

    // private function safe_urlencode(string $str)
    // {
    //     // Skip all URL reserved characters plus dot, dash, underscore and tilde..
    //     $result = preg_replace_callback(
    //         "/[^-\._~:\/\?#\\[\\]@!\$&'\(\)\*\+,;=]+/",
    //         function ($match) {
    //             // ..and encode the rest!  
    //             return rawurlencode($match[0]);
    //         },
    //         $str
    //     );
    //     return ($result);
    // }
}
