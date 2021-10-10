<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use function App\dd;
use App\Model\GalleriesManager;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{

    /** @var GalleriesManager */
    private $galleriesManager;

    public function injectGalleriesManager(GalleriesManager $galleriesManager)
    {
        $this->galleriesManager = $galleriesManager;
    }

    public function actionDefault(){

        if ($this->getHttpRequest()->isMethod('GET')){

            $data = $this->galleriesManager->findAllGalleries();
            $this->sendJson($data);
        }

        $this->error("Nepodporovaná metoda, pouzite GET", Nette\Http\IResponse::S405_METHOD_NOT_ALLOWED);

    }
}
