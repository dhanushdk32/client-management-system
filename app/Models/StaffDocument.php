<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDocument extends Model
{
    use HasFactory;

    protected $table = 'staff_documents';

    protected $fillable = [
        'staff_id',
        'document_name',
        'document_type',
        'file_path',
        'file_type',
        'status',
        'remarks',
        'verified_at',
        'verified_by_admin_id'
    ];

    public function staff()
    {
        return $this->belongsTo(StaffMember::class, 'staff_id', 'id');
    }

    public function verifiedByAdmin()
    {
        return $this->belongsTo(PortalAdmin::class, 'verified_by_admin_id', 'id');
    }
}
