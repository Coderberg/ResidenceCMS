<?php

declare(strict_types=1);

namespace App\Message;

use App\Dto\FeedbackDto;

final class SendFeedback
{
    private $feedback;

    public function __construct(FeedbackDto $feedback)
    {
        $this->feedback = $feedback;
    }

    public function getFeedback(): FeedbackDto
    {
        return $this->feedback;
    }
}
