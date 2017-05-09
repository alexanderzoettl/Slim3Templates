<?php

$app->get('/', 'StaticPageController:home')->setName('home');
$app->get('/about', 'StaticPageController:about')->setName('about');
$app->get('/impressum', 'StaticPageController:impressum')->setName('impressum');

$app->get('/exampleForm', 'FormController:get')->setName('exampleForm');
$app->post('/exampleForm', 'FormController:post')->setName('exampleForm');
