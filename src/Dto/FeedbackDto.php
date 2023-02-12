<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class FeedbackDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $from_name = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(min: 6)]
    private ?string $from_email = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(min: 6)]
    private ?string $to_email = null;

    private ?string $subject = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 14)]
    private ?string $message = null;

    public function getFromName(): ?string
    {
        return $this->from_name;
    }

    public function setFromName(string $from_name): self
    {
        $this->from_name = $from_name;

        return $this;
    }

    public function getFromEmail(): ?string
    {
        return $this->from_email;
    }

    public function setFromEmail(string $from_email): self
    {
        $this->from_email = $from_email;

        return $this;
    }

    public function getToEmail(): ?string
    {
        return $this->to_email;
    }

    public function setToEmail(string $to_email): self
    {
        $this->to_email = $to_email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
