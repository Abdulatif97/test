<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class AutoType.
 *
 * @package namespace App\Models;
 */
class AutoType extends Model implements Transformable
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
	];


    public function autos()
    {
        return $this->hasMany(Auto::class,'type_id');
    }
}
