<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiRequest extends Model
{
    use HasFactory;

    // Define the table name explicitly if it doesn't follow Laravel's convention
    protected $table = 'ai_requests';

    // Fields that are mass assignable
    protected $fillable = [
        'content_id',
        'content',
        'status',
        'image_url',  // This will allow the image_url field to be mass assigned
    ];

    // Optionally define hidden attributes or casts if necessary
    // protected $hidden = ['created_at', 'updated_at'];
    // protected $casts = [
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    // ];
}
