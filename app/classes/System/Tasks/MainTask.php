<?php namespace System\Tasks;

/**
 * Class MainTask
 * @package System\Tasks
 */
class MainTask extends BaseTask
{
    /**
     * @param array $params
     * @return string
     */
    protected function doSomething(array $params): string
    {
        return implode(', ', $params);
    }
}
