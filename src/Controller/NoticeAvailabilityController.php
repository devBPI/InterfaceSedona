<?php

namespace App\Controller;


use App\Entity\NoticeAvailabilityRequest;
use App\Form\AvailabilityNotificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class NoticeAvailabilityController
 * @package App\Controller
 */
final class NoticeAvailabilityController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * NoticeAvailabilityController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/notices/{sourceId}/availability/{configurationId}", methods={"POST", "GET"}, requirements={"sourceId"="\d+", "configurationId"="\d+"}, name="notice_availablity_request")
     * @param Request $request
     * @param int $sourceId
     * @param int $configurationId
     * @return Response
     */
    public function indexAction(Request $request, int $sourceId, int $configurationId = null): Response
    {
        $noticeAvailabilityRequest = new NoticeAvailabilityRequest(
            $sourceId,
            $configurationId
        );
        $form = $this->createForm(AvailabilityNotificationType::class, $noticeAvailabilityRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noticeAvailableRequest = $form->getData();

            $user = $this->getUser();
            if ($user instanceof UserInterface) {
                $noticeAvailableRequest->setRequester($user->getUsername());
            }

            $this->em->persist($noticeAvailableRequest);
            $this->em->flush();

            return $this->render('common/modal/availability-success.html.twig');
        }

        return $this->render(
            'common/modal/availability-content.html.twig',
            [
                'form' => $form->createView(),
                'sourceId' => $sourceId,
                'configurationId' => $configurationId,
            ]
        );
    }

}
