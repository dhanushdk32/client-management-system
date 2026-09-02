<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientService extends Model
{
    protected $table = 'client_services';

    protected $fillable = [
        'client_id',
        'service_name',
        'status',
        'start_date',
        'end_date',
        'description',
        'assigned_team',
        'team_name',
        'team_leader_id',
        'team_members',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'team_members' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    public function teamLeader()
    {
        return $this->belongsTo(StaffMember::class, 'team_leader_id');
    }
}
