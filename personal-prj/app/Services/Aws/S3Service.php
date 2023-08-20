<?php

namespace App\Services\Aws;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Aws\AwsClient;

/**
 * Class S3Service.
 *
 * @package App\Services\Aws
 */
class S3Service extends AwsBase
{
    /**
     * AWS client
     *
     * @var $client S3Client
     */
    protected AwsClient $client;

    /**
     * AWS S3 bucket name
     *
     * @var $bucket string
     */
    protected string $bucket;

    /**
     * S3Service constructor.
     *
     * @param string|null $bucket
     * @param string|null $region
     */
    public function __construct(string $bucket = null, string $region = null)
    {
        parent::__construct();
        $this->bucket = !empty($bucket) ? $bucket : config('filesystems.disks.s3.bucket');

        if (!empty($region)) {
            $this->region = $region;
        }

        $this->initClient();
    }

    /**
     * Get config by environment.
     *
     * @return array
     */
    private function getS3Config(): array
    {
        if ($this->isLocal) {
            return $this->getLocalS3Config();
        }

        return $this->getIamRoleConfig();
    }

    /**
     * Init S3Client with config.
     *
     * @return void
     */
    private function initClient()
    {
        $config = $this->getS3Config();

        $this->client = new S3Client($config);
    }

    /**
     * Get presigned URL for upload object
     *
     * @param string      $key
     * @param array       $options
     * @param string|null $expires
     *
     * @return string
     */
    public function putObjectWithPresignedRequest(string $key, array $options = [], string $expires = null): string
    {
        $expires = $expires ?? config('filesystems.disks.s3.expires_put_object');
        $args = array_merge([
            'Bucket' => $this->bucket,
            'Key' => $key,
        ], $options);
        $cmd = $this->client->getCommand('PutObject', $args);
        $request = $this->client->createPresignedRequest($cmd, $expires);

        return (string) $request->getUri();
    }

    /**
     * Generate Presigned URL of file key
     *
     * @param string      $key
     * @param string|null $expires
     *
     * @return null|string
     */
    public function getObjectWithPresignedRequest(string $key, string $expires = null): ?string
    {
        $fileExist = $this->client->doesObjectExist($this->bucket, $key);

        if (!$fileExist) {
            return null;
        }

        $expires = $expires ?? config('filesystems.disks.s3.expires_get_object');
        $cmd = $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);

        $request = $this->client->createPresignedRequest($cmd, $expires);

        return (string) $request->getUri();
    }
}
