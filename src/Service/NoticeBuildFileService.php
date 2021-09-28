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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

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
     * NoticeBuildFileService constructor.
     * @param NoticeProvider $noticeProvider
     * @param NoticeAuthorityProvider $noticeAuthority
     * @param \Knp\Snappy\Pdf $knpSnappy
     * @param Environment $templating
     */
    public function __construct(
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $noticeAuthority,
         \Knp\Snappy\Pdf $knpSnappy,
        Environment $templating
        )
    {
        $this->noticeProvider   = $noticeProvider;
        $this->noticeAuthority  = $noticeAuthority;
        $this->knpSnappy        = $knpSnappy;
        $this->templating       = $templating;
    }

    /**
     * @param ExportNotice $attachement
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildFileForSearch(ExportNotice $attachement)
    {
        $noticeWrapper  =  null;
        try {
            $noticeWrapper  = $this->getNoticeWrapper($attachement);

        } catch (\Exception $e) {
            /**
             * lunch an custom exception
             */
        }

        return  $this->templating->render(
            "search/index.".$attachement->getTemplateType().".twig",
            [
                'toolbar'=> ObjSearch::class,
                'isPrintLong'   => !$attachement->isShortFormat(),
                'includeImage'  => $attachement->isImage(),
                'printNoticeWrapper'=> $noticeWrapper
            ]
        );
    }

    /**
     * @param ExportNotice $attachement
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildFileForNotice(ExportNotice $attachement): string
    {
        $permalink = null;
        try {
            $permalink = $attachement->getNotices();
            $object = $this->noticeProvider->getNotice($permalink, !$attachement->isShortFormat()?:self::SHORT_PRINT);
        } catch(\Exception $e) {
           throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }

        return  $this->templating->render("notice/print.".$attachement->getTemplateType() .".twig", [
            'toolbar'           => Notice::class,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'notice'            => $object->getNotice()
        ]);
    }

    /**
     * @param ExportNotice $attachement
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildFileForAuthority(ExportNotice $attachement)
    {
        $permalink = null;
        try {
            $permalink = $attachement->getAuthorities();
            $object             = $this->noticeAuthority->getAuthority($permalink, !$attachement->isShortFormat()?:self::SHORT_PRINT);
            $relatedDocuments   = $this->noticeAuthority->getSubjectNotice($object->getId());
            $noticeAuthors      = $this->noticeAuthority->getAuthorsNotice($object->getId());

        } catch(\Exception $e) {
            throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }

        return  $this->templating->render("authority/print.".$attachement->getTemplateType() .".twig", [
            'toolbar'           => Authority::class,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'notice'            => $object,
            'relatedDocuments'  => $relatedDocuments,
            'noticeAuthors'     => $noticeAuthors,
        ]);
    }
    /**
     * @param ExportNotice $attachement
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildFileForIndice(ExportNotice $attachement)
    {
        $permalink = null;
        try {
            $permalink          = $attachement->getIndices();
            $object             = $this->noticeAuthority->getIndiceCdu($permalink);
            $relatedDocuments   = $this->noticeAuthority->getSubjectIndice($object->getId());
        } catch(NoResultException|\Exception $e) {
            throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }

        return  $this->templating->render("indice/print.".$attachement->getTemplateType() .".twig", [
            'toolbar'           => Authority::class,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'notice'            => $object,
            'relatedDocuments'  => $relatedDocuments
        ]);
    }

    /**
     * @param ExportNotice $attachement
     * @param string $type
     * @return mixed|string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function buildFile(ExportNotice $attachement, string $type)
    {
        $content = $this->buildContent($attachement, $type);

        $filename = 'vos-references_'.date('Y-m-d_h-i-s');

        switch ($attachement->getFormatType()){
            case 'txt':
                return new Response(
                $content, 200, [
                    'Content-Type' => 'application/force-download; charset=utf-8',
                    'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"',
                ]
            );
            case 'html':
                return new Response($content);
            case 'pdf':
                return new PdfResponse($content,$filename.".pdf");
            default:
                return $content;
        }
    }

    /**
     * @param ExportNotice $attachement
     * @param string $type
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
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

        if ($attachement->getFormatType() === 'pdf'){
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


    /**
     * @param ExportNotice $attachement
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildFileForUserSelectionList(ExportNotice $attachement)
    {
        $noticeWrapper =null;
        try {
            $noticeWrapper = $this->getNoticeWrapper($attachement);

        } catch (\Exception|NoResultException $e) {
        }

        return  $this->templating->render(
            "user/print.".$attachement->getTemplateType().".twig",
            [
                'toolbar'           => ObjSearch::class,
                'isPrintLong'       => !$attachement->isShortFormat(),
                'includeImage'      => $attachement->isImage(),
                'printNoticeWrapper'=> $noticeWrapper
            ]
        );
    }

    /**
     * @param ExportNotice $attachment
     * @return PrintNoticeWrapper
     */
    private function getNoticeWrapper(ExportNotice $attachment):PrintNoticeWrapper
    {
        $permalinkN = \json_decode($attachment->getNotices());
        $permalinkA = \json_decode($attachment->getAuthorities());
        $permalinkI = \json_decode($attachment->getIndices());
        $i=[];
        $n=[];
        $a=[];
        foreach ($permalinkA as $value){
            $a[] = $this->noticeAuthority->getAuthority($value, !$attachment->isShortFormat()?:self::SHORT_PRINT);
        }
        foreach ($permalinkN as $value){
            $n[] = $this->noticeProvider->getNotice($value, !$attachment->isShortFormat()?:self::SHORT_PRINT)->getNotice();
        }

        foreach ($permalinkI as $value){
            $i[] = $this->noticeAuthority->getIndiceCdu($value);
        }

        return new PrintNoticeWrapper([],  $a, $n, $i);
    }
}

