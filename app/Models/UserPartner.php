<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPartner extends Model
{
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'partner_id');
    }

    public function mainPartner()
    {
        return $this->belongsTo(self::class, 'main_partner_id');
    }
}
