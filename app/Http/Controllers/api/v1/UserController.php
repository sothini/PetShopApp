<?php

namespace App\Http\Controllers\api\v1;


use App\Http\Controllers\Controller;
use App\Http\Controllers\GenericController;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\GenericCrudTrait;
use App\Http\Traits\LoginTrait;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class UserController extends GenericController
{
    use ApiResponseTrait;
    use LoginTrait;

    protected function getModel(): string
    {
        return User::class;
    }


    protected function getItems()
    {
        $sortField = request()->query('sort_by', 'id');
        $sortOrder = request()->query('desc', 'asc');

        $query = $this->getModel()::query();

        $query = $this->filter($query, ['first_name', 'email', 'phone', 'address', 'created_at']);

        if ($sortField && in_array($sortField, ['id', 'uuid', 'first_name', 'last_name'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        $query->where('is_admin', 0);

        return $query;
    }

    /**
     * @OA\Post(
     *     path="/user/login",
     *     summary="User login",
     *     tags={"User"},
     *     description="Endpoint to authenticate a user.",
     *     @OA\RequestBody(
     *     required=true,
     *     description="Login credentials",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *         @OA\Schema(
     *              required={"email","password"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 example="admin@buckhill.co.uk"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 example="admin"
     *             )
     *         )
     *     )
     * ),
     * 
     *     @OA\Response(
     *         response="200",
     *         description="Successful login",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal Server Error",
     *     )
     * )
     */
    public function login(Request $request)
    {
        return $this->loginUser($request);
    }

    /**
     * @OA\Get(
     *     path="/user/logout",
     *     summary="Log a user out",
     *     tags={"User"},
     *     operationId="logoutUser",
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     )
     * )
     */
    public function logout()
    {
        return $this->logoutUser();
    }

    /**
     * @OA\Post(
     *     path="/user/create",
     *     summary="Create a new user",
     *     description="Create a new user",
     *     operationId="createUser",
     *     tags={"User"},
     * @OA\RequestBody(
     *     required=true,
     *     description="User data",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *          ref="#/components/schemas/User"
     *         )
     *     )
     * ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function create(Request $request)
    {

        $request->merge(['is_admin' => 0]);

        return parent::store($request);
    }




    /**
     * @OA\Delete(
     *     path="/user",
     *     summary="Delete a user",
     *     security={{"bearerAuth":{}}},
     *     description="Deletes a users identified by ID",
     *     operationId="destroyUser",
     *     tags={"User"},
     *     @OA\Response(
     *         response=204,
     *         description="user deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="user not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function delete()
    {
        $service = new JwtService();

        $bearer = $service->extractBearerToken(request());

        if ($bearer) {
            $user = $service->getUserFromBearer($bearer);
            return parent::destroy($user->uuid);
        
        }

        return $this->errorResponse("user not found", 400,);
    }



    /**
     * @OA\Put(
     *     path="/user/edit",
     *     summary="Update a User",
     *     security={{"bearerAuth":{}}},
     *     description="Update a user identified by ID",
     *     operationId="updateUser",
     *     tags={"User"},
     * @OA\RequestBody(
     *     required=true,
     *     description="User data",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *          ref="#/components/schemas/User"
     *         )
     *     )
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */

    public function edit(Request $request)
    {
        $service = new JwtService();

        $bearer = $service->extractBearerToken(request());

        if ($bearer) {
            $user = $service->getUserFromBearer($bearer);
            $request->merge(['uuid' => $user->uuid]);
            return parent::update($request,$user->uuid);
        }

        return $this->errorResponse("user not found", 400,); 
    }

    /**
     * @OA\Get(
     *     path="/user",
     *     summary="Retrieve a user",
     *     security={{"bearerAuth":{}}},
     *     description="Retrieve a user identified by ID",
     *     operationId="getUser",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function show_user()
    {
        $service = new JwtService();

        $bearer = $service->extractBearerToken(request());

        if ($bearer) {
            $user = $service->getUserFromBearer($bearer);
            return parent::show($user->uuid);
        }

        return $this->errorResponse("user not found", 400,); 
    }
}
