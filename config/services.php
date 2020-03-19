<?php

use Framework\Contracts\SessionInterface;
use Framework\Session\Session;
use QuizApp\Controller\QuestionInstanceController;
use QuizApp\Controller\QuestionTemplateController;
use QuizApp\Controller\QuizInstanceController;
use QuizApp\Controller\QuizTemplateController;
use QuizApp\Controller\UserController;
use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use Framework\DependencyInjection\SymfonyContainer;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Routing\Router;
use QuizApp\Controllers\AuthController;
use QuizApp\Entity\AnswerInstance;
use QuizApp\Entity\AnswerTemplate;
use QuizApp\Entity\QuestionInstance;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Entity\User;
use QuizApp\Repository\AnswerInstanceRepository;
use QuizApp\Repository\AnswerTemplateRepository;
use QuizApp\Repository\QuestionInstanceRepository;
use QuizApp\Repository\QuestionTemplateRepository;
use QuizApp\Repository\QuizInstanceRepository;
use QuizApp\Repository\QuizTemplateRepository;
use QuizApp\Repository\UserRepository;
use QuizApp\Service\AppMessageManager;
use QuizApp\Service\AuthService;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuestionTemplateService;
use QuizApp\Service\QuizInstanceService;
use QuizApp\Service\QuizTemplateService;
use QuizApp\Service\UserService;
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
$container->register(SessionInterface::class, Session::class);

$container->register(HydratorInterface::class, Hydrator::class)
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->setParameter('userClass',User::class);
$container->register(UserRepository::class, UserRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument('%userClass%')
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$container->setParameter('questionTemplateClass',QuestionTemplate::class);
$container->register(QuestionTemplateRepository::class, QuestionTemplateRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument('%questionTemplateClass%')
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$container->setParameter('questionInstanceClass',QuestionInstance::class);
$container->register(QuestionInstanceRepository::class, QuestionInstanceRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument('%questionInstanceClass%')
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$container->setParameter('answerTemplateClass',AnswerTemplate::class);
$container->register(AnswerTemplateRepository::class, AnswerTemplateRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument('%answerTemplateClass%')
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$container->setParameter('answerInstanceClass',AnswerInstance::class);
$container->register(AnswerInstanceRepository::class, AnswerInstanceRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument('%answerInstanceClass%')
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$container->setParameter('quizTemplateClass',QuizTemplate::class);
$container->register(QuizTemplateRepository::class, QuizTemplateRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument('%quizTemplateClass%')
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$container->setParameter('quizInstanceClass',QuizInstance::class);
$container->register(QuizInstanceRepository::class, QuizInstanceRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument('%quizInstanceClass%')
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$repoManager = $container->getDefinition(RepositoryManagerInterface::class);

foreach ($container->findTaggedServiceIds('repository') as $id => $value) {
    $repository = $container->getDefinition($id);
    $repoManager->addMethodCall('addRepository', [$repository]);
}

$container->register(AppMessageManager::class,AppMessageManager::class)
    ->addArgument(new Reference(SessionInterface::class));

$container->register(QuizTemplateService::class,QuizTemplateService::class)
    ->addArgument(new Reference(SessionInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->register(QuizInstanceService::class,QuizInstanceService::class)
    ->addArgument(new Reference(SessionInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->register(AuthService::class,AuthService::class)
    ->addArgument(new Reference(SessionInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->register(QuestionTemplateService::class,QuestionTemplateService::class)
    ->addArgument(new Reference(SessionInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->register(QuestionInstanceService::class,QuestionInstanceService::class)
    ->addArgument(new Reference(SessionInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->register(UserService::class,UserService::class)
    ->addArgument(new Reference(SessionInterface::class))
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$config = require dirname(__DIR__) . '/config/config.php';
$container->setParameter('config', $config);
$container->register(RouterInterface::class, Router::class)
    ->addArgument('%config%');

$baseViewPath = $config['renderer'][Renderer::CONFIG_KEY_BASE_VIEW_PATH];
$container->setParameter('baseViewPath', $baseViewPath);
$container->register(RendererInterface::class, Renderer::class)
    ->addArgument('%baseViewPath%');

$container->register(QuizTemplateController::class, QuizTemplateController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(QuizTemplateService::class))
    ->addTag('controller');

$container->register(QuizInstanceController::class, QuizInstanceController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(QuizInstanceService::class))
    ->addTag('controller');

$container->register(QuestionTemplateController::class, QuestionTemplateController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(QuestionTemplateService::class))
    ->addTag('controller');

$container->register(QuestionInstanceController::class, QuestionInstanceController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(QuestionInstanceService::class))
    ->addTag('controller');

$container->register(AuthController::class, AuthController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(AuthService::class))
    ->addArgument(new Reference(AppMessageManager::class))
    ->addTag('controller');

$container->register(UserController::class, UserController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(UserService::class))
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
