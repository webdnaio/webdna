<?php

namespace WebDNA\Bundle\AppBundle\Model\Crawler\Link;

/**
 * Class LinkList
 * @package WebDNA\Bundle\AppBundle\Model\Crawler\Link
 */
class LinkList extends \SplDoublyLinkedList
{
    /**
     * Filter links belongs to given domain.
     *
     * @param string $domain
     * @param bool $strict
     * @return LinkList
     */
    public function filterDomainLinks($domain, $strict = false)
    {
        $domain = mb_strtolower($domain, 'UTF-8');

        return $this->filter(function ($link) use ($domain, $strict) {
            return $strict
                // Strict case
                ? $link->getDomain() === $domain
                // Subdomains case
                : preg_match('/^(.*\.)?' . $domain . '/', $link->getDomain());
        });
    }

    /**
     * @return LinkList
     */
    public function filterFollowLinks()
    {
        return $this->filter(function ($link) {
            return $link->isFollow();
        });
    }

    /**
     * @return LinkList
     */
    public function filterNoFollowLinks()
    {
        return $this->filter(function ($link) {
            return !$link->isFollow();
        });
    }

    /**
     *
     * @param string $originDomain
     * @param bool $strict
     * @return LinkList
     */
    public function filterExternalLinks($originDomain, $strict = false)
    {
        $originDomain = mb_strtolower($originDomain, 'UTF-8');

        return $this->filter(function ($link) use ($originDomain, $strict) {
            $linkDomain = $link->getDomain();

            return $strict
                // Strict case
                ? $linkDomain && $linkDomain !== $originDomain
                // Subdomains case
                : $linkDomain && preg_match('/^(.*\.)?' . $originDomain . '/', $linkDomain) === 0;
        });
    }

    /**
     * @return LinkList
     */
    protected function filter($callback)
    {
        $list = new static();

        foreach ($this as $link) {
            if ($callback($link)) {
                $list->push($link);
            }
        }

        return $list;
    }
}
