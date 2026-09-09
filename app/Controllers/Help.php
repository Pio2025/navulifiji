<?php

namespace App\Controllers;

class Help extends BaseController
{
	public function index(): string
    {
        $data = [
            '_view'       => 'web/help/home',
            'active_page' => 'resources',
            'page_title'  => 'Help Center',
        ];

        $this->session->set('active_page', 'resources');

        return view('web/layouts/site', $data);
    }
}