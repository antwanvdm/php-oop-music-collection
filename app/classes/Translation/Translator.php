<?php namespace MusicCollection\Translation;

/**
 * Class Translator
 * @package System\Translation
 */
class Translator
{
    private string $language = DEFAULT_LANGUAGE;
    private array $groups = [];

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @param string $group
     * @return LangGroup
     * @throws \Exception
     */
    public function __get(string $group): LangGroup
    {
        return $this->get($group);
    }

    /**
     * @param string $group
     * @return LangGroup
     * @throws \Exception
     */
    public function get(string $group): LangGroup
    {
        if (array_key_exists($group, $this->groups)) {
            return $this->groups[$group];
        }

        $content = json_decode(file_get_contents(LANGUAGE_PATH . $this->language . '/' . $group . '.json'));
        if ($content === null || $content === false) {
            throw new \Exception("File for language $this->language not found or JSON is messed up");
        }

        $this->groups[$group] = new LangGroup($content);

        return $this->groups[$group];
    }
}
