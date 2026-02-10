<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssemblyComponent extends Model
{
    protected $table = 'assembly_components';

    public $timestamps = false;

    protected $fillable = [
        'assembly_id',
        'component_id',
        'location',
    ];
}
