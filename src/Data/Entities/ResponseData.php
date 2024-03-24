<?php
namespace Data\Entities;
class ResponseData
{
    public $data;
    public bool $success;
    public string $message;

    public function __construct($data, $success, $message)
    {
        $this->data = $data;
        $this->success = $success;
        $this->message = $message;
    }
}