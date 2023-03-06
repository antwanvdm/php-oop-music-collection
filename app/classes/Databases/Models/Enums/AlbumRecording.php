<?php namespace MusicCollection\Databases\Models\Enums;

use MusicCollection\Translation\Translator as T;

enum AlbumRecording: int
{
    case Studio = 1;
    case Live = 2;
    case EP = 3;
    case Compilation = 4;

    public function label(): string
    {
        return match ($this) {
            self::Studio => T::__('album.recordingLabels.studio'),
            self::Live => T::__('album.recordingLabels.live'),
            self::EP => T::__('album.recordingLabels.ep'),
            self::Compilation => T::__('album.recordingLabels.compilation'),
        };
    }
}
