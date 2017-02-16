<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\App as Slim;
use \Slim\Views\Twig as Twig;
use \Slim\Views\TwigExtension as TwigExtension;

use \Boot\Loader as Loader;

require __DIR__.'/../vendor/autoload.php';
require __DIR__ . '/../app/Loader.php';

define("DS", DIRECTORY_SEPARATOR);
define("ROOT_PATH", dirname(__FILE__), true);

// Start PHP session
session_start();

/** @var \Boot\Loader $loader */
$loader = new Loader();
$loader::run();

/** @var \Slim\App $app */
$app = new Slim([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$container = $app->getContainer();

// Register provider
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$container['view'] = function($container){
    /** @var \Slim\Views\Twig $view */
    $view = new Twig('../resources/view/html');
    /** @var string $basePath */
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new TwigExtension($container['router'], $basePath));

    return $view;
};

// Add middleware for flash message
$app->add(function ($request, $response, $next) {
    $this->view->offsetSet("flash", $this->flash);
    return $next($request, $response);
});

/**
 * Index URL path
 * @url {root} /
 */
$app->get('/', function (Request $request, Response $response) {
    $douceurHelper = new \Helpers\DouceurHelper();
    $douceurs = $douceurHelper->getDouceurs();

    // band
    $bandHelper = new \Helpers\BandsHelper();
    $bands = $bandHelper->getAllBands();

    return $this->view->render($response, 'page/index.html.twig', [
        "douceurs" => $douceurs,
        "bands" => $bands
    ]);
})->setName('root');

/**
 * Resource [DOUCEUR]
 *
 * @url [GET]       /douceur/{id} : view douceur
 * @url [GET]       /douceur/new : create view douceur
 * @url [POST]      /douceur/new : save view douceur
 * @url [PUT]       /douceur/{id}/update : update douceur
 * @url [DELETE]    /douceur/{id}/delete : delete douceur
 */
$app->get('/douceur/new', function (Request $request, Response $response) {
    return $this->view->render($response, 'page/douceur/create.html.twig');
})->setName('create_douceur');

$app->post('/douceur/new', function (Request $request, Response $response) use ($app) {
    /** @var \Helpers\DouceurHelper $douceurHelper */
    $douceurHelper = new \Helpers\DouceurHelper();
    if (!$douceurHelper->create([
        "body"      => $request->getParams(),
        "upload"    => $request->getUploadedFiles()
    ])) {
        $this->flash->addMessage('flash', ['info', 'Le format de votre image n\'est pas autorisée.']);
        return $response->withRedirect($app->getContainer()->get('router')->pathFor('create_douceur'));
    }
    $this->flash->addMessage('flash', ['success', 'Votre douceur a bien été créée']);
    return $response->withRedirect($app->getContainer()->get('router')->pathFor('root'));
});

$app->get('/douceur/{id}', function ($request, $response, $args) use ($app) {
    /** @var \Helpers\DouceurHelper $douceurHelper */
    $douceurHelper = new \Helpers\DouceurHelper();
    /** @var string $rootPath */
    $rootPath = $app->getContainer()->get('router')->pathFor('root');
    if (!isset($args["id"])) {
        return $response->withRedirect($rootPath);
    }
    /** @var \Models\Douceur $douceur */
    $douceur = $douceurHelper->getDouceur($args["id"]);
    if (!isset($douceur) || !$douceur) {
        return $response->withRedirect($rootPath);
    }

    return $this->view->render($response, 'page/douceur/view.html.twig', ["douceur" => $douceur]);
})->setName('view_douceur');

$app->get('/douceur/edit/{id}', function ($request, $response, $args) use ($app)  {
    /** @var \Helpers\DouceurHelper $douceurHelper */
    $douceurHelper = new \Helpers\DouceurHelper();
    /** @var string $rootPath */
    $rootPath = $app->getContainer()->get('router')->pathFor('root');
    if (!isset($args["id"])) {
        return $response->withRedirect($rootPath);
    }
    /** @var \Models\Douceur $douceur */
    $douceur = $douceurHelper->getDouceur($args["id"]);
    if (!isset($douceur) || !$douceur) {
        return $response->withRedirect($rootPath);
    }

    return $this->view->render($response, 'page/douceur/edit.html.twig', ["douceur" => $douceur]);
});

$app->post('/douceur/edit/{id}', function ($request, $response, $args) use ($app)  {
    /** @var \Helpers\DouceurHelper $douceurHelper */
    $douceurHelper = new \Helpers\DouceurHelper();
    /** @var string $rootPath */
    $rootPath = $app->getContainer()->get('router')->pathFor('root');
    if (!isset($args["id"])) {
        return $response->withRedirect($rootPath);
    }
    /** @var \Models\Douceur $douceur */
    $douceur = $douceurHelper->updateDouceur([
        "id"        => $args["id"],
        "body"      => $request->getParams(),
        "upload"    => $request->getUploadedFiles()
    ]);
    if (!isset($douceur) || !$douceur) {
        return $response->withRedirect($rootPath);
    }
    $this->flash->addMessage('flash', ['success', 'La douceur a bien été mis à jour']);

    return $response->withRedirect($app->getContainer()->get('router')->pathFor('view_douceur', ["id" => $args["id"]]));
});

$app->get('/douceur/delete/{id}', function (Request $request, Response $response, $args) use ($app) {
    /** @var \Helpers\DouceurHelper $douceurHelper */
    $douceurHelper = new \Helpers\DouceurHelper();
    /** @var string $rootPath */
    $rootPath = $app->getContainer()->get('router')->pathFor('root');
    if (!isset($args["id"]) || !$douceurHelper->deleteDouceur($args["id"])) {
        return $response->withRedirect($rootPath);
    }
    $this->flash->addMessage('flash', ['success', 'La douceur a bien été supprimer']);

    return $response->withRedirect($rootPath);
});

/**
 * Resource [Band]
 *
 * @url [GET]       /band/ : view band
 * @url [GET]       /band/new : create view band
 * @url [POST]      /band/new : create a new band
 * @url [PUT]       /band/{id}/update : update band
 * @url [DELETE]    /band/{id}/delete : delete band
 */
$app->get('/band', function(Request $request, Response $response, $args) use ($app) {
    $bandHelper = new \Helpers\BandsHelper();
    return $this->view->render($response, 'page/band/band.html.twig', [
        'band' => $bandHelper
    ]);
})->setName('bandView');

$app->get('/band/new', function(Request $request, Response $response, $args) use ($app) {
    $bandHelper = new \Helpers\BandsHelper();

    $douceurHelper = new \Helpers\DouceurHelper();
    $douceurs = $douceurHelper->getDouceurs();
    return $this->view->render($response, 'page/band/band_create.html.twig', [
        'band' => $bandHelper,
        'douceurs' => $douceurs
    ]);
});

$app->post('/band/new', function(Request $request, Response $response, $args) use ($app) {
    $bandHelper = new \Helpers\BandsHelper();
    $res = $bandHelper->createBand($request);

    if (is_bool($res) && $res)
        // we can't use the 'php redirect' as the response is based on fetch / ajax response..
        return $response->withJson(array('url' => '/band'));
    else
        return $response->withJson(array(
            'error' => $res
        ));
});

$app->delete('/band/{id}', function(Request $request, Response $response, $args) use ($app){
    $bandHelper = new \Helpers\BandsHelper();
    $res = $bandHelper->removeBand($args['id']);

    if($res)
        return $response->withJson(array('url' => '/band'));
    else
        return $response->withJson(array('error' => $res));

});

$app->get('/band/{name}', function(Request $request, Response $response, $args){
    $bandHelper = new \Helpers\BandsHelper();
    $data = $bandHelper->getBands($args['name']);
    $douceurHelper = new \Helpers\DouceurHelper();
    $douceurs = $douceurHelper->getDouceurs();

    return $this->view->render($response, 'page/band/band_edit.html.twig', [
        'controller' => $bandHelper,
        'band' => $data[0],
        'douceurs' => $douceurs
    ]);
});

$app->post('/band/removeAll', function(Request $request, Response $response, $args){
    $bandHelper = new \Helpers\BandsHelper();
    $res = $bandHelper->removeAllDouceur($request);

    if($res)
        return $response->withJson(array('status' => 'success'));
    else
        return $response->withJson(array('error' => $res));
});

$app->post('/band/update', function(Request $request, Response $response, $args){
    $bandHelper = new \Helpers\BandsHelper();
    $res = $bandHelper->update($request);
    //$bandPath = $app->getContainer()->get('router')->pathFor('bandView');

    if($res)
        return $response->withJson(array('url' => '/band'));
    else
        return $response->withJson(array('error' => $res));
});


$app->run();
