<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';

    protected $fillable = [
        'client_id',
        'created_by',
        'assigned_staff_id',
        'subject',
        'description',
        'attachment_path',
        'attachment_name',
        'status',
        'priority'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(StaffMember::class, 'assigned_staff_id');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class, 'ticket_id', 'id');
    }
}
