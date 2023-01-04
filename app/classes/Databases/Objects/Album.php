<?php namespace MusicCollection\Databases\Objects;

use MusicCollection\Databases\BaseObject;

/**
 * Class Album
 * @package MusicCollection\Databases\Objects
 * @method static Album[] getAll()
 * @method static Album getById($id)
 */
class Album extends BaseObject
{
    protected static string $table = 'albums';
    /**
     * @var array<string, array<string, mixed>>
     */
    protected static array $joinForeignKeys = [
        'artist_id' => [
            'table' => 'artists',
            'object' => Artist::class
        ],
        'user_id' => [
            'table' => 'users',
            'object' => User::class
        ]
    ];

    /**
     * @var int[]
     * @TODO: Try to make this more dynamic for any future many-to-many situation?
     */
    protected array $genreIds = [];
    public Artist $artist;
    public User $user;

    public function __construct(
        public ?int $id = null,
        public ?int $user_id = null,
        public ?int $artist_id = null,
        public string $name = '',
        public string $year = '',
        public int $tracks = 0,
        public string $image = ''
    ) {
        parent::__construct();
    }

    /**
     * @return Genre[]
     */
    public function genres(): array
    {
        return $this->getManyToManyItems(Genre::class, 'album_genre', ['genre_id', 'album_id']);
    }

    /**
     * @return bool
     */
    public function saveGenres(): bool
    {
        return $this->saveManyToManyItems('album_genre', ['genre_id', 'album_id'], $this->genreIds);
    }

    /**
     * @return int[]
     */
    public function getGenreIds(): array
    {
        return $this->genreIds;
    }

    /**
     * @param int[] $genreIds
     * @return void
     */
    public function setGenreIds(array $genreIds): void
    {
        $this->genreIds = $genreIds;
    }
}
