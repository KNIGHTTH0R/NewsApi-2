<?php
namespace AppBundle\Services;

use AppBundle\Entity\News;
use AppBundle\Exception\NewsNotFoundException;
use AppBundle\Repository\NewsRepository;
use FOS\UserBundle\Entity\User;

/**
 * Class NewsModel
 */
class NewsManager
{
    /**
     * @param NewsRepository $newsRepository
     */
    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * @param $newsId
     * @return News
     * @throws NewsNotFoundException
     */
    public function get($newsId)
    {
        $news = $this->newsRepository->find($newsId);

        if (!$news) {
            throw new NewsNotFoundException();
        }

        return $news;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getByUser(User $user)
    {
        return $this->newsRepository->findBy(['user' => $user]);
    }

    /**
     * @param $status
     * @return array
     */
    public function getByStatus($status)
    {
        return $this->newsRepository->findBy(['status' => $status]);
    }

    /**
     * @param News $news
     *
     *
     * @return int
     */
    public function create(News $news)
    {
        $this->newsRepository->save($news);
    }

    /**
     * @param News $news
     *
     * @throws NewsNotFoundException
     */
    public function update(News $news)
    {
        $this->newsRepository->update($news);
    }

    /**
     * @param $newsId
     */
    public function delete($newsId)
    {
        $this->newsRepository->delete($this->get($newsId));
    }
}