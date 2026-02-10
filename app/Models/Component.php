<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'manufacturer_id',
        'type',
        'image',
        'price',
    ];

    // public function manufacturer(): BelongsTo
    // {
    //     return $this->belongsTo(Manufacturer::class);
    // }

    public function assemblies(): BelongsToMany
    {
        return $this->belongsToMany(Assembly::class, 'assembly_components');
    }
}
