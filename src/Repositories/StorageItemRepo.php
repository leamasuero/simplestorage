<?php

namespace Lebenlabs\SimpleStorage\Repositories;

use Doctrine\DBAL\Connection;
use Lebenlabs\SimpleStorage\Factories\StorageItemFactory;
use Lebenlabs\SimpleStorage\Models\StorageItem;

class StorageItemRepo
{

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function find(int $id): StorageItem
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('lebenlabs_simplestorage_storage_items')
            ->where('id = :id')
            ->setParameter(':id', $id)->setMaxResults(1);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return null;
        }

        return StorageItemFactory::create($st->fetch());
    }

    public function findByEntidadId(string $entidadId): ?array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('lebenlabs_simplestorage_storage_items')
            ->where('entidad_id = :entidad_id')
            ->setParameter(':entidad_id', $entidadId);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return [];
        }

        return StorageItemFactory::transform($st->fetchAll());
    }

    public function insert(StorageItem $item)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert('lebenlabs_simplestorage_storage_items')
            ->values(
                [
                    'filename' => ':filename',
                    'original_filename' => ':original_filename',
                    'entidad_id' => ':entidad_id',
                    'atributos_exclusivo' => ':atributos_exclusivo',
                ]
            )
            ->setParameters([
                'filename' => $item->getFilename(),
                'original_filename' => $item->getOriginalFilename(),
                'entidad_id' => $item->getEntidadId(),
                'atributos_exclusivo' => (int) !!$item->getAtributo('exclusivo')
            ]);

        return $qb->execute();
    }

    public function delete(StorageItem $item)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->delete('lebenlabs_simplestorage_storage_items')
            ->where('id = :id')
            ->setParameters([
                'id' => $item->getId(),
            ]);

        return $qb->execute();
    }

}