<?php

namespace App\Models;

use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasStatus;

    protected $guarded = ['id'];

    protected $casts = ['details' => 'array'];
}
