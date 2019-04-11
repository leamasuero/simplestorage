<?php

namespace Lebenlabs\SimpleStorage\Services;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\File;
use SimpleCms\Repositories\StorageItemRepository;
use SimpleStorage\Exceptions\NotFoundException;
use SimpleStorage\Interfaces\Storable;
use SimpleStorage\Models\StorageItem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SimpleStorageService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FilesystemAdapter
     */
    private $storage;

    /**
     * @var StorageItemRepository
     */
    private $storageItemRepository;

    public function __construct(EntityManagerInterface $em, FilesystemAdapter $storage)
    {
        $this->em = $em;
        $this->storage = $storage;
        $this->storageItemRepository = $this->em->getRepository(StorageItem::class);
    }

    /**
     * 
     * @param Storable $entidad
     * @param UploadedFile $archivo
     */
    public function put(Storable $entidad, UploadedFile $archivo)
    {
        $storageItem = new StorageItem($entidad, $archivo);

        $this->storage->put($storageItem->getFilename(), File::get($archivo));

        $this->em->persist($storageItem);
        $this->em->flush();
    }

    /**
     * 
     * @param Storable $entidad
     * @return array
     */
    public function get(Storable $entidad)
    {
        return $this->storageItemRepository->findBy(['entidad_id' => $entidad->getStorageId()]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function remove($id)
    {
        $item = $this->find($id);
        $this->em->remove($item);
        $this->em->flush();

        return $this->storage->delete($item->getFilename());
    }

    /**
     * @param int $id
     * @return StorageItem
     * @throws NotFoundException
     */
    public function find($id)
    {
        $storageItem = $this->storageItemRepository->find($id);
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

    private function getFile(StorageItem $item)
    {
        return $this->storage->get($item->getFilename());
    }
}
