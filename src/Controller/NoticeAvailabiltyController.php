<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 27/09/19
 * Time: 11:48
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NoticeAvailabilty
{
    /**
     * @Route("/noitice/availability/{permalink}", methods={"POST"}, requirements={"permalink"=".+"})
     * @param Request $request
     * @param string $permalink
     */
    public function postAction(Request $request, string $permalink){


    }
}
