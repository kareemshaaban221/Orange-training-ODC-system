<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\ExamRevisionServices;
use App\Http\Controllers\Services\Response;
use App\Models\ExamRevision;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class ExamRevisionController extends Controller
{
    use Response;

    protected $authorized = false;

    public function __construct()
    {
        if(!Auth::guard('sanctum')->user()) return; //if not authenticated then pass

        ExamRevisionServices::cache();
        $this->authorized = true;
        return Auth::guard('sanctum')->user()->role;
    }

    public function getDegree($id) {
        $std_rev = ExamRevision::all()->where('student_id', $id)->first();

        if(!$std_rev) {
            return $this->notFoundResponse('student degree');
        }

        $response = [
            'degree' => $std_rev->student_degree,
        ];

        return $this->goodResponse($response);
    }

    public function passInterview($id) {
        $std = Student::find($id);

        if(!$std) return $this->notFoundResponse('entered student id');

        if($std->status == 'pending') {
            return $this->badRequestResponse('This Student Didn\'t Take Exam Yet!');
        } elseif ($std->status == 'accepted') {
            return $this->badRequestResponse('This Student Is Already Accepted!');
        } elseif ($std->status == 'rejected') {
            return $this->badRequestResponse('This Student Is Already Rejected!');
        }

        $std->status = 'accepted';
        $std->save();

        return $this->goodResponse([
            'student' => $std,
            'message' => 'Accepted Student!'
        ]);
    }

    public function failedInterview($id) {
        $std = Student::find($id);

        if(!$std) return $this->notFoundResponse('entered student id');

        if($std->status == 'pending') {
            return $this->badRequestResponse('This Student Didn\'t The Exam Yet!');
        } elseif ($std->status == 'accepted') {
            return $this->badRequestResponse('This Student Is Already Accepted!');
        } elseif ($std->status == 'rejected') {
            return $this->badRequestResponse('This Student Is Already Rejected!');
        }

        $std->status = 'rejected';
        $std->save();

        return $this->goodResponse([
            'student' => $std,
            'message' => 'Rejected Student!'
        ]);
    }
}
