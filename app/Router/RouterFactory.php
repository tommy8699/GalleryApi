<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Routing\Route;

final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(Nette\Http\IRequest $request): RouteList
	{
		$router = new RouteList;

        $router->addRoute('<presenter>/', 'Homepage:default');
        $router->addRoute('gallery/<path>[/<img>]', [
            'presenter' => 'Gallery',
            'action' => null,
            'path' => null,
            'img' => null,
            null => [
                Route::FILTER_IN => function (array $params) use ($request): ?array {
                    switch ($request->getMethod()) {
                        case Nette\Http\IRequest::GET:
                            $params['action'] = 'get';
                            break;
                        case Nette\Http\IRequest::POST:
                            $params['action'] = 'insert';
                            break;
                        case Nette\Http\IRequest::DELETE:
                            $params['action'] = 'delete';
                            if ($params['img']) {
                                $params['path'] .= '/' . $params['img'];
                                $params["img"] = null;
                            }
                            break;
                        default:
                            return null;
                    }
                    if ($params['img']) {
                        return null;
                    }
                    return $params;
                },
            ],
        ]);
        $router->addRoute('<presenter>/<width>/<height>/<fullPath>', 'Images:default');
		return $router;
	}
}
