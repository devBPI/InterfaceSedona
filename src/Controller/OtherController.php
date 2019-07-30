<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OtherController extends AbstractController
{

	/**
	* @Route("/aide-recherche", methods={"GET","HEAD"}, name="other_search")
	*/
	public function searchRecordAction(Request $request)
	{
		return $this->render('other/search-help.html.twig', []);
	}

	/**
	* @Route("/aide-resultat", methods={"GET","HEAD"}, name="other_result")
	*/
	public function resultRecordAction(Request $request)
	{
		return $this->render('other/result.html.twig', []);
	}

	/**
	* @Route("/aide-service", methods={"GET","HEAD"}, name="other_service")
	*/
	public function serviceRecordAction(Request $request)
	{
		return $this->render('other/service.html.twig', []);
	}

	/**
	* @Route("/aide-compte", methods={"GET","HEAD"}, name="other_account")
	*/
	public function accountRecordAction(Request $request)
	{
		return $this->render('other/account.html.twig', []);
	}

}
