<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'topic_id'
    ];

    public function Topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
