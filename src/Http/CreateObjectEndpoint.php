<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-04
 * Time: 3:01 PM
 */

namespace G\Core\Http;


use G\Core\Db\InsertBuilder;
use G\Core\Services\ValidatorInterface;
use Psr\Http\Message\ResponseInterface;

abstract class CreateObjectEndpoint implements EndpointInterface
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
     * @param InsertBuilder $builder
     * @param ValidatorInterface $validator
     */
    public function __construct(InsertBuilder $builder, ValidatorInterface $validator)
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

                //Execute the builder
                if ($builder->execute()) {
                    $data['id'] = $this->builder->getDb()->lastInsertId();

                    return $this->response->withJson($data);

                } else {
                    return $this->response->withJson(array("message" => "Object already exists"), 400);
                }


            } catch (\Exception $e) {
                return $this->response->withJson(array("message" => $e->getMessage()), 500);
            }
        } else {
            return $this->response->withJson($validator->getErrors(), 400);
        }
    }
}