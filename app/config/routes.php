<?php
/**
 * @var \System\Routing\Router $router
 */
$router->get('', 'HomeHandler@index')->name('home');
$router->get('artists', 'ArtistHandler@index')->name('artist.index');
$router->get('artists/add', 'ArtistHandler@add')->name('artist.add');
$router->get('artists/detail/{id}', 'ArtistHandler@detail')->name('artist.detail');
$router->get('artists/edit/{id}', 'ArtistHandler@edit')->name('artist.edit');
$router->get('artists/delete/{id}', 'ArtistHandler@delete')->name('artist.delete');
$router->post('artists/save', 'ArtistHandler@save')->name('artist.save');
$router->get('albums', 'AlbumHandler@index')->name('album.index');
$router->get('albums/add', 'AlbumHandler@add')->name('album.add');
$router->get('albums/detail/{id}', 'AlbumHandler@detail')->name('album.detail');
$router->get('albums/edit/{id}', 'AlbumHandler@edit')->name('album.edit');
$router->get('albums/delete/{id}', 'AlbumHandler@delete')->name('album.delete');
$router->post('albums/save', 'AlbumHandler@save')->name('album.save');
$router->get('genres', 'GenreHandler@index')->name('genre.index');
$router->get('genres/add', 'GenreHandler@add')->name('genre.add');
$router->get('genres/detail/{id}', 'GenreHandler@detail')->name('genre.detail');
$router->get('genres/edit/{id}', 'GenreHandler@edit')->name('genre.edit');
$router->get('genres/delete/{id}', 'GenreHandler@delete')->name('genre.delete');
$router->post('genres/save', 'GenreHandler@save')->name('genre.save');
$router->get('user/login', 'AccountHandler@login')->name('account.login');
$router->get('user/logout', 'AccountHandler@logout')->name('account.logout');
$router->get('user/register', 'AccountHandler@register')->name('account.register');
$router->get('api', 'APIHandler@index')->name('api');
