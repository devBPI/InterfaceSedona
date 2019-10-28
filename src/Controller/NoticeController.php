<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Exception\NoResultException;
use App\Model\Form\ExportNotice;
use App\Model\Notice;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\NoticeProvider;
use JMS\Serializer\SerializerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NoticeController
 * @package App\Controller
 */
class NoticeController extends CardController
{
    /**
     * @var NoticeProvider
     */
    private $noticeProvider;

    /**
     * @var NoticeBuildFileService
     */
    private $buildFileContent;

    /**
     * NoticeController constructor.
     * @param NoticeProvider $noticeProvider
     * @param SerializerInterface $serializer
     * @param NoticeBuildFileService $service
     * @param NavigationService $navigationService
     */
    public function __construct(
        NoticeProvider $noticeProvider,
        SerializerInterface $serializer,
        NoticeBuildFileService $service,
        NavigationService $navigationService
    ) {
        $this->noticeProvider = $noticeProvider;
        $this->buildFileContent = $service;
        parent::__construct($navigationService, $serializer);
    }

    /**
     * @Route("/notice-bibliographique/{permalink}", methods={"GET","HEAD"}, name="record_bibliographic", requirements={"permalink"=".+"})
     * @param Request $request
     * @param string $permalink
     * @param SessionInterface $session
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function bibliographicRecordAction(Request $request, string $permalink, SessionInterface $session)
    {
        try {
            $searchToken = $request->get('searchToken');
            $object = $this->noticeProvider->getNotice($permalink);
            $navigation = null;
            if ($session->has($searchToken)) {
                $navigation = $this->buildNavigationService($permalink, $searchToken,  $session->get($searchToken, ''), $object->getNotice()->isOnLine() ? Notice::ON_LIGNE : Notice::ON_SHELF);
            }
        }catch(NoResultException $e){
            return $this->render('common/error.html.twig');
        }

        return $this->render('notice/bibliographic.html.twig', [
            'object'            => $object,
            'notice'            => $object->getNotice(),
            'toolbar'           => Notice::class,
            'navigation'        => $navigation,
            'printRoute'        => $this->generateUrl('record_bibliographic_pdf',['permalink'=> $object->getNotice()->getPermalink() ,'format'=> 'pdf'])
        ]);
    }
    /**
     * @Route("/print/notice-bibliographique.{format}/{permalink}", methods={"GET","HEAD"}, name="record_bibliographic_pdf", requirements={"permalink"=".+", "format"="html|pdf|txt"}, defaults={"format" = "pdf"})
     * @param Request $request
     * @param string $format
     * @return PdfResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function bibliographicRecordPDFAction(Request $request, $format='pdf')
    {
        try {
            $sendWithAttachement = (new ExportNotice())
                ->setNotices($request->get('permalink'))
                ->setImage($request->get('print-image', null) === 'print-image')
                ->setFormatType($format)
                ->setShortFormat($request->get('print-type', 'print-long') !== 'print-long')
            ;
        }catch(NoResultException $e){
            return $this->render('common/error.html.twig');
        }

        return  $this->buildFileContent->buildFile($sendWithAttachement, Notice::class, $format);
    }
}
