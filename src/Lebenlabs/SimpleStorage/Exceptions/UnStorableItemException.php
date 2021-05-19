<?php

namespace Lebenlabs\SimpleStorage\Exceptions;

use Exception;

class UnStorableItemException extends Exception
{

    public function __construct()
    {
        parent::__construct(trans('simplestorage.archivos.unstorable_item_exception'));
    }
}
