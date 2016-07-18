<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\News;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadNewsData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        for($i=0;$i<10;$i++) {
            $news = new News();
            $news->setTitle('Title #' . $i);
            $news->setBody(
                'Lorem ipsum dolor sit amet sapien auctor velit. Mauris rutrum, enim enim ac viverra neque,
                vitae augue. Nulla gravida massa lacinia dui. In hac habitasse platea dictumst.'
            );
            $news->setStatus(($i % 2 == 0 ? News::STATUS_NEW : News::STATUS_PUBLISHED));
            $news->setUser($this->getReference('userAdmin'));

            $manager->persist($news);
        }

        for($i=22;$i<33;$i++) {
            $news = new News();
            $news->setTitle('Title #' . $i);
            $news->setBody(
                'Lorem ipsum dolor sit amet sapien auctor velit. Mauris rutrum, enim enim ac viverra neque,
                vitae augue. Nulla gravida massa lacinia dui. In hac habitasse platea dictumst.'
            );
            $news->setStatus(News::STATUS_PUBLISHED);
            $news->setUser($this->getReference('userTest'));

            $manager->persist($news);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}