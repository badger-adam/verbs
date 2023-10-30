<?php

namespace Thunk\Verbs\Models;

use Illuminate\Database\Eloquent\Model;
use Thunk\Verbs\Event;
use Thunk\Verbs\Support\EventSerializer;

class VerbEvent extends Model
{
    public $table = 'verb_events';

    public $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    protected ?Event $event = null;

    public function event(): Event
    {
        $this->event ??= app(EventSerializer::class)->deserialize($this->type, $this->data);
        $this->event->fired = true;

        return $this->event;
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWhereDataContains($query, array $data)
    {
        return $query->whereJsonContains('data', $data);
    }
}