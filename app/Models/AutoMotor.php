<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class AutoMotor.
 *
 * @package namespace App\Models;
 */
class AutoMotor extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'name',
		'image',
		'status',
		'tranz',
		'accept',
    ];


    public function autos()
    {
        return $this->hasMany(Auto::class,'motor_id');
    }
}
