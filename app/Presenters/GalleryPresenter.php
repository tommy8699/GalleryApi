<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Nette\Http\IRequest;
use function App\dd;
use Nette\Utils\Image;



final class GalleryPresenter extends Nette\Application\UI\Presenter
{


    public function renderDefault( $path = false): void
    {
        $dir = dirname(__DIR__ ,2);
        $galleryFile = $dir.'/www/AllGalleries'; // tu bude cesta k vsetkym galleriam
        $pathAllGallery = '/AllGalleries';

        if ($httpRequest->isMethod('GET')){
            $galleries = FileSystem::read($galleryFile);
           // dd($galleries);
            foreach ($galleries as $gallery){
            $data = [
                'path' => $gallery["path"],
                'name' => $gallery["name"]
            ];
            }
            $this->sendJson($data);

        }

        elseif ($httpRequest->isMethod('POST')){

            $data = [
                'path' => $path,
                'name' => Strings::firstUpper($path)
            ];

            FileSystem::write($galleryFile, $data["path"], 0666);
            $this->sendJson($data);
        }


        if ($path){

        if($httpRequest->isMethod('GET')){
            $gallery =FileSystem::read($galleryFile."/".$path);
            //dd($gallery);
            $data = [
                "path" => $gallery["path"],
                "fullpath" => $gallery["fullpath"],
                "name" => $gallery["name"],
                "modified" => $gallery["createdAt"]
            ];
            $this->sendJson($data);
        }

        elseif ($httpRequest->isMethod('POST')){

            if (file_exists($path)){
                echo "Subor uz existuje";
            }

            $data = [
                "path" => $path.".jpg",
                "fullpath" => $pathAllGallery."/".$path.".jpg",
                "name" => Strings::firstUpper($path),
                "modified" => date("Y-m-d H:i:s")
            ];

            FileSystem::write($path, $data["name"],0666);
            $image = Image::fromFile($data["fullpath"]);
            $image->save($data["path"]);
            $this->sendJson($data);
        }

        elseif ($httpRequest->isMethod('DELETE') ){

            if (file_exists($path)){
                FileSystem::delete($path);
            }
            else{
                echo "Subor neexistuje";
            }
           }
    }
    }
}
