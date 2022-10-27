<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'created_by', 'updated_by'
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_users')->withPivot('joined_at', 'left_at');
    }

    public function records()
    {
        return $this->hasMany(Record::class, 'group_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
