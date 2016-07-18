<?php
namespace AppBundle\Controller;

use AppBundle\Form\NewsType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\News;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class PostsController
 */
class NewsController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  description="Get news action",
     *  output="AppBundle\Entity\News",
     *  requirements={
     *      {
     *          "name"="newsId",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     *  }
     * )
     *
     * @param $newsId
     *
     * @return JsonResponse
     */
    public function getAction($newsId)
    {
        $news = $this->get('news_manager')->get($newsId);

        return new Response($this->get('jms_serializer')->serialize($news, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Get news array by user",
     *  output="AppBundle\Entity\News",
     *  requirements={
     *      {
     *          "name"="userId",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     *  }
     * )
     *
     * @param $userId
     *
     * @return Response
     */
    public function getUserAction($userId)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(['id'=>$userId]);

        if (!$user) {
            return new Response('User not found', Response::HTTP_NOT_FOUND);
        }

        $news = $this->get('news_manager')->getByUser($user);

        return new Response($this->get('jms_serializer')->serialize($news, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Get news array by status",
     *  output="AppBundle\Entity\News",
     *  requirements={
     *      {
     *          "name"="status",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     *  }
     * )
     *
     * @param $status
     *
     * @return Response
     */
    public function getStatusAction($status)
    {
        $news = $this->get('news_manager')->getByStatus($status);

        return new Response($this->get('jms_serializer')->serialize($news, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Create news",
     *  input="AppBundle\Form\NewsType",
     *  output="AppBundle\Entity\News"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function postAction(Request $request)
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $news->setUser($this->getUser());
            $news->setStatus(News::STATUS_NEW);

            $this->get('news_manager')->create($news);

            return new Response($this->get('jms_serializer')->serialize($news, 'json'), Response::HTTP_CREATED);
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *  description="Edit news",
     *  input="AppBundle\Form\NewsType",
     *  output="AppBundle\Entity\News"
     * )
     *
     * @param Request $request
     * @param int $newsId
     *
     * @return Response
     */
    public function putAction(Request $request, $newsId)
    {
        $news = $this->get('news_manager')->get($newsId);

        if ($news->getUser()->getId() != $this->getUser()->getId()) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        $form = $this->createForm(NewsType::class, $news, ['method' => 'PUT']);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('news_manager')->update($news);

            return new Response($this->get('jms_serializer')->serialize($news, 'json'));
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *  description="Delete news",
     *  requirements={
     *      {
     *          "name"="newsId",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     *  }
     * )
     *
     * @param $newsId
     *
     * @return JsonResponse
     */
    public function deleteAction($newsId)
    {
        $this->get('news_manager')->delete($newsId);

        return new Response('', Response::HTTP_OK);
    }
}