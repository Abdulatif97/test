<?php

namespace App\Services;

use Exception;
use App\Models\AutoModel;
use Illuminate\Log\Logger;
use App\Helpers\FileHelper;
use Illuminate\Support\Arr;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\AutoModelRepository;
use App\Services\Contracts\AutoModelService as AutoModelServiceInterface;

/**
 * @method bool destroy
 */
class AutoModelService  extends BaseService implements AutoModelServiceInterface
{

    /**
     * @var DatabaseManager $databaseManager
     */
    protected $databaseManager;

    /**
     * @var AutoModelRepository $repository
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
     * @param AutoModelRepository $repository
     * @param Logger $logger
     * @param FileHelper $fileHelper
     */
    public function __construct(
        DatabaseManager $databaseManager,
        AutoModelRepository $repository,
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
     * @return AutoModel
     * @throws \Exception
     */
    public function store(array $data)
    {
        $this->beginTransaction();
        try {
            $autoModel = $this->repository->newInstance();
            $this->storeFiles($data);

            $autoModel->fill($data);
            if (!$autoModel->save()) {
                throw new Exception('AutoModel was not saved to the database.');
            }
            $this->logger->info('AutoModel successfully saved.', ['autoModel_id' => $autoModel->id]);
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while storing an ', [
                'data' => $data,
            ]);
        }

        $this->commit();
        return $autoModel;
    }

    /**
     * Update block in the storage.
     *
     * @param  int  $id
     * @param  array  $data
     *
     * @return AutoModel
     *
     * @throws
     */
    public function update($id, array $data)
    {
        $this->beginTransaction();

        try {
            $autoModel = $this->repository->find($id);
            $this->storeFiles($data);


            if (!$autoModel->update($data)) {
                throw new Exception('An error occurred while updating a AutoModel');
            }

            $this->logger->info('AutoModel  was successfully updated.');
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while updating an articles.', [
                'id'   => $id,
                'data' => $data,
            ]);
        }
        $this->commit();
        return $autoModel;
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
            $autoModel = $this->repository->find($id);

            $bufferCategory['id'] = $autoModel->id;
            $bufferCategory['name'] = $autoModel->name;

            if (!$autoModel->delete($id)) {
                throw new Exception(
                    'AutoModel and  translations was not deleted from database.'
                );
            }
            $this->logger->info('AutoModel  was successfully deleted from database.');
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
