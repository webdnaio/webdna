<?php

namespace WebDNA\Bundle\AppBundle\Uploader\Naming;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;

/**
 * Class AnalysisInputUploadNamer
 * @package WebDNA\Bundle\AppBundle\Uploader\Naming
 */
class AnalysisInputUploadNamer implements NamerInterface
{
    /**
     * @param FileInterface $file
     * @return string
     */
    public function name(FileInterface $file)
    {
        return $this->generatePath($file->getClientOriginalName());
    }

    public function generatePath($filename)
    {
        return sprintf(
            '%d/%d/%d/%s_%s',
            date("Y"),
            date("m"),
            date("d"),
            uniqid(),
            $filename
        );
    }
}
