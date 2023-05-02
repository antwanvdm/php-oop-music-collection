<?php namespace MusicCollection\DTO;

class FileUpload implements Base
{
    /**
     * @param string $name
     * @param int $size
     * @param string $type
     * @param string $tmp_name
     * @param int $error
     */
    private function __construct(
        public string $name,
        public int $size,
        public string $type,
        public string $tmp_name,
        public int $error
    ) {
    }

    /**
     * @param array<string, string|int> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self($data['name'], $data['size'], $data['type'], $data['tmp_name'], $data['error']);
    }
}
