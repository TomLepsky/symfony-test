<?php

namespace App\Subscriber;

use App\Helper\ResponseBag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly SerializerInterface $serializer) {}

    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::VIEW => [
                ['serialize', 0]
            ]
        ];
    }

    public function serialize(ViewEvent $event) : void
    {
        if ($event->getRequest()->getContentTypeFormat() === 'json') {
            /** @var ResponseBag $responseBag */
            if (($responseBag = $event->getControllerResult()) instanceof  ResponseBag) {
                $context = [AbstractObjectNormalizer::SKIP_NULL_VALUES => true];
                if (!empty($serializeGroups = $responseBag->getSerializeGroups())) {
                    $context['groups'] = $serializeGroups;
                }
                $serializedContent = $this->serializer->serialize($responseBag->getData(), 'json', $context);

                $response = new Response();
                $response->setContent($serializedContent)
                    ->setStatusCode($responseBag->getStatusCode())
                    ->headers->add(['Content-Type' => 'Application/json']);

                $event->setResponse($response);
            }
        }
    }
}