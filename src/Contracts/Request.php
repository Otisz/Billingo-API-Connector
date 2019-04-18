<?php
/**
 * Deployed by Levente Otta <leventeotta@gmail.com>
 *
 * @author Levente Otta <leventeotta@gmail.com>
 * @copyright Copyright (c) 2019. Levente Otta
 */

namespace Otisz\BillingoConnector\Contracts;

/**
 * Interface Request
 *
 * @author Levente Otta <leventeotta@gmail.com>
 *
 * @package Otisz\BillingoConnector\Contracts
 */
interface Request
{
    /**
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param string $uri
     * @param array $payload
     *
     * @return mixed
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Otisz\BillingoConnector\Exceptions\JSONParseException
     * @throws \Otisz\BillingoConnector\Exceptions\RequestErrorException
     */
    public function get(string $uri, array $payload = []);

    /**
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param string $uri
     * @param array $payload
     *
     * @return mixed
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Otisz\BillingoConnector\Exceptions\JSONParseException
     * @throws \Otisz\BillingoConnector\Exceptions\RequestErrorException
     */
    public function post(string $uri, array $payload = []);

    /**
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param string $uri
     * @param array $payload
     *
     * @return mixed
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Otisz\BillingoConnector\Exceptions\JSONParseException
     * @throws \Otisz\BillingoConnector\Exceptions\RequestErrorException
     */
    public function put(string $uri, array $payload = []);

    /**
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param string $uri
     * @param array $payload
     *
     * @return mixed
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Otisz\BillingoConnector\Exceptions\JSONParseException
     * @throws \Otisz\BillingoConnector\Exceptions\RequestErrorException
     */
    public function delete(string $uri, array $payload = []);
}
