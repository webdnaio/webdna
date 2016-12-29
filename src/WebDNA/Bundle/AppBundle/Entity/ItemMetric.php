<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemMetric
 *
 * @ORM\Table(
 *     name="item_metric",
 *     indexes={
 *         @ORM\Index(columns={"analysis_process_id", "type"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\ItemMetricRepository")
 */
class ItemMetric extends Base\ItemMetric
{
    /**
     * Types constants.
     */
    const TYPE_DOMAIN_WHOIS_METRIC = 1;
    const TYPE_DOMAIN_MOZ_METRIC = 3;
    const TYPE_URL_INTERNET_ARCHIVE_METRIC = 2;
    const TYPE_DOCUMENT_HTML_TEXT_STRUCTURE_METRIC = 100;
    const TYPE_DOCUMENT_HTML_META_METRIC = 101;
    const TYPE_DOCUMENT_HTML_META_SOCIAL_METRIC = 102;
    const TYPE_PAGE_LINKS_DIRECTIONS_METRIC = 200;
    const TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC = 201;
    const TYPE_DOCUMENT_LINKS_DISTRIBUTION_METRIC = 202;
    const TYPE_DOCUMENT_WEBSITE_LINKS_DISTRIBUTION_METRIC = 203;
    const TYPE_DOCUMENT_EXTERNAL_LINKS_DISTRIBUTION_METRIC = 204;
    const TYPE_URL_PERFORMANCE_METRIC = 300;
    const TYPE_URL_SECURITY_METRIC = 400;

    /**
     * @var array
     */
    public static $FIELDS = array(
        self::TYPE_DOMAIN_WHOIS_METRIC => array(
            'value_numeric_1' => 'domain_whois_created',
            'value_numeric_2' => 'domain_whois_changed',
            'value_numeric_3' => 'domain_whois_expires',
            'value_numeric_4' => null,
            'value_numeric_5' => null,
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_DOMAIN_MOZ_METRIC => array(
            'value_numeric_1' => 'domain_moz_domain_authority',
            'value_numeric_2' => 'domain_moz_mozrank',
            'value_numeric_3' => null,
            'value_numeric_4' => null,
            'value_numeric_5' => null,
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_URL_INTERNET_ARCHIVE_METRIC => array(
            'value_numeric_1' => 'domain_internet_archive_created',
            'value_numeric_2' => null,
            'value_numeric_3' => null,
            'value_numeric_4' => null,
            'value_numeric_5' => null,
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_DOCUMENT_HTML_TEXT_STRUCTURE_METRIC => array(
            'value_numeric_1' => 'document_html_length',
            'value_numeric_2' => 'document_text_length',
            'value_numeric_3' => 'document_text_to_html_ratio',
            'value_numeric_4' => null,
            'value_numeric_5' => null,
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_DOCUMENT_HTML_META_METRIC => array(
            'value_numeric_1' => 'document_html_meta_title_length',
            'value_numeric_2' => 'document_html_meta_description_length',
            'value_numeric_3' => 'document_html_meta_keyword_length',
            'value_numeric_4' => null,
            'value_numeric_5' => null,
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_DOCUMENT_HTML_META_SOCIAL_METRIC => array(
            'value_numeric_1' => 'document_html_meta_social_open_graph_count',
            'value_numeric_2' => 'document_html_meta_social_twitter_cards_count',
            'value_numeric_3' => null,
            'value_numeric_4' => null,
            'value_numeric_5' => null,
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_PAGE_LINKS_DIRECTIONS_METRIC => array(
            'value_numeric_1' => null,
            'value_numeric_2' => null,
            'value_numeric_3' => null,
            'value_numeric_4' => null,
            'value_numeric_5' => null,
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC => array(
            'value_numeric_1' => 'document_links_count',
            'value_numeric_2' => 'document_links_attr_follow_count',
            'value_numeric_3' => 'document_links_attr_nofollow_count',
            'value_numeric_4' => 'document_website_links_attr_follow_count',
            'value_numeric_5' => 'document_website_links_attr_nofollow_count',
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_DOCUMENT_LINKS_DISTRIBUTION_METRIC => array(
            'value_numeric_1' => 'document_link_distribution_range_1',
            'value_numeric_2' => 'document_link_distribution_range_2',
            'value_numeric_3' => 'document_link_distribution_range_3',
            'value_numeric_4' => 'document_link_distribution_range_4',
            'value_numeric_5' => 'document_link_distribution_range_5',
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_DOCUMENT_WEBSITE_LINKS_DISTRIBUTION_METRIC => array(
            'value_numeric_1' => 'document_website_link_distribution_range_1',
            'value_numeric_2' => 'document_website_link_distribution_range_2',
            'value_numeric_3' => 'document_website_link_distribution_range_3',
            'value_numeric_4' => 'document_website_link_distribution_range_4',
            'value_numeric_5' => 'document_website_link_distribution_range_5',
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_DOCUMENT_EXTERNAL_LINKS_DISTRIBUTION_METRIC => array(
            'value_numeric_1' => 'document_external_link_distribution_range_1',
            'value_numeric_2' => 'document_external_link_distribution_range_2',
            'value_numeric_3' => 'document_external_link_distribution_range_3',
            'value_numeric_4' => 'document_external_link_distribution_range_4',
            'value_numeric_5' => 'document_external_link_distribution_range_5',
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_URL_PERFORMANCE_METRIC => array(
            'value_numeric_1' => 'document_performance_total_time',
            'value_numeric_2' => 'document_performance_speed_download',
            'value_numeric_3' => null,
            'value_numeric_4' => null,
            'value_numeric_5' => null,
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
        self::TYPE_URL_SECURITY_METRIC => array(
            'value_numeric_1' => 'document_security_google_safe_browsing',
            'value_numeric_2' => null,
            'value_numeric_3' => null,
            'value_numeric_4' => null,
            'value_numeric_5' => null,
            'value_text_1' => null,
            'value_text_2' => null,
            'value_text_3' => null,
            'value_text_4' => null,
            'value_text_5' => null,
        ),
    );
}
