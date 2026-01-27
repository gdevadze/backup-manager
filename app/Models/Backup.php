<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Backup extends Model
{
    protected $fillable = [
        'backup_server_id','backup_date','path','size_bytes','status','log','error_code'
    ];

    protected $casts = [
        'backup_date' => 'date',
        'size_bytes' => 'integer',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(BackupServer::class, 'backup_server_id');
    }

}
