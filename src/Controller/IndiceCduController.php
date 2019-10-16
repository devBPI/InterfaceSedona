<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 15/10/19
 * Time: 16:58
 */

namespace App\Controller;

use App\Model\Exception\NoResultException;
use App\Model\From\ExportNotice;
use App\Model\IndiceCdu;
use App\Model\RankedAuthority;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\NoticeAuthorityProvider;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndiceCduController extends CardController
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
     * @var NavigationService
     */
    private $navigationService;

    /**
     * IndiceCduController constructor.
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
        $this->navigationService = $navigationService;
        parent::__construct($navigationService, $serializer);
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
            $subject = $this->noticeAuhtority->getSubjectNotice($object->getId());
            $searchToken = $request->get('searchToken');
            $navigation = null;
            if ($session->has($searchToken)) {
                $navigation = $this->buildNavigationService($permalink, $searchToken,  $session->get($searchToken, ''), RankedAuthority::class);
            }
        }catch(NoResultException $e){
            return $this->render('common/error.html.twig');
        }

        return $this->render('indice/indice.html.twig', [
                  'toolbar'         => IndiceCdu::class,
                  'printRoute'      => $this->generateUrl('record_authority_pdf',  ['permalink'=>$permalink, 'format'=>'pdf']),
                  'subjects'        => $subject,
                  'notice'          => $object,
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
    {        try{
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

