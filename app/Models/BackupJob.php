<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BackupJob extends Model
{
    protected $fillable = ['backup_server_id','type','cron','is_active'];

    public function backup_server(): BelongsTo
    {
        return $this->belongsTo(BackupServer::class);
    }
}
