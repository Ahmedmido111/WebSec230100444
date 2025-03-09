<?php

namespace App\Mail;

use Illuminate\Mail\Transport\Transport;
use Swift_Mime_SimpleMessage;
use SendGrid\Mail\Mail as SendGridMail;
use SendGrid;

class SendGridTransport extends Transport
{
    protected $sendGrid;

    public function __construct(SendGrid $sendGrid)
    {
        $this->sendGrid = $sendGrid;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $sendGridMail = new SendGridMail();
        $sendGridMail->setFrom($message->getFrom());
        $sendGridMail->setSubject($message->getSubject());
        $sendGridMail->addTo($message->getTo());
        $sendGridMail->addContent("text/plain", $message->getBody());

        $this->sendGrid->send($sendGridMail);

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }
}
