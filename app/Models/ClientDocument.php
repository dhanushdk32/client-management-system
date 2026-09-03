<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientDocument extends Model
{
    protected $table = 'client_documents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'client_id',
        'uploaded_by',
        'document_type',
        'document_name',
        'file_path',
        'status',
        'approval_status',
        'client_feedback',
        'is_deliverable',
    ];

    protected $casts = [
        'is_deliverable' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }
}
