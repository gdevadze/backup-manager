<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class BackupServer extends Model
{
    protected $fillable = [
        'name','host','ssh_user','ssh_port',
        'remote_path','exclude_args',
        'db_name','db_user','db_password',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jobs()
    {
        return $this->hasMany(BackupJob::class);
    }

    public function backups()
    {
        return $this->hasMany(Backup::class);
    }

    public function databases()
    {
        return $this->hasMany(BackupDatabase::class);
    }

    public function setDbPasswordAttribute($value): void
    {
        $this->attributes['db_password'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getDbPasswordDecrypted(): ?string
    {
        if (!$this->db_password) return null;
        try {
            return Crypt::decryptString($this->db_password);
        } catch (\Throwable) {
            return null;
        }
    }
}
