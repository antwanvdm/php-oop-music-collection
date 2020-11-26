<?php namespace System\Form;

/**
 * Class Data
 * @package System\Form
 */
class Data
{
    /**
     * @var array
     */
    private array $post;

    /**
     * Data constructor.
     *
     * @param array $post
     */
    public function __construct(array $post)
    {
        $this->post = $post;
    }

    /**
     * Check if a var exists in the current post state
     *
     * @param string $var
     * @return bool
     */
    public function varExists(string $var): bool
    {
        return array_key_exists($var, $this->post);
    }

    /**
     * Retrieve a var from the post array
     *
     * @param string $var
     * @return string|array
     */
    public function getPostVar(string $var)
    {
        //I simply hacked a checkbox situation :(
        if (!isset($this->post[$var])) {
            return [];
        }

        //And if 1 or more checkbox values are added..
        $value = $this->post[$var];
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = htmlentities($val);
            }

            return $value;
        }

        //Or just a single non checkbox field
        return htmlentities($value);
    }
}
