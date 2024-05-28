<?php

namespace Inc\Helpers;

class CodeMessage
{
    private int $code = 0;
    private string $message = '';

    public function setCodeMessage($code = 0, $message = '')
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function resetCodeMessage()
    {
        $this->setCodeMessage(0, '');
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getCode()
    {
        return $this->code;
    }
}
