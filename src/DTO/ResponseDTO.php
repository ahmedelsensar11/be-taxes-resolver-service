<?php

namespace App\DTO;

class ResponseDTO
{
    public bool $status = true;
    public string $message;
    public array $data = [];

    public function __construct()

    {
        $this->message = "Taxes retrieved successfully";
    }
}
