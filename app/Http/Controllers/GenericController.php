<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\GenericCrudTrait;
use Exception;
use Illuminate\Http\Request;

abstract class GenericController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        try {
            $perPage = request()->query('limit', 10); // Default to 10 items per page
            $page = request()->query('page', 1);

            $query = $this->getItems();

            $results = $query->paginate($perPage, ['*'], 'page', $page);

            return $this->successResponse($results);
        } catch (Exception $ex) {
            return $this->errorResponse("An error occurred", 500, [$ex->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate($this->getModel()::validation_rules() ?? []);

            $item = $this->getModel()::create($validatedData);

            return $this->successResponse($item, 201);
        } catch (Exception $ex) {
            return $this->errorResponse("An error occurred", 500, [$ex->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $item = $this->getModel()::where('uuid', $id)->firstOrFail();
            return $this->successResponse($item);
        } catch (Exception $ex) {
            return $this->errorResponse("An error occurred", 500, [$ex->getMessage()]);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $item = $this->getModel()::where('uuid', $id)->firstOrFail();
            $validatedData = $request->validate($this->getModel()::validation_rules() ?? []);

            $item->update($validatedData);

            return $this->successResponse($item);
        } catch (Exception $ex) {
            return $this->errorResponse("An error occurred", 500, [$ex->getMessage()]);
        }
    }


    public function destroy($id)
    {
        try {
            $item = $this->getModel()::where('uuid', $id)->firstOrFail();
            $item->delete();

            return $this->successResponse('Item deleted successfully', 204);
        } catch (Exception $ex) {
            return $this->errorResponse("An error occured", 500, [$ex->getMessage()]);
        }
    }


    abstract protected function getModel(): string;
    abstract protected function getItems();


    public function search($query, $term, $columns)
    {
        if (!empty($columns) && is_array($columns)) {
            $query->where(function ($query) use ($columns, $term) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', '%' . $term . '%');
                }
            });
        }
        return $query;
    }


    public function filter($query, $keys)
    {
        foreach ($keys as $key) {
            if (request()->has($key) && request()->query($key) != null) {
                $query->where($key, request()->query($key));
            }
        }

        return $query;
    }
}
