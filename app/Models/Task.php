<?php

namespace App\Models;

use App\Models\Traits\HasChildren;
use App\Models\Traits\IsOrderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, HasChildren, IsOrderable, SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'status',
        'order'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sub_tasks() : HasMany
    {
        return $this->hasMany(Task::class, 'parent_id', 'id');
    }
}
