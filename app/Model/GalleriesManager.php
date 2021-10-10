<?php

declare(strict_types = 1);

namespace App\Model;

use http\Env\Request;
use http\Exception\InvalidArgumentException;
use Nette\Http\IRequest;
use Nette\SmartObject;
use Nette\Utils\DateTime;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Nette\Utils\Image;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use SplFileInfo;
use function App\dd;

class GalleriesManager
{
    use SmartObject;

    /** @var string AllGalleries */
    private $allGalleriesPath;
    /**
     * @var IRequest
     */
    private $request;

    public function __construct(string $allGalleriesPath, IRequest $request)
    {
        $this->allGalleriesPath = $allGalleriesPath;
        $this->request = $request;
    }

    private function findImagesInGallery(string $galleryPath): array
    {
        $retVal = [];

        $images = Finder::findFiles('*')
            ->in($galleryPath);

        foreach ($images as $imagePath => $imageInfo) {
            assert($imageInfo instanceof SplFileInfo);
            $retVal[] = [
                'path' => $imageInfo->getFilename(),
                'fullpath' => Strings::after($imagePath, $this->allGalleriesPath),
                'name' => $imageInfo->getBasename('.' . $imageInfo->getExtension()),
                'modified' => DateTime::from($imageInfo->getMTime()),		// prípadne pridat ->format()
            ];
        }

        return $retVal;
    }

    public function findAllGalleries(string $findThisGallery = "*"): array
    {
        $galleries = Finder::findDirectories($findThisGallery)
            ->in($this->allGalleriesPath);

        $retVal = [];

        foreach ($galleries as $galleryPath => $galleryInfo) {
            assert($galleryInfo instanceof SplFileInfo);

            if ($findThisGallery !== "*" && $galleryInfo->getBasename() !== $findThisGallery ){
                continue;
            }

            $retVal[] = [
                'Galeria' => [
                    'path' => Strings::after($galleryPath, $this->allGalleriesPath),
                    'name' => $galleryInfo->getBasename(),
                ],
                'Obrázky' => $this->findImagesInGallery($galleryPath),
            ];
        }

        return $retVal;
    }

    public function insertImage(string $galleryName)
    {
        $image = $this->request->getFile("file");
        if (!($image->isOk() && $image->hasFile() && $image->isImage())){
            throw new InvalidArgumentException("Zly format obrazka");
        }

        $path = $this->allGalleriesPath."/".$galleryName."/".$image->getSanitizedName();
        $image->move($path);

        return  [
            "path" => $image->getSanitizedName(),
            "fullpath" => Strings::after($path, $this->allGalleriesPath),
            "name" => Strings::before($image->getSanitizedName(),".". $image->getImageFileExtension(), -1),
            "modified" => date("Y-m-d H:i:s", filemtime($path))
        ];
    }

    public function delete(string $path)
    {
        $path = FileSystem::normalizePath($this->allGalleriesPath."/".$path);

        if (Strings::after($path,$this->allGalleriesPath."/") == null){
            return false;
        }

        if (file_exists($path)){
            FileSystem::delete($path);
            return true;
        }
        return false;
    }

}
