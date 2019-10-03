<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 02/10/19
 * Time: 11:48
 */

namespace App\Service;


use App\Model\Authority;
use App\Model\From\ExportNotice;
use App\Model\IndiceCdu;
use App\Model\Notice;
use App\Model\Search\ObjSearch;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;
use App\Utils\PrintNoticeWrapper;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Response;

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
    private $noticeAuhtority;
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
     * @param NoticeAuthorityProvider $noticeAuhtority
     * @param \Knp\Snappy\Pdf $knpSnappy
     * @param \Twig_Environment $templating
     */
    public function __construct(
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $noticeAuhtority,
         \Knp\Snappy\Pdf $knpSnappy,
        \Twig_Environment $templating
        )
    {
        $this->noticeProvider   = $noticeProvider;
        $this->noticeAuhtority  = $noticeAuhtority;
        $this->knpSnappy        = $knpSnappy;
        $this->templating = $templating;
    }

    /**
     * @param ExportNotice $attachement
     * @param string $format
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildFileForSearch(ExportNotice $attachement, string $format)
    {
        try {
            $authorities = [];
            $notices = [];

            parse_str(urldecode($attachement->getAuthorities()), $authorities);
            parse_str(urldecode($attachement->getNotices()), $notices);

            $attachement->setObject('Recherche des notices');
        }catch (\Exception $e){

        }
        return  $this->templating->render(
            "search/index.".$format.".twig",
            [
                'toolbar'=> ObjSearch::class,
                'isPrintLong'   => !$attachement->isShortFormat(),
                'includeImage'  => $attachement->isImage(),
                'printNoticeWrapper'=> $this->getPrintNoticeWrapper($authorities+$notices)
            ]
        );
    }
    private function buildFileForNotice(ExportNotice $attachement, string $format)
    {
        try{
            $permalink = $attachement->getNotices();
            $object = $this->noticeProvider->getNotice($permalink);

        }catch(\Exception $e){
            /**
             * catch and handle exception to tell
             */
        }

        return  $this->templating->render("record/bibliographic.".$format .".twig", [
            'toolbar'           => Notice::class,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'notice'            => $object->getNotice(),
            'noticeThemed'      => $object->getNoticesSameTheme(),
        ]);
    }

    /**
     * @param ExportNotice $attachement
     * @param string $format
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildFileForAuthority(ExportNotice $attachement, string $format)
    {
        try{
            $permalink = $attachement->getAuthorities();
            $object             = $this->noticeAuhtority->getAuthority($permalink);
            $relatedDocuments   = $this->noticeAuhtority->getSubjectNotice($object->getId());
            $noticeAuthors      = $this->noticeAuhtority->getAuthorsNotice($object->getId());

        }catch(\Exception $e){
            /**
             * catch and handle exception to tell
             */
        }

        return  $this->templating->render("record/authority.".$format .".twig", [
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
     * @param string $type
     * @param string $format
     * @return mixed|string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function buildFile(ExportNotice $attachement, string $type, string $format)
    {
        $content = $this->buildContent($attachement, $type, $format);
        $filename = 'search-'.date('Y-m-d_h-i-s');

        switch ($format){
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
     * @param string $format
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function buildContent(ExportNotice $attachement, string $type, string $format){

        $content = '';

        switch ($type){
            case ObjSearch::class:
                $content =  $this->buildFileForSearch($attachement, $format);
                break;
            case Notice::class:
                $content =  $this->buildFileForNotice($attachement, $format);
                break;
            case Authority::class:
                $content =  $this->buildFileForAuthority($attachement, $format);
                break;
            case IndiceCdu::class:
            default:
                throw new \InvalidArgumentException(sprintf('The type "%s" is not referenced on the app', $type));
                break;
        }

        if ($format === 'pdf'){
            return  $this->knpSnappy->getOutputFromHtml($content);
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
                if (($notice=$this->noticeAuhtority->getAuthority($value)) instanceof Authority) {
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

}

