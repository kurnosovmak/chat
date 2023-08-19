<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{
    use HasFactory;
    protected $table = 'chat_user';
    protected $guarded = [
        'chat_id',
        'user_id',
    ];

    protected $fillable = [
        'chat_id',
        'user_id',
    ];
}
