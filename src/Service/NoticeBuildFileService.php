<?php
declare(strict_types=1);

namespace App\Service;


use App\Entity\UserSelectionDocument;
use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\Form\ExportNotice;
use App\Model\IndiceCdu;
use App\Model\Notice;
use App\Model\Search\ObjSearch;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;
use App\Utils\PrintNoticeWrapper;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

use Monolog\Logger;

/**
 * Class NoticeBuildFileService
 * @package App\Service
 */
class NoticeBuildFileService
{

    const SHORT_PRINT = 'short-print';
    /**
     * @var NoticeProvider
     */
    private $noticeProvider;
    /**
     * @var NoticeAuthorityProvider
     */
    private $noticeAuthority;
    /**
     * @var \Knp\Snappy\Pdf
     */
    private $knpSnappy;
    /**
     * @var \Twig_Environment
     */
    private $templating;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $noticeAuthority,
        \Knp\Snappy\Pdf $knpSnappy,
        Environment $templating,
        SessionInterface $session,
        Logger $logger
        )
    {
        $this->noticeProvider   = $noticeProvider;
        $this->noticeAuthority  = $noticeAuthority;
        $this->knpSnappy        = $knpSnappy;
        $this->templating       = $templating;
        $this->session = $session;
        $this->logger = $logger;
    }


    public function buildFile(ExportNotice $attachement, string $type) :Response
    {
        $content = $this->buildContent($attachement, $type);

        $filename = 'vos-references_'.date('Y-m-d_h-i-s');

        switch ($attachement->getFormatType()){
            case ExportNotice::FORMAT_TEXT:
                return new Response(
                    $content, 200, [
                        'Content-Type' => 'application/force-download; charset=utf-8',
                        'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"',
                    ]
                );
            case ExportNotice::FORMAT_PDF:
                return new PdfResponse($content,$filename.".pdf");
            case ExportNotice::FORMAT_HTML:
            case ExportNotice::FORMAT_EMAIL:
            default:
                return new Response($content);
        }
    }

    public function buildContent(ExportNotice $attachement, string $type): string
    {

        switch ($type){
            case ObjSearch::class:
                $content =  $this->buildFileForSearch($attachement);
                break;
            case Notice::class:
                $content =  $this->buildFileForNotice($attachement);
                break;
            case Authority::class:
                $content =  $this->buildFileForAuthority($attachement);
                break;
            case IndiceCdu::class:
                $content =  $this->buildFileForIndice($attachement);
                break;
            case UserSelectionDocument::class:
                $content = $this->buildFileForUserSelectionList($attachement);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('The type "%s" is not referenced on the app', $type));
                break;
        }

        if ($attachement->getFormatType() === ExportNotice::FORMAT_PDF){
            return  $this->knpSnappy->getOutputFromHtml($content,[
                'orientation'       => 'Portrait',
                'page-size'         => 'A4',
                'encoding'          => 'UTF-8',
                'header-line'       => false,
                'footer-right'      => '[page]',
                'title'             => 'Catalogue Bpi - Export de notices',
            ]);
        }

        return $content;
    }

    private function buildFileForSearch(ExportNotice $attachement) :string
    {
        $noticeWrapper  =  null;
        try {
            $noticeWrapper  = $this->getNoticeWrapper($attachement);
        } catch (\Exception $e) {}

        $twig = $attachement->getTemplateType() == ExportNotice::FORMAT_TEXT ? "search/print.txt.twig" : "search/print.pdf.twig";
        return  $this->templating->render($twig, [
            'toolbar'               => ObjSearch::class,
            'attachement'           => $attachement,
            'isPrintLong'           => !$attachement->isShortFormat(),
            'includeImage'          => $attachement->isImage(),
            'printNoticeWrapper'    => $noticeWrapper
        ]);
    }

    private function buildFileForNotice(ExportNotice $attachement): string
    {
        $permalink = null;
        try {
            $permalink  = $attachement->getNotices();
            $object     = $this->noticeProvider->getNotice($permalink, !$attachement->isShortFormat()?:self::SHORT_PRINT);
        } catch(\Exception $e) {
           throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }

        $twig = $attachement->getTemplateType() == ExportNotice::FORMAT_TEXT ? "notice/print.txt.twig" : "notice/print.pdf.twig";
        return  $this->templating->render($twig, [
            'toolbar'           => Notice::class,
            'attachement'       => $attachement,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'notice'            => $object->getNotice()
        ]);
    }

    private function buildFileForAuthority(ExportNotice $attachement) :string
    {
        $permalink = null;
        try {
            $permalink          = $attachement->getAuthorities();
            $object             = $this->noticeAuthority->getAuthority($permalink, !$attachement->isShortFormat()?:self::SHORT_PRINT);
            $relatedDocuments   = $this->noticeAuthority->getSubjectNotice($object->getId());
            $noticeAuthors      = $this->noticeAuthority->getAuthorsNotice($object->getId());

        } catch(\Exception $e) {
            throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }

        return  $this->templating->render("authority/print.".$attachement->getTemplateType() .".twig", [
            'toolbar'           => Authority::class,
            'attachement'       => $attachement,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'notice'            => $object,
            'relatedDocuments'  => $relatedDocuments,
            'noticeAuthors'     => $noticeAuthors,
        ]);
    }

    private function buildFileForIndice(ExportNotice $attachement) :string
    {
        $permalink = null;
        try {
            $permalink          = $attachement->getIndices();
            $object             = $this->noticeAuthority->getIndiceCdu($permalink);
            $relatedDocuments   = $this->noticeAuthority->getSubjectIndice($object->getId());
        } catch(NoResultException|\Exception $e) {
            throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }
        $twig = $attachement->getTemplateType() == ExportNotice::FORMAT_TEXT ? "indice/print.txt.twig" : "indice/print.pdf.twig";
        return  $this->templating->render($twig, [
            'toolbar'           => Authority::class,
            'attachement'       => $attachement,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'notice'            => $object,
            'relatedDocuments'  => $relatedDocuments
        ]);
    }

    private function buildFileForUserSelectionList(ExportNotice $attachement)  :string
    {
        $noticeWrapper =null;
        try {
            $noticeWrapper = $this->getNoticeWrapper($attachement);
        } catch (\Exception|NoResultException $e) {}

        $twig = $attachement->getTemplateType() == ExportNotice::FORMAT_TEXT ? "user/print.txt.twig" : "user/print.pdf.twig";
        return  $this->templating->render($twig, [
            'toolbar'           => ObjSearch::class,
            'attachement'       => $attachement,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'printNoticeWrapper'=> $noticeWrapper
        ]);
    }

    private function getNoticeWrapper(ExportNotice $attachment):PrintNoticeWrapper
    {
        $payload = new PrintNoticeWrapper();
        $shortType = !$attachment->isShortFormat() ?: self::SHORT_PRINT;
        $listUnavailablePermalinks = ['notices'=> [],'autorites'=> [], 'indices'=> []];

        if($this->session->has('ItemsNotAvailable')) {
		    $listUnavailablePermalinks += json_decode($this->session->get('ItemsNotAvailable'), true);
        }

        if($attachment->hasAuthorities()) {
            foreach ($attachment->getAuthoritiesArray() as $value) {
                try {
                    if (!in_array($value, $listUnavailablePermalinks['autorites'])) {
                        $payload->addNoticeAuthority($this->noticeAuthority->getAuthority($value, $shortType));
                    }
                } catch (NoResultException $e) {
                    // we ignore autorities when we get 410
                }
            }
        }

        if($attachment->hasNotices()) {
            foreach ($attachment->getNoticesArray() as $value) {
                try {
                    if (!in_array($value, $listUnavailablePermalinks['notices'])) {
                        $payload->addNoticeOnShelves($this->noticeProvider->getNotice($value, $shortType)->getNotice());
                    }
                } catch (\Exception $e) {
                    // we ignore notices when we get 410
                }
            }
        }

        if($attachment->hasIndices()) {
            foreach ($attachment->getIndicesArray() as $value){
                try{
                    if(!in_array($value, $listUnavailablePermalinks['indices'])) {
                        $payload->addNoticeIndice($this->noticeAuthority->getIndiceCdu($value));
                    }
                } catch(NoResultException $exception){
                    // we ignore indices when we get 410
                }
            }
        }
        ;
        return $payload;
    }
}

