<?php

$app->get('/', 'HomeController:index')->setName('home');
$app->get('/about', 'AboutController:index')->setName('about');

$app->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
$app->post('/auth/signup', 'AuthController:postSignUp');
