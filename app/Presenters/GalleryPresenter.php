<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Tracy\Debugger;
use function App\dd;
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
            $this->error("Zvolená galéria neexistuje", Nette\Http\IResponse::S404_NOT_FOUND);
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
            $this->error("Chybne zadaný request - nevhodný obsah podľa schémy.", Nette\Http\IResponse::S400_BAD_REQUEST);
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
                "message" => "Galéria/obrázok bola úspešne vymazaná"
            ]);
        }
        else{
            $this->error("Zvolená galéria/obrázok neexistuje" , Nette\Http\IResponse::S404_NOT_FOUND );
        }
    }
}
