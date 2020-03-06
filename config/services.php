<?php

use QuizApp\Controllers\SecurityController;
use QuizApp\Controllers\UserController;
use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use Framework\DependencyInjection\SymfonyContainer;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Routing\Router;
use QuizApp\Entities\User;
use QuizApp\Repository\SecurityRepository;
use QuizApp\Repository\UserRepository;
use QuizApp\Services\SecurityServices;
use QuizApp\Services\UserServices;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Hydrator\Hydrator;
use ReallyOrm\Test\Repository\RepositoryManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$container = new ContainerBuilder();

$configDB = require '../tests/db_config.php';

$dsn = "mysql:host={$configDB['host']};dbname={$configDB['db']};charset={$configDB['charset']}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

//$this->pdo = new PDO($dsn, $configDB['user'], $configDB['pass'], $options);
//$this->repoManager = new RepositoryManager();
//$this->hydrator = new Hydrator($this->repoManager);
//$this->userRepo = new UserRepository($this->pdo, User::class, $this->hydrator);
//$this->repoManager->addRepository($this->userRepo);

$container->setParameter('dsn', $dsn);
$container->setParameter('user', $configDB['user']);
$container->setParameter('pass', $configDB['pass']);
$container->setParameter('options', $options);
$container->register(PDO::class, PDO::class)
    ->addArgument('%dsn%')
    ->addArgument('%user%')
    ->addArgument('%pass%')
    ->addArgument('%options%');


$container->register(RepositoryManagerInterface::class, RepositoryManager::class);

$container->register(HydratorInterface::class, Hydrator::class)
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->setParameter('className',User::class);

$container->register(SecurityRepository::class, SecurityRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument('%className%')
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$container->register(UserRepository::class, UserRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument('%className%')
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$repoManager = $container->getDefinition(RepositoryManagerInterface::class);

foreach ($container->findTaggedServiceIds('repository') as $id => $value) {
    $repository = $container->getDefinition($id);
    $repoManager->addMethodCall('addRepository', [$repository]);
}

$container->register(SecurityServices::class,SecurityServices::class)
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->register(UserServices::class,UserServices::class)
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$config = require dirname(__DIR__) . '/config/config.php';
$container->setParameter('config', $config);
$container->register(RouterInterface::class, Router::class)
    ->addArgument('%config%');

$baseViewPath = $config['renderer'][Renderer::CONFIG_KEY_BASE_VIEW_PATH];
$container->setParameter('baseViewPath', $baseViewPath);
$container->register(RendererInterface::class, Renderer::class)
    ->addArgument('%baseViewPath%');

$container->register(SecurityController::class, SecurityController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(SecurityServices::class))
    ->addTag('controller');

$container->register(UserController::class, UserController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(UserServices::class))
    ->addTag('controller');

$controllerNamespace = $config['dispatcher']['controllers_namespace'];
$controllerSuffix = $config['dispatcher']['controller_class_suffix'];
$container->setParameter('controllerNamespace', $controllerNamespace);
$container->setParameter('controllerSuffix', $controllerSuffix);
$container->register(DispatcherInterface::class, Dispatcher::class)
    ->addArgument('%controllerNamespace%')
    ->addArgument('%controllerSuffix%');

$dispatcher = $container->getDefinition(DispatcherInterface::class);

foreach ($container->findTaggedServiceIds('controller') as $id => $value) {
    $controller = $container->getDefinition($id);
    $dispatcher->addMethodCall('addController', [$controller]);
}

return new SymfonyContainer($container);
