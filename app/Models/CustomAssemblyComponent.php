<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomAssemblyComponent extends Model
{
    protected $table = 'custom_assembly_components';

    public $timestamps = false;

    protected $fillable = [
        'custom_assembly_id',
        'component_id',
    ];

    public function customAssembly(): BelongsTo
    {
        return $this->belongsTo(CustomAssembly::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
}
