<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\AutoModelRepository;
use App\Models\AutoModel;
use App\Validators\AutoModelValidator;

/**
 * Class AutoModelRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AutoModelRepositoryEloquent extends BaseRepository implements AutoModelRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AutoModel::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return AutoModelValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
