<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'mobile', 'google_id', 'avatar',
    ];

    public function records()
    {
        return $this->hasMany(Record::class, 'user_id', 'id');
    }

    public function incomes()
    {
        return $this->records()->where('type', "Income");
    }

    public function expenses()
    {
        return $this->records()->where('type', "Expense");
    }
}
