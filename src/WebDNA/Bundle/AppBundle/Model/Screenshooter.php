<?php

namespace WebDNA\Bundle\AppBundle\Model;

/**
 * Class Screenshooter
 * @package WebDNA\Bundle\AppBundle\Model
 */
class Screenshooter
{
    /**
     * @var
     */
    protected $screenshotService;

    /**
     * @var
     */
    protected $filesystemService;

    /**
     * @param $screenshotService
     */
    public function __construct($screenshotService, $filesystemService)
    {
        $this->screenshotService = $screenshotService;
        $this->filesystemService = $filesystemService;
    }

    /**
     * Capture screenshot via external service and save into filesystem.
     *
     * @param $url
     * @param $path
     * @param int $retries
     * @param int $interval
     * @return bool
     */
    public function capture($url, $path, $retries = 20, $interval = 3)
    {
        $result = false;

        try {
            $request = $this->screenshotService->screenshot_create(array(
                'url' => $url,
                'size' => 'large',
                'delay' => 2,
            ));

            if ($request->status != 'error') {
                for ($i = 0; $i < $retries; $i++) {
                    $screenshot = $this->screenshotService->screenshot_info($request->id);

                    if ($screenshot->status == 'finished') {
                        $this->filesystemService->write($path, file_get_contents($screenshot->screenshot_url));

                        $result = true;

                        break;
                    }

                    sleep($interval);
                }
            }
        } catch (\Exception $e) {
        }

        return $result;
    }
}
