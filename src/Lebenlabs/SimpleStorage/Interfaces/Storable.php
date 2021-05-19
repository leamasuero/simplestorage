<?php

namespace Lebenlabs\SimpleStorage\Interfaces;

interface Storable
{
    public function getStorageId();

    public function getIndexRoute();
}
