<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\GenericController;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\LoginTrait;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;



/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Product model",
 *     required={"category_uuid","title","price","metadata","image","description"},
 *     @OA\Property(property="category_uuid", type="string", format="uuid", description="UUID of the category"),
 *     @OA\Property(property="title", type="string", description="Product title"),
 *     @OA\Property(property="price", type="number", format="float", description="Product price"),
 *     @OA\Property(property="description", type="string", description="Product description"),
 *     @OA\Property(property="metadata", type="object", description="Product metadata", properties={
 *         @OA\Property(property="brand", type="string", format="uuid", description="UUID from petshop.brands"),
 *         @OA\Property(property="image", type="string", format="uuid", description="UUID from petshop.files")
 *     }),
 * )
 */

class ProductController extends GenericController
{
    use ApiResponseTrait;


    protected function getModel(): string
    {
        return Product::class;
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
     *     path="/product/create",
     *     summary="Create a new product",
     *     security={{"bearerAuth":{}}},
     *     description="Create a new product",
     *     operationId="createproduct",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product data",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Product"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
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
     *     path="/products",
     *     summary="List products",
     *     description="Retrieve a list of products",
     *     operationId="listproducts",
     *     tags={"Products"},
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="limit", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="sortBy", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="desc", in="query", @OA\Schema(type="boolean")),
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
     *     path="/product/{uuid}",
     *     summary="Update a product",
     *     security={{"bearerAuth":{}}},
     *     description="Update a product identified by UUID",
     *     operationId="updateproduct",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the product to update",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product data",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Product"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
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
     *     path="/product/{uuid}",
     *     summary="Delete a product",
     *     security={{"bearerAuth":{}}},
     *     description="Deletes a product identified by UUID",
     *     operationId="destroyproduct",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the product to delete",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
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
     *     path="/product/{uuid}",
     *     summary="Retrieve a product",
     *     description="Retrieve a product identified by UUID",
     *     operationId="getproduct",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the product to retrieve",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product retrieved successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
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
