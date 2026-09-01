<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAssignment extends Model
{
    protected $table = 'client_assignments';

    protected $fillable = [
        'staff_id',
        'client_id',
        'role_in_project',
        'assigned_by_admin_id',
    ];

    public function staff()
    {
        return $this->belongsTo(StaffMember::class, 'staff_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
