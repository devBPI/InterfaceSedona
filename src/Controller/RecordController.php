<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Notice;
use App\Model\RankedAuthority;
use App\Model\Search\SearchQuery;
use App\Service\NavigationService;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;
use App\Service\Provider\SearchProvider;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * RecordController constructor.
     * @param NoticeProvider $noticeProvider
     * @param NoticeAuthorityProvider $noticeAuhtority
     * @param SerializerInterface $serializer
     * @param SearchProvider $searchProvider
     */
    public function __construct(
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $noticeAuhtority,
        SerializerInterface $serializer,
        SearchProvider $searchProvider
    ) {
        $this->noticeProvider = $noticeProvider;
        $this->noticeAuhtority = $noticeAuhtority;
        $this->serializer = $serializer;
        $this->searchProvider = $searchProvider;
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
                    $object->getNotice()->isOnLigne() ? Notice::ON_LIGNE : Notice::ON_SHELF
                );
        }

        return $this->render('record/bibliographic.html.twig', [
            'object'            => $object,
            'notice'            => $object->getNotice(),
            'toolbar'           => 'document',
            'navigation'        => $navigation,
            'printRoute'        => $this->generateUrl('record_bibliographic_pdf',['format'=> 'pdf'])
        ]);
    }

    /**
     * @Route("/print/notice-bibliographique.{format}", methods={"GET","HEAD"}, name="record_bibliographic_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     * @param Request $request
     * @param \Knp\Snappy\Pdf $knpSnappy
     * @param string $format
     * @return PdfResponse|Response
     */
    public function bibliographicRecordPDFAction(Request $request, \Knp\Snappy\Pdf $knpSnappy, $format = "pdf")
    {
        $object = $this->noticeProvider->getNotice($request->get('permalink'));

        $content = $this->renderView("record/bibliographic.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
            'notice' => $object->getNotice(),
            'noticeThemed' => $object->getNoticesSameTheme(),
        ]);
        $filename = 'bibliographic'.date('Y-m-d_h-i-s');

        if ($format == 'txt') {
            return new Response($content,200,[
                'Content-Type' => 'application/force-download',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"'
            ]);
        } elseif ($format == 'html') {
            return new Response($content);
        }

        return new PdfResponse(
            $knpSnappy->getOutputFromHtml($content),
            $filename.".pdf"
        );
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

        return $this->render('record/authority.html.twig', [
                  'toolbar'         => 'document',
                  'printRoute'      => $this->generateUrl('record_authority_pdf'),
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

        return $this->render('record/authority.html.twig', [
                  'toolbar'         => 'document',
                  'printRoute'      => $this->generateUrl('record_authority_pdf'),
                  'subjects'        => $subject,
                  'authors'         => $authors,
                  'notice'          => $object,
                  'navigation'      => $navigation,
              ]
        );
    }

    /**
     * @Route("/print/notice-autorite.{format}", name="record_authority_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     * @param Request $request
     * @param \Knp\Snappy\Pdf $knpSnappy
     * @param string $format
     * @return PdfResponse|Response
     */
    public function authorityRecordPDFAction(Request $request, \Knp\Snappy\Pdf $knpSnappy, $format='pdf')
    {
        $object             = $this->noticeAuhtority->getAuthority($request->get('permalink'));
        $relatedDocuments   = $this->noticeAuhtority->getSubjectNotice($object->getId());
        $noticeAuthors      = $this->noticeAuhtority->getAuthorsNotice($object->getId());

        $content = $this->renderView("record/authority.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'       => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'      => $request->get('print-image', null) == 'print-image',
            'notice'            => $object,
            'relatedDocuments'  => $relatedDocuments,
            'noticeAuthors'     => $noticeAuthors,
        ]);

        $filename = 'authority_'.date('Y-m-d_h-i-s');

        if ($format == 'txt') {
            return new Response($content,200,[
                'Content-Type' => 'application/force-download',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"'
            ]);
        } elseif ($format == 'html') {
            return new Response($content);
        }

        return new PdfResponse(
            $knpSnappy->getOutputFromHtml($content),
            $filename.".pdf"
        );
    }


}

