<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_id', 'user_id', 'status', 'created_by', 'updated_by'
    ];

    protected $appends = [
        'received'
    ];

    public function getReceivedAttribute()
    {
        return $this->user_id == auth()->user()->id;
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
