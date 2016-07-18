<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Form\Model;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class RegisterController extends FOSRestController
{
    use FormErrorsTrait;

    /**
     * @ApiDoc(
     *  description="Register user",
     *  input="FOS\UserBundle\Form\Type\RegistrationFormType",
     *  output="AppBundle\Entity\User",
     *  statusCodes={
     *      201 = "Returned when successful",
     *      400 = "Returned when can't register user"
     *  }
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function registerAction(Request $request)
    {
        /** @var FormInterface $form */
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();
            return new Response($user, JsonResponse::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);

        return new JsonResponse($errors, JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *  description="Confirm user account",
     *  requirements={
     *      {
     *          "name"="token",
     *          "dataType"="string"
     *      }
     *  }
     * )
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    public function confirmAction($token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            return new JsonResponse([sprintf('The user with confirmation token "%s" does not exist', $token)], JsonResponse::HTTP_NOT_FOUND);
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $user->setLastLogin(new \DateTime());

        $this->container->get('fos_user.user_manager')->updateUser($user);

        return new JsonResponse(['confirmed']);
    }
}