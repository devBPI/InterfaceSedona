<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HelpController extends AbstractController
{

	/**
	* @Route("/aide/recherche", methods={"GET","HEAD"}, name="help_search")
	*/
	public function searchAction(Request $request)
	{
		return $this->render('help/search.html.twig', []);
	}

	/**
	* @Route("/aide/resultat", methods={"GET","HEAD"}, name="help_result")
	*/
	public function resultAction(Request $request)
	{
		return $this->render('help/result.html.twig', []);
	}

	/**
	* @Route("/aide/service", methods={"GET","HEAD"}, name="help_service")
	*/
	public function serviceAction(Request $request)
	{
		return $this->render('help/service.html.twig', []);
	}

	/**
	* @Route("/aide/compte", methods={"GET","HEAD"}, name="help_account")
	*/
	public function accountAction(Request $request)
	{
		return $this->render('help/account.html.twig', []);
	}
}
