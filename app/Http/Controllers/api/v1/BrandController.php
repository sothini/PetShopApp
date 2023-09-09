<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\GenericController;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\LoginTrait;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;



/**
 * @OA\Schema(
 *     schema="Brand",
 *     title="Brand",
 *     description="Brand model",
 *     required={"title","slug"},
 *     @OA\Property(property="title", type="string", description="Brand title"),
 *     @OA\Property(property="slug", type="string", description="Brand slug"),
 * )
 */
class BrandController extends GenericController
{
    use ApiResponseTrait;


    protected function getModel(): string
    {
        return Brand::class;
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
     *     path="/brand/create",
     *     summary="Create a new brand",
     *     security={{"bearerAuth":{}}},
     *     description="Create a new brand",
     *     operationId="createBrand",
     *     tags={"Brands"},
     * @OA\RequestBody(
     *     required=true,
     *     description="Brand data",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *         @OA\Schema(
     *             ref="#/components/schemas/Brand"
     *         )
     *     )
     * ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand created successfully",
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
     *     path="/brands",
     *     summary="List brands",
     *     description="Retrieve a list of brands",
     *     operationId="listBrands",
     *     tags={"Brands"},
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
     *     path="/brand/{uuid}",
     *     summary="Update a brand",
     *     security={{"bearerAuth":{}}},
     *     description="Update a brand identified by UUID",
     *     operationId="updateBrand",
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the brand to update",
     *         @OA\Schema(type="string")
     *     ),
     * @OA\RequestBody(
     *     required=true,
     *     description="Brand data",
     *     @OA\MediaType(
     *         mediaType="application/x-www-form-urlencoded",
     *         @OA\Schema(
     *             ref="#/components/schemas/Brand"
     *         )
     *     )
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found"
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
     *     path="/brand/{uuid}",
     *     summary="Delete a brand",
     *     security={{"bearerAuth":{}}},
     *     description="Deletes a brand identified by UUID",
     *     operationId="destroyBrand",
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the brand to delete",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Brand deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found"
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
     *     path="/brand/{uuid}",
     *     summary="Retrieve a brand",
     *     description="Retrieve a brand identified by UUID",
     *     operationId="getBrand",
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the brand to retrieve",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand retrieved successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found"
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
