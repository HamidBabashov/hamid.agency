<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainExtension extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'register_price' => 'decimal:2',
        'renew_price' => 'decimal:2',
        'transfer_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function setExtensionAttribute(string $value): void
    {
        $this->attributes['extension'] = ltrim(mb_strtolower(trim($value)), '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('extension');
    }

    public function label(): string
    {
        return '.'.$this->extension;
    }
}
