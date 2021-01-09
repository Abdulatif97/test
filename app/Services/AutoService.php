<?php

namespace App\Services;

use Exception;
use App\Models\Auto;
use Illuminate\Log\Logger;
use App\Helpers\FileHelper;
use Illuminate\Support\Arr;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\AutoRepository;
use App\Services\Contracts\AutoService as AutoServiceInterface;

/**
 * @method bool destroy
 */
class AutoService  extends BaseService implements AutoServiceInterface
{

    /**
     * @var DatabaseManager $databaseManager
     */
    protected $databaseManager;

    /**
     * @var AutoRepository $repository
     */
    protected $repository;

    /**
     * Language $language
     */
    protected $language;

    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * @var FileHelper $fileHelper
     */
    protected $fileHelper;

    /**
     * Category constructor.
     *
     * @param DatabaseManager $databaseManager
     * @param AutoRepository $repository
     * @param Logger $logger
     * @param FileHelper $fileHelper
     *
     */
    public function __construct(
        DatabaseManager $databaseManager,
        AutoRepository $repository,
        Logger $logger,
        FileHelper $fileHelper
    ) {

        $this->databaseManager     = $databaseManager;
        $this->repository     = $repository;
        $this->logger     = $logger;
        $this->fileHelper     = $fileHelper;
    }

    /**
     * Create category
     *
     * @param array $data
     * @return Auto
     * @throws \Exception
     */
    public function store(array $data)
    {
        $this->beginTransaction();
        try {
            $auto = $this->repository->newInstance();
            $this->storeFiles($data);
            $auto->fill($data);
            if (!$auto->save()) {
                throw new Exception('Auto was not saved to the database.');
            }
            $this->logger->info('Auto successfully saved.', ['auto_id' => $auto->id]);
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while storing an ', [
                'data' => $data,
            ]);
        }

        $this->commit();
        return $auto;
    }

    /**
     * Update block in the storage.
     *
     * @param  int  $id
     * @param  array  $data
     *
     * @return Auto
     *
     * @throws
     */
    public function update($id, array $data)
    {
        $this->beginTransaction();

        try {
            $auto = $this->repository->find($id);
            $this->storeFiles($data);

            if (!$auto->update($data)) {
                throw new Exception('An error occurred while updating a Auto');
            }

            $this->logger->info('Auto  was successfully updated.');
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while updating an articles.', [
                'id'   => $id,
                'data' => $data,
            ]);
        }
        $this->commit();
        return $auto;
    }
    /**
     * Delete block in the storage.
     *
     * @param  int  $id
     *
     * @return array
     *
     * @throws
     */
    public function delete($id)
    {

        $this->beginTransaction();

        try {
            $bufferCategory = [];
            $auto = $this->repository->find($id);

            $bufferCategory['id'] = $auto->id;
            $bufferCategory['name'] = $auto->name;

            if (!$auto->delete($id)) {
                throw new Exception(
                    'Auto and  translations was not deleted from database.'
                );
            }
            $this->logger->info('Auto  was successfully deleted from database.');
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while deleting an.', [
                'id'   => $id,
            ]);
        }
        $this->commit();
        return $bufferCategory;
    }


    protected function storeFiles(&$data = [])
    {
        $uploadedImage = Arr::get($data, 'image');
        if ($uploadedImage) {
            $data['image'] = $this->fileHelper->upload($uploadedImage, 'img\content');
        }
    }

}
