<?php
declare(strict_types=1);

namespace App\Service;


use App\Entity\UserSelectionDocument;
use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\Form\ExportInterface;
use App\Model\Form\ExportNotice;
use App\Model\Form\SendByMail;
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
     * @var Environment
     */
    private $templating;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $noticeAuthority,
        \Knp\Snappy\Pdf $knpSnappy,
        Environment $templating,
        SessionInterface $session
        )
    {
        $this->noticeProvider   = $noticeProvider;
        $this->noticeAuthority  = $noticeAuthority;
        $this->knpSnappy        = $knpSnappy;
        $this->templating       = $templating;
        $this->session = $session;
    }


    public function buildFile(ExportInterface $attachement, string $type) :Response
    {
        $content = $this->buildContent($attachement, $type);

        $filename = 'vos-references_'.date('Y-m-d_h-i-s');

        switch ($attachement->getFormatType()){
            case ExportNotice::FORMAT_TEXT:
                $headers = [];
                if (!$attachement->isDebug()) {
                    $headers =  [
                        'Content-Type' => 'application/force-download; charset=utf-8',
                        'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"',
                    ];
                }
                return new Response($content, 200, $headers );
            case ExportNotice::FORMAT_PDF:
                if ($attachement->isDebug()) {
                    return new Response($content);
                } else {
                    return new PdfResponse($content, $filename . ".pdf");
                }
            case ExportNotice::FORMAT_HTML:
            case ExportNotice::FORMAT_EMAIL:
            default:
                return new Response($content);
        }
    }

    public function buildContent(ExportInterface $attachement, string $type): string
    {

        switch ($type){
            case UserSelectionDocument::class:
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
            default:
                throw new \InvalidArgumentException(sprintf('The type "%s" is not referenced on the app', $type));
        }

        if ($attachement->getFormatType() === ExportNotice::FORMAT_PDF && !$attachement->isDebug()){
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

    private function buildFileForSearch(ExportInterface $attachement) :string
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
            'printNoticeWrapper'    => $noticeWrapper
        ]);
    }

    private function buildFileForNotice(ExportInterface $attachement): string
    {
        $permalink = null;
        try {
            $permalink  = $attachement->getNotices();
            $object     = $this->noticeProvider->getNotice($permalink, ($attachement->isShortFormat() ? self::SHORT_PRINT : ExportNotice::PRINT_LONG));
        } catch(\Exception $e) {
           throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }

        $twig = $attachement->getTemplateType() == ExportNotice::FORMAT_TEXT ? "notice/print.txt.twig" : "notice/print.pdf.twig";
        return  $this->templating->render($twig, [
            'toolbar'           => Notice::class,
            'attachement'       => $attachement,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'notice'            => $object->getNotice()
        ]);
    }

    private function buildFileForAuthority(ExportInterface $attachement) :string
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

        $twig = $attachement->getTemplateType() == ExportNotice::FORMAT_TEXT ? "authority/print.txt.twig" : "authority/print.pdf.twig";
        return  $this->templating->render($twig, [
            'toolbar'           => Authority::class,
            'attachement'       => $attachement,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'notice'            => $object,
            'relatedDocuments'  => $relatedDocuments,
            'noticeAuthors'     => $noticeAuthors,
        ]);
    }

    private function buildFileForIndice(ExportInterface $attachement) :string
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
            'notice'            => $object,
            'relatedDocuments'  => $relatedDocuments
        ]);
    }

    private function getNoticeWrapper(ExportInterface $attachment):PrintNoticeWrapper
    {
        $payload = new PrintNoticeWrapper();
        $nbNotice = 0;

        $shortType = $attachment->isShortFormat() ? self::SHORT_PRINT : ExportNotice::PRINT_LONG;
        $listUnavailablePermalinks = ['notices'=> [],'autorites'=> [], 'indices'=> []];

        if($this->session->has('ItemsNotAvailable')) {
		    $listUnavailablePermalinks += json_decode($this->session->get('ItemsNotAvailable'), true);
        }

        if($attachment->hasNotices()) {
            foreach ($attachment->getNoticesArray() as $value) {
                if (in_array($value, $listUnavailablePermalinks['notices'])) {
                    continue;
                }
                try {
                    $notice = $this->noticeProvider->getNotice($value, $shortType)->getNotice();
                    if ($notice instanceof Notice) {
                        $payload->addNoticeOnShelves($notice);
                        $nbNotice++;
                    }
                } catch (\Exception $e) {
                    // we ignore notices when we get 410
                }
                if ($nbNotice >= PrintNoticeWrapper::MAX_NOTICE) {
                    return $payload;
                }
            }
        }

        if($attachment->hasAuthorities()) {
            foreach ($attachment->getAuthoritiesArray() as $value) {
                if (in_array($value, $listUnavailablePermalinks['autorites'])) {
                    continue;
                }
                try {
                    $payload->addNoticeAuthority($this->noticeAuthority->getAuthority($value, $shortType));
                    $nbNotice++;
                } catch (NoResultException $e) {
                    // we ignore autorities when we get 410
                }
                if ($nbNotice >= PrintNoticeWrapper::MAX_NOTICE) {
                    return $payload;
                }
            }
        }

        if($attachment->hasIndices()) {
            foreach ($attachment->getIndicesArray() as $value) {
                if(!in_array($value, $listUnavailablePermalinks['indices'])) {
                    continue;
                }
                try{
                    $payload->addNoticeIndice($this->noticeAuthority->getIndiceCdu($value));
                    $nbNotice++;
                } catch(NoResultException $exception){
                    // we ignore indices when we get 410
                }
                if ($nbNotice >= PrintNoticeWrapper::MAX_NOTICE) {
                    return $payload;
                }
            }
        }

        return $payload;
    }
}

