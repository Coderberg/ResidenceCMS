<?php

declare(strict_types=1);

namespace App\Message;

use App\Dto\FeedbackDto;

final readonly class SendFeedback
{
    public function __construct(private FeedbackDto $feedback)
    {
    }

    public function getFeedback(): FeedbackDto
    {
        return $this->feedback;
    }
}
