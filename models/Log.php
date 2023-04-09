<?php

namespace Model;

class Log
{
    private $path = 'logs/';

    public function __construct($method = null)
    {
        $this->path .= (!empty($method) ? $method : 'log') . '.txt';
        $this->path = str_replace("\\", "/", $this->path);
    }

    public function setLog($title, $text)
    {
        file_put_contents(
            $this->path,
            $title . "\n" . $text . "\n" . date('Y-m-d H:i:s') . "\n\n\n",
            FILE_APPEND
        );
    }
}