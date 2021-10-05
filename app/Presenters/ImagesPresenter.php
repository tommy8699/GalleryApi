<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\Image;
use function App\dd;


final class ImagesPresenter extends Nette\Application\UI\Presenter
{

    public function actionDefault($width, $height , $fullPath){

        $dir = dirname(__DIR__ ,2);
        $pathAllGallery = $dir.'/www/AllGalleries';
        $thisGallery = $pathAllGallery."/".$fullPath;
        $image = glob($pathAllGallery."/*/".$fullPath.".{jpg,png,gif}", GLOB_BRACE);

        if (count($image) !== 0){
            $thisImage = Image::fromFile($image[0]);

        $thisImage->resize($width, $height);

        if ($this->getHttpRequest()->isMethod('GET')){
                $data = [
                    'fullPath' => $thisGallery,
                    'width' => $thisImage->getWidth(),
                    'height' => $thisImage->getHeight()
                ];
                $this->sendJson($data);
        }
        }
        else{
            echo "Obrazok neexistuje";
        }
    }

}
