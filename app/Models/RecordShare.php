<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecordShare extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'record_id', 'share'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function record()
    {
        return $this->belongsTo(Record::class, 'record_id', 'id');
    }
}
