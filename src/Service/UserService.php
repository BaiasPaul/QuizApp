<?php

namespace QuizApp\Service;

use Framework\Contracts\SessionInterface;
use phpDocumentor\Reflection\Types\This;
use QuizApp\Entity\QuestionInstance;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\User;
use ReallyOrm\Test\Repository\RepositoryManager;

/**
 * Class UserService
 * @package QuizApp\Service
 */
class UserService extends AbstractService
{

    /**
     * This method creates an user
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $role
     */
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

    /**
     * This method updates an existing user
     *
     * @param $id
     * @param $name
     * @param $email
     * @param $password
     * @param $role
     */
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

    /**
     * This method returns an array with the attributes of an user for autocomplete in the edit user form
     *
     * @param int $id
     * @return array
     */
    public function getParams(int $id)
    {
        $user = $this->repoManager->getRepository(User::class)->find($id);

        return ['id' => $user->getId(), 'name' => $user->getName(), 'email' => $user->getEmail(), 'password' => $user->getPassword(), 'role' => $user->getRole()];
    }

    /**
     * This method removes a user with the specified id from the database
     *
     * @param $id
     * @return bool
     */
    public function deleteUser($id)
    {
        $user = $this->repoManager->getRepository(User::class)->find($id);

        return $this->repoManager->getRepository(User::class)->delete($user);
    }

    /**
     * Will be moved in another issue
     *
     * @param int $currentPage
     * @return mixed
     */
    public function getQuestionsInfo(int $currentPage)
    {
        return $this->repoManager->getRepository(User::class)->getQuestions(($currentPage - 1) * 5, 5);
    }

    /**
     * Will be moved in another issue
     *
     * @param $id
     * @return mixed
     */
    public function getQuestionsAnswered($id)
    {
        return $this->repoManager->getRepository(QuestionInstance::class)->getAllQuestionsAnswered($id);
    }

    /**
     * Will be moved in another issue
     *
     * @param $score
     */
    public function setScore($score)
    {
        $quizInstance = $this->repoManager->getRepository(QuizInstance::class)->find($this->session->get('quizInstanceId'));
        $quizInstance->setScore($score);
        $quizInstance->setRepositoryManager($this->repoManager);
        $quizInstance->save();
    }

}
