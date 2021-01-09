<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Log\Logger;
use App\Helpers\FileHelper;
use Illuminate\Support\Arr;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\UserRepository;
use App\Services\Contracts\UserService as UserServiceInterface;

/**
 * @method bool destroy
 */
class UserService  extends BaseService implements UserServiceInterface
{

    /**
     * @var DatabaseManager $databaseManager
     */
    protected $databaseManager;

    /**
     * @var UserRepository $repository
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
     * @param UserRepository $repository
     * @param Logger $logger
     * @param FileHelper $fileHelper
     *
     */
    public function __construct(
        DatabaseManager $databaseManager,
        UserRepository $repository,
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
     * @return User
     * @throws \Exception
     */
    public function store(array $data)
    {
        $this->beginTransaction();
        try {
            $user = $this->repository->newInstance();
            $this->storeFiles($data);
            $user->fill($data);
            if (!$user->save()) {
                throw new Exception('User was not saved to the database.');
            }
            $this->logger->info('User successfully saved.', ['user_id' => $user->id]);
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while storing an ', [
                'data' => $data,
            ]);
        }

        $this->commit();
        return $user;
    }

    /**
     * Update block in the storage.
     *
     * @param  int  $id
     * @param  array  $data
     *
     * @return User
     *
     * @throws
     */
    public function update($id, array $data)
    {
        $this->beginTransaction();

        try {
            $user = $this->repository->find($id);
            $this->storeFiles($data);
            if (!$user->update($data)) {
                throw new Exception('An error occurred while updating a User');
            }

            $this->logger->info('User  was successfully updated.');
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while updating an articles.', [
                'id'   => $id,
                'data' => $data,
            ]);
        }
        $this->commit();
        return $user;
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
            $user = $this->repository->find($id);

            $bufferCategory['id'] = $user->id;
            $bufferCategory['name'] = $user->name;

            if (!$user->delete($id)) {
                throw new Exception(
                    'User and  translations was not deleted from database.'
                );
            }
            $this->logger->info('User  was successfully deleted from database.');
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
        $uploadedImage = Arr::get($data, 'photo');
        if ($uploadedImage) {
            $data['photo'] = $this->fileHelper->upload($uploadedImage, 'img\content');
        }
    }
}
