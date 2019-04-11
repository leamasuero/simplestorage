<?php

namespace Lebenlabs\SimpleStorage\Exceptions;

use Exception;

class NotFoundException extends Exception
{

    public function __construct()
    {
        parent::__construct(trans('simplestorage.archivos.not_found'));
    }
}
