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
        $image = glob($pathAllGallery."/*/".$fullPath.".{jpg,png,gif}", GLOB_BRACE);

        if (count($image) !== 0){

        $pathInfoDir = pathinfo($image[0], PATHINFO_DIRNAME);
        $folderName = (basename($pathInfoDir));
        $finfImg = basename($image[0]);

        $thisImage = Image::fromFile('../www/AllGalleries/'.$folderName."/".$finfImg);

        if($width !==  0 && $height !== 0){
            $thisImage->resize($width, $height);
        }
        elseif ($width == 0 ){
            $thisImage->resize(null, $height);
        }
        elseif($height == 0){
            $thisImage->resize($width, null);
        }

        if ($this->getHttpRequest()->isMethod('GET')){
                $data = [
                    'fullPath' => $folderName."/".$fullPath,
                    'width' => $thisImage->getWidth(),
                    'height' => $thisImage->getHeight()
                ];
                $this->sendJson($data);
        }
        }
        else {
            $this->error("Obrázok sa nenašiel", Nette\Http\IResponse::S404_NOT_FOUND);
        }
    }
}
