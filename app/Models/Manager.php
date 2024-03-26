<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Manager extends Model
{
    use Notifiable;
    use HasFactory;

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
