<?php

namespace Lebenlabs\SimpleStorage\Models;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Lebenlabs\SimpleStorage\Interfaces\Storable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Lebenlabs\SimpleStorage\Embeddables\AtributosStorageItem;

/**
 * @ORM\Entity
 * @ORM\Table(name="lebenlabs_simplestorage_storage_items")
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

    /**
     * @ORM\Embedded(class="Lebenlabs\SimpleStorage\Embeddables\AtributosStorageItem")
     * @var AtributosStorageItem
     */
    private $atributos;


    public function __construct(Storable $entidad, UploadedFile $archivo, AtributosStorageItem $atributos = null)
    {
        $this->archivo = $archivo;
        $this->entidad = $entidad;

        $this->created_at = new DateTime;
        $now = DateTime::createFromFormat('U.u', microtime(true));
        $this->filename = $now->format('u') . '-' . $archivo->getClientOriginalName();
        $this->original_filename = $archivo->getClientOriginalName();

        $this->entidad_id = $entidad->getStorageId();

        $this->atributos = $atributos;
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

    /**
     * Get type extension of file
     *
     * @return string
     *
     */
    public function getExtensionType()
    {
        $extension = pathinfo($this->original_filename, PATHINFO_EXTENSION);
        $extensionType = 'file';
        switch ($extension) {
            case 'xls':
            case 'xlsx':
                $extensionType = 'file-excel';
                break;

            case 'doc':
            case 'docx':
            case 'odt':
                $extensionType = 'file-word';
                break;

            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'bmp':
            case 'gif':
                $extensionType = 'file-image';
                break;

            case 'pdf':
                $extensionType = 'file-pdf';
                break;

            case 'mp4':
            case 'avi':
            case 'ogg':
            case 'webm':
            case 'flv':
            case 'wmb':
            case 'wmv':
                $extensionType = 'file-video';
                break;

            case 'mp3':
            case 'wav':
                $extensionType = 'file-audio';
                break;

        }

        return $extensionType;
    }

    /**
     * @return AtributosStorageItem
     */
    public function getAtributos()
    {
        return $this->atributos;
    }

    /**
     * @param array $atributos
     * @return $this
     */
    public function setAtributosFromArray(array $atributos = [])
    {
        $this->atributos->setAtributosFromArray($atributos);
        return $this;
    }
}
