<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\AutoType;

/**
 * Class AutoTypeTransformer.
 *
 * @package namespace App\Transformers;
 */
class AutoTypeTransformer extends TransformerAbstract
{
    /**
     * Transform the AutoType entity.
     *
     * @param \App\Models\AutoType $model
     *
     * @return array
     */
    public function transform(AutoType $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
