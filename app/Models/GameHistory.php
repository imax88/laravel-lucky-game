<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'random_number', 'result', 'winnings'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
