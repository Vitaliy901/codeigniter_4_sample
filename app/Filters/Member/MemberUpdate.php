<?php

namespace App\Filters\Member;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use OpenApi\Attributes as OA;

class MemberUpdate implements FilterInterface
{
    #[OA\Schema(
        schema: "RequestUpdateMember",
        properties: [
            new OA\Property(property: "role", type: "string", enum: ["head", "member"]),
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
                'role' => ['required', 'min_length[3]', 'max_length[100]', 'string', 'in_list[member,head]'],
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
