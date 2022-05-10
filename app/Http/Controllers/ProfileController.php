<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Services\AuthenticationMethods;
use App\Http\Controllers\Services\EnrollServices;
use App\Http\Controllers\Services\ExamRevisionServices;
use App\Http\Controllers\Services\ExamServices;
use App\Http\Controllers\Services\Response;
use App\Http\Controllers\Services\StudentServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use Response, AuthenticationMethods;

    public function index() {
        return $this->goodResponse([
            'student' => Auth::user(),
            'message' => 'Profile Data!'
        ]);
    }

    public function update(Request $request) {
        StudentServices::validator($request, 'update', Auth::user()->id);

        $response = [
            'student' => StudentServices::update(Auth::user() , $request),
        ];

        return $this->resetContentResponse($response, 'Updated Successfully');
    }

    public function updatePassword(Request $request) {
        StudentServices::validator($request, 'password');

        StudentServices::updatePassword(Auth::user() , $request);

        return $this->resetContentResponse([], 'Password Updated Successfully!');
    }

    public function getStatusMessage() {
        return $this->isExaminedAuth() ?
            $this->goodResponse([
                'student_status' => Auth::user()->status,
                'degree' => ExamRevisionServices::getAll()->where('student_id', Auth::user()->id)->first()->student_degree,
                'message' => 'Examined!'
            ]) : $this->goodResponse([
                'student_status' => Auth::user()->status,
                'message' => 'Not Examined!'
            ]);
    }

    private function isExaminedAuth() {
        return !$this->compareStatus('pending');
    }

    private function compareStatus($status) {
        return $status === Auth::user()->status;
    }
}
