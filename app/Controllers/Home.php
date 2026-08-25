<?php

namespace App\Controllers;

use App\Models\ContactMessageModel;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            '_view'        => 'web/site/home',
            'active_page'  => 'home',
            'page_title'   => 'School Management System for Fiji',
            'plans'        => $this->planModel->getAllPlan(),
        ];

        $this->session->set('active_page', 'home');

        return view('web/layouts/site', $data);
    }

    public function product(): string
    {
        $data = [
            '_view'       => 'web/site/product',
            'active_page' => 'product',
            'page_title'  => 'Product & Features',
        ];
        $this->session->set('active_page', 'product');

        return view('web/layouts/site', $data);
    }

    public function pricing(): string
    {
        $data = [
            '_view'       => 'web/site/pricing',
            'active_page' => 'pricing',
            'page_title'  => 'Pricing',
            'plans'       => $this->planModel->getAllPlan(),
        ];
        $this->session->set('active_page', 'pricing');

        return view('web/layouts/site', $data);
    }

    public function resources(): string
    {
        $data = [
            '_view'       => 'web/site/resources',
            'active_page' => 'resources',
            'page_title'  => 'Resources',
        ];
        $this->session->set('active_page', 'resources');

        return view('web/layouts/site', $data);
    }

    public function about(): string
    {
        $data = [
            '_view'       => 'web/site/about',
            'active_page' => 'about',
            'page_title'  => 'About Us',
        ];
        $this->session->set('active_page', 'about');

        return view('web/layouts/site', $data);
    }

    public function forSchools(): string
    {
        $data = [
            '_view'            => 'web/site/for_schools',
            'active_page'      => 'for-schools',
            'page_title'       => 'For Schools',
            'schoolCategories' => $this->schoolCategoryModel->getAllSchoolCategory(),
        ];
        $this->session->set('active_page', 'for-schools');

        return view('web/layouts/site', $data);
    }

    public function contact(): string
    {
        $data = [
            '_view'       => 'web/site/contact',
            'active_page' => 'contact',
            'page_title'  => 'Contact Us',
        ];
        $this->session->set('active_page', 'contact');

        return view('web/layouts/site', $data);
    }

    /**
     * Handles the public contact form submission (BootstrapMade php-email-form
     * contract: response body must be the literal string "OK" on success, or a
     * plain-text error message otherwise).
     */
    public function submitContact()
    {
        $contactMessageModel = new ContactMessageModel();

        $name    = trim((string) $this->request->getPost('name'));
        $email   = trim((string) $this->request->getPost('email'));
        $phone   = trim((string) $this->request->getPost('phone'));
        $school  = trim((string) $this->request->getPost('school_name'));
        $subject = trim((string) $this->request->getPost('subject'));
        $message = trim((string) $this->request->getPost('message'));

        if (empty($name) || empty($email) || empty($message)) {
            return $this->response->setStatusCode(200)->setBody('Please fill in your name, email, and message.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setStatusCode(200)->setBody('Please enter a valid email address.');
        }

        try {
            $contactMessageModel->registerMessage([
                'name'        => $name,
                'email'       => $email,
                'phone'       => $phone,
                'school_name' => $school,
                'subject'     => $subject,
                'message'     => $message,
                'ip_address'  => $this->request->getIPAddress(),
                'user_agent'  => substr((string) $this->request->getUserAgent(), 0, 255),
            ]);

            $this->sendEmail([
                'to'      => 'info@navulifiji.com',
                'subject' => 'New enquiry from ' . $name . ($school ? ' (' . $school . ')' : ''),
                'view'    => 'web/emails/contact_notification',
                'viewData' => [
                    'name' => $name, 'email' => $email, 'phone' => $phone,
                    'school_name' => $school, 'subject' => $subject, 'message' => $message,
                ],
                'replyTo' => $email,
            ]);

            return $this->response->setStatusCode(200)->setBody('OK');
        } catch (\Exception $e) {
            log_message('error', '[Home::submitContact] ' . $e->getMessage());
            return $this->response->setStatusCode(200)->setBody('Something went wrong on our end. Please try again shortly.');
        }
    }
}
