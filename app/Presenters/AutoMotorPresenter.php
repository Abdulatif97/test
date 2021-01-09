<?php

namespace App\Presenters;

use App\Transformers\AutoMotorTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AutoMotorPresenter.
 *
 * @package namespace App\Presenters;
 */
class AutoMotorPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AutoMotorTransformer();
    }
}
