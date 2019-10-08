<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 27/09/19
 * Time: 11:48
 */

namespace App\Controller;


use App\Entity\NoticeAvailabilityRequest;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class NoticeAvailabiltyController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * NoticeAvailabiltyController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;
    }


    /**
     * @Route("/notices/{sourceId}/configurations/{configurationId}/availability", methods={"POST", "GET"}, requirements={", sourceId"="\d+", "configurationId"="\d+"}, name="notice_availablity_request")
     * @param int $sourceId
     * @param int $configurationId
     * @return JsonResponse
     */
    public function postAction(Request $request, int $sourceId, int $configurationId):JsonResponse
    {
        try{
            $user = $this->getUser();

            $date = new \DateTime('now');
            $noticeAvailable = new NoticeAvailabilityRequest();
            $noticeAvailable
                ->setNoticeConfigurationId($configurationId)
                ->setNoticeSourceId($sourceId)
                ->setRequestDate($date)
                ->setModificationDate($date)
                ->setNotificationEmail($request->get('email'))
            ;
            if ($user instanceof  UserInterface){
               $noticeAvailable->setRequester($user->getUsername());
            }
            $this->em->persist($noticeAvailable);
            $this->em->flush();
            return new JsonResponse([
                "code"      => 200,
                'response'  =>'ok'
            ]);

        }catch(\Exception $e){
            return new JsonResponse([
                'message'   => $e->getMessage(),
                'code'      => $e->getCode()
            ]);
        }

    }
}
