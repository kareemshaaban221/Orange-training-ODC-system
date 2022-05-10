<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function exams() {
        return $this->hasMany(Exam::class);
    }
}
