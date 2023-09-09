<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\GenericController;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Order;
use App\Services\JwtService;
use Exception;
use Illuminate\Http\Request;


/**
 * @OA\Schema(
 *     schema="Order",
 *     title="Order",
 *     description="Order model",
*  required={"order_status_uuid","payment_uuid","products","address"},
 *     @OA\Property(property="order_status_uuid", type="integer", description="Order Status ID"),
 *     @OA\Property(property="payment_uuid", type="integer", description="Payment ID"),
 *     @OA\Property(property="products", type="array", description="Array of products", @OA\Items(
 *         type="object",
 *         @OA\Property(property="product", type="string", description="Product UUID"),
 *         @OA\Property(property="quantity", type="integer", description="Product quantity")
 *     )),
 *     @OA\Property(property="address", type="object", description="Billing and shipping address", properties={
 *         @OA\Property(property="billing", type="string", description="Billing address"),
 *         @OA\Property(property="shipping", type="string", description="Shipping address")
 *     }),

 * )
 */
class OrderController extends GenericController
{
    use ApiResponseTrait;


    protected function getModel(): string
    {
        return Order::class;
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
     * @OA\Get(
     *     path="/orders/dashboard",
     *     summary="List orders for Dashboard",
     *      security={{"bearerAuth":{}}},
     *     description="Retrieve a list of orders",
     *     operationId="listOrdersDashboard",
     *     tags={"Orders"},
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


     public function dashboard()
     {
        try {
            $perPage = request()->query('limit', 10); // Default to 10 items per page
            $page = request()->query('page', 1);

            $sortField = request()->query('sort_by', 'id');
            $sortOrder = request()->query('desc', 'asc');

            $query = $this->getModel()::query();

            $query->with('user','order_status')->get(['amount','created_at','delivery_fee','products','shipped_at','uuid',]);
            
            //dd($query->get());

            if ($sortField && in_array($sortField, ['uuid'])) {
                $query->orderBy($sortField, $sortOrder);
            }

            $results = $query->paginate($perPage, ['*'], 'page', $page);

            return $this->successResponse($results);
        } catch (Exception $ex) {
            return $this->errorResponse("An error occurred", 500, [$ex->getMessage()]);
        }
     }





    /**
     * @OA\Post(
     *     path="/order/create",
     *     summary="Create a new order",
     *     security={{"bearerAuth":{}}},
     *     description="Create a new order",
     *     operationId="createOrder",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Order data",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Order"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
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

        //get user from the bearer token
        $service = new JwtService();

        $bearer = $service->extractBearerToken(request());

        $user = $service->getUserFromBearer($bearer);

        $request->merge(['user_id' => $user->id]);

        return parent::store($request);
    }


    /**
     * @OA\Get(
     *     path="/orders",
     *     summary="List orders",
     *     security={{"bearerAuth":{}}},
     *     description="Retrieve a list of orders",
     *     operationId="listOrders",
     *     tags={"Orders"},
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
     *     path="/order/{uuid}",
     *     summary="Update an order",
     *     security={{"bearerAuth":{}}},
     *     description="Update an order identified by UUID",
     *     operationId="updateOrder",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the order to update",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Order data",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Order"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */

    public function edit(Request $request, $uuid)
    {

        $service = new JwtService();

        $bearer = $service->extractBearerToken(request());

        $user = $service->getUserFromBearer($bearer);

        $request->merge(['user_id' => $user->id]);


        return parent::update($request, $uuid);
    }

    /**
     * @OA\Delete(
     *     path="/order/{uuid}",
     *     summary="Delete an order",
     *     security={{"bearerAuth":{}}},
     *     description="Deletes an order identified by UUID",
     *     operationId="deleteOrder",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the order to delete",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Order deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
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
     *     path="/order/{uuid}",
     *     summary="Retrieve an order",
     *       security={{"bearerAuth":{}}},
     *     description="Retrieve an order identified by UUID",
     *     operationId="retrieveOrder",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the order to retrieve",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order retrieved successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
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
