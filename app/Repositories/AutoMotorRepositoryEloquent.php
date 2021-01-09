<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\AutoMotorRepository;
use App\Models\AutoMotor;
use App\Validators\AutoMotorValidator;

/**
 * Class AutoMotorRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AutoMotorRepositoryEloquent extends BaseRepository implements AutoMotorRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AutoMotor::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return AutoMotorValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
