<?php namespace MusicCollection\Responses;

/**
 * Handle the logic of returning JSON in API situations
 * @package MusicCollection\Responses
 */
class Json extends Response
{
    public function __toString(): string
    {
        header('Content-Type: application/json');
        return json_encode($this->data);
    }

    /**
     * @param array<string, mixed> $data
     * @return Json
     */
    public function data(array $data): self
    {
        $this->data = $data;
        return $this;
    }
}
