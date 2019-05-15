<?php

namespace src\Controllers;

use Psr\Log\LoggerInterface;
use src\Integration\DataProviderInterface;
use src\Integration\RequestException;
use src\Kernel\Request;
use src\Kernel\Response;

class Controller
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DataProviderInterface
     */
    private $provider;

    /**
     * @param DataProviderInterface $provider
     * @param LoggerInterface $logger
     */
    public function __construct(DataProviderInterface $provider, LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        try {
            $data = $this->provider->get($request->getParams());

            $response = new Response;
            $response->setCode(200);
            $response->setData($data);

            return $response;
        } catch (RequestException $e) {
            $this->logger->critical("An error occurred {$e->getMessage()}");

            $response = new Response;
            $response->setCode(400);
            $response->setData('Service unavailable');
        }
    }
}