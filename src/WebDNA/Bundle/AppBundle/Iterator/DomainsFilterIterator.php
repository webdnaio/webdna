<?php

namespace WebDNA\Bundle\AppBundle\Iterator;

use WebDNA\Bundle\AppBundle\Entity\Website;

class DomainsFilterIterator extends \FilterIterator
{
    private $cachedWebsites;
    private $systemUser;
    private $websiteService;

    public function __construct(\Iterator $iterator, $systemUser, $websiteService)
    {
        parent::__construct($iterator);
        $this->systemUser = $systemUser;
        $this->websiteService = $websiteService;
    }

    public function accept()
    {
        // Ensure existing website object associated with analyzed url
        $domain = parse_url($this->current(), PHP_URL_HOST);
        if ($this->isCachedUserWebsiteByName($domain) === false) {
            return true;
        }
        return false;
    }

    /**
     * Helper method for more efficient retrieving objects.
     *
     * @todo For future: add cache size limit, if needed
     *
     * @param string $domain
     * @return boolean
     */
    protected function isCachedUserWebsiteByName($domain)
    {
        // Get from local cache array of fetch object
        // from service and put into cache
        if (isset($this->cachedWebsites[$domain])) {
            return true;
        } else {
            $website = $this->websiteService->findUserWebsiteByName($domain, $this->systemUser);

            if ($website instanceof Website) {
                $this->cachedWebsites[$domain] = $website;
                return true;
            }
        }

        return false;
    }
}
