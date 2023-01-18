<?php

use MusicCollection\Controllers\Api\FavoriteController as ApiFavoriteController;
use MusicCollection\Controllers\Web\AccountController;
use MusicCollection\Controllers\Web\AlbumController;
use MusicCollection\Controllers\Web\ArtistController;
use MusicCollection\Controllers\Web\GenreController;
use MusicCollection\Controllers\Web\HomeController;
use MusicCollection\Controllers\Web\LanguageController;
use MusicCollection\Middleware\IsLoggedInMiddleware;
use MusicCollection\Routing\Router;

/**
 * @var Router $router
 */
$router->get('', [HomeController::class, 'index'])->name('home');
$router->post('language/change', [LanguageController::class, 'change'])->name('language.change');

$router->group('artists', null, function (Router $router) {
    $router->get('', [ArtistController::class, 'index'])->name('index');
    $router->get('{id}', [ArtistController::class, 'detail'])->name('detail');
    $router->group(null, IsLoggedInMiddleware::class, function (Router $router) {
        $router->get('create', [ArtistController::class, 'create'])->name('create');
        $router->get('{id}/edit', [ArtistController::class, 'edit'])->name('edit');
        $router->post('save', [ArtistController::class, 'save'])->name('save');
        $router->get('{id}/delete', [ArtistController::class, 'delete'])->name('delete');
    });
});

$router->group('albums', null, function (Router $router) {
    $router->get('', [AlbumController::class, 'index'])->name('index');
    $router->get('{id}', [AlbumController::class, 'detail'])->name('detail');
    $router->group(null, IsLoggedInMiddleware::class, function (Router $router) {
        $router->get('create', [AlbumController::class, 'create'])->name('create');
        $router->get('{id}/edit', [AlbumController::class, 'edit'])->name('edit');
        $router->post('save', [AlbumController::class, 'save'])->name('save');
        $router->get('{id}/delete', [AlbumController::class, 'delete'])->name('delete');
    });
});

$router->resource('genres', GenreController::class);

$router->group('user', null, function (Router $router) {
    $router->get('login', [AccountController::class, 'login'])->name('login');
    $router->post('login/post', [AccountController::class, 'loginPost'])->name('login.post');
    $router->get('logout', [AccountController::class, 'logout'])->name('logout');
});

$router->group('api', null, function (Router $router) {
    $router->get('favorite/{id}', [ApiFavoriteController::class, 'toggle'])->middleware(IsLoggedInMiddleware::class)->name('favorite.toggle');
});
