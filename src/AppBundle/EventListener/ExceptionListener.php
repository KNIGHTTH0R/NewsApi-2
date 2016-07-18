<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\NewsNotFoundException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExceptionListener
 */
class ExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof NewsNotFoundException) {
            $response = new Response('', Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
        }
    }
}