<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TenderDto
{
    #[Assert\NotBlank]
    public string $name;

    public ?string $status = null;

    #[Assert\NotBlank]
    public string $code;

    #[Assert\NotBlank]
    public string $number;
}