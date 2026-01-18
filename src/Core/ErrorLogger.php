<?php

class ErrorLogger
{
    public static function log(Throwable $e)
    {
        $time = date("Y-m-d H:i:s");
        $message = "[$time]: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}" . PHP_EOL;
        
        error_log($message, 3, __DIR__ . "/error_logs.txt");
    }
}