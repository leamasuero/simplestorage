<?php

namespace Lebenlabs\SimpleStorage\Models;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Lebenlabs\SimpleStorage\Interfaces\Storable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="lebenlabs_simplestorage_sotorage_items")
 * @ORM\HasLifecycleCallbacks
 */
class StorageItem
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250, unique=true, nullable=false)
     * @var string 
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @var string 
     */
    private $original_filename;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @var string 
     */
    private $entidad_id;

    /**
     * @var string 
     */
    private $archivo;

    /**
     * @var Storable
     */
    private $entidad;

    public function __construct(Storable $entidad, UploadedFile $archivo)
    {
        $this->archivo = $archivo;
        $this->entidad = $entidad;

        $this->created_at = new DateTime;
        $now = DateTime::createFromFormat('U.u', microtime(true));
        $this->filename = $now->format('u') . '-' . $archivo->getClientOriginalName();
        $this->original_filename = $archivo->getClientOriginalName();

        $this->entidad_id = $entidad->getStorageId();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getEntidad()
    {
        return $this->entidad;
    }

    public function setEntidad($entidad)
    {
        $this->entidad = $entidad;
        return $this;
    }

    public function __toString()
    {
        return $this->getOriginalFilename();
    }

    public function getOriginalFilename()
    {
        return $this->original_filename;
    }

    public function setOriginalFilename($originalFilename)
    {
        $this->original_filename = $originalFilename;
        return $this;
    }

    public function getArchivo()
    {
        return $this->archivo;
    }

    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;
        return $this;
    }

    public function getUrl()
    {
        return route('publico.archivos.show', $this->getId());
    }
}
