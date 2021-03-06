<?php

namespace Lebenlabs\SimpleStorage\Services;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Lebenlabs\SimpleStorage\Embeddables\AtributosStorageItem;
use SimpleCms\Repositories\StorageItemRepository;
use Lebenlabs\SimpleStorage\Exceptions\NotFoundException;
use Lebenlabs\SimpleStorage\Interfaces\Storable;
use Lebenlabs\SimpleStorage\Models\StorageItem;
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
    public function put(Storable $entidad, UploadedFile $archivo, array $atributos = [])
    {
        $atributosStorageItem = new AtributosStorageItem(Arr::get($atributos, 'exclusivo', false));
        $storageItem = new StorageItem($entidad, $archivo, $atributosStorageItem);

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
     * @throws NotFoundException
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
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function findAll()
    {
        $storageItems = $this->storageItemRepository->findAll();

        foreach ($storageItems as $storageItem) {
            $archivo = $this->getFile($storageItem);
            $storageItem->setArchivo($archivo);
        }

        return $storageItems;
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

    /**
     * @param $id
     * @param array $atributos
     * @return mixed
     * @throws NotFoundException
     */
    public function setAtributos($id, array $atributos = [])
    {
        $storageItem = $this->storageItemRepository->find($id);
        if (!$storageItem) {
            throw new NotFoundException();
        }

        //Set de posibles atributos
        $storageItem->setAtributosFromArray($atributos);

        $this->em->persist($storageItem);
        $this->em->flush();

        return $storageItem;
    }
}
