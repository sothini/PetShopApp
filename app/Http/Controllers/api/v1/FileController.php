<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    use ApiResponseTrait;
    /**
     * @OA\Post(
     *     path="/file/upload",
     *     tags={"File"},
     *     summary="Upload a file",
     *     description="Upload a file to the pet shop.",
     *     operationId="uploadFile",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="file",
     *                     description="The file to be uploaded",
     *                     type="file",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="File uploaded successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     * )
     */
    public function upload(Request $request)
    {
        // Validate the request data
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048', 
        ]);

        // Store the uploaded file
        $file = $request->file('file');
        $path = $file->store('uploads'); 

        // Create a new PetshopFile record
        $petshopFile = new File([
           
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getClientMimeType(),
            
        ]);
        $petshopFile->save();

        return $this->successResponse($petshopFile);
        
    }

    /**
     * @OA\Get(
     *     path="/file/{uuid}",
     *     tags={"File"},
     *     summary="Get a file by UUID",
     *     description="Retrieve a file from the pet shop by its UUID.",
     *     operationId="getFileByUuid",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the file to retrieve",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File retrieved successfully",
     *         @OA\MediaType(
     *             mediaType="application/octet-stream",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="File not found",
     *     ),
     * )
     */
    public function show($uuid)
    {
        $petshopFile = File::where('uuid', $uuid)->firstOrFail();

      
        return response()->download(storage_path('app/' . $petshopFile->path), $petshopFile->name);
    }

   
}
