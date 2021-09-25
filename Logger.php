<?php

namespace PhpHelper;

class Logger
{
    public string $logFile;
    public int $permissions;

    public function __construct(string $logFile = __DIR__ . "/.log", int $permissions = 0777)
    {
        $this->logFile = $logFile;
        $this->permissions = $permissions;
    }

    public function log(string $message, string $logLevel = LogLevel::Info)
    {
        $logMessage = "[ " . date("Y-m-d H:i:s") . " ] [ " . $logLevel . " ] " . $message . "\r\n";
        $logDir = dirname($this->logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }
        return file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}

class LogLevel
{
    const Debug = 'DEBUG';
    const Info = 'INFO';
    const Information = 'INFORMATION';
    const Notice = 'NOTICE';
    const Warning = 'WARNING';
    const Warn = 'WARN';
    const Critical = 'CRITICAL';
    const Error = 'ERROR';
    const Alert = 'ALERT';
    const Emergency = 'EMERGENCY';
    const Fail = 'FAIL';
    const Failure = 'FAILURE';
}
