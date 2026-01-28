<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Backup extends Model
{
    protected $guarded = [];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(BackupServer::class, 'backup_server_id');
    }

    public function sizeHuman(): Attribute
    {
        $size = $this->size
            ? $this->formatBytes($this->size)
            : '-';
        return new Attribute(
            get: fn () => $size
        );
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

}
