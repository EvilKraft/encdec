<?php
/**
 * Created by PhpStorm.
 * User: Kraft
 * Date: 16.04.2019
 * Time: 16:13
 */

namespace EvilKraft\encdec;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class EncDecMiddleware
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        try{
            $license = EncDecController::getLicenseData();

            $dateNow = new \DateTime();
            $dateExp = new \DateTime($license['expared']);

            if ($dateNow >= $dateExp) {
                throw new \Exception('License has been expired!');
            }
        }catch (\Exception $e){
            return $response->withStatus(302)->withHeader('Location', $this->url);
        }

        return $next($request, $response);
    }
}