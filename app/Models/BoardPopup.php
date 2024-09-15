<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardPopup extends Model
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

    public function setByData($data, $b_sid)
    {
        if (empty($this->sid)) {
            $this->b_sid = $b_sid;
            $this->u_sid = thisPK();
        }

        $popup_select = $data['popup_select'];
        $popup_detail = $data['popup_detail'] ?? 'N';
        $popup_contents = ($popup_select == '1') ? $data['contents'] : $data['popup_contents'];

        $this->width = $data['width'];
        $this->height = $data['height'];
        $this->position_x = $data['position_x'];
        $this->position_y = $data['position_y'];
        $this->popup_sDate = $data['popup_sDate'];
        $this->popup_eDate = $data['popup_eDate'];
        $this->popup_skin = $data['popup_skin'];
        $this->popup_link = ($popup_detail === 'N') ? null : $data['popup_link'];
        $this->popup_detail = $popup_detail;
        $this->popup_select = $popup_select;
        $this->popup_contents = $popup_contents;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'u_sid');
    }

    public function board()
    {
        return $this->belongsTo(Board::class, 'b_sid');
    }
}
