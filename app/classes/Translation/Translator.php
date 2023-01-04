<?php namespace MusicCollection\Translation;

use MusicCollection\Utils\Logger;
use MusicCollection\Utils\Singleton;

/**
 * Class Translator
 * @package MusicCollection\Translation
 */
class Translator implements Singleton
{
    private string $language = DEFAULT_LANGUAGE;
    private array $groups = [];

    /**
     * @var Translator|null
     */
    private static ?Translator $instance = null;

    /**
     * @return Translator
     */
    public static function i(): Translator
    {
        if (self::$instance === null) {
            self::$instance = new Translator();
        }

        return self::$instance;
    }

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
     * @param string $key
     * @param array $replacements
     * @return string
     */
    public static function __(string $key, array $replacements = []): string
    {
        return self::i()->_($key, $replacements);
    }

    /**
     * A simple template function to prevent breaking errors. If a key doesn't exist, it will just show the provided key
     * Optional replacement can be provided like ['MY_KEY' => 'my replacement'].
     * Replacements should be mark like [MY_KEY] in the language files
     *
     * @param string $key
     * @param array $replacements
     * @return string
     */
    public function _(string $key, array $replacements = []): string
    {
        try {
            $transLateKeys = explode('.', $key);
            $value = $this->{array_shift($transLateKeys)};

            foreach ($transLateKeys as $transLateKey) {
                $value = $value->{$transLateKey};
            }

            if (is_string($value) === false) {
                throw new \Exception();
            }

            foreach ($replacements as $searchToken => $replacement) {
                $value = str_replace("[$searchToken]", $replacement, $value);
            }
        } catch (\Exception $e) {
            Logger::error($e);
            return $key;
        }

        return $value;
    }

    /**
     * @param string $group
     * @return LangGroup
     * @throws \Exception
     */
    private function get(string $group): LangGroup
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
