<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;
use function App\dd;
use Nette\Utils\Image;
use Nette\Utils\Json;


final class GalleryPresenter extends Nette\Application\UI\Presenter
{

    public function actionDefault($path): void
    {
        $dir = dirname(__DIR__ ,2);
        $galleryFile = $dir.'/www/AllGalleries';


        if($this->getHttpRequest()->isMethod('GET')){
            $thisGallery = glob($galleryFile."/".$path, GLOB_ONLYDIR);
            $allImg = glob($galleryFile."/".$path."/*.{jpg,png,gif}", GLOB_BRACE);

            if (count($thisGallery)> 0){
            $data = array( "Gallerie" => array(),"Obrázky" => array() );

                $thisGalleryPath = basename($thisGallery[0]);

                $data["Gallerie"]["path"] = $thisGalleryPath;
                $data["Gallerie"]["name"] = ucfirst(str_replace("-"," ",$thisGalleryPath));


            for($i=0; $i < count($allImg); $i++){
                $thisPath = basename($allImg[$i]);
                $name= (ucfirst(basename($allImg[$i],".jpg")));
                $modified = date ("Y-m-d H:i:s.", filemtime($allImg[$i]));

                $data["Obrázky"][$i]["path"] = $thisPath;
                $data["Obrázky"][$i]["fullpath"] = $path."/".$thisPath;
                $data["Obrázky"][$i]["name"] = $name;
                $data["Obrázky"][$i]["modified"] = $modified;
            }
            $this->sendJson($data);
        }
            else{
                $this->template->gallery = $path;
            }
        }

        elseif ($this->getHttpRequest()->isMethod('POST')){
            $pathAllGallery = '/AllGalleries/'.$path;


            $json = $this->getHttpRequest()->getRawBody();
            $datas= Json::decode($json, Json::FORCE_ARRAY);

            $data = [
                "path" => $datas["path"],
                "fullpath" => $datas["fullpath"],
                "name" => $datas["name"],
                "modified" => date("Y-m-d H:i:s")
            ];

            $type = Image::JPEG;
            $image = Image::fromString($datas["path"],$type);
            $image->save("../www/AllGalleries/".$path);

            $this->sendJson($data);
        }

        elseif ($this->getHttpRequest()->isMethod('DELETE') ){
                $this->template->deleteGallery = $path;
                FileSystem::delete("../www/AllGalleries/".$path);
           }
    }
}
