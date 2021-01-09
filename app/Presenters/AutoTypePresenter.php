<?php

namespace App\Presenters;

use App\Transformers\AutoTypeTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AutoTypePresenter.
 *
 * @package namespace App\Presenters;
 */
class AutoTypePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AutoTypeTransformer();
    }
}
