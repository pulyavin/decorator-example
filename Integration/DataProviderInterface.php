<?php

namespace src\Integration;

interface DataProviderInterface
{
    /**
     * Returns result of request
     *
     * @param array $request
     *
     * @return array
     *
     * @throws RequestException
     */
    public function get(array $request): array;
}