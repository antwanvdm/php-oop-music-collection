<?php namespace MusicCollection\Bootstrap;

/**
 * Interface BootstrapInterface
 * @package MusicCollection\Bootstrap
 */
interface BootstrapInterface
{
    public function setup(): void;

    public function render(): string;
}
