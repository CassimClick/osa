<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AdminController extends BaseController
{
    protected $user;
    protected $token;
    protected $adminModel;
    public function __construct()
    {
        $this->user = auth()->user();
        $this->token = csrf_hash();
        $this->adminModel = new AdminModel;
    }
    public function getVariable($var)
    {
        return $this->request->getVar($var, FILTER_SANITIZE_SPECIAL_CHARS);
    }
    public function companies()
    {
        $data['title'] = 'Companies';
        $data['user'] = $this->user;
        $data['companies'] = $this->getCompanies();

        

        return view('Pages/Admin/Companies', $data);
    }


    public function createCompany()
    {
        try {
            $data = [
                'companyName' => $this->getVariable('companyName'),
                'region' => $this->getVariable('region'),
                'phoneNumber' => $this->getVariable('phoneNumber'),
                'email' => $this->getVariable('email'),
                'companyId' => randomString(),
            ];
            $request = $this->adminModel->createCompany($data);
            if ($request) {
                $response = [
                    'status' => 1,
                    'msg' => 'Company Created',
                    'token' => $this->token,
                    'companies' => $this->getCompanies(),
                ];
            }
        } catch (\Throwable $th) {
            $response = [
                'status' => 0,
                'msg' => $th->getMessage(),
                'token' => $this->token,
                'companies' => '',
            ];
        }

        return  $this->response->setJSON($response);
    }

    public function getCompanies()
    {
        $companies = $this->adminModel->getCompanies();
        $tr = '';
        foreach ($companies as $company) {



            $tr .= <<<HTML
                    <tr>
                    <td>$company->companyName</td>
                    <td>$company->region</td>
                    <td>$company->phoneNumber</td>
                    <td>$company->email</td>
                    <td><span class="badge bg-label-primary me-1">Active</span></td>
                    <td>
                        <button type="button" class="btn rounded-pill btn-sm btn-icon btn-primary" onclick="editCompany('$company->companyId')">
                            <span class="far fa-edit"></span>
                        </button>
                        <button type="button" class="btn rounded-pill  btn-sm btn-icon btn-danger" onclick="deleteCompany('$company->companyId')">
                            <span class="far fa-trash-alt"></span>
                        </button>


                    </td>
                </tr>   
            HTML;
        }

        $table = <<<HTML
            <table id="dataTable" class="table shorting">
             <thead class="table-light">
                 <tr>
                     <th>Company Name</th>
                     <th>Region</th>
                     <th>Phone Number</th>
                     <th>Email</th>
                     <th>Status</th>
                     <th>Action</th>

                    </tr>
             </thead>
             <tbody >
               $tr
             
             </tbody>
         </table>   
         HTML;



        return $table;
    }

    public function sendMail()
    {
        $msg = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxLorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, mollitia.';
        sendEmail('cassims44@gmail.com', 'Hello World', $msg);
    }
}
