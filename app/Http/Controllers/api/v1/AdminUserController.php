<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\GenericController;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\LoginTrait;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */
class AdminUserController extends GenericController
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
     *     path="/admin/login",
     *     summary="User login",
     *     tags={"Admin"},
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
        return $this->loginUser($request,true);
    }

    /**
     * @OA\Get(
     *     path="/admin/logout",
     *     summary="Log a user out",
     *     tags={"Admin"},
     *     operationId="logoutAdminUser",
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
     *     path="/admin/create",
     *     summary="Create a new user",
     *     description="Create a new user",
     *     operationId="createAdminUser",
     *     tags={"Admin"},
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

        $request->merge(['is_admin' => 1]);

        return parent::store($request);
    }

    /**
     * @OA\Get(
     *     path="/admin/user-listing",
     *     summary="List users",
     *     security={{"bearerAuth":{}}},
     *     description="Retrieve a non admin list of users",
     *     operationId="listAdminUsers",
     *     tags={"Admin"},
     *     @OA\Parameter(name="page", in="query",@OA\Schema(type="integer")),
     *     @OA\Parameter(name="limit", in="query",@OA\Schema(type="integer")),
     *     @OA\Parameter(name="sortBy", in="query",@OA\Schema(type="string")),
     *     @OA\Parameter(name="desc", in="query",@OA\Schema(type="boolean")),
     *     @OA\Parameter(name="first_name", in="query",@OA\Schema(type="string")),
     *     @OA\Parameter(name="email", in="query",@OA\Schema(type="string")),
     *     @OA\Parameter(name="phone", in="query",@OA\Schema(type="string")),
     *     @OA\Parameter(name="address", in="query",@OA\Schema(type="string")),
     *     @OA\Parameter(name="created_at", in="query",@OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function index()
    {
        return parent::index();
    }


    /**
     * @OA\Put(
     *     path="/admin/user-edit/{uuid}",
     *     summary="Update a User",
     *     security={{"bearerAuth":{}}},
     *     description="Update a user identified by UUID",
     *     operationId="updateAdminUser",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="Uuid of the user to update",
     *         @OA\Schema(type="string")
     *     ),
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
    public function edit(Request $request, $uuid)
    {
        if($this->getModel()::where('uuid', $uuid)->where('is_admin',1)->exists())
            return $this->errorResponse("Cant edit an admin", 401);

        return parent::update($request, $uuid);
    }

    /**
     * @OA\Delete(
     *     path="/admin/user-delete/{uuid}",
     *     summary="Delete a user",
     *     security={{"bearerAuth":{}}},
     *     description="Deletes a users identified by uuid",
     *     operationId="destroyAdminUser",
     *     tags={"Admin"},
     *     @OA\Parameter(name="uuid", in="path",required=true,description="Uuid of the user to delete",@OA\Schema(type="string") ),
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
    public function destroy($uuid)
    {
        if($this->getModel()::where('uuid', $uuid)->where('is_admin',1)->exists())
            return $this->errorResponse("Cant delete an admin", 401);

        return parent::destroy($uuid);
    }
}
