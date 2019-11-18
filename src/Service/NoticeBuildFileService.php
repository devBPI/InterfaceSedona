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
        $noticeWrapper  =  null;
        try {
            $noticeWrapper  = $this->getNoticeWrapper($attachement->getNotices(), $attachement->getAuthorities(), $attachement->getIndices());

        }catch (\Exception $e){
            /**
             * lunch an custom exception
             */
        }

        return  $this->templating->render(
            "search/index.".$format.".twig",
            [
                'toolbar'=> ObjSearch::class,
                'isPrintLong'   => !$attachement->isShortFormat(),
                'includeImage'  => $attachement->isImage(),
                'printNoticeWrapper'=>$noticeWrapper
            ]
        );
    }

    /**
     * @param ExportNotice $attachement
     * @param string $format
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildFileForNotice(ExportNotice $attachement, string $format):string
    {
        $permalink = null;
        try{
            $permalink = $attachement->getNotices();
            $object = $this->noticeProvider->getNotice($permalink);
        }catch(\Exception $e){
           throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }

        return  $this->templating->render("notice/print.".$format .".twig", [
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
        $permalink = null;
        try{
            $permalink = $attachement->getAuthorities();
            $object             = $this->noticeAuthority->getAuthority($permalink);
            $relatedDocuments   = $this->noticeAuthority->getSubjectNotice($object->getId());
            $noticeAuthors      = $this->noticeAuthority->getAuthorsNotice($object->getId());

        }catch(\Exception $e){
            throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }

        return  $this->templating->render("authority/print.".$format .".twig", [
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
     * @param string $format
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildFileForIndice(ExportNotice $attachement, string $format)
    {
        $permalink = null;
        try{
            $permalink          = $attachement->getIndices();
            $object             = $this->noticeAuthority->getIndiceCdu($permalink);
        }catch(NoResultException|\Exception $e){
            throw new NotFoundHttpException(sprintf('the permalink %s not referenced', $permalink));
        }

        return  $this->templating->render("indice/print.".$format .".twig", [
            'toolbar'           => Authority::class,
            'isPrintLong'       => !$attachement->isShortFormat(),
            'includeImage'      => $attachement->isImage(),
            'notice'            => $object,
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
    public function buildContent(ExportNotice $attachement, string $type, string $format):string
    {
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
                $content =  $this->buildFileForIndice($attachement, $format);
                break;
            case UserSelectionDocument::class:
                $content = $this->buildFileForUserSelectionList($attachement, $format);
                break;
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
     * @param ExportNotice $attachement
     * @param string $format
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildFileForUserSelectionList(ExportNotice $attachement, string $format)
    {
        $noticeWrapper =null;
        try {
            $noticeWrapper = $this->getNoticeWrapper($attachement->getNotices(), $attachement->getAuthorities(), $attachement->getIndices());

        }catch (\Exception|NoResultException $e){
           // throw new NotFoundHttpException();
        }

        return  $this->templating->render(
            "user/print.".$format.".twig",
            [
                'toolbar'           => ObjSearch::class,
                'isPrintLong'       => !$attachement->isShortFormat(),
                'includeImage'      => $attachement->isImage(),
                'printNoticeWrapper'=> $noticeWrapper
            ]
        );
    }

    /**
     * @param string|null $notice
     * @param string|null $authority
     * @param string|null $indices
     * @return PrintNoticeWrapper
     */
    private function getNoticeWrapper(string $notice=null, string $authority=null, string $indices=null):PrintNoticeWrapper
    {
        $permalinkN = \json_decode($notice);
        $permalinkA = \json_decode($authority);
        $permalinkI = \json_decode($indices);
        $i=[];
        $n=[];
        $a=[];
        foreach ($permalinkA as $value){
            $a[] = $this->noticeAuthority->getAuthority($value);
        }
        foreach ($permalinkN as $value){
            $n[] = $this->noticeProvider->getNotice($value)->getNotice();
        }

        foreach ($permalinkI as $value){
            $i[] = $this->noticeAuthority->getIndiceCdu($value);
        }

        return new PrintNoticeWrapper([],  $a, $n, $i);
    }
}

