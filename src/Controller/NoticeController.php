<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Exception\NoResultException;
use App\Model\Form\ExportNotice;
use App\Model\Notice;
use App\Model\NoticeThemed;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\NoticeProvider;
use JMS\Serializer\SerializerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NoticeController
 * @package App\Controller
 */
final class NoticeController extends AbstractController
{

    /**
     * @var NoticeBuildFileService
     */
    private $buildFileContent;

    /**
     * NoticeController constructor.
     * @param NoticeBuildFileService $buildFileContent
     */
    public function __construct(
        NoticeBuildFileService $buildFileContent
    ) {
        $this->buildFileContent = $buildFileContent;
    }

    /**
     * @Route("/{parcours}/document/{permalink}", methods={"GET","HEAD"}, name="record_bibliographic_parcours", requirements={"permalink"=".+"})
     * @Route("/document/{permalink}", methods={"GET","HEAD"}, name="record_bibliographic", requirements={"permalink"=".+"})
     * @param NoticeThemed $notice
     * @param NavigationService|null $navigation
     * @return Response
     */
    public function bibliographicRecordAction(NoticeThemed $notice, NavigationService $navigation=null)
    {

        return $this->render('notice/bibliographic.html.twig', [
            'object'            => $notice,
            'notice'            => $notice->getNotice(),
            'toolbar'           => Notice::class,
            'navigation'        => $navigation,
            'printRoute'        => $this->generateUrl(
                'record_bibliographic_pdf',['permalink'=> $notice->getNotice()->getPermalink() ,'format'=> 'pdf'])
        ]);
    }

    /**
     * @Route("/print/document.{format}/{permalink}", methods={"GET","HEAD"}, name="record_bibliographic_pdf", requirements={"permalink"=".+", "format"="html|pdf|txt"}, defaults={"format" = "pdf"})
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

        return  $this->buildFileContent->buildFile($sendWithAttachement, Notice::class);
    }
}
