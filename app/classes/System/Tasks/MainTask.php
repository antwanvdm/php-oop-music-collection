<?php namespace System\Tasks;

/**
 * Class MainTask
 * @package System\Tasks
 */
class MainTask extends BaseTask
{
    protected function doSomething($params)
    {
        return implode(", ", $params);
    }
}
