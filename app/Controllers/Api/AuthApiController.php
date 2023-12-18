<?php

namespace App\Controllers\Api;

use App\Models\ProfileModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class ApiAuthController extends ResourceController
{
    use ResponseTrait;
    protected $helpers = ['setting'];

    public function __construct(

     

    ) {
    }

    public function getVariable($var)
    {
        return $this->request->getVar($var, FILTER_SANITIZE_SPECIAL_CHARS);
    }
    public function login()
    {
        try {
        
          
            
                //get validation rules
                $rules = setting('Validation.login');

                if (!$this->validate($rules)) {
                    return  $this->response->setJSON([
                        'status' => 0,
                        'data' => [
                            'errors' => $this->validator->getErrors(),
                        ]
                    ]);
                }

                $credentials = [
                    'email' => $this->getVariable('email'),
                    'password' => $this->getVariable('password'),
                ];

                if (auth()->loggedIn()) {
                    auth()->logout();
                }

                $loginAttempt = auth()->attempt($credentials);



                // Check if the user's account is activated before logging in
                if ($loginAttempt->isOK()) {
                    $user = auth()->user();

                    if (!$user->active) {
                        auth()->logout();

                        return $this->response->setJSON([
                            'status' => 0,
                            'data' => [
                                'msg' => 'Your account is not  Activated'
                            ]
                        ])->setStatusCode(401);
                    } else {
                        // Generate token and return to client
                        $token = auth()->user()->generateAccessToken('api-token')->raw_token;

                        $profileModel = new ProfileModel();
                        $sessionId = randomString();
                        session()->set('sessionId', $sessionId);
                        $logData = [
                            'name' => $user->first_name . ' ' . $user->last_name,
                            'email' => $user->email,
                            'sessionId' =>  $sessionId,
                            'loginTime' => date('d-m-Y H:i:s'),
                            'ipAddress' => $this->request->getIPAddress(),
                            'userAgent' => $this->request->getUserAgent(),
                        ];
                        $profileModel->createLog($logData);

                        return $this->response->setJSON([
                            'status' => 1,
                            'data' => [
                                'token' => $token
                            ]
                        ]);
                    }
                } else {

                    return $this->response
                        ->setJSON([
                            'status' => 0,
                            'data' => [
                                'msg' => $loginAttempt->reason()
                            ]
                        ])
                        ->setStatusCode(401);
                }
            
        } catch (\Throwable $th) {
            $response = [
                'status' => 0,
                'msg' => $th->getMessage(),
            ];
            return $this->response->setJSON($response)->setStatusCode(500);
        }
    }






    public function exit()
    {
        return $this->response->setJSON([
            'status' => 0,
            'data' => [],
            // 'token' => $this->token
        ]);
    }
    public function logout()
    {
        $sessionId = session()->get('sessionId');
        $profileModel = new ProfileModel();
        $profileModel->updateLog(['logoutTime' => date('d-m-Y H:i:s')], $sessionId);
        auth()->logout();
        return redirect()->to('noAuth');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function noAuth()
    {
        return  $this->response->setJSON([
            'status' => 401,
            'data' => [
                'error' => 'Unauthorized',
            ]
        ])->setStatusCode(401);
    }
}
