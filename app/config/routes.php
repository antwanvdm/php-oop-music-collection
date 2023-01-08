<?php

use MusicCollection\Handlers\AccountHandler;
use MusicCollection\Handlers\AlbumHandler;
use MusicCollection\Handlers\APIHandler;
use MusicCollection\Handlers\ArtistHandler;
use MusicCollection\Handlers\GenreHandler;
use MusicCollection\Handlers\HomeHandler;
use MusicCollection\Handlers\LanguageHandler;
use MusicCollection\Middleware\IsLoggedInMiddleware;
use MusicCollection\Routing\Router;

/**
 * @var Router $router
 */
$router->get('', [HomeHandler::class, 'index'])->name('home');
$router->post('language/change', [LanguageHandler::class, 'change'])->name('language.change');

$router->get('artists', [ArtistHandler::class, 'index'])->name('artists.index');
$router->get('artists/{id}/detail', [ArtistHandler::class, 'detail'])->name('artists.detail');
$router->group(IsLoggedInMiddleware::class, function (Router $router) {
    $router->get('artists/create', [ArtistHandler::class, 'create'])->name('artists.create');
    $router->get('artists/{id}/edit', [ArtistHandler::class, 'edit'])->name('artists.edit');
    $router->post('artists/save', [ArtistHandler::class, 'save'])->name('artists.save');
    $router->get('artists/{id}/delete', [ArtistHandler::class, 'delete'])->name('artists.delete');
});

$router->get('albums', [AlbumHandler::class, 'index'])->name('albums.index');
$router->get('albums/{id}/detail', [AlbumHandler::class, 'detail'])->name('albums.detail');
$router->group(IsLoggedInMiddleware::class, function (Router $router) {
    $router->get('albums/create', [AlbumHandler::class, 'create'])->name('albums.create');
    $router->get('albums/{id}/edit', [AlbumHandler::class, 'edit'])->name('albums.edit');
    $router->post('albums/save', [AlbumHandler::class, 'save'])->name('albums.save');
    $router->get('albums/{id}/delete', [AlbumHandler::class, 'delete'])->name('albums.delete');
});

$router->resource('genres', GenreHandler::class);
$router->get('user/login', [AccountHandler::class, 'login'])->name('account.login');
$router->post('user/login/post', [AccountHandler::class, 'loginPost'])->name('account.login.post');
$router->get('user/logout', [AccountHandler::class, 'logout'])->name('account.logout');
$router->get('user/register', [AccountHandler::class, 'register'])->name('account.register');
$router->get('api', [APIHandler::class, 'index'])->name('api');
