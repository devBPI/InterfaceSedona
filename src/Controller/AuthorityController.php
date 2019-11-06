<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\Form\ExportNotice;
use App\Model\RankedAuthority;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\NoticeAuthorityProvider;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorityController
 * @package App\Controller
 */
class AuthorityController extends AbstractController
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
     * AuthorityController constructor.
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
     * @Route("/notice-autorite/{permalink}", methods={"GET","HEAD"}, name="record_authority", requirements={"permalink"=".+"})
     * @return Response
     */
    public function authorityRecordAction(Authority $notice, NavigationService $navigation = null)
    {
        $subject = $this->noticeAuhtority->getSubjectNotice($notice->getId());
        $authors = $this->noticeAuhtority->getAuthorsNotice($notice->getId());

        return $this->render('authority/authority.html.twig', [
                'toolbar'         => Authority::class,
                'printRoute'      => $this->generateUrl('record_authority_pdf', ['permalink'=>$notice->getPermalink(), 'format'=>'pdf']),
                'subjects'        => $subject,
                'authors'         => $authors,
                'notice'          => $notice,
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
