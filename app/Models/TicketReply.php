<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    protected $table = 'ticket_replies';

    protected $fillable = [
        'ticket_id',
        'sender_type',
        'sender_id',
        'message',
        'attachment_path',
        'attachment_name',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'id');
    }
}
