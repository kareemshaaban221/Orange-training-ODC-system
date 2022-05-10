<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

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

    public function enroll() {
        return $this->hasOne(Enroll::class);
    }
}
