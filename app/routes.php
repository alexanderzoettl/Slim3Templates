<?php

//Static Pages
$app->get('/', 'StaticPageController:home')->setName('home');
$app->get('/about', 'StaticPageController:about')->setName('about');
$app->get('/impressum', 'StaticPageController:impressum')->setName('impressum');

//Authentication (Signup, Signin)
$app->get('/signup', 'AuthController:getSignUp')->setName('auth.signup');
$app->post('/signup', 'AuthController:postSignUp');
$app->get('/signin', 'AuthController:getSignIn')->setName('auth.signin');
$app->post('/signin', 'AuthController:postSignIn');
$app->get('/logout', 'AuthController:getLogout')->setName('auth.logout');
