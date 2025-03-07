<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileRecord extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'original_name',
        'path',
        'mime_type',
        'size',
        'created_by',
        'updated_by',
        'fileable_id',
        'fileable_type',
    ];

    
}
