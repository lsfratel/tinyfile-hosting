<?php

$routes->group(['namespace' => 'app\controllers'], function ($routes) {
    $routes->get('/', 'App::welcome', 'welcome');
    $routes->get('/{id}', 'App::show', 'show');
    $routes->get('/download/{token}', 'App::download', 'download');
    $routes->post('/delete/{token}', 'App::delete', 'delete');
    $routes->post('/upload', 'App::upload', 'upload');
});
