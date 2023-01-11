<?php

namespace App\Filters\User;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class UserCreate implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null): mixed
    {
        if ($request->getMethod() === 'post') {
            $validator = Services::validation();
            $rules = [
                'first_name' => 'required|min_length[3]|max_length[100]|alpha',
                'last_name' => 'required|min_length[3]|max_length[100]|alpha',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|alpha_numeric_punct|min_length[3]|max_length[80]',
                'role' => 'required|min_length[3]|max_length[50]|alpha'
            ];

            if (!$validator->validate($rules, $request)) {
                return Services::response()->setStatusCode(403)->setJSON([
                    'errors' => $validator->getErrors()
                ]);
            }

            $validCred = $validator->validated();
            $request->setBody(json_encode($validCred));
        }
        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
