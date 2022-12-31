<?php namespace MusicCollection\Translation;

/**
 * Class LangGroup
 * @package System\Translation
 */
class LangGroup
{
    /**
     * @param \stdClass $content
     */
    public function __construct(private readonly \stdClass $content)
    {
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
