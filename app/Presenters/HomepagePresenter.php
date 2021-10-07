<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use function App\dd;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{

    public function actionDefault(){

        $dir = dirname(__DIR__ ,2);
        $galleryFile = $dir.'/www/AllGalleries'; //Cesta k vsetkym galleriam
        $galleries = glob($galleryFile."/*", GLOB_ONLYDIR);

        if ($this->getHttpRequest()->isMethod('GET')){

            $data = array( "Gallerie" => array(),"Obrázky" => array());

            for ($i= 0;$i< count($galleries);$i++ ) {
                $thisGalleryPath = basename($galleries[$i]);

                $data["Gallerie"][$i]["path"] = $thisGalleryPath;
                $data["Gallerie"][$i]["name"] = ucfirst($thisGalleryPath);

                $thisGallery = glob($galleryFile . "/" . $thisGalleryPath . "/*.{jpg,png,gif}", GLOB_BRACE);

            if ($thisGallery){
                for ($e=0; $e < count($thisGallery); $e++){

                    $thisPath = basename($thisGallery[$e]);
                    $name= (ucfirst(basename($thisGallery[$e],".jpg")));
                    $modified = date ("Y-m-d H:i:s.", filemtime($thisGallery[$e]));

                    $data["Obrázky"][$e]["path"] = $thisPath;
                    $data["Obrázky"][$e]["fullpath"] = $thisGalleryPath."/".$thisPath;
                    $data["Obrázky"][$e]["name"] = $name;
                    $data["Obrázky"][$e]["modified"] = $modified;
                }
            }
            }
            $this->sendJson($data);
        }

        elseif ($this->getHttpRequest()->isMethod('POST')){

            $json = $this->getHttpRequest()->getRawBody();
            $datas= Json::decode($json, Json::FORCE_ARRAY);

            $data = [
                'path' => $datas["path"],
                'name' => ucfirst($datas["name"])
            ];

            mkdir("../www/AllGalleries/".$data["name"], 0700);
            $this->sendJson($data);
        }
    }
}
