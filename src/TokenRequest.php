<?php
/**
 * Copyright (c) 2019, Daniel Fekete
 * All rights reserved.
 */

namespace Otisz\BillingoConnector;

use Otisz\BillingoConnector\Exceptions\SignatureInvalid;
use Otisz\BillingoConnector\Exceptions\TimingInvalid;

class TokenRequest
{
    // one minute delta
    public const MAX_TIMING_DELTA = 60;

    /**
     * @var string $pubKey
     */
    private $pubKey;

    /**
     * @var string $privateKey
     */
    private $privateKey;

    /**
     * TokenRequest constructor.
     *
     * @param string $pubKey
     * @param string $privateKey
     */
    public function __construct(string $pubKey, string $privateKey)
    {
        $this->pubKey = $pubKey;
        $this->privateKey = $privateKey;
    }

    /**
     * Generate token request data.
     *
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param $timing
     *
     * @return string
     */
    public function generate($timing): string
    {
        return implode('|', [$this->pubKey, $timing]);
    }

    /**
     * Return timing information (ie. unix epoch).
     *
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @return int
     */
    public function generateTiming(): int
    {
        return time();
    }

    /**
     * Generate data string with signature.
     *
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param $timing
     *
     * @return string
     */
    public function generateWithSignature($timing): string
    {
        $data = $this->generate($timing);

        return $data . '|' . $this->sign($data);
    }

    /**
     * Generate a data string with signature and timing
     *
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @return string
     */
    public function generateWithSignatureAndTiming(): string
    {
        return $this->generateWithSignature($this->generateTiming());
    }

    /**
     * Return TRUE if timing is valid.
     *
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param $userTiming
     *
     * @return bool
     */
    public function validateTiming($userTiming): bool
    {
        return abs($this->generateTiming() - $userTiming) <= static::MAX_TIMING_DELTA;
    }

    /**
     * Validate user string to be valid.
     *
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param $userString
     * @param $timing
     *
     * @return bool
     */
    public function validateSignature($userString, $timing): bool
    {
        $data = $this->generate($timing);

        return hash_equals($this->sign($data), $userString);
    }

    /**
     * Return the data from the token request string.
     *
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param string $requestString
     *
     * @return array
     */
    public static function requestStringData(string $requestString): array
    {
        [$pubKey, $timing, $signature] = explode('|', $requestString);

        return compact('pubKey', 'timing', 'signature');
    }

    /**
     * Validate a full token request string.
     *
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param string $requestString
     * @param string $privateKey
     *
     * @return bool
     * @throws \Otisz\BillingoConnector\Exceptions\SignatureInvalid
     * @throws \Otisz\BillingoConnector\Exceptions\TimingInvalid
     */
    public static function validateRequestString(string $requestString, string $privateKey): bool
    {
        $data = static::requestStringData($requestString);
        $self = new static($data['pubKey'], $privateKey);

        if (!$self->validateTiming($data['timing'])) {
            throw new TimingInvalid;
        }

        if (!$self->validateSignature($data['signature'], $data['timing'])) {
            throw new SignatureInvalid;
        }

        return true;
    }

    /**
     * Generate hash signature.
     *
     * @author Levente Otta <leventeotta@gmail.com>
     *
     * @param string $data
     *
     * @return string
     */
    public function sign(string $data): string
    {
        return hash_hmac('sha256', $data, $this->privateKey);
    }
}
