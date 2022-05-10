<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRevision extends Model
{
    use HasFactory;

    protected $table = 'exam_revision';

    protected $fillable = [
        'student_degree',
        'no_correct_answers',
        'no_wrong_answers',
        'exam_id',
        'student_id'
    ];

    public function exam() {
        return $this->belongsTo(Exam::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
