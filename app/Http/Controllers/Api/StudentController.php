<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Response;
use App\Http\Controllers\Services\StudentServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    use Response; // admins and cache admins method

    protected $authorized = false;

    public function __construct()
    {
        if(!Auth::guard('sanctum')->user()) return; //if not authenticated then pass

        StudentServices::cache();
        $this->authorized = true;
        return Auth::guard('sanctum')->user()->role;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->authorized)   return $this->notAuthorizedResponse();
        return $this->goodResponse( ['students' => StudentServices::getAll()] );
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
        StudentServices::validator($request);

        $response = [
            'student' => StudentServices::store($request),
            'message' => 'Student Has Been Added Successfully!'
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
        StudentServices::validator($request, 'update', $id);

        $request->name = htmlspecialchars($request->name);

        $student = StudentServices::getAll($id);
        if($student) {
            $response = [
                'student' => StudentServices::update($student, $request),
                'message' => 'Student Has Been Updated Successfully!'
            ];
            return $this->goodResponse($response);
        } else {
            return $this->notFoundResponse('students');
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
        $student = StudentServices::getAll($id);

        if($student) {
            $response = [
                'student' => StudentServices::delete($student),
                'message' => 'Student Has Been Deleted Successfully!'
            ];
            return $this->goodResponse($response);
        } else {
            return $this->notFoundResponse('student');
        }
    }
}
