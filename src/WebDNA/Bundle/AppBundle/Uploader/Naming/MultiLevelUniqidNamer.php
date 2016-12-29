<?php

namespace WebDNA\Bundle\AppBundle\Uploader\Naming;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;

/**
 * Class MultiLevelUniqidNamer
 * @package WebDNA\Bundle\AppBundle\Uploader\Naming
 */
class MultiLevelUniqidNamer implements NamerInterface
{
    /**
     * @param FileInterface $file
     * @return string
     */
    public function name(FileInterface $file)
    {
        $checksum = crc32($file->getClientOriginalName());
        $splits = str_split($checksum, 3);

        return sprintf(
            '%d/%d/%d/%s_%s',
            $splits[0],
            $splits[1],
            $splits[2],
            uniqid(),
            $file->getClientOriginalName()
        );
    }
}
