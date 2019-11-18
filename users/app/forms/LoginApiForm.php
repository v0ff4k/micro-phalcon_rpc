<?php

namespace app\forms;

use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Regex as RegexValidator;

/**
 * Class LoginForm
 *
 * This component allows to build form using an object-oriented interface
 * @package app\forms
 */
class LoginApiForm extends \Phalcon\Forms\Form
{
    /**
     * Array of validators for hidden crypt field
     * @return array
     */
    private function cryptValidators()
    {
        return [
            new Identical([
                'value' => $this->checkCrypt(),
                'message' => 'Cross site request is forbidden'
            ]),
            new PresenceOf([
                'message' => 'Crypt is strictly required'
            ]),
            new RegexValidator([
                "pattern" => "/^[a-z0-9_$\.,-\/\-]{16,64}$/i",
                "message" => "Crypt is invalid"
            ]),
        ];
    }

    /**
     * Compare posted and initial crypt tokens
     * Important !
     * @return bool
     */
    private function checkCrypt()
    {
        $flatPass = $this->getValue('password');
        if ($oldCrypt = $this->security->hash($flatPass)) {
            return $this->security->checkHash($flatPass, $oldCrypt);
        }
        return false;
    }

    /**
     * This method returns the default value for field 'crypt'
     * Important !
     * @return string
     */
    public function getCrypt()
    {
        return $this->security->getToken();
    }

    /**
     * Array of validators for text login field
     * @return array
     */
    private function loginValidators()
    {
        return [
            new PresenceOf([
                'message' => 'Login is strictly required'
            ]),
            new RegexValidator([
                "pattern" => "/^[0-9a-z_\.]{3,24}$/i",
                "message" => "Your login is invalid"
            ]),
            new StringLength([
                'min'            => 3,
                'messageMinimum' => 'Login is too short, enlarge it!',
                'max'            => 24,
                'messageMaximum' => 'We do not like really long names',
            ]),
        ];
    }

    /**
     * Array of validators for *** password field
     * @return array
     */
    private function passwordValidators()
    {
        $devMinLength = ('dev' === APP_ENV ? 3 : 6);
        return [
            new PresenceOf([
                'message' => 'Password must be set'
            ]),
            new RegexValidator([
                "pattern" => "/^[0-9a-z_\.,-\/\-]{3,32}$/i",
                "message" => "Password is invalid"
            ]),
            new StringLength([
                'min'            => $devMinLength,
                'messageMinimum' => 'Minimum ' . $devMinLength . ' symbols!',
                'max'            => 32,
                'messageMaximum' => 'Password too long, it is too hard to remember!',
            ]),
        ];
    }


    /**
     * Init Form. Registered function name!
     * Important !
     * @return void
     */
    public function initialize()
    {
        //crypt
        $crypt = (new Hidden('crypt'))
            ->addValidators($this->cryptValidators());
        $crypt->clear();
        $this->add($crypt);

        //login
        $login = (new Text('login'))
            ->addValidators($this->loginValidators());
        $this->add($login);

        //password
        $password = (new Password('password'))
            ->addValidators($this->passwordValidators());
        $password->clear();
        $this->add($password);

//        // go
//        $submit = new Submit('go', [
//            'class' => 'btn btn-success'
//        ]);
//        $this->add($submit);
    }
}
