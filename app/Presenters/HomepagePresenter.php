<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use function App\dd;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{

    public static function actionDefault($path){
        $dir = dirname(__DIR__ ,2);
        $galleryFile = $dir.'/www/AllGalleries'; //Cesta k vsetkym galleriam
        $galleries = FileSystem::read($galleryFile);
        dd($galleries);
        if ($this->getHttpRequest()->isMethod('GET')){
            $galleries = FileSystem::read($galleryFile);


            $data = array( "Gallerie" => array() );

            for($i=0; $i <= count($galleries); $i++){
                $data[$i]["path"] = $galleries[$i]["path"];
                $data[$i]["name"] = $galleries[$i]["name"];
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
