<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\CategoryServices;
use App\Http\Controllers\Services\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use Response; // admins and cache admins method

    protected $authorized = false;

    public function __construct()
    {
        if(!Auth::guard('sanctum')->user()) return; //if not authenticated then pass

        if(Auth::guard('sanctum')->user()->role == 'admin') {
            CategoryServices::cache();
            $this->authorized = true;
            return Auth::guard('sanctum')->user()->role;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->authorized)   return $this->notAuthorizedResponse();
        return $this->goodResponse( ['categories' => CategoryServices::getAll()] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->authorized)   return $this->notAuthorizedResponse();
        CategoryServices::validator($request);

        $response = [
            'category' => CategoryServices::store($request),
            'message' => 'Category Has Been Added Successfully!'
        ];
        return $this->goodResponse($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$this->authorized)   return $this->notAuthorizedResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$this->authorized)   return $this->notAuthorizedResponse();
        CategoryServices::validator($request, 'update', $id);

        $category = CategoryServices::getAll($id);
        if($category) {
            $response = [
                'category' => CategoryServices::update($category, $request),
                'message' => 'Category Has Been Updated Successfully!'
            ];
            return $this->goodResponse($response);
        } else {
            return $this->notFoundResponse('Category');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$this->authorized)   return $this->notAuthorizedResponse();
        $category = CategoryServices::getAll($id);

        if($category) {
            $response = [
                'category' => CategoryServices::delete($category),
                'message' => 'Category Has Been Deleted Successfully!'
            ];
            return $this->goodResponse($response);
        } else {
            return $this->notFoundResponse('Category');
        }
    }
}
