<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-02
 * Time: 2:31 PM
 */

namespace G\Core\Api;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Gateway
{
    /** @var string */
    protected $serviceDiscoveryUrl;

    /** @var Client */
    protected $client;

    /**
     * Gateway constructor.
     * @param string $serviceDiscoveryUrl
     */
    public function __construct($serviceDiscoveryUrl = '')
    {
        $this->serviceDiscoveryUrl = $serviceDiscoveryUrl;
        $this->client = new Client();
    }

    /**
     * @param $service string
     * @param $method string
     * @param $headers array
     * @param $body string
     *
     * @return bool|\Psr\Http\Message\ResponseInterface
     */
    public function makeApiCall($service, $method, array $headers, $body) {
        //Pull list from Service Discovery
        $urls = $this->queryService($service);
        if ($urls) {
            $request = new Request($method, $urls[0], $headers, $body);
            $response = $this->client->send($request);

            return $response;
        } else {
            return false;
        }
    }

    /**
     * @param $service
     * @return bool|mixed
     */
    protected function queryService($service) {
        $response = $this->client->get($this->serviceDiscoveryUrl."/service/".$service);
        if ($response->getStatusCode() == 200) {
            return json_decode((string)$response->getBody(), true);
        } else {
            return false;
        }
    }
}