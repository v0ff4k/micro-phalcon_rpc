<?php

namespace App\Controllers;

use app\forms\LoginForm;
use app\plugins\Logger;
use app\plugins\JsonHelper;
use \Phalcon\Http\Client\Request as ClientRequest;
use Phalcon\Http\Client\Response as ClientResponse;
use Phalcon\Http\Request;

/**
 * Class UserController
 *
 * @property \Phalcon\Mvc\View\Engine|\Phalcon\Mvc\ViewInterface $view
 * @package App\Controllers
 */
class UserController extends ControllerBase
{
    /**
     * Default process for logout
     * @example Add sendJsonRpc to //users:80/logout  for 100% logout).
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function logoutAction()
    {
        $this->session->remove('auth');
        $this->session->remove('auth-identity');

        $this->flash->notice('Log out successful!');

        return $this->response->redirect('home');
    }

    /**
     * Login processor
     * if request POST - process inputted data, other - display form.
     *
     * @return bool|\Phalcon\Mvc\View|string
     */
    public function loginAction()
    {
        $form = new LoginForm();
        $params = ['error' => ['login' => [], 'password' => []]];

        try {

            if (!$this->request->isPost()) {
                $this->flash->notice('Please fill all fields carefully!');
            } else {
                //process here only after we got some  $_POST
                $params['login'] = $this->request->getPost('login') ?? '';
                $params['password'] = $this->request->getPost('password') ?? '';

                if (false === $form->isValid($this->request->getPost())) {

                    /** @var \Phalcon\Validation\Message $jsonRpc */
                    foreach ($form->getMessages() as $message) {
                        $params['error'][$message->getField()][] = $message->getMessage();
                    }
                } else {
                    //   params['response'] = request, response, message
                    $params['response'] = $this->processRequestData($this->request);
                }
            }

        } catch (\Exception $e) {

            $m = $e->getMessage();
            $msg = $m . PHP_EOL .
                '[Stack Trace]' . PHP_EOL .
                $e->getTraceAsString();
            (new \app\plugins\Logger())->error($msg);

            $this->flash->error($m);
        }

        $this->view->form = $form;

        //not exactly needed. cause phalcon will auto render:
        // controllerNameWithoutNameController/actionWithoutNameAction.(phtml|volt)
        $this->view->app = $this;

        return $this->view->render('user/login.volt', $params);

    }

    /**
     * Process simple requested data to backend(with DB) and return response.
     *
     * @param Request $data
     * @return string
     */
    protected function processRequestData(Request $data)
    {
        //prepare data and send to data server
        $jsonBody = [
            'login' => $this->crypt->encryptBase64($data->getPost('login')),
            'password' => $this->crypt->encryptBase64($data->getPost('password')),
            'crypt' => $this->crypt->encryptBase64($this->security->hash($data->getPost('password')))
        ];

        $jsonRpc = JsonHelper::simpleJsonRpcRequest('user/login', $jsonBody);
        /** @var ClientResponse $response */
        $response = $this->sendRequestToDbContainer($jsonRpc);

        //      request, response($response->getContent()), message
        return $this->processResponse($response);

    }

    /**
     * Preparing response gathered from  'users' container,
     * and lil validate them.
     *
     * @param ClientResponse $response
     * @return string
     */
    protected function processResponse(ClientResponse $response)
    {
        /** @var Phalcon\Http\Client\Header $header */
        $header = $response->header;

        if (200 !== $header->statusCode) {
            (new Logger())->debug('wrong status code: ' . $header->statusCode);
            return 'Error, status code: ' . $header->statusCode;
        }

        if (!isset($response->body)) {
            (new Logger())->error('Returned body from users is empty!');
            return 'Error, body is empty';
        }

        //@todo move into plugins as jsonRpc checker
        /** @var array $result  ["jsonrpc"=>"2.0", "result"=>["login"=>"admin","id"=>"1"], "id"=>1] */
        /** @var array $result  ["jsonrpc"=>"2.0", "error" => ["message"=>"some error message"], "id":1] */
        $result = json_decode((string)$response->body, true, 512);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $humanized = JsonHelper::humanizeError(json_last_error());
            (new Logger())->error('Error, convert to json return:' . $humanized);
            throw new \RuntimeException($humanized);
        }

        if (null === $result) {
            (new Logger())->debug('Warning, maybe empty json?');
            return 'Incorrect input json data!';
        }

        if (isset($result['error'])) {
            if (!is_array($result['error']) || !isset($result['error']['message'])) {
                (new Logger())->debug('There is no message or error in json, check:' . json_encode($result));
                return 'Not found error[message]';
            }
            (new Logger())->debug('Json rpc return error: ' . $result['error']['message']);
            return $result['error']['message'];
        }

        if (!isset($result['result'])) {
            if (!isset($result['result']['id'])) {
                (new Logger())->debug('There is no res[result][id] in json, check:' . json_encode($result));
                return 'Not found result[id]';
            }
            (new Logger())->debug('There is no res[result] in json, check:' . json_encode($result));
            return 'Not found result';
        }

        return 'Auth successful, welcome id: ' . $result['result']['id'];
    }



    /**
     * reSend body request to db container, default called 'users'
     *
     * @param string $jsonRpc A JSON formatted string that will be send to DB_CONTAINER
     * @return ClientResponse
     */
    protected function sendRequestToDbContainer(string $jsonRpc)
    {
        $response = null;

        if (is_string($jsonRpc) &&
            is_object(json_decode($jsonRpc)) &&
            json_last_error() === JSON_ERROR_NONE) {

            // same  $this->ClientRequest  AND  $this->getDiContainer('ClientRequest')
            $response = $this->ClientRequest->post(
                'http://' . getenv('DB_CONTAINER'),
                $jsonRpc
            );
        }

        return $response;
    }

}
