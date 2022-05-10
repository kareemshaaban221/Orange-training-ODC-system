<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Services\CourseServices;
use App\Http\Controllers\Services\EnrollServices;
use App\Http\Controllers\Services\Response;
use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    use Response;

    public function index() {
        $response = [
            'courses' => CourseServices::getAll(),
            'message' => 'All Courses'
        ];
        return $this->goodResponse($response);
    }

    public function enroll($course_name) {
        $enrollment = EnrollServices::getAll()->where('student_id', Auth::user()->id)->first();
        if($enrollment) {
            $response = [
                'message' => 'You Have Already Erolled In A Course!',
                'enrollment' => $enrollment
            ];

            return $this->alreadyExistsResponse($response);
        } else {
            $enrollment = EnrollServices::store($course_name);

            if(!$enrollment) return $this->notFoundResponse('course');
            $response = [
                'enrollment' => $enrollment,
                'message' => 'You Have Enrolled In Course ' . ucwords($enrollment->course->name) . ' Successfully!'
            ];
            return $this->goodResponse($response);
        }
    }
}
