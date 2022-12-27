<?php namespace MusicCollection\Translation;

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
     * @return string|\stdClass
     */
    public function __get(string $translationKey): string|\stdClass
    {
        return $this->content->$translationKey;
    }
}
