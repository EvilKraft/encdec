<?php
/**
 * Created by PhpStorm.
 * User: Kraft
 * Date: 16.04.2019
 * Time: 16:13
 */

namespace EvilKraft\encdec;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class EncDecMiddleware implements MiddlewareInterface
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = EncDecController::getData();

        $dateNow = new \DateTime();
        $dateExp = new \DateTime($data['expared']);

        if ($dateNow >= $dateExp) {
            $response = new Response();
            $response->getBody()->write('License has been expired!');
            $response->withStatus(302)->withHeader('Location', $this->url);
        }
        
        return $handler->handle($request);
    }
}
