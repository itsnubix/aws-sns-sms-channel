<?php

namespace Nubix\Notifications\Messages;

class SmsMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * Create a new message instance.
     *
     * @param string|null $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content(string $content)
    {
        $this->content = trim($content);

        return $this;
    }
}
