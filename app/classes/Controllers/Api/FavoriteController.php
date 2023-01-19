<?php namespace MusicCollection\Controllers\Api;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Databases\Models\Album;
use MusicCollection\Databases\Models\User;
use MusicCollection\Responses\Json;
use MusicCollection\Utils\Session;

/**
 * Class FavoriteController
 * @package MusicCollection\Controllers\Api
 */
class FavoriteController extends BaseController
{
    protected function toggle(int $id): Json
    {
        $user = User::getById(Session::i()->get('user')->id);
        $currentItemIds = array_map(fn (Album $album) => $album->id, $user->favoriteAlbums);
        if (($key = array_search($id, $currentItemIds)) !== false) {
            unset($currentItemIds[$key]);
        } else {
            $currentItemIds[] = $id;
        }

        $user->setFavoriteAlbumsIds($currentItemIds);
        $result = $user->saveFavoriteAlbums();
        return $this->json->data(['result' => $result]);
    }
}
