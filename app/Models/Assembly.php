<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assembly extends Model
{
    //use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'price',
    ];

    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Component::class, 'assembly_components')->withPivot('location');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_assemblies');
    }
}
