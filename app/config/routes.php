<?php
/**
 * @var \System\Routing\Router $router
 */
$router->addRoute('', 'HomeHandler@index')->name('home');
$router->addRoute('artists', 'ArtistHandler@index')->name('artist.index');
$router->addRoute('artists/detail', 'ArtistHandler@detail')->name('artist.detail');
$router->addRoute('artists/add', 'ArtistHandler@add')->name('artist.add');
$router->addRoute('artists/edit', 'ArtistHandler@edit')->name('artist.edit');
$router->addRoute('artists/delete', 'ArtistHandler@delete')->name('artist.delete');
$router->addRoute('albums', 'AlbumHandler@index')->name('album.index');
$router->addRoute('albums/detail', 'AlbumHandler@detail')->name('album.detail');
$router->addRoute('albums/add', 'AlbumHandler@add')->name('album.add');
$router->addRoute('albums/edit', 'AlbumHandler@edit')->name('album.edit');
$router->addRoute('albums/delete', 'AlbumHandler@delete')->name('album.delete');
$router->addRoute('genres', 'GenreHandler@index')->name('genre.index');
$router->addRoute('genres/detail', 'GenreHandler@detail')->name('genre.detail');
$router->addRoute('genres/add', 'GenreHandler@add')->name('genre.add');
$router->addRoute('genres/edit', 'GenreHandler@edit')->name('genre.edit');
$router->addRoute('genres/delete', 'GenreHandler@delete')->name('genre.delete');
$router->addRoute('user/login', 'AccountHandler@login')->name('account.login');
$router->addRoute('user/logout', 'AccountHandler@logout')->name('account.logout');
$router->addRoute('user/register', 'AccountHandler@register')->name('account.register');
