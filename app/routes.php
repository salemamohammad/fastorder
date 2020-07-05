<?php

$app->get('/','HomeController:index');
$app->post('/fetch_product','HomeController:fetchProduct')->setName('fetch_product');