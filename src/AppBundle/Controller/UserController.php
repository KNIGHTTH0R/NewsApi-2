<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 */
class UserController extends FOSRestController
{
    const EMPTY_RESPONSE = '';

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
    public function postPasswordAction()
    {
        $user = $this->getUser();

        $form = $this->container->get('fos_user.change_password.form');
        $formHandler = $this->container->get('fos_user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            return new JsonResponse();
        }

        return new JsonResponse($this->getFormErrors($form), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *  description="Get news list by user",
     *  output="AppBundle\Entity\News",
     *  requirements={
     *      {
     *          "name"="userId",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     *  },
     *  statusCodes={
     *      200 = "List of news for given userId",
     *  }
     * )
     *
     * @param $userId
     *
     * @return Response
     */
    public function getUserNewsAction($userId)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(['id'=>$userId]);

        if (!$user) {
            return new Response(self::EMPTY_RESPONSE, Response::HTTP_NOT_FOUND);
        }

        $news = $this->get('news_manager')->getByUser($user);

        return new Response($this->get('jms_serializer')->serialize($news, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Get user news list",
     *  output="AppBundle\Entity\News",
     *  requirements={
     *      {
     *          "name"="userId",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     *  },
     *  statusCodes={
     *      200 = "List of news for given userId",
     *  }
     * )
     *
     *
     * @return Response
     */
    public function getNewsAction()
    {
        $news = $this->get('news_manager')->getByUser($this->getUser());

        return new Response($this->get('jms_serializer')->serialize($news, 'json'));
    }
}