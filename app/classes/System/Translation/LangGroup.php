<?php namespace System\Translation;

/**
 * Class LangGroup
 * @package System\Translation
 */
class LangGroup
{
    private object $content;

    /**
     * @param object $content
     */
    public function __construct(object $content)
    {
        $this->content = $content;
    }

    /**
     * @param string $translationKey
     * @return string|object
     */
    public function __get(string $translationKey)
    {
        return $this->content->$translationKey;
    }
}
