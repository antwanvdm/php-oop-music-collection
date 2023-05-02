<?php namespace MusicCollection\DTO;

interface Base
{
    /**
     * @param array<int, mixed> $data
     * @return Base
     */
    public static function fromArray(array $data): Base;
}
