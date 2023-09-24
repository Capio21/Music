<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'MusicController::index');
$routes->post('/saveMusic', 'MusicController::saveMusic');
$routes->post('/savePlayList', 'MusicController::savePlaylist');
$routes->post('/addToPlaylist', 'MusicController::addToPlaylist');
$routes->get('/(:any)', 'MusicController::playlist/$1');
