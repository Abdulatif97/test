<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\AutoMotor;

/**
 * Class AutoMotorTransformer.
 *
 * @package namespace App\Transformers;
 */
class AutoMotorTransformer extends TransformerAbstract
{
    /**
     * Transform the AutoMotor entity.
     *
     * @param \App\Models\AutoMotor $model
     *
     * @return array
     */
    public function transform(AutoMotor $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
