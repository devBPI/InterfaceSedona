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
use \Exception;

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
    public function __construct(
        NoticeBuildFileService $buildFileContent,
        NavigationService $navigationService
    ) {
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
		if(null != $notice->getNotice()->getContentsTable()) {
			try {
				$tableMatieres = $this->xsltTransform($notice->getNotice()->getContentsTable(), "../templates/xslt/table-matieres.xsl");
			} catch(Exception $e) {
				$logger->error("Erreur durant le traitement de la table des matières : ".$e->getMessage()." : ".$notice->getNotice()->getContentsTable());
			}
		}
		$quatrieme = null;
		if(null != $notice->getNotice()->getFourth()) {
			try {
				$quatrieme = $this->xsltTransform($notice->getNotice()->getFourth(), "../templates/xslt/quatrieme.xsl");
			} catch(Exception $e) {
				$logger->error("Erreur durant le traitement de le quatrième de couverture : ".$e->getMessage()." : ".$notice->getNotice()->getContentsTable());
			}
		}

		$printRoute = $this->generateUrl('record_bibliographic_pdf', [
                'permalink' => $notice->getNotice()->getPermalink(),
                'format' => ExportNotice::FORMAT_PDF
            ]);
		$page = 1;
		$rows = SearchQuery::ROWS_DEFAULT;
		try {
			$navigation = $this->navigationService->buildNotices($notice->getNotice());
			$page = (int) ceil($navigation->getCurrentIndex()/$this->navigationService->getSearchRows());
		} catch (\Exception $e) {
			$logger->error('Navigation failed for notice '.$notice->getPermalink(). ' : '.$e->getMessage());
			$navigation = null;
		}

        $rows = $this->navigationService->getSearchRows();
        $sort = $this->navigationService->getSort();
		return $this->render('notice/index.html.twig', [
			'object'            => $notice,
			'quatrieme'         => $quatrieme,
			'tableMatieres'     => $tableMatieres,
			'toolbar'           => Notice::class,
            'isNotice'          => true,
			'navigation'        => $navigation,
			'printRoute'        => $printRoute,
			'page'              => $page,
			'rows'              => $rows,
            'sort'              => $sort,
			'seeAll'            => $this->navigationService->getSeeAll(),
		]);
	}

    /**
     * @Route("/print/document.{format}/{permalink}", methods={"GET","HEAD"}, name="record_bibliographic_pdf", requirements={"permalink"=".+", "format"="html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function printAction(Request $request, string $format= ExportNotice::FORMAT_PDF) :Response
    {
        try {
            $sendAttachement = ExportNotice::createFromRequest($request, $format)
                ->setNotices($request->get('permalink'))
            ;
        } catch(NoResultException $e) {
            return $this->render('common/error.html.twig');
        }

        return $this->buildFileContent->buildFile($sendAttachement,Notice::class);
    }

    private function xsltTransform(string $baseXml, string $xslUrl) :?string
    {
        $simpleXml = new SimpleXMLElement($baseXml);
        $xmlTxt =  $simpleXml->asXML();

        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->loadXML($xmlTxt);

        $xsl = new DOMDocument('1.0', 'utf-8');
        $xsl->load($xslUrl);

        $xslt = new XSLTProcessor();
        $xslt->importStylesheet($xsl);

        $result = $xslt->transformToXML($xml);
        return $result!== false ? $result : null ;
    }
}
