<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'client_id',
        'user_id',
        'admin_id',
        'action',
        'module',
        'description',
        'ip_address'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    public function admin()
    {
        return $this->belongsTo(PortalAdmin::class, 'admin_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(ClientUser::class, 'user_id', 'id');
    }
}
