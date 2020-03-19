<?php


namespace QuizApp\Entity;

use ReallyOrm\Entity\AbstractEntity;

class User extends AbstractEntity
{
    /**
     * @var int
     * @ID
     * @ORM id
     */
    private $id;

    /**
     * @var string
     * @ORM name
     */
    private $name;

    /**
     * @var string
     * @ORM email
     */
    private $email;

    /**
     * @var string
     * @ORM password
     */
    private $password;

    /**
     * @var string
     * @ORM role
     */
    private $role;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->id = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'Candidate';
    }

    /**
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param string
     */
    public function setPassword($password)
    {
        $this->password = md5($password);
    }

    /**
     * @param string
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

}
