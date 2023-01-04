<?php namespace MusicCollection\Utils;

/**
 * Class Logger
 * @package MusicCollection\Utils
 */
class Logger implements Singleton
{
    private string $filePath = LOG_PATH . 'application.log';

    /**
     * @var resource
     */
    private $file; //@see https://stackoverflow.com/questions/38429595/php-7-and-strict-resource-types

    /**
     * @var Logger|null
     */
    private static ?Logger $instance = null;

    public function __construct()
    {
        $this->file = fopen($this->filePath, 'a');
    }

    /**
     * @return Logger
     */
    public static function i(): Logger
    {
        if (self::$instance === null) {
            self::$instance = new Logger();
        }

        return self::$instance;
    }

    /**
     * @param \Exception $e
     * @return void
     */
    public static function error(\Exception $e): void
    {
        $message = "{$e->getMessage()} on line {$e->getLine()} of {$e->getFile()}";
        self::i()->log('ERROR', $message);
    }

    /**
     * @param string $message
     * @return void
     */
    public static function info(string $message): void
    {
        self::i()->log('INFO', $message);
    }

    /**
     * @param string $type
     * @param string $message
     */
    private function log(string $type, string $message): void
    {
        $date = date('d-m-Y H:i');
        $message = "[$date][$type] $message" . PHP_EOL;
        fwrite($this->file, $message);
    }

    public function __destruct()
    {
        if (is_resource($this->file)) {
            fclose($this->file);
        }
    }
}
