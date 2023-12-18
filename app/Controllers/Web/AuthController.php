<?php

namespace App\Controllers;


use App\Models\UsersModel;
use CodeIgniter\Events\Events;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Controllers\RegisterController;
use CodeIgniter\Shield\Exceptions\ValidationException;

class AuthController extends RegisterController
{

    public function __construct()
    {
        helper(['form','text']);
    }

    public function appRedirect(){
        if(auth()->user()->inGroup('admin')){
            return 'admin/dashboard';
        }else{
            return 'dashboard';
        }
    }

    public function getVariable($var)
    {
        return $this->request->getVar($var, FILTER_SANITIZE_SPECIAL_CHARS);
    }
    public function registerView()
    {
        if (auth()->loggedIn()) {
            return redirect()->to($this->appRedirect());
        }

        // Check if registration is allowed
        if (!setting('Auth.allowRegistration')) {
            return redirect()->back()->withInput()
                ->with('error', lang('Auth.registerDisabled'));
        }

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        // If an action has been defined, start it up.
        if ($authenticator->hasAction()) {
            return redirect()->route('auth-action-show');
        }

        return $this->view('Pages/Auth/Register');
    }
    public function registerAction(): RedirectResponse
    {
       
        if (auth()->loggedIn()) {
            return redirect()->to($this->appRedirect());
        }

        // Check if registration is allowed
        if (!setting('Auth.allowRegistration')) {
            return redirect()->back()->withInput()
                ->with('registerError', lang('Auth.registerDisabled'));
        }

        $users = $this->getUserProvider();

        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'firstName' => 'required',
            'middleName' => 'permit_empty',
            'lastName' => 'required',
            'region' => 'required',
            'nidaNumber' => 'required|exact_length[20]',
            'password' => 'required|min_length[8]',  // Add any password rules you need
        ];
        

        if (!$this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Save the user
        $allowedPostFields = array_keys($rules);
        $user              = $this->getUserEntity();
        $userData = $this->request->getPost($allowedPostFields);
        $userData['userId'] = randomString();
        $user->fill($userData);
 
        // Workaround for email only registration/login
        if ($user->username === null) {
            $user->username = null;
        }

        try {
            $users->save($user);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('registerError', $users->errors());
        }

        // To get the complete user object with ID, we need to get from the database
        $user = $users->findById($users->getInsertID());

        // Add to default group
        $users->addToDefaultGroup($user);

        Events::trigger('register', $user);

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $authenticator->startLogin($user);

        // If an action has been defined for register, start it up.
        $hasAction = $authenticator->startUpAction('register', $user);
        if ($hasAction) {
            return redirect()->to('auth/a/show');
        }

        // Set the user active
        $user->activate();

        $authenticator->completeLogin($user);

        // Success!
        return redirect()->to($this->appRedirect())
            ->with('message', lang('Auth.registerSuccess'));
    }



    public function loginView()
    {
        if (auth()->loggedIn()) {
            return redirect()->to($this->appRedirect());
        }

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        // If an action has been defined, start it up.
        if ($authenticator->hasAction()) {
            return redirect()->route('auth-action-show');
        }

        return $this->view('Pages/Auth/Login');
    }



    public function loginAction(): RedirectResponse
    {
        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = [
            'email' => [
                'label' => 'email',
                'rules' => 'required',
            ],
            'password' => [
                'label'  => 'Auth.password',
                'rules'  => 'required|strong_password',
                
            ],
        ];

        if (!$this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('validationError', $this->validator->getErrors());
        }

        $credentials             = $this->request->getPost(setting('Auth.validFields'));
        $credentials             = array_filter($credentials);
        $credentials['password'] = $this->request->getPost('password');
        $remember                = (bool) $this->request->getPost('remember');

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

      

        // Attempt to login
        $result = $authenticator->remember($remember)->attempt($credentials);
        if (!$result->isOK()) {
            return redirect()->route('login')->withInput()->with('loginError','Invalid Credentials');
        }

        // If an action has been defined for login, start it up.
        if ($authenticator->hasAction()) {
            return redirect()->route('auth-action-show')->withCookies();
        }

        return redirect()->to($this->appRedirect())->withCookies();
    }

    


}
