<?php
namespace Data\Entities;

class IsAvailable
{
    public bool $isAvailable;

    public function __construct(bool $isAvailable)
    {
        $this->isAvailable = $isAvailable;
    }
}