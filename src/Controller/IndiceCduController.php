<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Exception\NoResultException;
use App\Model\Form\ExportNotice;
use App\Model\IndiceCdu;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\NoticeAuthorityProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class IndiceCduController
 * @package App\Controller
 */
class IndiceCduController extends AbstractController
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
     * IndiceCduController constructor.
     * @param NoticeAuthorityProvider $noticeAuhtority
     * @param NoticeBuildFileService $buildFileContent
     */
    public function __construct(
        NoticeAuthorityProvider $noticeAuhtority,
        NoticeBuildFileService $buildFileContent
    ) {
        $this->noticeAuhtority = $noticeAuhtority;
        $this->buildFileContent = $buildFileContent;
    }

    /**
     * @Route("/indice-cdu/{permalink}", methods={"GET","HEAD"}, name="record_indice_cdu", requirements={"permalink"=".+"})
     * @ParamConverter("notice",     class="App\Model\IndiceCdu")
     * @ParamConverter("navigation", class="App\Service\NavigationService")
     * @param IndiceCdu $notice
     * @param NavigationService $navigation
     * @return Response
     */
    public function cduIndiceRecordAction(IndiceCdu $notice, NavigationService $navigation=null)
    {
        $subject = $this->noticeAuhtority->getSubjectNotice($notice->getId());

        return $this->render('indice/indice.html.twig', [
                  'toolbar'         => IndiceCdu::class,
                  'printRoute'      => $this->generateUrl('record_authority_pdf',  ['permalink'=>$notice->getPermalink(), 'format'=>'pdf']),
                  'subjects'        => $subject,
                  'notice'          => $notice,
                  'navigation'      => $navigation,
              ]
        );
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
                'html'=> $this->renderView('indice/index-browsing.html.twig',
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

    /**
     * @Route("/print/indice.{format}/{permalink}", methods={"GET"}, name="indice_pdf", requirements={"permalink"=".+", "format" = "html|pdf|txt"}, defaults={"format" = "pdf"})

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

        return  $this->buildFileContent->buildFile($sendAttachement, IndiceCdu::class, $format);
    }
}

