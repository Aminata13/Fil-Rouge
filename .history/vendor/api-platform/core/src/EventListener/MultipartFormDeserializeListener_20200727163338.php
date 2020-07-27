<?php
use ApiPlatform\Core\EventListener\DeserializeListener as DecoratedListener;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Updates the entity retrieved by the data provider depending on request content-type.
 *
 * If form given (multipart/form-data), data is setted with the request files
 * Otherwise, data is setted with the request body
 */
final class MultipartFormDeserializeListener
{
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    /**
     * @var SerializerContextBuilderInterface
     */
    private $serializerContextBuilder;

    /**
     * @var DecoratedListener
     */
    private $decorated;

    public function __construct(DenormalizerInterface $denormalizer, SerializerContextBuilderInterface $serializerContextBuilder, DecoratedListener $decorated)
    {
        $this->denormalizer             = $denormalizer;
        $this->serializerContextBuilder = $serializerContextBuilder;
        $this->decorated                = $decorated;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (
            $request->isMethodSafe(false)
            || $request->isMethod(Request::METHOD_DELETE)
            || !($attributes = RequestAttributesExtractor::extractAttributes($request))
            || !$attributes['receive']
            || ('' === ($requestContent = $request->getContent()) && $request->isMethod(Request::METHOD_PUT))
        ) {
            return;
        }

        if ('form' === $request->getContentType()) {
            $this->denormalizeFormRequest($request);
        } else {
            $this->decorated->onKernelRequest($event);
        }
    }

    private function denormalizeFormRequest(Request $request)
    {
        if (!$attributes = RequestAttributesExtractor::extractAttributes($request)) {
            return;
        }

        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attributes);

        $objectToPopulate = $request->attributes->get('data');
        if (null !== $objectToPopulate) {
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $objectToPopulate;
        }

        $data         = [];
        $requestFiles = $request->files;
        foreach ($requestFiles as $key => $requestFile) {
            $data[$key] = $requestFile;
        }

        $request->attributes->set(
            'data',
            $this->denormalizer->denormalize(
                $data, $attributes['resource_class'], null, $context
            )
        );
    }
}