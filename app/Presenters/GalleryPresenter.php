<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use function App\dd;
use Nette\Utils\Image;
use Nette\Utils\Finder;




final class GalleryPresenter extends Nette\Application\UI\Presenter
{

    public function actionDefault($path): void
    {
        $dir = dirname(__DIR__ ,2);
        $galleryFile = $dir.'/www/AllGalleries'; //Cesta k vsetkym galleriam


        if($this->getHttpRequest()->isMethod('GET')){

            $allDirectory = glob($galleryFile."/".$path."/*", GLOB_ONLYDIR);

            $thisGallery = glob($galleryFile."/".$path."/*.{jpg,png,gif}", GLOB_BRACE);

            $data = array( "Gallerie" => array(),"Obrázky" => array() );

                $data["Gallerie"]["path"] = $thisGallery["path"];  //path galerie
                $data["Gallerie"]["name"] = $thisGallery["name"];  // name galerie



            for($i=0; $i <= count($thisGallery); $i++){

                $data["Obrázky"][$i]["path"] = $thisGallery[$i]["path"];
                $data["Obrázky"][$i]["fullpath"] = $thisGallery[$i]["fullpath"];
                $data["Obrázky"][$i]["name"] = $thisGallery[$i]["name"];
                $data["Obrázky"][$i]["modified"] = $thisGallery[$i]["modified"];
            }
            $this->sendJson($data);
        }

        elseif ($this->getHttpRequest()->isMethod('POST')){
            $pathAllGallery = '/AllGalleries/'.$path; //cesta k obrazku

            if (file_exists($path)){
                echo "Subor uz existuje";
            }

            $data = [
                "path" => $path.".jpg",
                "fullpath" => $pathAllGallery.".jpg",
                "name" => Strings::firstUpper($path),
                "modified" => date("Y-m-d H:i:s")
            ];

            $image = Image::fromFile($data["fullpath"]);
            $image->save($data["path"]);
            $this->sendJson($data);
        }

        elseif ($this->getHttpRequest()->isMethod('DELETE') ){

            if (file_exists($path)){
                FileSystem::delete($path);
            }
            else{
                echo "Subor neexistuje";
            }
           }
    }
}
