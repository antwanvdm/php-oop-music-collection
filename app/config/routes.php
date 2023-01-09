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

$router->group('artists', null, function ($router) {
    $router->get('', [ArtistHandler::class, 'index'])->name('index');
    $router->get('{id}/detail', [ArtistHandler::class, 'detail'])->name('detail');
    $router->group(null, IsLoggedInMiddleware::class, function (Router $router) {
        $router->get('create', [ArtistHandler::class, 'create'])->name('create');
        $router->get('{id}/edit', [ArtistHandler::class, 'edit'])->name('edit');
        $router->post('save', [ArtistHandler::class, 'save'])->name('save');
        $router->get('{id}/delete', [ArtistHandler::class, 'delete'])->name('delete');
    });
});

$router->group('albums', null, function ($router) {
    $router->get('', [AlbumHandler::class, 'index'])->name('index');
    $router->get('{id}/detail', [AlbumHandler::class, 'detail'])->name('detail');
    $router->group(null, IsLoggedInMiddleware::class, function (Router $router) {
        $router->get('create', [AlbumHandler::class, 'create'])->name('create');
        $router->get('{id}/edit', [AlbumHandler::class, 'edit'])->name('edit');
        $router->post('save', [AlbumHandler::class, 'save'])->name('save');
        $router->get('{id}/delete', [AlbumHandler::class, 'delete'])->name('delete');
    });
});

$router->resource('genres', GenreHandler::class);

$router->group('user', null, function ($router) {
    $router->get('login', [AccountHandler::class, 'login'])->name('login');
    $router->post('login/post', [AccountHandler::class, 'loginPost'])->name('login.post');
    $router->get('logout', [AccountHandler::class, 'logout'])->name('logout');
    $router->get('register', [AccountHandler::class, 'register'])->name('register');
});

$router->group('api', null, function ($router) {
    $router->get('', [APIHandler::class, 'index'])->name('index');
});
