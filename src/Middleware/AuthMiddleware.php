<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-04
 * Time: 10:50 AM
 */

namespace G\Core\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($request->getHeader('X-Auth-Token')[0] != getenv('auth-token')) {
            return $response->withStatus(401);
        } else {
            return $next($request, $response);
        }
    }
}