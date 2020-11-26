<?php
$routes = [
    '' => 'HomeHandler@index',
    'artists' => 'ArtistHandler@index',
    'artists/detail' => 'ArtistHandler@detail',
    'artists/add' => 'ArtistHandler@add',
    'artists/edit' => 'ArtistHandler@edit',
    'artists/delete' => 'ArtistHandler@delete',
    'albums' => 'AlbumHandler@index',
    'albums/detail' => 'AlbumHandler@detail',
    'albums/add' => 'AlbumHandler@add',
    'albums/edit' => 'AlbumHandler@edit',
    'albums/delete' => 'AlbumHandler@delete',
    'genres' => 'GenreHandler@index',
    'genres/detail' => 'GenreHandler@detail',
    'genres/add' => 'GenreHandler@add',
    'genres/edit' => 'GenreHandler@edit',
    'genres/delete' => 'GenreHandler@delete',
    'user/login' => 'AccountHandler@login',
    'user/logout' => 'AccountHandler@logout',
    'user/register' => 'AccountHandler@register'
];
