<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;


final class ImagesPresenter extends Nette\Application\UI\Presenter
{


    public function actionGetImages($width, $height ,$fullPath): void
    {
        $data = [
            'w' => $width,
            'h' => $height,
            'fullpath' => $fullPath
        ];
        $this->sendJson($data);
    }
}
