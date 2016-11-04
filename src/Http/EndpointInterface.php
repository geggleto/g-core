<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-02
 * Time: 2:19 PM
 */

namespace G\Core\Http;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface EndpointInterface
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args);
}