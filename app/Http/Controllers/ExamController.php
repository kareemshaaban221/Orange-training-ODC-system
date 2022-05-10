<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Services\EnrollServices;
use App\Http\Controllers\Services\ExamRevisionServices;
use App\Http\Controllers\Services\ExamServices;
use App\Http\Controllers\Services\Response;
use App\Models\ExamRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    use Response;

    public function getExamCode() {
        $code = $this->checkExamCode();

        if(!$code)
            return $this->notFoundResponse('enrollment');
        else
            return $code == 1 ? $this->goodResponse([
                'message' => 'Your Enrollment Is Not Accepted Yet! try again later.'
            ]) : $this->goodResponse([
                'exam_code' => $code
            ]);
    }

    public function getExam($exam_code) {
        $code = $this->checkExamCode();

        if(!$code)
            return $this->notFoundResponse('enrollment');
        elseif($code == 1)
            return $this->goodResponse([
                'message' => 'Your Enrollment Is Not Accepted Yet!'
            ]);

        if($code !== $exam_code)
            return $this->badRequestResponse('Invalid Exam Code, Please Enter The Code Sent To You!');

        $exams = Auth::user()->enroll->course->exams;

        if(count($exams)) {
            $exam = $exams->random(1)->first();
            $duration = $exam->duration;
            $exam_id = $exam->id;

            $exam = $exam->questions->shuffle();

            return count($exam) ? $this->goodResponse([
                'exam_id' => $exam_id,
                'exam' => $exam,
                'duration' => $duration,
                'message' => 'Your Exam Has Been Generated'
            ]) : $this->notFoundResponse('Questions');
        } else {
            return $this->notFoundResponse('Exam');
        }
    }

    public function submit(Request $request) {
        // $request = exam_id, correct questions, wrong questions (student_id, student_degree)
        $ids = ExamServices::getStringOfAllRowsOf(ExamServices::class, 'id');

        $request->validate([
            'exam_id' => "required|integer|in:$ids",
            'no_correct_answers' => 'required|integer|max:255',
            'no_wrong_answers' => 'required|integer|max:255'
        ]);

        $rev = ExamRevisionServices::store($request);
        if(!$rev) return $this->alreadyExistsResponse(['message' => 'You Have Already Submitted!']);

        $response = [
            'exam_revision' => $rev,
            'course' => ExamServices::getAll($request->exam_id)->course->name
        ];

        return $this->createdResponse($response, 'Exam Revision');
    }

    private function checkExamCode() {
        $std_enroll = EnrollServices::getAll()->where('student_id', Auth::user()->id)->first();

        if(!$std_enroll) {
            return 0;
        } else {
            return $std_enroll->exam_code ? $std_enroll->exam_code : 1;
        }
    }
}
