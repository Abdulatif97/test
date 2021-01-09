<?php

namespace App\Services\Contracts;

use App\Models\AutoModel;

/**
 * Interface AutoModelSevice
 *
 * @package App\Services\Contracts
 * @method bool destroy(int)
 */
interface AutoModelService extends BaseService
{
    /**
     * Store a newly created resource in storage
     *
     * @param array $data
     * @return AutoModel
     */
    public function store(array $data);

    /**
     * Update block in the storage.
     *
     * @param  int  $id
     * @param  array  $data
     * @return AutoModel
     */
    public function update($id, array $data);

    /**
     * Update block in the storage.
     *
     * @param  int  $id
     * @return array
     */
    public function delete($id);
}
