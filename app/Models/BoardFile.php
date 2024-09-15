<?php

namespace App\Models;

use App\Services\CommonServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardFile extends Model
{
    use HasFactory;

    protected $primaryKey = 'sid';

    protected $guarded = [
        'sid',
        'b_sid',
        'u_sid',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected static function booted()
    {
        parent::boot();

        static::deleting(function ($file) {
            // 파일 데이터 삭제시 파일경로에 있는 실제 파일 삭제
            (new CommonServices())->fileDeleteService($file->realfile);
        });
    }

    public function setByData($data, $b_sid)
    {
        if (empty($this->sid)) {
            $this->b_sid = $b_sid;
            $this->u_sid = thisPK();
        }

        $this->realfile = $data['realfile'];
        $this->filename = $data['filename'];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'u_sid');
    }

    public function board()
    {
        return $this->belongsTo(Board::class, 'b_sid');
    }

    public function downloadUrl()
    {
        return route('download', ['type' => 'only', 'tbl' => 'boardFile', 'sid' => enCryptString($this->sid)]);
    }
}
