<?php

use MusicCollection\Middleware\IsLoggedInMiddleware;
use MusicCollection\Routing\Router;

/**
 * @var Router $router
 */
$router->get('', 'HomeHandler@index')->name('home');
$router->post('language/change', 'LanguageHandler@change')->name('language.change');
$router->get('artists', 'ArtistHandler@index')->name('artist.index');
$router->get('artists/detail/{id}', 'ArtistHandler@detail')->name('artist.detail');
$router->get('artists/create', 'ArtistHandler@create')->middleware(IsLoggedInMiddleware::class)->name('artist.create');
$router->get('artists/edit/{id}', 'ArtistHandler@edit')->middleware(IsLoggedInMiddleware::class)->name('artist.edit');
$router->get('artists/delete/{id}', 'ArtistHandler@delete')->middleware(IsLoggedInMiddleware::class)->name('artist.delete');
$router->post('artists/save', 'ArtistHandler@save')->middleware(IsLoggedInMiddleware::class)->name('artist.save');
$router->get('albums', 'AlbumHandler@index')->name('album.index');
$router->get('albums/detail/{id}', 'AlbumHandler@detail')->name('album.detail');
$router->get('albums/create', 'AlbumHandler@create')->middleware(IsLoggedInMiddleware::class)->name('album.create');
$router->get('albums/edit/{id}', 'AlbumHandler@edit')->middleware(IsLoggedInMiddleware::class)->name('album.edit');
$router->get('albums/delete/{id}', 'AlbumHandler@delete')->middleware(IsLoggedInMiddleware::class)->name('album.delete');
$router->post('albums/save', 'AlbumHandler@save')->middleware(IsLoggedInMiddleware::class)->name('album.save');
$router->get('genres', 'GenreHandler@index')->name('genre.index');
$router->get('genres/create', 'GenreHandler@create')->name('genre.create');
$router->get('genres/detail/{id}', 'GenreHandler@detail')->name('genre.detail');
$router->get('genres/edit/{id}', 'GenreHandler@edit')->name('genre.edit');
$router->get('genres/delete/{id}', 'GenreHandler@delete')->name('genre.delete');
$router->post('genres/save', 'GenreHandler@save')->name('genre.save');
$router->get('user/login', 'AccountHandler@login')->name('account.login');
$router->post('user/login/post', 'AccountHandler@loginPost')->name('account.login.post');
$router->get('user/logout', 'AccountHandler@logout')->name('account.logout');
$router->get('user/register', 'AccountHandler@register')->name('account.register');
$router->get('api', 'APIHandler@index')->name('api');
