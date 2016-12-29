<?php

namespace  WebDNA\Bundle\AppBundle\ApiClient\Rserve;

/**
 * Rserve client class
 *
 * @package WebDNA\Bundle\AppBundle\ApiClient\Rserve\Client
 */
class Client
{
    /**
     * Rserve connection instance
     *
     * @var \Rserve_Connection|Rserve_Connection
     */
    protected $cnx;

    /**
     * Suffic for column labels
     */
    const COLUMN_LABELS_SUFFIX = 'ColumnLabels';

    /**
     * Constructor
     *
     * @param \Rserve_Connection $cnx Rserve connection instance
     */
    public function __construct(\Rserve_Connection $cnx)
    {
        $this->cnx = $cnx;
    }

    /**
     * Assign variable and optionally column labels (for vector data types)
     *
     * @param string     $dataType   Rserve data type
     * @param string     $varName    variable name
     * @param array      $metricData metric data
     * @param array|null $labels     optionally added column labels
     *
     * @return null
     */
    public function assign($dataType, $varName, array $metricData, array $labels = null)
    {
        $className = 'Rserve_REXP_' . $dataType;
        $dataObject = new $className();

        if (is_array($labels)) {
            $varNameLabels = $varName . self::COLUMN_LABELS_SUFFIX;
            $dataObjectLabels = new \Rserve_REXP_String();
        }

        $dataObject->setValues($metricData);
        $this->cnx->assign($varName, $dataObject);

        if (is_array($labels)) {
            $dataObjectLabels->setValues($labels);
            $this->cnx->assign($varNameLabels, $dataObjectLabels);
        }
    }

    /**
     * Returns values associated with variable name
     *
     * @param string $varName variable name
     *
     * @return mixed
     */
    public function getValues($varName)
    {
        return $this->evalString($varName);
    }

    /**
     * Returns column labels associated with variable name
     *
     * @param string $varName variable name
     *
     * @return array
     */
    public function getColumnLabels($varName)
    {
        return $this->evalString($varName . self::COLUMN_LABELS_SUFFIX);
    }

    /**
     * Evaluates R expression string
     *
     * @param string $query query string
     *
     * @return mixed
     */
    public function evalString($query)
    {
        return $this->cnx->evalString($query);
    }
}
