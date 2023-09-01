<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Exception\NoResultException;
use App\Model\Form\ExportNotice;
use App\Model\IndiceCdu;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\NoticeAuthorityProvider;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class IndiceCduController
 * @package App\Controller
 */
final class IndiceCduController extends AbstractController
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
     * IndiceCduController constructor.
     * @param NoticeAuthorityProvider $noticeAuthority
     * @param NoticeBuildFileService $buildFileContent
     * @param NavigationService $navigationService
     */
    public function __construct(
        NoticeAuthorityProvider $noticeAuthority,
        NoticeBuildFileService $buildFileContent,
        NavigationService $navigationService
    ) {
        $this->noticeAuhtority = $noticeAuthority;
        $this->buildFileContent = $buildFileContent;
        $this->navigationService = $navigationService;
    }

    /**
     * @Route("/{parcours}/indice-cdu/{permalink}", methods={"GET","HEAD"}, name="record_indice_cdu_parcours", requirements={"permalink"=".+"})
     * @Route("/indice-cdu/{permalink}", methods={"GET","HEAD"}, name="record_indice_cdu", requirements={"permalink"=".+"})
     * @param IndiceCdu $notice
     * @param LoggerInterface $logger
     * @return Response
     */
    public function cduIndiceRecordAction(IndiceCdu $notice, LoggerInterface $logger)
    {
        $subjects = $this->noticeAuhtority->getSubjectIndice($notice->getId());
        $printRoute = $this->generateUrl(
            'indice_pdf',
            [ 'permalink' => $notice->getPermalink(), 'format' => ExportNotice::FORMAT_PDF ]
        );
        $origin = 'author';
        try {
            $navigation = $this->navigationService->buildAuthorities($notice);
        } catch (\Exception $e) {
            $logger->error('Navigation failed for indice '.$notice->getPermalink(). ' : '.$e->getMessage());
            $navigation = null;
        }

        return $this->render('indice/index.html.twig', [
                'origin'          => $origin,
                'toolbar'         => IndiceCdu::class,
                'isNotice'        => false,
                'printRoute'      => $printRoute,
                'subjects'        => $subjects,
                'notice'          => $notice,
                'navigation'      => $navigation,
            ]
        );
    }

    /**
     * @Route("indice-cdu/{cote}/around/{current}", methods={"POST"}, name="indice_around_indexes", requirements={"cote"=".+", "current"=".+"})
     * @param string $cote
     * @param string|null $current
     * @return JsonResponse
     */
    public function aroundIndexesAction(string $cote, string $current = null): JsonResponse
    {
        try {
            $indiceCdu = $this->noticeAuhtority->getIndiceCduAroundOf($cote);
            return new JsonResponse([
                'html'=> $this->renderView('indice/blocs/index-browsing.html.twig',
                    ['indexList'=> $indiceCdu, 'current' => $current]
                )
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message'=> $e->getMessage(),
                'code'=> $e->getCode()
            ]);
        }
    }

    /**
     * @Route("/print/indice.{format}/{permalink}", methods={"GET"}, name="indice_pdf", requirements={"permalink"=".+", "format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function printAction(Request $request, string $format= ExportNotice::FORMAT_PDF) :Response
    {
        try {
            $sendAttachement = ExportNotice::createFromRequest($request, $format)
                ->setIndices($request->get('permalink'))
            ;
        } catch(NoResultException $e) {
            return $this->render('common/error.html.twig');
        }

        return  $this->buildFileContent->buildFile($sendAttachement, IndiceCdu::class);
    }

}
