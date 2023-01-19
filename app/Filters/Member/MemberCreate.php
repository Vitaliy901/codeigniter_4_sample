<?php

namespace App\Filters\Member;

use App\Constants\UserRoles;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use OpenApi\Attributes as OA;

class MemberCreate implements FilterInterface
{
    #[OA\Schema(
        schema: "RequestCreateMember",
        properties: [
            new OA\Property(property: "team_id", type: "integer", example: "1"),
            new OA\Property(property: "user_id", type: "integer", example: "1"),
            new OA\Property(property: "role", type: "string", enum: ["head", "member"]),
        ],
        type: "object"
    )]
    public function before(RequestInterface $request, $arguments = null)
    {
        $list = service('authManager')->auth()->role === UserRoles::ADMIN ? 'member, head' : 'member';
        if ($request->getMethod() === 'post') {
            $validator = Services::validation();
            $rules = [
                'team_id' => 'required|numeric',
                'user_id' => 'required|numeric|is_unique[team_members.user_id]',
                'role' => "required|min_length[3]|max_length[100]|string|in_list[$list]",
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
