<?php

namespace App\Services;

use Exception;
use App\Models\AutoType;
use Illuminate\Log\Logger;
use App\Helpers\FileHelper;
use Illuminate\Support\Arr;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\AutoTypeRepository;
use App\Services\Contracts\AutoTypeService as AutoTypeServiceInterface;

/**
 * @method bool destroy
 */
class AutoTypeService  extends BaseService implements AutoTypeServiceInterface
{

    /**
     * @var DatabaseManager $databaseManager
     */
    protected $databaseManager;

    /**
     * @var AutoTypeRepository $repository
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
     * @param AutoTypeRepository $repository
     * @param Logger $logger
     * @param FileHelper $fileHelper
     *
     */
    public function __construct(
        DatabaseManager $databaseManager,
        AutoTypeRepository $repository,
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
     * @return AutoType
     * @throws \Exception
     */
    public function store(array $data)
    {
        $this->beginTransaction();
        try {
            $autoType = $this->repository->newInstance();
            $this->storeFiles($data);
            $autoType->fill($data);
            if (!$autoType->save()) {
                throw new Exception('AutoType was not saved to the database.');
            }
            $this->logger->info('AutoType successfully saved.', ['autoType_id' => $autoType->id]);
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while storing an ', [
                'data' => $data,
            ]);
        }

        $this->commit();
        return $autoType;
    }

    /**
     * Update block in the storage.
     *
     * @param  int  $id
     * @param  array  $data
     *
     * @return AutoType
     *
     * @throws
     */
    public function update($id, array $data)
    {
        $this->beginTransaction();

        try {
            $autoType = $this->repository->find($id);
            $this->storeFiles($data);
            if (!$autoType->update($data)) {
                throw new Exception('An error occurred while updating a AutoType');
            }

            $this->logger->info('AutoType  was successfully updated.');
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while updating an articles.', [
                'id'   => $id,
                'data' => $data,
            ]);
        }
        $this->commit();
        return $autoType;
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
            $autoType = $this->repository->find($id);

            $bufferCategory['id'] = $autoType->id;
            $bufferCategory['name'] = $autoType->name;

            if (!$autoType->delete($id)) {
                throw new Exception(
                    'AutoType and  translations was not deleted from database.'
                );
            }
            $this->logger->info('AutoType  was successfully deleted from database.');
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
