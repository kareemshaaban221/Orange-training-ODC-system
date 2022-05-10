<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'phone',
        'address',
        'college_name',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
}
