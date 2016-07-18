<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\NewsNotFoundException;
use AppBundle\Exception\NewsNotOwnedByGivenUserException;
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
        $response = $this->getResponseFromException($event->getException());
        if ($response) {
            $event->setResponse($response);
        }
    }

    /**
     * @param \Exception $exception
     *
     * @return Response|null
     */
    private function getResponseFromException(\Exception $exception)
    {
        switch (true) {
            case $exception instanceof NewsNotFoundException:
                return new Response('', Response::HTTP_NOT_FOUND);
                break;
            case $exception instanceof NewsNotOwnedByGivenUserException:
                return new Response('', Response::HTTP_FORBIDDEN);
                break;
        }

        return null;
    }
}