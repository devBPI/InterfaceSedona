<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 02/10/19
 * Time: 11:48
 */

namespace App\Service;


use App\Entity\UserSelectionDocument;
use App\Model\Authority;
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

/**
 * Class NoticeBuildFileService
 * @package App\Service
 */
class NoticeBuildFileService
{
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
     * @param \Twig_Environment $templating
     */
    public function __construct(
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $noticeAuthority,
         \Knp\Snappy\Pdf $knpSnappy,
        \Twig_Environment $templating
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
        try {
            $authorities = [];
            $notices = [];

            parse_str(urldecode($attachement->getAuthorities()), $authorities);
            parse_str(urldecode($attachement->getNotices()), $notices);

        }catch (\Exception $e){
            /**
             * lunch an custom exception
             */
        }
        return  $this->templating->render(
            "search/index.".$attachement->getFormatType().".twig",
            [
                'toolbar'=> ObjSearch::class,
                'isPrintLong'   => !$attachement->isShortFormat(),
                'includeImage'  => $attachement->isImage(),
                'printNoticeWrapper'=> $this->getPrintNoticeWrapper($authorities+$notices)
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
    private function buildFileForNotice(ExportNotice $attachement):string
    {
        try{
            $permalink = $attachement->getNotices();
            $object = $this->noticeProvider->getNotice($permalink);
        }catch(\Exception $e){

            throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }

        return  $this->templating->render("notice/print.".$attachement->getFormatType() .".twig", [
            'toolbar'           => Notice::class,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'notice'            => $object->getNotice(),
            'noticeThemed'      => $object->getNoticesSameTheme(),
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
        try{
            $permalink = $attachement->getAuthorities();
            $object             = $this->noticeAuthority->getAuthority($permalink);
            $relatedDocuments   = $this->noticeAuthority->getSubjectNotice($object->getId());
            $noticeAuthors      = $this->noticeAuthority->getAuthorsNotice($object->getId());

        }catch(\Exception $e){
            /**
             * catch and handle exception to tell
             */
        }

        return  $this->templating->render("authority/print.".$attachement->getFormatType() .".twig", [
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
        try{
            $permalink          = $attachement->getAuthorities();
            $object             = $this->noticeAuthority->getIndiceCdu($permalink);
        }catch(\Exception $e){
            /**
             * catch and handle exception to tell
             */
        }

        return  $this->templating->render("indice/print.".$attachement->getFormatType() .".twig", [
            'toolbar'           => Authority::class,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'notice'            => $object,
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
        $filename = 'search-'.date('Y-m-d_h-i-s');

        switch ($attachement->getFormatType()){
            case 'txt':
                return  new Response(
                $content, 200, [
                    'Content-Type' => 'application/force-download',
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
    public function buildContent(ExportNotice $attachement, string $type){

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
                "orientation"       => 'Portrait',
                'page-size'         => 'A4',
                'encoding'          => 'UTF-8',
//                'margin-top'        => '20mm',
//                "default-header"    => true,
//                'header-spacing'    => 3,
                "header-line"       => true,
                "header-left"      => "[title]",
                "header-center"     => "Page [page] of [toPage]"
            ]);
        }

        return $content;
    }
    /**
     * @param $array
     * @return PrintNoticeWrapper
     */
    private function getPrintNoticeWrapper($array):PrintNoticeWrapper
    {
        $noticeOnShelves = $noticeOnline=$noticeAuthority=$noticeIndice= [];
        if (array_key_exists('onshelves', $array)){
            foreach ($array['onshelves'] as $value){
                if (($notice= $this->noticeProvider->getNotice($value)->getNotice()) instanceof Notice) {
                    $noticeOnShelves[] = $notice;
                }
            }
        }
        if (array_key_exists('online', $array)){
            foreach ($array['online'] as $value){
                if (($notice=$this->noticeProvider->getNotice($value)->getNotice()) instanceof Notice){
                    $noticeOnline[] = $notice;
                }
            }
        }
        if (array_key_exists('authority', $array)){
            foreach ($array['authority'] as $value){
                if (($notice=$this->noticeAuthority->getAuthority($value)) instanceof Authority) {
                    $noticeAuthority[] = $notice;
                }
            }
        }
        if (array_key_exists('indice', $array)){
            foreach ($array['indice'] as $value){
                if (($notice=$this->noticeAuthority->getIndiceCdu($value)) instanceof IndiceCdu){
                    $noticeIndice[] =$notice;
                }
            }
        }

        return new PrintNoticeWrapper($noticeOnline, $noticeAuthority, $noticeOnShelves, $noticeIndice);
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
        $a = [];
        $n = [];
        try {
            $permalinkN = json_decode($attachement->getNotices());
            $permalinkA = json_decode($attachement->getAuthorities());

            foreach ($permalinkA as $value){
              $a[] = $this->noticeAuthority->getAuthority($value);
            }
            foreach ($permalinkN as $value){
              $n[] = $this->noticeProvider->getNotice($value)->getNotice();
            }

        }catch (\Exception $e){
            /**
             * lunch an custom exception
             */
        }

        return  $this->templating->render(
            "user/print.".$attachement->getFormatType().".twig",
            [
                'toolbar'           => ObjSearch::class,
                'isPrintLong'       => !$attachement->isShortFormat(),
                'includeImage'      => $attachement->isImage(),
                'printNoticeWrapper'=> new PrintNoticeWrapper([],  $a, $n)
            ]
        );

    }
}

