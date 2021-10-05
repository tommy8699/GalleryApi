<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use function App\dd;
use Nette\Utils\Image;



final class GalleryPresenter extends Nette\Application\UI\Presenter
{

    public function actionDefault($path): void
    {
        $dir = dirname(__DIR__ ,2);
        $galleryFile = $dir.'/www/AllGalleries'; //Cesta k vsetkym galleriam

        if($this->getHttpRequest()->isMethod('GET')){
            $gallery =FileSystem::read($galleryFile."/".$path);

            $data = array( "Obrázky" => array() );

            for($i=0; $i <= count($gallery); $i++){
                $data[$i]["path"] = $gallery[$i]["path"];
                $data[$i]["fullpath"] = $gallery[$i]["fullpath"];
                $data[$i]["name"] = $gallery[$i]["name"];
                $data[$i]["modified"] = $gallery[$i]["modified"];
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
