<?php

namespace App\Controllers;

class MessageController extends BaseController
{
    public function index(?int $userId = null)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Messages', 'Message', 'Messages');

        $data['initial_target_user_id']    = $userId;
        $data['initial_target_user_name']  = (string) $this->request->getGet('name');
        $data['initial_target_user_photo'] = (string) $this->request->getGet('photo');
        $data['_view'] = 'app/message/index';

        return view('app/layouts/main', $data);
    }
}
