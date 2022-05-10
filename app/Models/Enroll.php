<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    use HasFactory;
    
    protected $table = 'std_enroll';

    protected $fillable = [
        'student_id',
        'course_id',
    ];
}
