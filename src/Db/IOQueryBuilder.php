<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-04
 * Time: 1:24 PM
 */

namespace G\Core\Db;


abstract class IOQueryBuilder
{
    /** @var \PDO  */
    protected $db;

    /** @var string  */
    protected $table;

    /** @var array  */
    protected $columns;

    /** @var int */
    protected $id;

    /**
     * InsertBuilder constructor.
     *
     * @param \PDO $pdo
     */
    protected function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
        $this->table = "";
        $this->columns = array();
    }

    /**
     * @param $table
     * @return $this
     */
    public function setTable($table) {
        $this->table = $table;

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function addColumn($column, $value) {
        $this->columns[$column] = $value;

        return $this;
    }

    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    public abstract function execute();

    /**
     * @return \PDO
     */
    public function getDb()
    {
        return $this->db;
    }

}