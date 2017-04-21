<?php

//Static Pages
$app->get('/', 'StaticPageController:home')->setName('home');
$app->get('/about', 'StaticPageController:about')->setName('about');
$app->get('/impressum', 'StaticPageController:impressum')->setName('impressum');

//Authentication (Signup, Signin)
$app->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
$app->post('/auth/signup', 'AuthController:postSignUp');
