<?php


namespace App\Controller;

use App\Entity\UserSelectionCategory;
use App\Form\SelectionCategoryType;
use App\Service\SelectionListService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserSelectionController
 * @package App\Controller
 */
class UserSelectionController extends AbstractController
{
    const INPUT_NAME = 'selection';
    const INPUT_TITLE_NAME = 'selection_category_title';
    const INPUT_CATEGORY = 'selection_category';

    /**
     * @var SelectionListService
     */
    private $selectionService;

    /**
     * UserSelectionController constructor.
     * @param SelectionListService $selectionListService
     */
    public function __construct(SelectionListService $selectionListService)
    {
        $this->selectionService = $selectionListService;
    }

    /**
     * @Route("/selection", methods={"GET"}, name="user_selection")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function selectionAction(): Response
    {
        return $this->render( 'user/selection.html.twig',[
            'selectionCategories' => $this->selectionService->getCategories(),
        ]);
    }


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/list/creer", methods={"POST"}, name="user_list_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createListAction(Request $request): Response
    {
        $param = [];
        $title = $request->get(self::INPUT_TITLE_NAME, null);
        if ($title !== null) {
            if ($title !== '') {
                $this->selectionService->createCategory($title);

                return $this->render('user/modal/creation-list-success.html.twig', [
                    'title' => $title,
                ]);
            }

            $param = [
                'error' => 'modal.list-create.mandatory-field',
            ];
        }

        return $this->render('user/modal/creation-list-content.html.twig', $param);
    }

    /**
     * @Route("/list/ajout", methods={"GET","HEAD"}, name="user_list_add")
     * @param Request $request
     * @return Response
     */
    public function addListAction(Request $request): Response
    {
        return $this->render('user/modal/list_add.html.twig', []);
    }

    /**
     * @Route("/list/modifier/{category}", methods={"GET","POST"}, name="user_list_edit")
     * @param UserSelectionCategory $category
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editListAction(UserSelectionCategory $category, Request $request): Response
    {
        $param = ['category' => $category];
        $title = $request->get(self::INPUT_TITLE_NAME, null);
        if ($title !== null) {
            if ($title !== '') {
                $this->selectionService->updateCategory($category, $title);

                return $this->render(
                    'user/modal/creation-list-success.html.twig',
                    [
                        'title' => $title,
                    ]
                );
            }

            $param += [
                'error' => 'modal.list-create.mandatory-field',
            ];
        }

        return $this->render('user/modal/edition-list-content.html.twig', $param);
    }

    /**
     * @Route("/list/supprimer", methods={"GET","HEAD"}, name="user_list_remove")
     */
    public function removeListAction(Request $request): Response
    {
        return $this->render('user/modal/list_remove.html.twig', []);
    }
}
