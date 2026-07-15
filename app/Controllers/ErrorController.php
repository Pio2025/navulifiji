<?php

namespace App\Controllers;

class ErrorController extends BaseController
{
    public function show404()
    {
        $this->response->setStatusCode(404);

        if ($this->isLoggedIn()) {
            $this->setPageData('Page Not Found', 'Error', '404');
            return view('app/layouts/main', ['_view' => 'app/errors/404']);
        }

        return view('app/errors/404_guest');
    }
}
