<?php
declare(strict_types=1);

namespace App\Controller;

use \DOMDocument;
use \SimpleXMLElement;
use \XSLTProcessor;
use App\Model\Exception\NoResultException;
use App\Model\Form\ExportNotice;
use App\Model\Notice;
use App\Model\NoticeThemed;
use App\Model\Search\SearchQuery;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NoticeController
 * @package App\Controller
 */
final class NoticeController extends AbstractController
{
	/**
	 * @var NoticeBuildFileService
	 */
	private $buildFileContent;
	/**
	 * @var NavigationService
	 */
	private $navigationService;


	/**
	 * NoticeController constructor.
	 * @param NoticeBuildFileService $buildFileContent
	 * @param NavigationService $navigationService
	 */
	public function __construct(NoticeBuildFileService $buildFileContent, NavigationService $navigationService)
	{
		$this->buildFileContent = $buildFileContent;
		$this->navigationService = $navigationService;
	}

	/**
	 * @Route("/{parcours}/document/{permalink}", methods={"GET","HEAD"}, name="record_bibliographic_parcours", requirements={"permalink"=".+"})
	 * @Route("/document/{permalink}", methods={"GET","HEAD"}, name="record_bibliographic", requirements={"permalink"=".+"})
	 * @param NoticeThemed $notice
	 * @param LoggerInterface $logger
	 * @return Response
	 */
 	public function bibliographicRecordAction(NoticeThemed $notice, LoggerInterface $logger)
	{
		$tableMatieres = null;
		if(null != $notice->getNotice()->getContentsTable())
		{
			$simpleXml = new SimpleXMLElement($notice->getNotice()->getContentsTable());
			$xmlTxt =  $simpleXml->asXML();
			$xml = new DOMDocument('1.0', 'utf-8');
			$xml->loadXML($xmlTxt);

			$xslHeadUrl = "../templates/xslt/table-matieres.xsl";
			$xsl = new DOMDocument('1.0', 'utf-8');
			$xsl->load($xslHeadUrl);
			$xslt = new XSLTProcessor();
			$xslt->importStylesheet($xsl);
			$tableMatieres = $xslt->transformToXML($xml);
		}
		$quatrieme = null;
		if(null != $notice->getNotice()->getFourth())
		{
			$quatrieme = "<div id=\"quatrieme\">".$notice->getNotice()->getFourth()."</div>";
		}

		$printRoute = $this->generateUrl(
			'record_bibliographic_pdf',
			[ 'permalink' => $notice->getNotice()->getPermalink(), 'format' => 'pdf' ]
        	);
		$page = 1;
		$rows = SearchQuery::ROWS_DEFAULT;
		try
		{
			$navigation = $this->navigationService->buildNotices($notice->getNotice());
			$page = (int) ceil($navigation->getCurrentIndex()/$this->navigationService->getSearchRows());
		}
		catch (\Exception $e)
		{
			$logger->error('Navigation failed for notice '.$notice->getPermalink(). ' : '.$e->getMessage());
			$navigation = null;
		}

		$rows = $this->navigationService->getSearchRows();
		return $this->render('notice/index.html.twig', [
			'object'            => $notice,
			'quatrieme'         => $quatrieme,
			'tableMatieres'     => $tableMatieres,
			'toolbar'           => Notice::class,
			'navigation'        => $navigation,
			'printRoute'        => $printRoute,
			'page'              => $page,
			'rows'              => $rows,
			'seeAll'            => $this->navigationService->getSeeAll(),
		]);
	}

    /**
     * @Route("/print/document.{format}/{permalink}", methods={"GET","HEAD"}, name="record_bibliographic_pdf", requirements={"permalink"=".+", "format"="html|pdf|txt"}, defaults={"format" = "pdf"})
     * @param Request $request
     * @param string $format
     * @return PdfResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function bibliographicRecordPDFAction(Request $request, $format='pdf')
    {
        try {
            $sendWithAttachement = (new ExportNotice())
                ->setNotices($request->get('permalink'))
                ->setImage($request->get('print-image', null) === 'print-image')
                ->setFormatType($format)
                ->setShortFormat($request->get('print-type', 'print-long') !== 'print-long')
            ;
        } catch(NoResultException $e) {
            return $this->render('common/error.html.twig');
        }

        return $this->buildFileContent->buildFile($sendWithAttachement,Notice::class);
    }
}
