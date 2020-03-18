<?php

use PHPUnit\Framework\TestCase;
use QuizApp\Entities\QuestionTemplate;
use QuizApp\Entities\QuizInstance;
use QuizApp\Entities\QuizTemplate;
use QuizApp\Entities\User;
use QuizApp\Repository\QuestionTemplateRepository;
use QuizApp\Repository\QuizTemplateRepository;
use ReallyOrm\Test\Hydrator\Hydrator;
use ReallyOrm\Test\Repository\QuizRepository;
use ReallyOrm\Test\Repository\RepositoryManager;
use ReallyOrm\Test\Repository\UserRepository;

/**
 * Class FunTest.
 *
 * Have fun!
 */
class FunTest extends TestCase
{
    private $pdo;

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
    * @var QuestionTemplateRepository
     */
    private $questionRepo;

    /**
     * @var QuizTemplateRepository
     */
    private $quizRepo;

    /**
     * @var RepositoryManager
     */
    private $repoManager;

    protected function setUp(): void
    {
        parent::setUp();

        $config = require 'db_config.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['db']};charset={$config['charset']}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->pdo = new PDO($dsn, $config['user'], $config['pass'], $options);
        $this->repoManager = new RepositoryManager();
        $this->hydrator = new Hydrator($this->repoManager);
        $this->userRepo = new UserRepository($this->pdo, User::class, $this->hydrator);
        $this->questionRepo = new QuestionTemplateRepository($this->pdo, QuestionTemplate::class, $this->hydrator);
        $this->quizRepo = new QuizTemplateRepository($this->pdo, QuizTemplate::class, $this->hydrator);
        $this->repoManager->addRepository($this->userRepo);
        $this->repoManager->addRepository($this->questionRepo);
        $this->repoManager->addRepository($this->quizRepo);
    }

    public function testCreateUser(): void
    {
        $user = new User();
        $user->setName('ciwawa');
        $user->setEmail('email');
        $this->repoManager->register($user);
        $result = $user->save();

        $this->assertEquals(true, $result);
    }

    public function testCreateQuestion(): void
    {
        $questionTemplate = new QuestionTemplate();
        $questionTemplate->setText('test question');
        $questionTemplate->setType('text');
        $this->repoManager->register($questionTemplate);
        $result = $questionTemplate->save();

        $this->assertEquals(true, $result);
    }

    public function testCreateQuizTemplate(): void
    {
        $quizTemplate = new QuizTemplate();
        $quizTemplate->setName('test quiz');
        $quizTemplate->setDescription('testare');

        $this->repoManager->register($quizTemplate);
        $result = $quizTemplate->save();

        $this->assertEquals(true, $result);
    }

    public function testUpdateUser(): void
    {
        $user = $this->userRepo->find(1);
        $user->setPassword('admin');
        $this->repoManager->register($user);
        $result = $user->save();

        $this->assertEquals(true, $result);
    }


    public function testFind(): void
    {
        /** @var User $user */
        $user = $this->userRepo->find(1);
        $this->assertEquals(1, $user->getId());
    }

    /**
     * @throws ReflectionException
     */
    public function testHydrate(): void
    {
        $entitie = new User();
        $entitie->setName('john');
        $entitie->setEmail('john@email.com');
        $data = [
            'id' => null,
            'name' => 'john',
            'email' => 'john@email.com'
        ];
        $result = $this->hydrator->hydrate(User::class, $data);
        $this->assertEquals($entitie, $result);
    }

    /**
     * @throws ReflectionException
     */
    public function testExtract(): void
    {
        $user = new User();
        $user->setName('john');
        $user->setEmail('john@email.com');
        $data = [
            'id' => null,
            'name' => 'jhon',
            'email' => 'jhon@email.com'
        ];
        $result = $this->hydrator->extract($user);
        $this->assertEquals($data, $result);
    }

    /**
     * @test
     * @dataProvider findOneByProvider
     * @param $filter
     */
    public function testFindOneBy($filter): void
    {
        /** @var User $user */
        $user = $this->userRepo->findOneBy($filter);
        $this->assertEquals(1, $user->getId());
    }

    /**
     * @return array
     */
    public function findOneByProvider()
    {
        return [
            [
                [
                    'email' => 'admin@email.com'
                ]
            ],
            [
                [
                    'password' => 'admin',
                    'email' => 'admin@email.com'
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider findByProvider
     * @param $filter
     * @param $sorts
     */
    public function testFindBy($filter, $sorts): void
    {
        /** @var User $user */
        $user = $this->userRepo->findBy($filter, $sorts, 0, 1);
        $this->assertEquals(1, $user[0]->getId());
    }

    /**
     * @return array
     */
    public function findByProvider()
    {
        return [
            [
                [
                    'name' => 'paul'
                ],
                [
                    'name' => 'DESC'
                ]
            ],
            [
                [
                    'name' => 'paul',
                    'email' => 'paul@email.com'
                ],
                [
                    'name' => 'DESC',
                    'email' => 'ASC'
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function testInsertOnDuplicateKeyUpdate(): void
    {
        /** @var User $user */

        $user = $this->userRepo->find(2);
        $user->setName("paul");
        $result = $this->userRepo->insertOnDuplicateKeyUpdate($user);

        $this->assertEquals(1, $result);
    }


    /**
     * @test
     */
    public function testDelete()
    {
        $user = $this->userRepo->find(9);
        $result = $this->userRepo->delete($user);

        $this->assertEquals(1, $result);
    }

    /**git
     * @test
     */
    public function testCreateQuizInstance(): void
    {
        $quiz = new QuizInstance();
        $quiz->setName('ciwawa');
        $quiz->setScore(9);
        $this->repoManager->register($quiz);
        $result = $quiz->save();

        $this->assertEquals(true, $result);
    }

    /**
     * @test
     */
    public function testUpdateQuiz(): void
    {
        $quiz = $this->quizRepo->find(3);
        $quiz->setScore(8);
        $this->repoManager->register($quiz);
        $result = $quiz->save();

        $this->assertEquals(true, $result);
    }

}
