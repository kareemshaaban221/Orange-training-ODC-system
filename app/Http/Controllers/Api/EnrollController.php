<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\EnrollServices;
use App\Http\Controllers\Services\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EnrollController extends Controller
{
    use Response; // admins and cache admins method

    protected $authorized = false;

    public function __construct()
    {
        if(!Auth::guard('sanctum')->user()) return; //if not authenticated then pass

        if(Auth::guard('sanctum')->user()->role == 'admin') {
            EnrollServices::cache();
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
        return $this->goodResponse( ['enrolls' => EnrollServices::getAll()] );
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

        $request->validate([
            'course_code' => 'required|string'
        ]);

        $enroll = EnrollServices::getAll($id);
        if($enroll) {
            $response = [
                'enroll' => EnrollServices::update($enroll, $request),
                'message' => 'Course Has Been Updated Successfully!'
            ];
            return $this->goodResponse($response);
        } else {
            return $this->notFoundResponse('Course');
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
        //
    }

    public function sendCode($enroll_id) {
        $enroll = EnrollServices::getAll($enroll_id);

        if($enroll) {
            if(!$enroll->exam_code) {
                $enroll->exam_code = Str::random(10);
                $enroll->save();

                EnrollServices::resetCacheVariable('enrolls');

                $response = [
                    'code' => $enroll->exam_code,
                    'message' => 'Code Has Been Generated Successfully!'
                ];
                return $this->goodResponse($response);
            } else {
                $response = [
                    'message' => 'This Enroll Has Already taken A Code',
                    'code' => $enroll->exam_code
                ];
                return $this->alreadyExistsResponse($response);
            }
        } else {
            return $this->notFoundResponse('Enroll');
        }
    }
}
