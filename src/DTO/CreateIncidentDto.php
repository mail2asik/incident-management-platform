<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateIncidentDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public ?string $title = null;

    #[Assert\NotBlank]
    public ?string $description = null;

    #[Assert\Choice(choices: ['low', 'medium', 'high', 'critical'])]
    public string $priority = 'medium';
}