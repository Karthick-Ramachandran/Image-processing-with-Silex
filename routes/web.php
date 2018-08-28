<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/{width}/{height}', function(Request $request, Silex\Application $app, $width, $height ){

$clause = $request->get('image') ? "WHERE id= ?" : "ORDER BY rand() LIMIT 1";
 
    
$image = $app['db']->fetchAssoc("SELECT filename FROM images {$clause}", [$request->get('image')]);


$placeholder = $app['cache']->fetch($cacheKey = "{$width}:{$height}:{$request->get('image')}");

if($placeholder === false){

    $placeholder = $app['image']
    ->make(__DIR__ . '/../public/img/' . $image['filename'])
    ->fit($width, $height)
    ->response('jpg');

    $app['cache']->store($cacheKey, $placeholder);
}

    return new Response($placeholder, 200, [
        'Content-Type' => 'image/jpg'
    ]);
})->assert('width', '[0-9]+')->assert('height', '[0-9]+');