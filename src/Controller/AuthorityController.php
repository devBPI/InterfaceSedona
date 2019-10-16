<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 15/10/19
 * Time: 16:58
 */

namespace App\Controller;
use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\From\ExportNotice;
use App\Model\RankedAuthority;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\NoticeAuthorityProvider;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class AuthorityController extends CardController
{
    /**
     * @var NoticeAuthorityProvider
     */
    private $noticeAuhtority;
    /**
     * @var Serializer
     */
    protected $serializer;
    /**
     * @var NoticeBuildFileService
     */
    private $buildFileContent;

    /**
     * RecordController constructor.
     * @param NoticeAuthorityProvider $noticeAuhtority
     * @param SerializerInterface $serializer
     * @param NoticeBuildFileService $service
     * @param NavigationService $navigationService
     */
    public function __construct(
        NoticeAuthorityProvider $noticeAuhtority,
        SerializerInterface $serializer,
        NoticeBuildFileService $service,
        NavigationService $navigationService

    ) {
        $this->noticeAuhtority = $noticeAuhtority;
        $this->serializer = $serializer;
        $this->buildFileContent = $service;
        parent::__construct($navigationService, $serializer);
    }


    /**
     * @Route("/notice-autorite/{permalink}", methods={"GET","HEAD"}, name="record_authority", requirements={"permalink"=".+"})
     * @param Request $request
     * @param string $permalink
     * @param SessionInterface $session
     * @return Response
     */
    public function authorityRecordAction(Request $request, string $permalink, SessionInterface $session)
    {
        try{
            $object = $this->noticeAuhtority->getAuthority($permalink);
            $subject = $this->noticeAuhtority->getSubjectNotice($object->getId());
            $authors = $this->noticeAuhtority->getAuthorsNotice($object->getId());
            $searchToken = $request->get('searchToken');
            $navigation = null;
            if ($session->has($searchToken)) {
                $navigation = $this->buildNavigationService($permalink, $searchToken,  $session->get($searchToken, ''), RankedAuthority::class);
            }
        }catch(NoResultException $e){
            return $this->render('common/error.html.twig');
        }

        return $this->render('authority/authority.html.twig', [
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

}
