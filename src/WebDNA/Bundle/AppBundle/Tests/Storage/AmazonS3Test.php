<?php

namespace WebDNA\Bundle\AppBundle\Tests\Storage;

use Aws\S3\Exception\S3Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AmazonS3Test
 * @package WebDNA\Bundle\AppBundle\Tests\Sybilla
 */
class AmazonS3Test extends WebTestCase
{

    /**
     * AmazonS3 classification test
     */
    public function testUploadFile()
    {
        $client = static::createClient(
            array(
                'environment' => 'test',
                'debug' => false,
            )
        );

        $container = $client->getContainer();

        $s3 = $container->get('storage.aws_s3.client');

        $bucket_name = 'webdnastorage';
        $key = '/test/object1';
        $tmp_filename = '/tmp/aws_test_1';

        try {
            file_put_contents($tmp_filename, 'test1' . PHP_EOL . time());
            $resource = fopen($tmp_filename, 'r');
            $s3->upload($bucket_name, $key, $resource, 'private');
        } catch (S3Exception $e) {
            echo "There was an error uploading the file" . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
            return false;
        }

        return true;
    }
}
