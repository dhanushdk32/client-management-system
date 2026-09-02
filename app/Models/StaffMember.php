<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StaffMember extends Authenticatable
{
    use Notifiable;

    protected $table = 'staff_members';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'secondary_phone',
        'designation',
        'department',
        'password',
        'status',
        'avatar',
        'created_by_admin_id',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function assignedClients()
    {
        return $this->belongsToMany(Client::class, 'client_assignments', 'staff_id', 'client_id')
                    ->withPivot('role_in_project', 'assigned_by_admin_id')
                    ->withTimestamps('created_at', 'updated_at');
    }

    public function assignedTickets()
    {
        return $this->hasMany(SupportTicket::class, 'assigned_staff_id');
    }

    public function documents()
    {
        return $this->hasMany(StaffDocument::class, 'staff_id');
    }
}
