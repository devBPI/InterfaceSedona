<?php
declare(strict_types=1);

namespace App\Request\ParamConverter;


use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\IndiceCdu;
use App\Model\Notice;
use App\Model\NoticeThemed;
use App\Model\Search\SearchQuery;
use App\Service\NavigationService;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;
use JMS\Serializer\SerializerInterface;
use ReflectionClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class BpiConverter
 * @package App\Request\ParamConverter
 */
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
     * @var NoticeProvider
     */
    private $noticeProvider;

    /**
     * BpiConverter constructor.
     * @param NoticeProvider $noticeProvider
     * @param NoticeAuthorityProvider $authorityProvider
     * @param SerializerInterface $serializer
     * @param SessionInterface $session
     * @param \Twig_Environment $templating
     * @param NavigationService $navigationService
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
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool|string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        try {
            $object = $this->buildObject($request, $configuration);
        } catch (NoResultException $e) {
            throw new NotFoundHttpException();
        }

        $request->attributes->set($configuration->getName(), $object);
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return Authority|IndiceCdu|NoticeThemed|NavigationService|null|object
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildObject(Request $request, ParamConverter $configuration)
    {
        $permalink = $request->get('permalink');

        $object = $request->get($configuration->getName());
        if ($configuration->getClass() === NoticeThemed::class &&
            (!$object instanceof NoticeThemed || $object->getNotice()->getPermalink() !== $permalink)) {
            $object = $this->noticeProvider->getNotice($permalink);
        }

        if ($configuration->getClass() === Authority::class &&
            (!$object instanceof Authority || $object->getPermalink() !== $permalink)) {
            $object = $this->authorityProvider->getAuthority($permalink);
        }
        if ($configuration->getClass() === IndiceCdu::class &&
            (!$object instanceof IndiceCdu || $object->getPermalink() !== $permalink)) {
            $object = $this->authorityProvider->getIndiceCdu($permalink);
        }

        if (
            $configuration->getClass() === NavigationService::class &&
            (!$object instanceof NavigationService || $object->getHash() !== $request->get('searchToken'))
        ) {
            $object = $this->buildNavigationService($request->attributes->get('notice'), $request);
        }

        return $object;
    }

    /**
     * @param BpiConverterInterface $object
     * @param Request $request
     * @return NavigationService|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function buildNavigationService(BpiConverterInterface $object, Request $request): ?NavigationService
    {
        $searchToken = $request->get('searchToken', '');
        $class = get_class($object);
        if ($object instanceof NoticeThemed) {
            $class = $object->getNotice()->isOnLine() ? Notice::ON_LIGNE : Notice::ON_SHELF;
        }

        return $this->getNavigationService(
            $object->getPermalink(),
            $searchToken,
            $this->session->get($searchToken, ''),
            $class
        );
    }

    /**
     * @param string $permalink
     * @param string $searchToken
     * @param string $searchTokenValue
     * @param $classType
     * @return NavigationService|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function getNavigationService(
        string $permalink,
        string $searchToken,
        string $searchTokenValue,
        $classType
    ): ?NavigationService {
        try {
            $object = $this->serializer->deserialize(
                $searchTokenValue,
                SearchQuery::class,
                'json'
            );
        } catch (\Exception $e) {
            return null;
        }

        return $this->navigationService->build(
            $permalink,
            $object,
            $searchToken,
            $classType
        );
    }

    /**
     * @param ParamConverter $configuration
     * @return bool
     * @throws \ReflectionException
     */
    public function supports(ParamConverter $configuration): bool
    {
        if (empty($configuration->getClass()) || !class_exists($configuration->getClass())) {
            return false;
        }

        $class = new ReflectionClass($configuration->getClass());

        return $class->implementsInterface(BpiConverterInterface::class);
    }
}

