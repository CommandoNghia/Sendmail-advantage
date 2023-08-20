<?php

namespace App\Services\Aws;

use Aws\AwsClient;
use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;

/**
 * Class AwsBase.
 *
 * @package App\Services\Aws
 */
class AwsBase
{
    /**
     * AWS client
     *
     * @var $client AwsClient
     */
    protected AwsClient $client;

    /**
     * AWS region
     *
     * @var $region string
     */
    protected string $region;

    /**
     * Check env is local
     *
     * @var $isLocal boolean
     */
    protected $isLocal;

    /**
     * AwsBase constructor.
     */
    public function __construct()
    {
        $this->isLocal = app()->environment('local');
        $this->region = config('filesystems.disks.s3.region');
    }

    /**
     * Get IAM roles config connect AWS S3.
     *
     * @param array $config
     *
     * @return array
     */
    protected function getIamRoleConfig(array $config = []): array
    {
        // Use the default credential provider
        $credentials = CredentialProvider::defaultProvider();

        $defaultConfig = [
            'credentials' => $credentials,
            'region' => $this->region,
            'version' => 'latest'
        ];

        return array_merge($defaultConfig, $config);
    }

    /**
     * Get local config connect AWS S3.
     *
     * @return array
     */
    protected function getLocalS3Config(): array
    {
        $endpoint = config('filesystems.disks.s3.endpoint');
        $credentials = new Credentials(
            config('filesystems.disks.s3.key'),
            config('filesystems.disks.s3.secret'),
            config('filesystems.disks.s3.token'),
            config('filesystems.disks.s3.expiration')
        );

        $defaultConfig = [
            'credentials' => $credentials,
            'region' => $this->region,
            'version' => 'latest',
        ];

        if (!empty($endpoint)) {
            $defaultConfig['endpoint'] = $endpoint;
        }

        return $defaultConfig;
    }
}
