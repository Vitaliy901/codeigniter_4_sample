<?php

namespace App\Filters\Team;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use OpenApi\Attributes as OA;

class TeamUpdate implements FilterInterface
{
    #[OA\Schema(
        schema: "RequestUpdateTeam",
        properties: [
            new OA\Property(property: "name", type: "string", example: "Some name of team"),
            new OA\Property(property: "url", type: "string", example: "https://example.com"),
        ],
        type: "object"
    )]
    public function before(RequestInterface $request, $arguments = null)
    {
        $segments = $request->getUri()->getSegments();
        $id = array_pop($segments);
        if ($request->getMethod() === 'put' || $request->getMethod() === 'patch') {
            $validator = Services::validation();
            $rules = [
                'name' => ['min_length[3]', 'max_length[100]', 'string', "is_unique[teams.name,id,{$id}]"],
                'url' => ['min_length[3]', 'max_length[100]', 'string', "is_unique[teams.url,id,{$id}]"],
            ];
            $currentRules = array_intersect_key($rules, $request->getJSON(true));

            if (!$validator->validate($currentRules, $request)) {
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
