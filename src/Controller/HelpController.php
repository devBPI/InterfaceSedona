<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HelpController extends AbstractController
{

	/**
	* @Route("/aide/recherche", methods={"GET","HEAD"}, name="help_search")
	*/
	public function searchAction() :?Response
	{
		return $this->render('help/search.html.twig', []);
	}

	/**
	* @Route("/aide/exploitationresultats", methods={"GET","HEAD"}, name="help_result")
	*/
	public function resultAction() :?Response
	{
		return $this->render('help/result.html.twig', []);
	}

	/**
	* @Route("/aide/services", methods={"GET","HEAD"}, name="help_service")
	*/
	public function serviceAction() :?Response
	{
		return $this->render('help/service.html.twig', []);
	}

	/**
	* @Route("/aide/compte", methods={"GET","HEAD"}, name="help_account")
	*/
	public function accountAction() :?Response
	{
		return $this->render('help/account.html.twig', []);
	}
}
