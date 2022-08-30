<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\Form\ExportNotice;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\NoticeAuthorityProvider;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorityController
 * @package App\Controller
 */
final class AuthorityController extends AbstractController
{
    /**
     * @var NoticeAuthorityProvider
     */
    private $noticeAuhtority;
    /**
     * @var NoticeBuildFileService
     */
    private $buildFileContent;
    /**
     * @var NavigationService
     */
    private $navigationService;

    /**
     * AuthorityController constructor.
     * @param NoticeAuthorityProvider $noticeAuhtority
     * @param NoticeBuildFileService $buildFileContent
     * @param NavigationService $navigationService
     */
    public function __construct(
        NoticeAuthorityProvider $noticeAuhtority,
        NoticeBuildFileService $buildFileContent,
        NavigationService $navigationService
    ) {
        $this->noticeAuhtority = $noticeAuhtority;
        $this->buildFileContent = $buildFileContent;
        $this->navigationService = $navigationService;
    }

    /**
     * @Route("/{parcours}/autorite/{permalink}", methods={"GET","HEAD"}, name="record_authority_parcours", requirements={"permalink"=".+"})
     * @Route("/autorite/{permalink}", methods={"GET","HEAD"}, name="record_authority", requirements={"permalink"=".+"})
     * @param Authority $notice
     * @param LoggerInterface $logger
     * @return Response
     */
    public function authorityRecordAction(Authority $notice, LoggerInterface $logger)
    {
        $subject = $this->noticeAuhtority->getSubjectNotice($notice->getId());
        $authors = $this->noticeAuhtority->getAuthorsNotice($notice->getId());
        $printRoute = $this->generateUrl(
            'record_authority_pdf',
            [ 'permalink' => $notice->getPermalink(), 'format' => 'pdf']
        );
        $is_document = str_contains($printRoute, 'document');
        try {
            $navigation = $this->navigationService->buildAuthorities($notice);
        } catch (\Exception $e) {
            $logger->error('Navigation failed for author '.$notice->getPermalink(). ' : '.$e->getMessage());
            $navigation = null;
        }

        return $this->render('authority/index.html.twig', [
                'is_document'   => $is_document,
                'toolbar'       => Authority::class,
                'printRoute'    => $printRoute,
                'subjects'      => $subject,
                'authors'       => $authors,
                'notice'        => $notice,
                'navigation'    => $navigation,
            ]
        );
    }

    /**
     * @Route("/print/autorite.{format}/{permalink}", name="record_authority_pdf", requirements={"permalink"=".+", "format" = "html|pdf|txt"}, defaults={"format" = "pdf"})

     * @param Request $request
     * @param string $format
     * @return mixed|string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function authorityRecordPDFAction(Request $request, $format='pdf')
    {
        try {
            $sendAttachement = new ExportNotice();

            $sendAttachement
                ->setAuthorities($request->get('permalink'))
                ->setImage($request->get('print-image', null) === 'print-image')
                ->setFormatType($format)
                ->setShortFormat($request->get('print-type', 'print-long') !== 'print-long')
            ;
        } catch(NoResultException $e) {
            return $this->render('common/error.html.twig');
        }

        return  $this->buildFileContent->buildFile($sendAttachement, Authority::class);
    }

}
