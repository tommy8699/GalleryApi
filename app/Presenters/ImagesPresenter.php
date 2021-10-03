<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;
use Nette\Utils\Image;



final class ImagesPresenter extends Nette\Application\UI\Presenter
{


    public function renderDefault($width, $height ,$fullPath){

        $dir = dirname(__DIR__ ,2);
        $pathAllGallery = '/AllGalleries';
        $galleryFile = $dir.'/www/AllGalleries'; // tu bude cesta k vsetkym galleriam
        $findGallery = $galleryFile."/".$fullPath;
        $thisGallery= FileSystem::read($findGallery);
        $image = Image::fromFile($thisGallery);

        $image->resize($width, $height);

        if ($httpRequest->isMethod('GET')){

                $data = [
                    'fullPath' => $pathAllGallery."/".$fullPath,
                    'width' => $width,
                    'height' => $height
                ];
                $this->sendJson($data);
        }

        }
}
