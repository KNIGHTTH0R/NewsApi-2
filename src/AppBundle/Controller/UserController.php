<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserController
 */
class UserController extends FOSRestController
{
    use FormErrorsTrait;

    /**
     * @ApiDoc(
     *  description="Change user password",
     *  input="FOS\UserBundle\Form\Type\ChangePasswordFormType",
     *  output="AppBundle\Entity\User"
     * )
     *
     * @return JsonResponse
     */
    public function putChangePasswordAction()
    {
        $user = $this->getUser();
        $form = $this->container->get('fos_user.change_password.form');
        $formHandler = $this->container->get('fos_user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            return new JsonResponse();
        }

        $errors = $this->getFormErrors($form);

        return new JsonResponse($errors, JsonResponse::HTTP_BAD_REQUEST);
    }
}