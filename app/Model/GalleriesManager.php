<?php

declare(strict_types = 1);

namespace App\Model;

use http\Exception\InvalidArgumentException;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\SmartObject;
use Nette\Utils\DateTime;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
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
            $time = DateTime::from($imageInfo->getMTime());
            $retVal[] = [
                'path' => $imageInfo->getFilename(),
                'fullpath' => Strings::after($imagePath, $this->allGalleriesPath."/"),
                'name' => $imageInfo->getBasename('.' . $imageInfo->getExtension()),
                'modified' =>$time->format("d.m.Y H:i:s"),		// prípadne pridat ->format()
            ];
        }

        return $retVal;
    }

    private function findImagesForHomepage(string $galleryPath): array
    {
        $retVal = [];

        $images = Finder::findFiles('*')
            ->in($galleryPath);

        foreach ($images as $imagePath => $imageInfo) {
            assert($imageInfo instanceof SplFileInfo);
            $retVal[] = [
                'path' => Strings::before($imageInfo->getFilename(),"."),
                'name' => $imageInfo->getBasename('.' . $imageInfo->getExtension()),
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

                $retVal[] = [
                    'Galeria' => [
                        'path' => Strings::after($galleryPath, $this->allGalleriesPath."/"),
                        'name' => $galleryInfo->getBasename(),
                    ], ];
                if ($findThisGallery != "*"){
                    $retVal[] = [
                    'Obrázky' => $this->findImagesInGallery($galleryPath),
                        ];
            }
                else{
                    $retVal[] = [
                    'Obrázky' => $this->findImagesForHomepage($galleryPath),
                        ];
            }

        }

        return $retVal;
    }

    public function insertImage(string $galleryName)
    {
        $image = $this->request->getFile("file");
        if (!($image->isOk() && $image->hasFile() && $image->isImage())){
            throw new InvalidArgumentException("Zly format obrazka");
        }
        if (!(file_exists($galleryName))){
            $this->error("Galéria pre upload sa nenašla",IResponse::S404_NOT_FOUND);
        }

        $path = $this->allGalleriesPath."/".$galleryName."/".$image->getSanitizedName();
        $image->move($path);

        return  [
            "path" => $image->getSanitizedName(),
            "fullpath" => Strings::after($path, $this->allGalleriesPath),
            "name" => Strings::before($image->getSanitizedName(), $image->getImageFileExtension(), -1),
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
