<?php

session_start();

require __DIR__ .'/../vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['driver'] 	= 'mysql';
$config['db']['host'] 		= '127.0.0.1';
$config['db']['database'] 	= 'fastorder';
$config['db']['username'] 	= 'root';
$config['db']['password']   = '';
$config['db']['charset'] 	= 'utf8';
$config['db']['collection'] = 'utf8_unicode_ci';
$config['db']['prefix'] 	= '';

// $app = new \Slim\App;
$app = new \Slim\App(['settings' => $config]);

// Get container
$container = $app->getContainer();
$capsule = new \Illuminate\Database\Capsule\Manager;

$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Register component on container for db
$container['db'] = function ($container) use ($capsule) {
	return $capsule;
};

// Register component on container for view
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ .'/../resources/views', [
        'cache' => false
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
    	$container->router,
    	$container->request->getUri()
    ));
    return $view;
};


// Register component on container home controller
$container['HomeController'] = function ($container) {
    return new \App\Controllers\HomeController($container);
};

require __DIR__ .'/../app/routes.php';
?>