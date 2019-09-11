<?php


namespace App\Controller;


use App\Model\Authority;
use App\Model\Search;
use App\Service\NavigationService;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;
use App\Service\Provider\SearchProvider;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use App\Model\Notice;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;

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

    /** @var Serializer */
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
    public function __construct(NoticeProvider $noticeProvider,
                                NoticeAuthorityProvider $noticeAuhtority,
                                SerializerInterface $serializer,
                                SearchProvider $searchProvider
    )
    {
        $this->noticeProvider = $noticeProvider;
        $this->noticeAuhtority = $noticeAuhtority;
        $this->serializer = $serializer;
        $this->searchProvider = $searchProvider;
    }

    /**
     * @Route("/notice-bibliographique", methods={"GET","HEAD"}, name="record_bibliographic")
     */
    public function bibliographicRecordAction(Request $request, SessionInterface $session)
    {
        $permalink = $request->get('permalink');
        $searchToken = $request->get('searchToken');
        $object = $this->noticeProvider->getNotice($permalink);

        $navigation = new NavigationService(
            $this->searchProvider,
            $permalink,
            $this->serializer->deserialize($session->get($searchToken, ''),  Search::class, 'json'),
            $searchToken
        );

        return $this->render('record/bibliographic.html.twig', [
            'object'        => $object,
            'toolbar'       => 'document',
            'navigation'    => $navigation,
            'printRoute'    => $this->generateUrl('record_bibliographic_pdf',['format'=> 'pdf'])
        ]);
    }

    /**
     * @Route("/print/notice-bibliographique.{format}", methods={"GET","HEAD"}, name="record_bibliographic_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function bibliographicRecordPDFAction(Request $request, \Knp\Snappy\Pdf $knpSnappy, $format = "pdf")
    {
        $content = $this->renderView("record/bibliographic.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
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
     * @Route("/notice-autorite", methods={"GET","HEAD"}, name="record_authority")
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function authorityRecordAction(Request $request, SessionInterface $session)
    {
        $permalink = $request->get('permalink');
        $object = $this->noticeAuhtority->getAuthority($permalink);
        $id = $object->getId();
        $subject = $this->noticeAuhtority->getSubjectNotice($id);
        $authors = $this->noticeAuhtority->getAuthorsNotice($id);
        $searchToken = $request->get('searchToken');

        $navigation = new NavigationService(
            $this->searchProvider,
            $permalink,
            $this->serializer->deserialize($session->get($searchToken, ''),  Search::class, 'json'),
            $searchToken,
            Authority::class
        );

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
     * @Route("/print/notice-autorite.{format}", name="record_authority_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function authorityRecordPDFAction(Request $request, \Knp\Snappy\Pdf $knpSnappy, $format = "pdf")
    {
        $content = $this->renderView("record/authority.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
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

