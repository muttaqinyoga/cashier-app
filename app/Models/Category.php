<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // protected $table = 'categoru';
    public $incrementing = false;

    public function food()
    {
        return $this->belongsToMany(Food::class);
    }
}
