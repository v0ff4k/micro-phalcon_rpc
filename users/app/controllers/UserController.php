<?php

namespace App\Controllers;

use app\forms\LoginApiForm;
use app\plugins\Logger;
use Datto\JsonRpc\Exceptions\ApplicationException;
use app\models\Users;
use app\plugins\Password;

class UserController extends ControllerBase
{
    /**
     * Default process for logout
     * @example Add sendJsonRpc to //users:80/logout  for 100% logout).
     *
     * @return string
     */
    public function logoutAction($userId, $token)
    {
        //find user id+token
        //set token  to 0

        return 'Log out successful!';
    }

    /**
     * Login processor
     * if request POST - process inputted data, other - display form.
     *
     * @return bool|string|array
     */
    public function loginAction()
    {
        $form = new LoginApiForm();
        try {

            /** @var array $encryptedParams   ['login', 'password', 'crypt'] */
            $encryptedParams = $this->dispatcher->getParams();

            $newParams = [];
            //decrypt data from   Crypt + salt + base64)  = ~200msec
            foreach ($encryptedParams as $key => $val) {
                if ($newParams[$key] = @$this->crypt->decryptBase64($val)){
                } else {
                    (new Logger())->warning('Security breached! Got data from unknown encoders/place!');
                    throw new ApplicationException('Bad transaction', 1);
                }
            }

            //if POST + pass is crypto(data is send from trusted source, with known security)
            if ($this->request->isPost() && $this->security->checkHash($newParams['password'], $newParams['crypt'])) {
                if(false === $form->isValid($newParams)) {
                    $comma = false; $messages = '';
                    /** @var \Phalcon\Validation\Message $jsonRpc */
                    foreach ($form->getMessages() as $message) {
                        $messages .= ($comma? '' : ', ').$message->getMessage();
                        $comma = true;
                    }
                    throw new ApplicationException($messages, 1);
                }else {
                    if ('dev' === APP_ENV) return ['id' => 1, 'login' => 'admin'];
                    //injection safe, protected before by form validators.
                    $oUsers = Users::findFirst(['login' => $newParams['login']]);

                    if ($oUsers === null) {
                        throw new ApplicationException('User not found', 1);
                    }

                    if (!Password::compare($newParams['password'], $oUsers->getPassword())) {
                        throw new ApplicationException('Incorrect login/password', 1);
                    }

                    return $oUsers->toArray(['id', 'login']);
                }
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage() . PHP_EOL . '[Stack Trace]' . PHP_EOL . $e->getTraceAsString();
            (new \app\plugins\Logger())->error($msg);

            return 'There is big problem with your data.' . $e->getMessage();

        }

        return 'No data inserted';
    }
}
