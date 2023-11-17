<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    // 指定連接的DB名稱
    protected $connection = 'mysql';
    // 指定Table名稱
    protected $table = 'Player';
    // 主鍵名稱
    protected $primaryKey = 'id';

    public $timestamps = false;

    // 可批量賦值的欄位
    protected $fillable = [
        'account',
        'displayName',
        'password',
        'email',
        'status',
        'balance',
        'lastLoggedInIp',
        'lastLoggedInAt',
    ];

}
