<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class BackupDatabase extends Model
{
    protected $fillable = [
        'backup_server_id',
        'name',
        'db_host',
        'db_port',
        'db_name',
        'db_user',
        'db_password',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // 🔐 encrypt/decrypt automatically
    public function setDbPasswordAttribute($value)
    {
        $this->attributes['db_password'] = Crypt::encryptString($value);
    }

    public function getDbPasswordAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function server()
    {
        return $this->belongsTo(BackupServer::class, 'backup_server_id');
    }
}
