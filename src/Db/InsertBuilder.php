<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-04
 * Time: 1:03 PM
 */

namespace G\Core\Db;


class InsertBuilder extends IOQueryBuilder
{

    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);
    }

    /**
     * @return bool
     */
    public function execute() {
        $statement = "insert into `$this->table` set ";

        $columns = array();
        $values = array();

        foreach ($this->columns as $name => $value) {
            $columns[] = "`$name` = ?";
            $values[] = $value;
        }

        $statement .= join(",", $columns);
        $prepStatment = $this->db->prepare($statement);

        return $prepStatment->execute($values);
    }
}