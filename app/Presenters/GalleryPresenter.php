<?php

declare(strict_types=1);

namespace App\Presenters;

use mysql_xdevapi\Exception;
use Nette;
use Nette\Utils\FileSystem;
use Tracy\Debugger;
use function App\dd;
use Nette\Utils\Image;
use Nette\Http\FileUpload;
use Nette\Utils\Json;
use App\Model\GalleriesManager;


final class GalleryPresenter extends Nette\Application\UI\Presenter
{
    /** @var GalleriesManager */
    private $galleriesManager;

    public function injectGalleriesManager(GalleriesManager $galleriesManager)
    {
        $this->galleriesManager = $galleriesManager;
    }

    public function actionGet(string $path)
    {
        $datas =  $this->galleriesManager->findAllGalleries($path);
        if (empty($datas)  ){
            //$this->template->gallery = $path;
            $this->error('Galleria '. $path.' zial neexistuje');
        }
        else{
            $this->sendJson($datas);
        }
    }

    public function actionInsert(string $path)
    {
        try {
            $data = $this->galleriesManager->insertImage($path);
        }
        catch (\Exception $exception){
            Debugger::log($exception);
            $this->error("Zly parameter obrazka", Nette\Http\IResponse::S422_UNPROCESSABLE_ENTITY);
        }
        $this->sendJson($data);
    }

    public function actionDelete(string $path)
    {
        $this->template->deleteGallery = $path;
        if ($this->galleriesManager->delete($path)){
            $this->sendJson([
                "path" => $path,
                "fullPath" => $path,
                "message" => "Zmazanie prebehlo uspešne"
            ]);
        }
        else{
            $this->error("Galeria neexistuje" , Nette\Http\IResponse::S404_NOT_FOUND );
        }
    }
}
