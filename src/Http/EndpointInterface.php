<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-02
 * Time: 2:19 PM
 */

namespace G\Core\Http;


use Slim\Http\Request;
use Slim\Http\Response;

interface EndpointInterface
{
    public function __invoke(Request $request, Response $response, array $args);
}