<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\GenericController;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\LoginTrait;
use App\Models\Category;
use Illuminate\Http\Request;



/**
 *    @OA\Schema(
 *    schema="Category",
 *     title="Category",
 *     description="Category model",
 * required={"title","slug"},
 *     @OA\Property(property="title", type="string", description="Category title"),
 *     @OA\Property(property="slug", type="string", description="Category slug"),
 * )
 */
class CategoryController extends GenericController
{
    use ApiResponseTrait;


    protected function getModel(): string
    {
        return Category::class;
    }

    protected function getItems()
    {
        $sortField = request()->query('sort_by', 'id');
        $sortOrder = request()->query('desc', 'asc');

        $query = $this->getModel()::query();

        $query = $this->filter($query, ['uuid', 'title', 'slug']);

        if ($sortField && in_array($sortField, ['uuid', 'title', 'slug'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        return $query;
    }


   /**
     * @OA\Post(
     *     path="/category/create",
     *     summary="Create a new category",
     * security={{"bearerAuth":{}}},
     *     description="Create a new category",
     *     operationId="createcategory",
     *     tags={"Categories"},
     * @OA\RequestBody(
     *     required=true,
     *     description="category data",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *          ref="#/components/schemas/Category"
     *         )
     *     )
     * ),
     *     @OA\Response(
     *         response=201,
     *         description="category created successfully",
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

        return parent::store($request);
    }

    /**
     * @OA\Get(
     *     path="/categories",
     *     summary="List categories ",
     *     
     *     description="Retrieve a list of categories",
     *     operationId="listcategories ",
     *     tags={"Categories"},
     *     @OA\Parameter(name="page", in="query",@OA\Schema(type="integer")),
     *     @OA\Parameter(name="limit", in="query",@OA\Schema(type="integer")),
     *     @OA\Parameter(name="sortBy", in="query",@OA\Schema(type="string")),
     *     @OA\Parameter(name="desc", in="query",@OA\Schema(type="boolean")),
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
     *     path="/category/{uuid}",
     *     summary="Update a category",
     *     security={{"bearerAuth":{}}},
     *     description="Update a category identified by UUID",
     *     operationId="updatecategory",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="Uuid of the category to update",
     *         @OA\Schema(type="string")
     *     ),
     * @OA\RequestBody(
     *     required=true,
     *     description="category data",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *          ref="#/components/schemas/Category"
     *         )
     *     )
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="category updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="category not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function edit(Request $request, $uuid)
    {

        return parent::update($request, $uuid);
    }

     /**
     * @OA\Delete(
     *     path="/category/{uuid}",
     *     summary="Delete a category",
     *     security={{"bearerAuth":{}}},
     *     description="Deletes a category identified by uuid",
     *     operationId="destroycategory",
     *     tags={"Categories"},
     *     @OA\Parameter(name="uuid", in="path",required=true,description="Uuid of the category to delete",@OA\Schema(type="string") ),
     *     @OA\Response(
     *         response=204,
     *         description="category deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="category not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function delete($uuid)
    {

        return parent::destroy($uuid);
    }

    /**
     * @OA\Get(
     *     path="/category/{uuid}",
     *     summary="Retrieve a category",
     *     description="Retrieve a category identified by ID",
     *     operationId="getcategory",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="Uuid of the category to update",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="category retrieved successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="category not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function show($uuid)
    {
        
            return parent::show($uuid);
        
    }
}
