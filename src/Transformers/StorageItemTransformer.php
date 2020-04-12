<?php

namespace Lebenlabs\SimpleStorage\Transformers;

use Lebenlabs\SimpleStorage\Interfaces\Transformer;
use Lebenlabs\SimpleStorage\Models\StorageItem;

class StorageItemTransformer implements Transformer
{
    public function transform(array $row): StorageItem
    {
        $item = new StorageItem();

        return $item
            ->setId($row['id'])
            ->setEntidadId($row['entidad_id'])
            ->setFilename($row['filename'])
            ->setOriginalFilename($row['original_filename']);

    }

    public function transformCollection(iterable $rows): iterable
    {
        $items = [];
        foreach ($rows as $row) {
            $items[] = self::create($row);
        }

        return $items;
    }
}

