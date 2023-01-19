<?php

namespace App\Filters\Team;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use OpenApi\Attributes as OA;

class TeamCreate implements FilterInterface
{

    #[OA\Schema(
        schema: "RequestCreateTeam",
        properties: [
            new OA\Property(property: "name", type: "string", example: "Some name of team"),
            new OA\Property(property: "url", type: "string", example: "https://example.com"),
            ],
        type: "object"
     )]
    public function before(RequestInterface $request, $arguments = null)
    {
        if ($request->getMethod() === 'post') {
            $validator = Services::validation();
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]|string|is_unique[teams.name,deleted_at]',
                'url' => 'required|min_length[3]|max_length[100]|string|is_unique[teams.url]',
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
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
