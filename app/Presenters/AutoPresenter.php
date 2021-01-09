<?php

namespace App\Presenters;

use App\Transformers\AutoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AutoPresenter.
 *
 * @package namespace App\Presenters;
 */
class AutoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AutoTransformer();
    }
}
