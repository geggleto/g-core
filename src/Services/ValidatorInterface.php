<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-04
 * Time: 2:40 PM
 */
namespace G\Core\Services;


/**
 * Class UserValidator
 *
 * @package G\Services\User
 */
interface ValidatorInterface
{
    public function validate();

    public function setData(array $data);

    /**
     * @return array|bool
     */
    public function getErrors();
}