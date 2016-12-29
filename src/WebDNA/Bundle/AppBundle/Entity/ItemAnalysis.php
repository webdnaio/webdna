<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemAnalysis
 *
 * @ORM\Table(name="item_analysis")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\ItemAnalysisRepository")
 */
class ItemAnalysis extends Base\ItemAnalysis
{
    /**
     * Types constants
     */
    const TYPE_WEBSITE = 1;
    const TYPE_PAGE = 2;

    /**
     * Status constants
     */
    const STATUS_NEW = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_FAILED = 4;

    /**
     *
     */
    const CLASS_UNCLASSIFIED = 0;
    const CLASS_POSITIVE = 1;
    const CLASS_NEGATIVE = 2;
    const CLASS_SUSPICIOUS = 3;
    const CLASS_UNKNOWN = 999;

    /**
     * @var array
     */
    public static $CLASSES = array(
        self::CLASS_UNCLASSIFIED => 'Unclassified',
        self::CLASS_POSITIVE => 'Positive',
        self::CLASS_NEGATIVE => 'Negative',
        self::CLASS_SUSPICIOUS => 'Suspicious',
        self::CLASS_UNKNOWN => 'Unknown',
    );

    public static $CLASS_RANGES = array(
        self::CLASS_NEGATIVE => [0, 0.3],
        self::CLASS_SUSPICIOUS => [0.3, 0.615],
        self::CLASS_POSITIVE => [0.615, 1],
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->status = self::STATUS_NEW;

        $this->setClass(self::CLASS_UNCLASSIFIED);
        $this->setClassUser(self::CLASS_UNCLASSIFIED);
        $this->setClassSystem(self::CLASS_UNCLASSIFIED);
    }

    /**
     * Setup objectId and type fields.
     *
     * @param $object
     * @return $this
     */
    public function setObject($object)
    {
        switch (get_class($object)) {
            case 'WebDNA\Bundle\AppBundle\Entity\Website':
            case 'Proxies\__CG__\WebDNA\Bundle\AppBundle\Entity\Website':
                $this->setObjectId($object->getId());
                $this->setType(self::TYPE_WEBSITE);

                break;
            case 'WebDNA\Bundle\AppBundle\Entity\Page':
            case 'Proxies\__CG__\WebDNA\Bundle\AppBundle\Entity\Page':
                $this->setObjectId($object->getId());
                $this->setType(self::TYPE_PAGE);

                break;
            default:
                throw new \LogicException('Invalid class');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setClassSystem($class)
    {
        parent::setClassSystem($class);

        return $this->calculateClass();
    }

    /**
     * @param integer $class
     * @return mixed
     */
    public function setClassUser($class)
    {
        parent::setClassUser($class);

        return $this->calculateClass();
    }

    /**
     * Calculate final page class based on system and user decisions.
     * User decision is always more important than system classification.
     *
     * @param void
     * @return $this
     * @access protected
     */
    protected function calculateClass()
    {
        $this->setClass($this->getClassUser() ?: $this->getClassSystem());

        return $this;
    }
}
