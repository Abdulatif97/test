<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Auto.
 *
 * @package namespace App\Models;
 */
class Auto extends Model implements Transformable
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
		'model_id',
		'type_id',
		'motor_id',
		'status',
		'description',
		'color',
		'year',
	];

    public function model()
    {
        return $this->belongsTo(AutoModel::class);
    }
    public function type()
    {
        return $this->belongsTo(AutoType::class);
    }

    public function motor()
    {
        return $this->belongsTo(AutoMotor::class);
    }
}
