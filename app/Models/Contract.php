<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function userPartner()
    {
        return $this->belongsTo(UserPartner::class, 'partner_id');
    }
}
