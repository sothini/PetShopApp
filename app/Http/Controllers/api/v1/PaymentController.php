<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\GenericController;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\LoginTrait;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;



/**
 * @OA\Schema(
 *     schema="Payment",
 *     title="Payment",
 *     description="Payment model",
 *     required={"type","details"},
 *  * @OA\Property(
 *     property="type",
 *     type="string",
 *     description="Payment type",
 *     enum={"credit_card", "cash_on_delivery", "bank_transfer"}
 * ),
 *     @OA\Property(property="details", type="object", description="Payment details (empty object)", properties={}),
 * )
 */


class PaymentController extends GenericController
{
    use ApiResponseTrait;


    protected function getModel(): string
    {
        return Payment::class;
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
     *     path="/payment/create",
     *     summary="Create a new payment",
     *     security={{"bearerAuth":{}}},
     *     description="Create a new payment",
     *     operationId="createPayment",
     *     tags={"Payments"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product data",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Payment"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Payment created successfully",
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
     *     path="/payments",
     *     summary="List payments",
     *     description="Retrieve a list of payments",
     *     operationId="listPayments",
     *     tags={"Payments"},
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
     *     path="/payment/{uuid}",
     *     summary="Update a payment",
     *     security={{"bearerAuth":{}}},
     *     description="Update a payment identified by UUID",
     *     operationId="updatePayment",
     *     tags={"Payments"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the payment to update",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product data",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Payment"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
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
     *     path="/payment/{uuid}",
     *     summary="Delete a payment",
     *     security={{"bearerAuth":{}}},
     *     description="Deletes a payment identified by UUID",
     *     operationId="deletePayment",
     *     tags={"Payments"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the payment to delete",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Payment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
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
     *     path="/payment/{uuid}",
     *     summary="Retrieve a payment",
     *     description="Retrieve a payment identified by UUID",
     *     operationId="retrievePayment",
     *     tags={"Payments"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID of the payment to retrieve",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment retrieved successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
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
