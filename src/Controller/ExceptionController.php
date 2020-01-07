<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ExceptionController
 * @package App\Controller
 */
final class ExceptionController extends AbstractController
{
    /**
     * @Route("/error/404",methods={"GET"}, name="error_bpi_not_found")
     * @return \Symfony\Component\HttpFoundation\Response
     */
     public function showBpi404(){
        return $this->render('common/error.html.twig');
    }
}
