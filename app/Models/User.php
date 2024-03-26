<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'password', 'email',
        'first_name_ru', 'first_name_en', 
        'first_name', 'middle_name', 'last_name',
        'company_name', 'country_code',
        'phone', 'comment',
        'last_login_ip', 'last_login_time',
        'added_time', 'upd_time',
        'was_updated',  'status',
        'code1c', 'is_deleted', 'link',
        'sub_dealer', 'role_id',
        'hash', 'remember_token',
        'edo_role_id',
        'has_unsigned_documents',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_login_time' => 'datetime',
            'added_time' => 'datetime',
            'upd_time' => 'datetime',
            'status' => 'boolean',
            'was_updated' => 'boolean',
            'is_deleted' => 'boolean',
            'has_unsigned_documents' => 'boolean',
        ];
    }


    public function unsignedInvoices()
{
    return $this->hasMany(OrderSalesInvoice::class, 'partner_id')->where('status', '!=', 'customer-signed');
}
}
