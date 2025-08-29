<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Message extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($message) {
            if (empty($message->id)) {
                $message->id = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'messageable_type',
        'messageable_id',
        'sender_id',
        'body',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    /**
     * Get the messageable entity (e.g., Report)
     */
    public function messageable()
    {
        return $this->morphTo();
    }

    /**
     * Get the sender of the message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
