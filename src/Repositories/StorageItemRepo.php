<?php

namespace Lebenlabs\SimpleStorage\Repositories;

use Doctrine\DBAL\Connection;
use Lebenlabs\SimpleStorage\Transformers\StorageItemTransformer;
use Lebenlabs\SimpleStorage\Models\StorageItem;

class StorageItemRepo
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var StorageItemTransformer
     */
    private $storageItemTransformer;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->storageItemTransformer = new StorageItemTransformer();
    }

    public function find(int $id): StorageItem
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(StorageItem::$tabla)
            ->where('id = :id')
            ->setParameter(':id', $id)->setMaxResults(1);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return null;
        }

        return $this->storageItemTransformer->transform($st->fetch());
    }

    public function findByEntidadId(string $entidadId): ?array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(StorageItem::$tabla)
            ->where('entidad_id = :entidad_id')
            ->setParameter(':entidad_id', $entidadId);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return [];
        }

        return $this->storageItemTransformer->transformCollection($st->fetchAll());
    }

    public function insert(StorageItem $item): int
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert(StorageItem::$tabla)
            ->values([
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
                'atributos_exclusivo' => (int)!!$item->getAtributo('exclusivo')
            ]);

        return $qb->execute();
    }

    public function delete(StorageItem $item)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->delete(StorageItem::$tabla)
            ->where('id = :id')
            ->setParameters([
                'id' => $item->getId(),
            ]);

        return $qb->execute();
    }

}