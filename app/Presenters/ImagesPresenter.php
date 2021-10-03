<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;
use Nette\Utils\Image;



final class ImagesPresenter extends Nette\Application\UI\Presenter
{

    public function actionDefault($width, $height ,$fullPath){

        $dir = dirname(__DIR__ ,2);
        $pathAllGallery = '/AllGalleries';
        $galleryFile = $dir.'/www/AllGalleries';
        $findGallery = $galleryFile."/".$fullPath;
        $thisGallery= FileSystem::read($findGallery);
        $image = Image::fromFile($galleryFile);
        $image->resize($width, $height);

        if ($this->getHttpRequest()->isMethod('GET')){

                $data = [
                    'fullPath' => $pathAllGallery."/".$fullPath,
                    'width' => $image->getWidth(),
                    'height' => $image->getHeight()
                ];
                $this->sendJson($data);
        }
        }
}
