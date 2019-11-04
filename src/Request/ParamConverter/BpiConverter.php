<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: afoullous
 * Date: 30/10/19
 * Time: 10:37
 */

namespace App\Request\ParamConverter;


use App\Entity\UserSelectionDocument;
use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\IndiceCdu;
use App\Model\Notice;
use App\Model\NoticeThemed;
use App\Model\Search\SearchQuery;
use App\Service\MailSenderService;
use App\Service\NavigationService;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use JMS\Serializer\SerializerInterface;

class BpiConverter implements ParamConverterInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var \Twig_Environment
     */
    private $templating;
    /**
     * @var NavigationService
     */
    private $navigationService;
    /**
     * @var NoticeAuthorityProvider
     */
    private $authorityProvider;

    /**
     * NoticeController constructor.
     */
    public function __construct(
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $authorityProvider,
        SerializerInterface $serializer,
        SessionInterface $session,
        \Twig_Environment $templating,
        NavigationService $navigationService

    ) {
        $this->noticeProvider = $noticeProvider;
        $this->serializer = $serializer;
        $this->session = $session;
        $this->templating = $templating;
        $this->navigationService = $navigationService;
        $this->authorityProvider = $authorityProvider;
    }

    /**
     * @return bool|string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        try{
            $object     = $this->buildObject( $request,  $configuration);
        }
        catch(NoResultException $e){
            return $this->templating->render('common/error.html.twig');
        }

        $request->attributes->set($configuration->getName(), $object);
    }

    /**
     * @return bool|void
     */
    public function supports(ParamConverter $configuration)
    {
        if (!$configuration->getClass()
            || in_array($configuration->getClass(),[UserSelectionDocument::class, SessionInterface::class, MailSenderService::class])
        )
        {
            return false;
        }

        return true;
    }

    /**
     * @return Authority|IndiceCdu|NoticeThemed|NavigationService|null|object
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildObject(Request $request, ParamConverter $configuration)
    {
        $permalink = $request->get('permalink');

       if ($configuration->getClass() === NoticeThemed::class){
           return $this->noticeProvider->getNotice($permalink);
       }

       if ($configuration->getClass() === Authority::class){
           return $this->authorityProvider->getAuthority($permalink);
       }
       if ($configuration->getClass() === IndiceCdu::class){
           return $this->authorityProvider->getIndiceCdu($permalink);
       }

       if ($configuration->getClass() === NavigationService::class){
           $object = $request->attributes->get('notice');

           return $this->buildNavigationService($object, $request);
       }
    }

    /**
     * @param $request
     * @return NavigationService|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildNavigationService($object, $request){
        if ($object instanceof NoticeThemed && $searchToken = $request->get('searchToken')){
            return $this->getNavigationService($object->getNotice()->getPermalink(), $searchToken,  $this->session->get($searchToken, ''), $object->getNotice()->isOnLine() ? Notice::ON_LIGNE : Notice::ON_SHELF);
        }
        if ($object instanceof Authority && $searchToken = $request->get('searchToken')){
            return $this->getNavigationService($object->getPermalink(), $searchToken,  $this->session->get($searchToken, ''),Authority::class);
        }
        if ($object instanceof IndiceCdu && $searchToken = $request->get('searchToken')){
            return $this->getNavigationService($object->getPermalink(), $searchToken,  $this->session->get($searchToken, ''), IndiceCdu::class);
        }

        return null;
    }

    /**
     * @param string $permalink
     * @param string $searchToken
     * @param string $searchTokenValue
     * @param $classType
     * @return NavigationService|null
     */
    private function getNavigationService(string $permalink,string $searchToken,  string $searchTokenValue, $classType):?NavigationService
    {
        try {
            $object = $this->serializer->deserialize(
                $searchTokenValue,
                SearchQuery::class, 'json'
            );
        }catch (\Exception $e){
            return null;
        }

        return $this->navigationService->build(
            $permalink,
            $object,
            $searchToken,
            $classType
        );
    }
}
