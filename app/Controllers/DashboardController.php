<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    protected $validation;
    protected $session;
    protected $email;
    protected $helpers = ['form', 'url']; // Add form helper
    
    
    public function __construct()
    {
        helper('form,url'); // Load URL helper if you use base_url() in views
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();
    }
    
    
    public function index(){
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $view = '';
        $this->session->set('prevUrl',$this->session->get('url'));
        $this->session->set('url','dashboard');
        
        //check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
         // Set page metadata
        $this->setPageData('View Dashboard', 'Dashboard', 'Dashboard');
        
        // Check permission
        $accessCheck = $this->require_access('_view_dashboard');
        if ($accessCheck !== true) {
            $view = 'app/auth/access_control';
        }else{
            $view = 'app/dashboard/index';
        }
        
        $data = $this->loadCommonData($view);
        
        return view('app/layouts/main', $data);
    }
    
    
	
}
