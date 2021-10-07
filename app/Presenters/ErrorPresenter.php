<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\Responses;
use Nette\Http;
use Tracy\ILogger;


final class ErrorPresenter implements Nette\Application\IPresenter
{
	use Nette\SmartObject;

	/** @var ILogger */
	private $logger;


	public function __construct(ILogger $logger)
	{
		$this->logger = $logger;
	}

    public function run(Nette\Application\Request $request): Nette\Application\Response
    {
        $exception = $request->getParameter('exception');

        if ($exception instanceof Nette\Application\BadRequestException) {
            [$module, , $sep] = Nette\Application\Helpers::splitName($request->getPresenterName());
            return new Responses\ForwardResponse($request->setPresenterName($module . $sep . 'Error4xx'));
        }

        $this->logger->log($exception, ILogger::EXCEPTION);

        return new Responses\JsonResponse([
            'status' => 'error',
            'data' => [
                'code' => Http\IResponse::S500_INTERNAL_SERVER_ERROR,
                'message' => 'Nedefinovaná chyba.',
            ]
        ]);
    }
}
