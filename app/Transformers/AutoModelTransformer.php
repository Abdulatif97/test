<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\AutoModel;

/**
 * Class AutoModelTransformer.
 *
 * @package namespace App\Transformers;
 */
class AutoModelTransformer extends TransformerAbstract
{
    /**
     * Transform the AutoModel entity.
     *
     * @param \App\Models\AutoModel $model
     *
     * @return array
     */
    public function transform(AutoModel $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
