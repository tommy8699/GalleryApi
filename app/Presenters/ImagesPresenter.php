<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\Image;



final class ImagesPresenter extends Nette\Application\UI\Presenter
{

    public function actionDefault($width, $height ,$fullPath){

        $dir = dirname(__DIR__ ,2);
        $galleryFile = $dir.'/www/AllGalleries'.$fullPath; //cela cesta ku konkretnemu obrazku
        $pathAllGallery = '/AllGalleries';
        $thisGallery = $pathAllGallery."/".$fullPath;
        $image = Image::fromFile($galleryFile);  //Skontrolovat ci je potrebne zadat celu cestu alebo len cestu ku allGalleries/thisGallery/thisImg
        $image->resize($width, $height);

        if ($this->getHttpRequest()->isMethod('GET')){
                $data = [
                    'fullPath' => $thisGallery,
                    'width' => $image->getWidth(),
                    'height' => $image->getHeight()
                ];
                $this->sendJson($data);
        }
        }
}
