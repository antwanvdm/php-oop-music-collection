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

    private array $genreIds = [];
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
        try {
            $this->db->beginTransaction();

            //Delete all current references
            $statement = $this->db->prepare('DELETE FROM album_genre WHERE album_id = :album_id');
            $statement->execute([':album_id' => $this->id]);

            //Add the current references
            foreach ($this->genreIds as $genreId) {
                $statement = $this->db->prepare('INSERT INTO album_genre (genre_id, album_id) VALUES (:genre_id, :album_id)');
                $statement->execute([
                    ':genre_id' => $genreId,
                    ':album_id' => $this->id
                ]);
            }
            $this->db->commit();
            return true;
        } catch (\PDOException) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * @return array
     */
    public function getGenreIds(): array
    {
        return $this->genreIds;
    }

    /**
     * @param array $genreIds
     * @return void
     */
    public function setGenreIds(array $genreIds): void
    {
        $this->genreIds = $genreIds;
    }
}
