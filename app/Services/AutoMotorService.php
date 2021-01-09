<?php

namespace App\Services;

use Exception;
use App\Models\AutoMotor;
use Illuminate\Log\Logger;
use App\Helpers\FileHelper;
use Illuminate\Support\Arr;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\AutoMotorRepository;
use App\Services\Contracts\AutoMotorService as AutoMotorServiceInterface;

/**
 * @method bool destroy
 */
class AutoMotorService  extends BaseService implements AutoMotorServiceInterface
{

    /**
     * @var DatabaseManager $databaseManager
     */
    protected $databaseManager;

    /**
     * @var AutoMotorRepository $repository
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
     * @param AutoMotorRepository $repository
     * @param Logger $logger
     * @param FileHelper $fileHelper
     *
     */
    public function __construct(
        DatabaseManager $databaseManager,
        AutoMotorRepository $repository,
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
     * @return AutoMotor
     * @throws \Exception
     */
    public function store(array $data)
    {
        $this->beginTransaction();
        try {
            $autoMotor = $this->repository->newInstance();
            $this->storeFiles($data);

            $autoMotor->fill($data);
            if (!$autoMotor->save()) {
                throw new Exception('AutoMotor was not saved to the database.');
            }
            $this->logger->info('AutoMotor successfully saved.', ['autoMotor_id' => $autoMotor->id]);
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while storing an ', [
                'data' => $data,
            ]);
        }

        $this->commit();
        return $autoMotor;
    }

    /**
     * Update block in the storage.
     *
     * @param  int  $id
     * @param  array  $data
     *
     * @return AutoMotor
     *
     * @throws
     */
    public function update($id, array $data)
    {
        $this->beginTransaction();

        try {
            $autoMotor = $this->repository->find($id);
            $this->storeFiles($data);


            if (!$autoMotor->update($data)) {
                throw new Exception('An error occurred while updating a AutoMotor');
            }

            $this->logger->info('AutoMotor  was successfully updated.');
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while updating an articles.', [
                'id'   => $id,
                'data' => $data,
            ]);
        }
        $this->commit();
        return $autoMotor;
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
            $autoMotor = $this->repository->find($id);

            $bufferCategory['id'] = $autoMotor->id;
            $bufferCategory['name'] = $autoMotor->name;

            if (!$autoMotor->delete($id)) {
                throw new Exception(
                    'AutoMotor and  translations was not deleted from database.'
                );
            }
            $this->logger->info('AutoMotor  was successfully deleted from database.');
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
