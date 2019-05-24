<?php


namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/donnees-personnelles", name="user_personal_data")
     * @Method("GET")
     */
    public function personalDataAction(Request $request)
    {
        return $this->render('user/personal-data.html.twig', []);
    }

    /**
     * @Route("/suggestion", name="user_suggestion")
     * @Method("GET")
     */
    public function suggestionAction(Request $request)
    {
        return $this->render('user/suggestion.html.twig', []);
    }

    /**
     * @Route("/envoyer-a-un-amis", name="user_send_to_friend")
     * @Method("GET")
     */
    public function sendToFriendAction(Request $request)
    {
        return $this->render('user/send-to-friend.html.twig', []);
    }

    /**
     * @Route("/list/cree", name="user_list_add")
     * @Method("GET")
     */
    public function addListAction(Request $request)
    {
        return $this->render('user/list_add.html.twig', []);
    }

    /**
     * @Route("/list/modifier", name="user_list_edit")
     * @Method("GET")
     */
    public function editListAction(Request $request)
    {
        return $this->render('user/list_edit.html.twig', []);
    }

    /**
     * @Route("/list/supprimer", name="user_list_remove")
     * @Method("GET")
     */
    public function removeListAction(Request $request)
    {
        return $this->render('user/list_remove.html.twig', []);
    }

}
