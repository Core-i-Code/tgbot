<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'group_id',
        'key',
        'value',
    ];

    /**
     * Get the group that owns the setting.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the value attribute with JSON decoding.
     */
    public function getValueAttribute($value)
    {
        // Try to decode as JSON, if fails return as string
        $decoded = json_decode($value, true);
        return $decoded !== null ? $decoded : $value;
    }

    /**
     * Set the value attribute with JSON encoding.
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = is_array($value) || is_object($value) 
            ? json_encode($value) 
            : $value;
    }
}