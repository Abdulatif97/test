<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\AutoTypeRepository;
use App\Models\AutoType;
use App\Validators\AutoTypeValidator;

/**
 * Class AutoTypeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AutoTypeRepositoryEloquent extends BaseRepository implements AutoTypeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AutoType::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return AutoTypeValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
