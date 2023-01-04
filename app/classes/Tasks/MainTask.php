<?php namespace MusicCollection\Tasks;

/**
 * Class MainTask
 * @package MusicCollection\Tasks
 */
class MainTask extends BaseTask
{
    /**
     * @param string[] $params
     * @return string
     */
    protected function doSomething(array $params): string
    {
        return implode(', ', $params);
    }
}
