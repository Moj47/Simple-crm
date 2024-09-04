<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    use HasFactory;
    public function project()
    {
        return $this->hasMany(Project::class);
    }

    protected $fillable=['name','email','phone','address'];
}
