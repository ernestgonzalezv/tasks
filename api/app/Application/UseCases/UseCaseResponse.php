<?php

namespace App\Application\UseCases;

use JsonSerializable;

class UseCaseResponse implements JsonSerializable
{
    public bool $success;
    public mixed $data;
    public ?string $message;
    public int $statusCode;

    private function __construct(bool $success, mixed $data = null, ?string $message = null, int $statusCode = 200)
    {
        $this->success    = $success;
        $this->data       = $data;
        $this->message    = $message;
        $this->statusCode = $statusCode;
    }

    public static function success(mixed $data = null, string $message  = null, int $statusCode = 200): self
    {
        return new self(true, $data, $message, $statusCode);
    }

    public static function error(string $message, int $statusCode = 400): self
    {
        return new self(false, null, $message, $statusCode);
    }

    public function jsonSerialize(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data'    => $this->data,
        ];
    }
}
