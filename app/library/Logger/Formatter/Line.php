<?php

namespace Library\Logger\Formatter;

use Phalcon\Logger\Formatter\AbstractFormatter;
use Library\Logger\Formatter\Exception;

use Phalcon\Logger\Item;

class Line extends AbstractFormatter
{
    protected string $format;

    public function setFormat(string $format) : void
    {
        if (empty($format)) {
            throw new Exception('format string must be not empty.');
        }
        $this->format = $format;
    }

    public function getFormat() : string
    {
        return $this->format;
    }
    
    public function __construct(string $format = "[%date%][%type%] %message%", string $dateFormat = "'Y-m-d H:i:s'")
    {
        $this->setFormat($format);
        $this->dateFormat = $dateFormat;
    }

    public function format(Item $item) : string
    {
        $message = $item->getMessage();
        if (empty($message) || $message === PHP_EOL) {
            return PHP_EOL;
        }
        
        $currentFormat = $this->format;

        if (strpos($this->format, '%message%') !== false) {
            $currentFormat = str_replace('%message%', $message, $currentFormat);
        }

        if (strpos($this->format, '%date%') !== false) {
            $currentFormat = str_replace('%date%', $this->getFormattedDate(), $currentFormat);
        }
        
        if (strpos($this->format, '%type%') !== false) {
            $currentFormat = str_replace('%type%', $item->getName(), $currentFormat);
        }

        $context = $item->getContext();
        if (is_array($context)) {
            return $this->interpolate($currentFormat,  $context);
        }

        return $currentFormat;
    }
}
