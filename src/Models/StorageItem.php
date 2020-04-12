<?php

namespace Lebenlabs\SimpleStorage\Models;

use DateTime;
use Illuminate\Support\Arr;
use Lebenlabs\SimpleStorage\Interfaces\Storable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Lebenlabs\SimpleStorage\Embeddables\AtributosStorageItem;

class StorageItem
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $originalFilename;

    /**
     * @var string
     */
    private $entidadId;

    /**
     * @var string
     */
    private $archivo;

    /**
     * @var Storable
     */
    private $entidad;

    /**
     * @var array
     */
    private $atributos;


    public function __construct(Storable $entidad = null, UploadedFile $archivo = null, array $atributos = [])
    {
        $this->entidad = $entidad;
        $this->archivo = $archivo;
        $this->setAtributos($atributos);

        if ($archivo) {
            $this->originalFilename = $archivo->getClientOriginalName();
            $this->filename = (new DateTime())->format('U') . '-' . $archivo->getClientOriginalName();
        }

        if ($entidad) {
            $this->entidadId = $entidad->getStorageId();
        }

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): StorageItem
    {
        $this->id = $id;
        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return StorageItem
     */
    public function setFilename(string $filename): StorageItem
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @param string $entidadId
     * @return StorageItem
     */
    public function setEntidadId(string $entidadId): StorageItem
    {
        $this->entidadId = $entidadId;
        return $this;
    }

    public function setAtributos(array $atributos = []): StorageItem
    {
        $this->atributos = json_encode($atributos);
        return $this;
    }


    public function getEntidad()
    {
        return $this->entidad;
    }

    public function getEntidadId(): ?string
    {
        return $this->entidadId;
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

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(string $originalFilename): StorageItem
    {
        $this->originalFilename = $originalFilename;
        return $this;
    }

    public function getArchivo(): ?string
    {
        return $this->archivo;
    }

    public function setArchivo(string $archivo): StorageItem
    {
        $this->archivo = $archivo;
        return $this;
    }

    /**
     * Get type extension of file
     *
     * @return string
     *
     */
    public function getExtensionType(): string
    {
        $extension = pathinfo($this->originalFilename, PATHINFO_EXTENSION);
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
     * @return array
     */
    public function getAtributos(): array
    {
        return json_decode($this->atributos, true);
    }

    public function getAtributo($key): ?string
    {
        return Arr::get($this->getAtributos(), $key);
    }

}
