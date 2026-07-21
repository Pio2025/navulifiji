<?php

namespace App\Filters;

use App\Libraries\ApiJwt;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Validates the Bearer JWT for stateless mobile API routes and injects the
 * decoded claims as a request attribute for controllers to read.
 */
class ApiJwtFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');

        if (empty($header) || !preg_match('/^Bearer\s+(.+)$/i', $header, $m)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Missing or malformed Authorization header.']);
        }

        $claims = ApiJwt::decode($m[1]);

        if ($claims === null || empty($claims['userId'])) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Invalid or expired token.']);
        }

        \App\Libraries\ApiAuth::setClaims($claims);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after.
    }
}
