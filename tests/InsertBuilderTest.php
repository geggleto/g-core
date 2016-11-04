<?php

/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-04
 * Time: 1:13 PM
 */
class InsertBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testInsert() {
        $builder = new \G\Core\Db\InsertBuilder($this->getPdo());

        $this->assertTrue($builder
            ->setTable('users')
            ->addColumn('username', 'a')
            ->addColumn('password', password_hash('abcdef', PASSWORD_BCRYPT))
            ->addColumn('name', 'b')
            ->addColumn('email', 'c')
            ->execute());

    }

    public function getPdo() {
        $host = getenv('DBHOST');
        $username = getenv('DBUSERNAME');
        $password = getenv('DBPASSWORD');
        $name = getenv('DBNAME');

        $db = new PDO("mysql:host=$host;dbname=$name;", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $db;
    }

    public function tearDown()
    {
        $stmt = $this->getPdo()->prepare("delete from `users` where `username`='a'");
        $stmt->execute();
    }
}