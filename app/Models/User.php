<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
/**
 * Class User.
 *
 * @package namespace App\Models;
 */
class User extends Authenticatable implements Transformable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, TransformableTrait;


    /**
     * User  active
     */
    const STATUS_ACTIVE = 'ACTIVE';
    /**
     * User  active
     */
    const STATUS_BLOCK = 'BLOCK';

    /**
     * Role  admin
     */
    const ROLE_ADMIN = 'ADMIN';

    /**
     * Role  user
     */
    const ROLE_USER = 'USER';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'phone',
		'name',
		'photo',
        'email',
        'role',
        'status',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function setPasswordAttribute($password)
    {

        $this->attributes['password'] = bcrypt($password);
    }

    public function generatePassword($password)
    {
        if ($password != null) {
            $this->password = bcrypt($password);
            $this->save();
        }
    }
}
