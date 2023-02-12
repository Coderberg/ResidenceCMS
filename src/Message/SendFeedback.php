<?php

declare(strict_types=1);

namespace App\Message;

use App\Dto\FeedbackDto;

final class SendFeedback
{
    public function __construct(private readonly FeedbackDto $feedback)
    {
    }

    public function getFeedback(): FeedbackDto
    {
        return $this->feedback;
    }
}
