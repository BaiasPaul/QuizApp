<?php

namespace QuizApp\Services;

use Framework\Contracts\SessionInterface;
use phpDocumentor\Reflection\Types\This;
use QuizApp\Entities\QuestionInstance;
use QuizApp\Entities\QuizInstance;
use QuizApp\Entities\User;
use ReallyOrm\Test\Repository\RepositoryManager;

class UserService extends AbstractService
{

    public function saveUser(string $name, string $email, string $password, string $role)
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($role);
        $filters = ['email' => $email];
        $result = $this->repoManager->getRepository(User::class)->findOneBy($filters);
        if (!$result) {
            $this->repoManager->register($user);
            $user->save();
        }
    }

    public function getUsers(array $filters, int $currentPage)
    {
        return $this->repoManager->getRepository(User::class)->findBy($filters, [], ($currentPage - 1) * 5, 5);
    }

    public function getUserNumber(array $filters)
    {
        return $this->repoManager->getRepository(User::class)->getNumberOfEntities($filters);
    }

    public function getQuizzesNumber(array $filters)
    {
        return $this->repoManager->getRepository(QuizInstance::class)->getNumberOfEntities($filters);
    }

    public function editUser($id, $name, $email, $password, $role)
    {
        $user = $this->repoManager->getRepository(User::class)->find($id);
        $user->setName($name);
        $user->setEmail($email);
        if ($password !== "") {
            $user->setPassword($password);
        }
        $user->setRole($role);
        $filters = ['email' => $email];
        $result = $this->repoManager->getRepository(User::class)->findOneBy($filters);
        if ($result){
            $this->repoManager->register($user);
            $user->save();
        }
    }

    public function getParams(int $id)
    {
        $user = $this->repoManager->getRepository(User::class)->find($id);

        return ['id' => $user->getId(), 'name' => $user->getName(), 'email' => $user->getEmail(), 'password' => $user->getPassword(), 'role' => $user->getRole()];
    }

    public function deleteUser($id)
    {
        $user = $this->repoManager->getRepository(User::class)->find($id);

        return $this->repoManager->getRepository(User::class)->delete($user);
    }

    public function getEmptyParams()
    {
       return  ['id' => '', 'name' => '', 'email' => '', 'password' => '', 'role' => ''];
    }

    public function getQuestionsInfo(int $currentPage)
    {
        return $this->repoManager->getRepository(User::class)->getQuestions(($currentPage - 1) * 5, 5);
    }

    public function getQuestionsAnswered($id)
    {
        return $this->repoManager->getRepository(QuestionInstance::class)->getAllQuestionsAnswered($id);
    }

    public function setScore($score)
    {
        $quizInstance = $this->repoManager->getRepository(QuizInstance::class)->find($this->session->get('quizInstanceId'));
        $quizInstance->setScore($score);
        $quizInstance->setRepositoryManager($this->repoManager);
        $quizInstance->save();
    }

}
