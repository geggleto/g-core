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

    /**
     * CreateUser constructor.
     *
     * @param InsertBuilder $builder
     */
    public function __construct(InsertBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param ValidatorInterface $validator
     * @param array $mapping
     * @param $table
     * @param array $data
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function createObject(ValidatorInterface $validator, array $mapping, $table, $data = array(), ResponseInterface $response) {

        if ($validator->validate()) {
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

                    return $response->withJson($data);

                } else {
                    return $response->withJson(array("message" => "Object already exists"), 400);
                }


            } catch (\Exception $e) {
                return $response->withJson(array("message" => $e->getMessage()), 500);
            }
        } else {
            return $response->withJson($validator->getErrors(), 400);
        }
    }
}