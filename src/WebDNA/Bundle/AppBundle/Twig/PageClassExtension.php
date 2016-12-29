<?php

namespace WebDNA\Bundle\AppBundle\Twig;

use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;

class PageClassExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('page_class_name', array($this, 'nameFilter')),
            new \Twig_SimpleFilter('page_class_similarity_name', array($this, 'similarityNameFilter')),
            new \Twig_SimpleFilter('page_class_css_label', array($this, 'cssLabelFilter')),
            new \Twig_SimpleFilter('page_class_similarity_css_label', array($this, 'cssSimilarityLabelFilter')),
            new \Twig_SimpleFilter('page_class_css_link_label', array($this, 'cssLinkLabelFilter')),
            new \Twig_SimpleFilter('page_class_changed_css_icon', array($this, 'changedCssIconFilter'))

        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('page_class_css_label', array($this, 'cssLabelFilter')),
            new \Twig_SimpleFunction('page_class_changed_label', array($this, 'changedLabelFunction')),
            new \Twig_SimpleFunction('page_class_changed_css_label', array($this, 'changedCssLinkLabelFunction')),
        );
    }

    /**
     * @param float $class_positive_similarity
     * @return mixed
     */
    public function similarityNameFilter($class_positive_similarity)
    {
        foreach (ItemAnalysis::$CLASS_RANGES as $state => $range) {
            if ($class_positive_similarity >= $range[0] && $class_positive_similarity <= $range[1]) {
                return ItemAnalysis::$CLASSES[$state];
            }
        }

        return ItemAnalysis::$CLASSES[ItemAnalysis::CLASS_POSITIVE];
    }

    /**
     * @param int $class_id
     * @return mixed
     */
    public function nameFilter($class_id)
    {
        if (isset(ItemAnalysis::$CLASSES[$class_id])) {
            return ItemAnalysis::$CLASSES[$class_id];
        } else {
            return ItemAnalysis::$CLASSES[ItemAnalysis::CLASS_UNKNOWN];
        }
    }

    /**
     * @param float $class_positive_similarity
     * @return mixed
     */
    public function cssSimilarityLabelFilter($class_positive_similarity)
    {
        foreach (ItemAnalysis::$CLASS_RANGES as $state => $range) {
            if ($class_positive_similarity >= $range[0] && $class_positive_similarity <= $range[1]) {
                return $this->cssLabelFilter($state);
            }
        }

        return $this->cssLabelFilter(ItemAnalysis::CLASS_POSITIVE);
    }

    /**
     * @param int $class_id
     * @return mixed
     */
    public function cssLabelFilter($class_id)
    {
        switch ($class_id) {
            case ItemAnalysis::CLASS_POSITIVE:
                return 'success';
            case ItemAnalysis::CLASS_SUSPICIOUS:
                return 'warning';
            case ItemAnalysis::CLASS_NEGATIVE:
                return 'danger';
            default:
                return 'default';
        }
    }

    /**
     * @param int $class_id
     * @return mixed
     */
    public function cssLinkLabelFilter($class_id)
    {
        switch ($class_id) {
            case ItemAnalysis::CLASS_POSITIVE:
                return 'plus';
            case ItemAnalysis::CLASS_SUSPICIOUS:
                return '';
            case ItemAnalysis::CLASS_NEGATIVE:
                return 'minus';
            default:
                return '';
        }
    }

    /**
     * @param int $class_id
     * @return string
     */
    public function changedCssLinkLabelFunction($class_id)
    {
        return $this->cssLinkLabelFilter($class_id);
    }

    /**
     * @param int $class_id
     * @return string
     */
    public function changedCssIconFilter($class_id)
    {
        switch ($class_id) {
            case ItemAnalysis::CLASS_POSITIVE:
                return 'fa fa-thumbs-o-up';
            case ItemAnalysis::CLASS_SUSPICIOUS:
                return 'fa fa-question-circle';
            case ItemAnalysis::CLASS_NEGATIVE:
                return 'fa fa-thumbs-o-down';
            default:
                return '';
        }
    }

    /**
     * @param array $classes
     * @return string
     */
    public function changedLabelFunction($classes)
    {
        if ($classes['classSystem'] != $classes['class']) {
            return "(changed from " . $this->nameFilter($classes['classSystem']) . ')';
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'page_class_name';
    }
}
