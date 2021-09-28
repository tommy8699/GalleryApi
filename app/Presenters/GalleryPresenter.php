<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;


final class GalleryPresenter extends Nette\Application\UI\Presenter
{

    public function actionGetGallery(): void
    {
        $data = [
            'path' => 'nette',
            'name' => 'netteGallery'
        ];
        $this->sendJson($data);
    }

    public function actionPostGallery(): void
    {
        $data = [
            'path' => 'nette',
            'name' => 'netteGallery'
            ];
        $this->sendJson($data);
    }

    public function renderDefault($path): void
    {

        FileSystem::createDir($path, 0777);

    }


    public function actionGetGalleryWithPath($path): void
    {
        $data = [
            "path" => $path,
            "fullpath" => "Animals/lion.jpg",
            "name" => "Lion",
            "modified" => "2017-04-19T08:11:32.0+0200"
        ];
        $this->sendJson($data);
    }

    public function actionDeleteGalleryWithPath(): void
    {
        $data = [
            "path" => "lion.jpg",
            "fullpath" => "Animals/lion.jpg",
            "name" => "Lion",
            "modified" => "2017-04-19T08:11:32.0+0200"
        ];
        $this->sendJson($data);
    }

    public function actionPostGalleryWithPath(): void
    {
        $data = [
            "path" => "lion.jpg",
            "fullpath" => "Animals/lion.jpg",
            "name" => "Lion",
            "modified" => "2017-04-19T08:11:32.0+0200"
        ];
        $this->sendJson($data);
    }

}
