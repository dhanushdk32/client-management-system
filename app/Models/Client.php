<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client_tbl';
    protected $primaryKey = 'client_id';

    const CREATED_AT = 'client_created_date';
    const UPDATED_AT = 'client_updated_date';

    protected $fillable = [
        'entity_id',
        'client_name',
        'client_company',
        'client_location',
        'client_email',
        'primary_contact',
        'client_gst',
        'client_status',
        'industry',
        'company_size',
        'website',
        'city',
        'state',
        'country',
        'primary_contact',
        'secondary_contact',
    ];

    public function users()
    {
        return $this->hasMany(ClientUser::class, 'client_id', 'client_id');
    }
}
