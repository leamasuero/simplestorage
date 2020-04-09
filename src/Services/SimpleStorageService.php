<?php

namespace Lebenlabs\SimpleStorage\Services;

use Doctrine\DBAL\Connection;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Lebenlabs\SimpleStorage\Repositories\StorageItemRepo;
use SimpleCms\Repositories\StorageItemRepository;
use Lebenlabs\SimpleStorage\Exceptions\NotFoundException;
use Lebenlabs\SimpleStorage\Interfaces\Storable;
use Lebenlabs\SimpleStorage\Models\StorageItem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SimpleStorageService
{
    /**
     * @var FilesystemAdapter
     */
    private $storage;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection, FilesystemAdapter $storage)
    {
        $this->connection = $connection;
        $this->storage = $storage;
        $this->storageItemRepo = new StorageItemRepo($connection);
    }

    /**
     *
     * @param Storable $entidad
     * @param UploadedFile $archivo
     */
    public function put(Storable $entidad, UploadedFile $archivo, array $atributos = [])
    {
        $storageItem = new StorageItem($entidad, $archivo, $atributos);

        $this->storage->put($storageItem->getFilename(), File::get($archivo));
        $this->storageItemRepo->insert($storageItem);
    }

    /**
     * @param Storable $entidad
     */
    public function get(Storable $entidad): array
    {
        $items = $this->storageItemRepo->findByEntidadId($entidad->getStorageId());
        return $items;
    }

    /**
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function remove($id)
    {
        $item = $this->find($id);
        $this->storageItemRepo->delete($item);
        return $this->storage->delete($item->getFilename());
    }

    /**
     * @param int $id
     * @return StorageItem
     * @throws NotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function find(int $id): StorageItem
    {
        $storageItem = $this->storageItemRepo->find($id);

        if (!$storageItem) {
            throw new NotFoundException();
        }

        $archivo = $this->getFile($storageItem);
        return $storageItem->setArchivo($archivo);
    }

    /**
     * @param StorageItem $item
     * @return string
     */
    public function mimeType(StorageItem $item)
    {
        return $this->storage->mimeType($item->getFilename());
    }

    /**
     * @param StorageItem $item
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getFile(StorageItem $item)
    {
        return $this->storage->get($item->getFilename());
    }
}
