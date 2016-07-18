<?php
namespace AppBundle\Repository;

use AppBundle\Entity\News;
use Doctrine\ORM\EntityRepository;

/**
 * Class NewsRepository
 */
class NewsRepository extends EntityRepository
{
    /**
     * @param News $news
     *
     * @return News
     */
    public function save(News $news)
    {
        $this->_em->persist($news);
        $this->_em->flush();
    }

    /**
     * @param News $news
     */
    public function update(News $news)
    {
        $this->_em->flush($news);
    }

    /**
     * @param News $news
     */
    public function delete(News $news)
    {
        $this->_em->remove($news);
        $this->_em->flush();
    }
}