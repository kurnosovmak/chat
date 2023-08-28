<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Images extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        ];

    public function users(): MorphToMany{
        return $this->morphToMany(User::class,'model', 'model_image');
    }
}
