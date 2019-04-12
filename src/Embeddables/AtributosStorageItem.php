<?php

namespace Lebenlabs\SimpleStorage\Embeddables;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Embeddable */
class AtributosStorageItem
{
    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $exclusivo;

    public function __construct($exclusivo = false)
    {
        $this->exclusivo = $exclusivo;
    }

    /**
     * @return bool
     */
    public function getExclusivo()
    {
        return $this->exclusivo;
    }

    /**
     * @param bool $exclusivo
     * @return AtributosStorageItem
     */
    public function setExclusivo($exclusivo = false)
    {
        $this->exclusivo = $exclusivo;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->exclusivo ? 'exclusivo' : '';
    }
}