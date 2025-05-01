<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['username', 'phonenumber'];

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function gameHistories()
    {
        return $this->hasMany(GameHistory::class);
    }
}
