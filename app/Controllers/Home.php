<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            '_view' => 'web/pages/home',
            'active_page' => 'home'
        ];
        
        $this->session->set('active_page','home');
        
        //return view('web/layouts/main',$data);
        
        return view('web/coming_soon');
    }
}
