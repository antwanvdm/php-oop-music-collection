<?php
/**
 * @var \System\Routing\Router $router
 */
$router->addRoute('', 'HomeHandler@index')->name('home');
$router->addRoute('artists', 'ArtistHandler@index')->name('artist.index');
$router->addRoute('artists/add', 'ArtistHandler@add')->name('artist.add');
$router->addRoute('artists/detail/{id}', 'ArtistHandler@detail')->name('artist.detail');
$router->addRoute('artists/edit/{id}', 'ArtistHandler@edit')->name('artist.edit');
$router->addRoute('artists/delete/{id}', 'ArtistHandler@delete')->name('artist.delete');
$router->addRoute('albums', 'AlbumHandler@index')->name('album.index');
$router->addRoute('albums/add', 'AlbumHandler@add')->name('album.add');
$router->addRoute('albums/detail/{id}', 'AlbumHandler@detail')->name('album.detail');
$router->addRoute('albums/edit/{id}', 'AlbumHandler@edit')->name('album.edit');
$router->addRoute('albums/delete/{id}', 'AlbumHandler@delete')->name('album.delete');
$router->addRoute('genres', 'GenreHandler@index')->name('genre.index');
$router->addRoute('genres/add', 'GenreHandler@add')->name('genre.add');
$router->addRoute('genres/detail/{id}', 'GenreHandler@detail')->name('genre.detail');
$router->addRoute('genres/edit/{id}', 'GenreHandler@edit')->name('genre.edit');
$router->addRoute('genres/delete/{id}', 'GenreHandler@delete')->name('genre.delete');
$router->addRoute('user/login', 'AccountHandler@login')->name('account.login');
$router->addRoute('user/logout', 'AccountHandler@logout')->name('account.logout');
$router->addRoute('user/register', 'AccountHandler@register')->name('account.register');
$router->addRoute('api', 'APIHandler@index')->name('api');
