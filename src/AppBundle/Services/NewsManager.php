<?php
namespace AppBundle\Services;

use AppBundle\Entity\News;
use AppBundle\Exception\NewsNotFoundException;
use AppBundle\Exception\NewsNotOwnedByGivenUserException;
use AppBundle\Repository\NewsRepository;
use AppBundle\Entity\User;

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
     * @param User $user
     *
     *
     * @return int
     */
    public function create(News $news, User $user)
    {
        $news->setUser($user);
        $news->setStatus(News::STATUS_NEW);

        $this->newsRepository->save($news);
    }

    /**
     * @param News $news
     *
     * @throws NewsNotOwnedByGivenUserException
     */
    public function update(News $news, User $user)
    {
        if (!$this->isNewsOwnedByUser($news, $user)) {
            throw new NewsNotOwnedByGivenUserException();
        }

        $this->newsRepository->update($news);
    }

    /**
     * @param News $news
     * @param User $user
     *
     * @throws NewsNotOwnedByGivenUserException
     */
    public function delete(News $news, User $user)
    {
        if (!$this->isNewsOwnedByUser($news, $user)) {
            throw new NewsNotOwnedByGivenUserException();
        }

        $this->newsRepository->delete($news);
    }

    /**
     * @param News $news
     * @param User $user
     *
     * @return bool
     */
    private function isNewsOwnedByUser(News $news, User $user)
    {
        return $news->getUser() == $user;
    }
}