<?php namespace MusicCollection\Utils;

/**
 * Class Logger
 * @package System\Utils
 */
class Logger
{
    private string $errorLog = LOG_PATH . 'error.log';
    /**
     * @var resource
     */
    private $file; //@see https://stackoverflow.com/questions/38429595/php-7-and-strict-resource-types

    public function __construct()
    {
        $this->file = fopen($this->errorLog, 'a');
    }

    /**
     * @param \Exception $e
     */
    public function error(\Exception $e): void
    {
        $date = date('d-m-Y H:i');
        $message = "[{$date}] {$e->getMessage()} on line {$e->getLine()} of {$e->getFile()}" . PHP_EOL;
        fwrite($this->file, $message);
    }

    public function __destruct()
    {
        if (is_resource($this->file)) {
            fclose($this->file);
        }
    }
}
