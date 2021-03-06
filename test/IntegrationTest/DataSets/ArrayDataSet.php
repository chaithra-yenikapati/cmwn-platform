<?php

namespace IntegrationTest\DataSets;

/**
 * Class DefaultDataSet
 * @deprecated
 */
class ArrayDataSet extends \PHPUnit_Extensions_Database_DataSet_AbstractDataSet
{
    /**
     * @var array
     */
    protected $tables = [];

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $tableName => $rows) {
            $columns = [];
            if (isset($rows[0])) {
                $columns = array_keys($rows[0]);
            }

            $metaData = new \PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData($tableName, $columns);
            $table = new \PHPUnit_Extensions_Database_DataSet_DefaultTable($metaData);

            foreach ($rows as $row) {
                $table->addRow($row);
            }
            $this->tables[$tableName] = $table;
        }
    }

    /**
     * @param bool $reverse
     *
     * @return \PHPUnit_Extensions_Database_DataSet_DefaultTableIterator
     */
    protected function createIterator($reverse = false)
    {
        return new \PHPUnit_Extensions_Database_DataSet_DefaultTableIterator($this->tables, $reverse);
    }

    /**
     * @param string $tableName
     *
     * @return mixed
     */
    public function getTable($tableName)
    {
        if (!isset($this->tables[$tableName])) {
            throw new \InvalidArgumentException("$tableName is not a table in the current database.");
        }

        return $this->tables[$tableName];
    }
}
