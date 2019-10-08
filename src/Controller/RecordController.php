<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\ExportNoticeType;
use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\From\ExportNotice;
use App\Model\IndiceCdu;
use App\Model\Notice;
use App\Model\RankedAuthority;
use App\Model\Search\SearchQuery;
use App\Service\MailSenderService;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;
use App\Service\Provider\SearchProvider;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RecordController
 * @package App\Controller
 */
class RecordController extends AbstractController
{
    /**
     * @var NoticeProvider
     */
    private $noticeProvider;
    /**
     * @var NoticeAuthorityProvider
     */
    private $noticeAuhtority;

    /**
     * @var Serializer
     */
    protected $serializer;
    /**
     * @var SearchProvider
     */
    private $searchProvider;
    /**
     * @var NoticeBuildFileService
     */
    private $buildFileContent;

    /**
     * RecordController constructor.
     * @param NoticeProvider $noticeProvider
     * @param NoticeAuthorityProvider $noticeAuhtority
     * @param SerializerInterface $serializer
     * @param SearchProvider $searchProvider
     * @param NoticeBuildFileService $service
     */
    public function __construct(
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $noticeAuhtority,
        SerializerInterface $serializer,
        SearchProvider $searchProvider,
        NoticeBuildFileService $service
    ) {
        $this->noticeProvider = $noticeProvider;
        $this->noticeAuhtority = $noticeAuhtority;
        $this->serializer = $serializer;
        $this->searchProvider = $searchProvider;
        $this->buildFileContent = $service;

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
                $navigation =
                    new NavigationService(
                        $this->searchProvider,
                        $permalink,
                        $this->serializer->deserialize($session->get($searchToken), SearchQuery::class, 'json'),
                        $searchToken,
                        $object->getNotice()->isOnLine() ? Notice::ON_LIGNE : Notice::ON_SHELF
                    );
            }
        }catch(NoResultException $e){
            return $this->render('common/error.html.twig');
        }

        return $this->render('record/bibliographic.html.twig', [
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
            $sendWithAttachement = new ExportNotice();

            $sendWithAttachement
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

    /**
     * @Route("/notice-autorite/{permalink}", methods={"GET","HEAD"}, name="record_authority", requirements={"permalink"=".+"})
     * @param Request $request
     * @param string $permalink
     * @param SessionInterface $session
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function authorityRecordAction(Request $request, string $permalink, SessionInterface $session)
    {
        try{


            $object = $this->noticeAuhtority->getAuthority($permalink);
            $id = $object->getId();
            $subject = $this->noticeAuhtority->getSubjectNotice($id);
            $authors = $this->noticeAuhtority->getAuthorsNotice($id);
            $searchToken = $request->get('searchToken');
            $navigation = null;

            if ($session->has($searchToken)) {
                $navigation = new NavigationService(
                    $this->searchProvider,
                    $permalink,
                    $this->serializer->deserialize($session->get($searchToken, ''), SearchQuery::class, 'json'),
                    $searchToken,
                    RankedAuthority::class
                );
            }
        }catch(NoResultException $e){
            return $this->render('common/error.html.twig');
        }

        return $this->render('record/authority.html.twig', [
                  'toolbar'         => Authority::class,
                  'printRoute'      => $this->generateUrl('record_authority_pdf', ['permalink'=>$permalink, 'format'=>'pdf']),
                  'subjects'        => $subject,
                  'authors'         => $authors,
                  'notice'          => $object,
                  'navigation'     => $navigation,
              ]
        );
    }

    /**
     * @Route("/indice-cdu/{permalink}", methods={"GET","HEAD"}, name="record_indice_cdu", requirements={"permalink"=".+"})
     * @param Request $request
     * @param string $permalink
     * @param SessionInterface $session
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function cduIndiceRecordAction(Request $request, string $permalink, SessionInterface $session)
    {
        try{


            $object = $this->noticeAuhtority->getIndiceCdu($permalink);
            $id = $object->getId();
            $subject = $this->noticeAuhtority->getSubjectNotice($id);
            $authors = [];
            $searchToken = $request->get('searchToken');
            $navigation = null;
            if ($session->has($searchToken)) {
                $navigation = new NavigationService(
                    $this->searchProvider,
                    $permalink,
                    $this->serializer->deserialize($session->get($searchToken, ''), SearchQuery::class, 'json'),
                    $searchToken,
                    RankedAuthority::class
                );
            }
        }catch(NoResultException $e){
            return $this->render('common/error.html.twig');
        }
        return $this->render('record/authority.html.twig', [
                  'toolbar'         => IndiceCdu::class,
                  'printRoute'      => $this->generateUrl('record_authority_pdf',  ['permalink'=>$permalink, 'format'=>'pdf']),
                  'subjects'        => $subject,
                  'authors'         => $authors,
                  'notice'          => $object,
                  'navigation'      => $navigation,
              ]
        );
    }

    /**
     * @Route("/print/notice-autorite.{format}/{permalink}", name="record_authority_pdf", requirements={"permalink"=".+", "format" = "html|pdf|txt"}, defaults={"format" = "pdf"})

     * @param Request $request
     * @param string $format
     * @return mixed|string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function authorityRecordPDFAction(Request $request, $format='pdf')
    {
        try{
            $sendAttachement = new ExportNotice();

            $sendAttachement
                ->setAuthorities($request->get('permalink'))
                ->setImage($request->get('print-image', null) === 'print-image')
                ->setFormatType($format)
                ->setShortFormat($request->get('print-type', 'print-long') !== 'print-long')
            ;
        }catch(NoResultException $e){
            return $this->render('common/error.html.twig');
        }

        return  $this->buildFileContent->buildFile($sendAttachement, Authority::class, $format);
    }

    /**
     * @Route("indice-cdu/around/{cote}", name="indice_around_indexes", requirements={"cote"=".+"})
     * @param $cote
     * @return JsonResponse
     */
    public function aroundIndexesAction($cote): JsonResponse
    {
        try{
            $indiceCdu = $this->noticeAuhtority->getIndiceCduAroundOf($cote);
            return new JsonResponse([
                'html'=> $this->renderView('record/blocs/index-browsing.html.twig',
                    ['indexList'=> $indiceCdu]
                )
            ]);

        }catch (\Exception $e){
             return new JsonResponse([
                'message'=> $e->getMessage(),
                'code'=> $e->getCode()
            ]);

        }
    }
}

