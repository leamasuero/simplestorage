<?php

namespace Lebenlabs\SimpleStorage\Factories;

use Lebenlabs\SimpleStorage\Models\StorageItem;

class StorageItemFactory
{
    public static function create(array $row): StorageItem
    {
        $item = new StorageItem();

        return $item
            ->setId($row['id'])
            ->setEntidadId($row['entidad_id'])
            ->setFilename($row['filename'])
            ->setOriginalFilename($row['original_filename']);

    }

    public static function transform(array $rows): array
    {
        $items = [];
        foreach ($rows as $row) {
            $items[] = self::create($row);
        }

        return $items;
    }
}

