<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Auto;

/**
 * Class AutoTransformer.
 *
 * @package namespace App\Transformers;
 */
class AutoTransformer extends TransformerAbstract
{
    /**
     * Transform the Auto entity.
     *
     * @param \App\Models\Auto $model
     *
     * @return array
     */
    public function transform(Auto $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
