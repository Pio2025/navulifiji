<?php

namespace App\Controllers;

class Help extends BaseController
{
    
	
	public function index(): string
    {
        //delete this later as this is for testing
        session()->destroy();
        return view('web/help/home');
    }
}