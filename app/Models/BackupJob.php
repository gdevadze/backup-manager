<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupJob extends Model
{
    protected $fillable = ['backup_server_id','type','cron','is_active'];

    public function server()
    {
        return $this->belongsTo(BackupServer::class);
    }
}
