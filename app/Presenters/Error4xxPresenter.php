<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;


final class Error4xxPresenter extends Nette\Application\UI\Presenter
{
    public function startup(): void
    {
        parent::startup();
        if (!$this->getRequest()->isMethod(Nette\Application\Request::FORWARD)) {
            $this->error();
        }
    }

    public function actionDefault(Nette\Application\BadRequestException $exception): void
    {
        $this->getHttpResponse()->setCode($exception->getCode());
        $this->sendJson([
            'status' => 'error',
            'data' => [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(), // Nezabudnut msg
            ]
        ]);
    }
}
