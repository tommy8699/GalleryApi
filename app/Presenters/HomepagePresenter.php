<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;
use Nette\Utils\Image;
use Nette\Utils\Strings;
use function App\dd;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{

    public static function actionDefault($path){
        $dir = dirname(__DIR__ ,2);
        $galleryFile = $dir.'/www/AllGalleries'; //Cesta k vsetkym galleriam
        if ($this->getHttpRequest()->isMethod('GET')){

            $galleries = glob($galleryFile."/*", GLOB_ONLYDIR);

            $images = glob($galleryFile."/".$path."/*.{jpg,png,gif}", GLOB_BRACE);
           dd($images);

            $data = array( "Gallerie" => array(),"Obrázky" => array());

            for($i=0; $i <= count($galleries); $i++){
                $data["Gallerie"][$i]["path"] = $galleries[$i]["path"];
                $data["Gallerie"][$i]["name"] = $galleries[$i]["name"];
                $imgsInGallery= $dir.'/www/AllGalleries/'.$galleries[$i]["name"];
                $images = Image::fromFile($imgsInGallery);

                if ($images){
                    for ($e=0; $e <= count($images); $e++){
                        $data["Obrázky"][$i]["path"] = $images[$i]["path"];
                        $data["Obrázky"][$i]["fullpath"] = $images[$i]["fullpath"];
                        $data["Obrázky"][$i]["name"] = $images[$i]["name"];
                        $data["Obrázky"][$i]["modified"] = $images[$i]["modified"];
                    }
                }
        }

            $this->sendJson($data);

        }

        elseif ($this->getHttpRequest()->isMethod('POST')){

            $data = [
                'path' => Strings::firstUpper($path),
                'name' => Strings::firstUpper(str_replace("-"," ",$path))
            ];

            FileSystem::write($galleryFile, $data["name"], 0666);
            $this->sendJson($data);
        }
    }
}
