<?php

namespace App\Models;

use Phalcon\Mvc\Model;

/**
 * Class Users
 * @package App\Models
 */
class Users extends Model
{
    /** @var integer $id */
    private $id;
    /** @var string $login */
    private $login;
    /** @var string $password */
    private $password;
    /** @var integer $login_attempts*/
    public $login_attempts;
    /** @var \DateTime $created */
    private $created;

    public function getDI()
    {
        parent::getDI(); // TODO: Change the autogenerated stub
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param null $parameters
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Model
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Delete the current user token in session
     */
    protected function deleteToken($token)
    {
        $user = Users::find([
            'conditions' => 'token = :token:',
            'bind'       => [
                'token' => $token
            ]
        ]);

        if ($user) {
            $user->delete();
        }
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'login' => 'login',
            'password' => 'password',
            'login_attempts' => 'loginAttempts',
            'created' => 'created',
        );
    }

    public function getUserLiset()
    {
        return "user list";
    }

    /**
     * @param $username
     * @param $password
     * @return Model|null|bool
     */
    public function getUser($username, $password)
    {
        $result = self::query()
            ->where('login = :login:')
            ->bind(['login' => $username])
            ->execute()
            ->getFirst();
        return $result ?: null;
    }


    //* default getters and setters are below*//

    /**
     * Get getId
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set Id
     * Private, cause must be generated automatically, i doubt
     * @param int $id
     */
    private function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get getLogin
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Set Login
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * Get getPassword
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set Password
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Get getSalt
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * Set Salt
     * @param string $salt
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * Get getCreated
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * Set Created
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }
}