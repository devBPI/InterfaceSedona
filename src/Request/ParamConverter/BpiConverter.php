<?php
declare(strict_types=1);

namespace App\Request\ParamConverter;


use App\Model\Authority;
use App\Model\IndiceCdu;
use App\Model\NoticeThemed;
use App\Service\NavigationService;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;
use ReflectionClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BpiConverter
 * @package App\Request\ParamConverter
 */
class BpiConverter implements ParamConverterInterface
{
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
     */
    public function __construct(
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $authorityProvider

    ) {
        $this->noticeProvider = $noticeProvider;
        $this->authorityProvider = $authorityProvider;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool|string
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $object = $this->buildObject($request, $configuration);

        $request->attributes->set($configuration->getName(), $object);
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return Authority|IndiceCdu|NoticeThemed|NavigationService|null|object
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

        return $object;
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

