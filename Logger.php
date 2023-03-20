<?php

namespace PhpHelpers;

/**
 * Create custom log files
 */
class Logger
{
    public string $logFile;
    public int $permissions;
    public string $dateFormat;

    public function __construct(string $logFile = __DIR__ . "/.log", int $permissions = 0777)
    {
        $this->logFile = $logFile;
        $this->permissions = $permissions;
        $this->dateFormat = "Y-m-d H:i:s";
    }

    /**
     * Insert data into custom log
     *
     * @param string $message
     * @param string $logLevel
     * @return int|false
     */
    public function log(string $message, string $logLevel = LogLevel::Info): int|false
    {
        $date = date($this->dateFormat);
        $logMessage = "[ $date ] [ $logLevel ] $message" . PHP_EOL;
        $logDir = dirname($this->logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, $this->permissions, true);
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
