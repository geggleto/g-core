<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-04
 * Time: 3:01 PM
 */

namespace G\Core\Http;


use G\Core\Db\InsertBuilder;
use G\Core\Db\IOQueryBuilder;
use G\Core\Services\ValidatorInterface;
use Psr\Http\Message\ResponseInterface;

abstract class IOObjectEndpoint implements EndpointInterface
{
    /** @var InsertBuilder  */
    protected $builder;

    /** @var ResponseInterface */
    protected $response;

    /** @var ValidatorInterface  */
    protected $validator;

    /**
     * CreateUser constructor.
     *
     * @param IOQueryBuilder $builder
     * @param ValidatorInterface $validator
     */
    public function __construct(IOQueryBuilder $builder, ValidatorInterface $validator)
    {
        $this->builder = $builder;
        $this->validator = $validator;
    }

    /**
     * Validates, Mutates and persists data
     *
     * Mutates via Closures
     *
     * @param array $mapping
     * @param $table
     * @param array $data
     *
     * @return ResponseInterface
     */
    public function createObject(array $mapping, $table, $data = array()) {
        return $this->processObject($mapping, $table, $data);
    }

    /**
     * @param $id
     * @param array $mapping
     * @param $table
     * @param array $data
     *
     * @return ResponseInterface
     */
    public function updateObject($id, array $mapping, $table, $data = array()) {
        return $this->processObject($mapping, $table, $data, $id);
    }

    /**
     * @param array $mapping
     * @param $table
     * @param array $data
     * @param int $id
     *
     * @return ResponseInterface
     */
    protected function processObject(array $mapping, $table, $data = array(), $id=0) {
        $this->validator->setData($data);

        if ($this->validator->validate()) {
            try {
                //Mutate the data
                foreach ($mapping as $item => $mutator) {
                    $data[$item] = $mutator($data[$item]);
                }

                $builder = $this->builder->setTable($table);

                foreach ($data as $item => $value) {
                    $builder->addColumn($item, $value);
                }

                if ($id === 0) {
                    $this->builder->setId($id);
                }


                //Execute the builder
                if ($builder->execute()) {
                    if ($id === 0) {
                        $data['id'] = $this->builder->getDb()->lastInsertId();
                    }

                    return $this->response->withJson($data);

                } else {
                    return $this->response->withJson(array("message" => "Object already exists"), 400);
                }


            } catch (\Exception $e) {
                return $this->response->withJson(array("message" => $e->getMessage()), 500);
            }
        } else {
            return $this->response->withJson($this->validator->getErrors(), 400);
        }
    }
}