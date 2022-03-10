<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static where(string $string, string $string1, $phoneNumber)
 * @method static create(array $array)
 * @property mixed $pax_name
 * @property mixed $pax_email
 * @property mixed $pax_mobile
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    public $timestamps = false;
    protected $primaryKey = 'pax_id';

    protected $fillable = [
        'pax_id',
        'pax_name',
        'pax_email',
        'pax_mobile',
        'is_verified'
    ];

}
