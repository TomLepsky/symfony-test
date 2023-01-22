<?php

namespace App\Helper;

class ResponseBag
{
    public function __construct(private mixed $data = null, private ?int $statusCode = null, private array $serializeGroups = []) {}

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return array
     */
    public function getSerializeGroups(): array
    {
        return $this->serializeGroups;
    }

    /**
     * @param array $serializeGroups
     */
    public function setSerializeGroups(array $serializeGroups): void
    {
        $this->serializeGroups = $serializeGroups;
    }

}