<?php

namespace App\Presenters;

use App\Transformers\AutoModelTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AutoModelPresenter.
 *
 * @package namespace App\Presenters;
 */
class AutoModelPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AutoModelTransformer();
    }
}
