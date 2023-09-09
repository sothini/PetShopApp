<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\GenericController;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\LoginTrait;
use App\Models\Category;
use App\Models\OrderStatus;
use Illuminate\Http\Request;



/**
 * @OA\Schema(
 *     schema="OrderStatus",
 *     title="OrderStatus",
 *     description="OrderStatus model",
 *  required={"title"},
 *     @OA\Property(property="title", type="string", description="Order status title"),
 * )
 */
class OrderStatusController extends GenericController
{
    use ApiResponseTrait;


    protected function getModel(): string
    {
        return OrderStatus::class;
    }

    protected function getItems()
    {
        $sortField = request()->query('sort_by', 'id');
        $sortOrder = request()->query('desc', 'asc');

        $query = $this->getModel()::query();

        $query = $this->filter($query, ['uuid', 'title']);

        if ($sortField && in_array($sortField, ['uuid', 'title'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        return $query;
    }


    /**
     * @OA\Post(
     *     path="/order-status/create",
     *     summary="Create a new order status",
     *     security={{"bearerAuth":{}}},
     *     description="Create a new order status",
     *     operationId="createOrderStatus",
     *     tags={"Order Statuses"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Order status data",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/OrderStatus"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order status created successfully",
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
     *     path="/order-statuses",
     *     summary="List order statuses",
     *     description="Retrieve a list of order statuses",
     *     operationId="listOrderStatuses",
     *     tags={"Order Statuses"},
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
     *     path="/order-status/{uuid}",
     *     summary="Update an order status",
     *     security={{"bearerAuth":{}}},
     *     description="Update an order status identified by UUID",
     *     operationId="updateOrderStatus",
     *     tags={"Order Statuses"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the order status to update",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Order status data",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/OrderStatus"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order status updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order status not found"
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
     *     path="/order-status/{uuid}",
     *     summary="Delete an order status",
     *     security={{"bearerAuth":{}}},
     *     description="Deletes an order status identified by UUID",
     *     operationId="destroyOrderStatus",
     *     tags={"Order Statuses"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the order status to delete",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Order status deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order status not found"
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
     *     path="/order-status/{uuid}",
     *     summary="Retrieve an order status",
     *     description="Retrieve an order status identified by UUID",
     *     operationId="getOrderStatus",
     *     tags={"Order Statuses"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the order status to retrieve",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order status retrieved successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order status not found"
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
