<?php

namespace Lebenlabs\SimpleStorage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Lebenlabs\SimpleStorage\Services\SimpleStorageService;

class ArchivosController extends Controller
{

    /**
     * @var SimpleStorageService
     */
    private $storage;

    public function __construct(SimpleStorageService $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $id
     * @return Response
     * @throws \SimpleStorage\Exceptions\NotFoundException
     */
    public function show($id)
    {
        $storageItem = $this->storage->find($id);

        return response()->make(
            $storageItem->getArchivo(),
            Response::HTTP_OK,
            [
                'Content-Type' => $this->storage->mimeType($storageItem),
                'Content-Disposition' => "attachment;filename='{$storageItem->getOriginalFilename()}'"
            ]
        );
    }
}
