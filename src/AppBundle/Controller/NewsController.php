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
    use FormErrorsTrait;

    const EMPTY_RESPONSE = '';

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
     *  },
     *  statusCodes={
     *      200 = "News found",
     *      404 = "News not found"
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
     *  description="Get news list by status",
     *  output="AppBundle\Entity\News",
     *  requirements={
     *      {
     *          "name"="status",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     *  },
     *  statusCodes={
     *      200 = "List of news with given status",
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
     *  output="AppBundle\Entity\News",
     *  statusCodes={
     *      201 = "News Created",
     *      400 = "Validation errors"
     *  }
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postAction(Request $request)
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isValid()) {


            $this->get('news_manager')->create($news, $this->getUser());

            return new Response($this->get('jms_serializer')->serialize($news, 'json'), Response::HTTP_CREATED);
        }

        return new Response($this->getFormErrors($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *  description="Edit news",
     *  input="AppBundle\Form\NewsType",
     *  output="AppBundle\Entity\News",
     *  statusCodes={
     *      200 = "News Updated",
     *      400 = "Validation errors"
     *  }
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

        $form = $this->createForm(NewsType::class, $news, ['method' => 'PUT']);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('news_manager')->update($news, $this->getUser());

            return new Response($this->get('jms_serializer')->serialize($news, 'json'));
        }

        return new JsonResponse($this->getFormErrors($form), JsonResponse::HTTP_BAD_REQUEST);
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
     *  },
     *  statusCodes={
     *      200 = "News Deleted",
     *      403 = "News not owned by user",
     *      404 = "News not found"
     *  }
     * )
     *
     * @param $newsId
     *
     * @return JsonResponse
     */
    public function deleteAction($newsId)
    {
        $news = $this->get('news_manager')->get($newsId);

        $this->get('news_manager')->delete($news, $this->getUser());

        return new Response(self::EMPTY_RESPONSE, Response::HTTP_OK);
    }
}