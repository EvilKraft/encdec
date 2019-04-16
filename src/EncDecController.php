<?php
/**
 * Created by PhpStorm.
 * User: Kraft
 * Date: 16.04.2019
 * Time: 16:33
 */

namespace EvilKraft\encdec;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class EncDecController
{
    //Constructor
    public function __construct(ContainerInterface $ci) {

    }

    public function create(Request $request, Response $response, Array $args)
    {
        $queryParams = $request->getQueryParams();

        if(isset($queryParams['d']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $queryParams['d'])){
            $dateExp = new \DateTime($queryParams['d']);
        }else{
            $dateExp = new \DateTime();
            $dateExp->modify('+1 month');
        }

        $dateExp->setTime(0,0,0,0);

        $data = array(
            'expared' => $dateExp->format('Y-m-d')
        );

        echo print_r($data, true);

        $data = json_encode($data);
        $data = EncDec::securedEncrypt($data);

        file_put_contents(LICENSE, $data);

        return $response;
    }

    public function show(Request $request, Response $response, Array $args)
    {
        $data = self::getData();
        echo print_r($data, true);
        return $response;
    }

    public static function getData(){
        if(!file_exists(LICENSE)){
            throw new \Exception('File not exists!');
        }

        $data = EncDec::securedDecrypt(file_get_contents(LICENSE));
        $data = json_decode($data, true);

        if(!isset($license['expared'])){
            throw new \Exception('Wrong file format!');
        }

        return $data;
    }
}