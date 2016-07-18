<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /** @var  ContainerInterface */
    private $container;
    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $factory = $this->container->get('security.encoder_factory');
        /** @var $manager \FOS\UserBundle\Doctrine\UserManager */
        $manager = $this->container->get('fos_user.user_manager');

        /** @var $user \AppBundle\Entity\User */
        $user = $manager->createUser();
        $user->setUsername('admin');
        $user->setEmail('admin@example.com');
        $user->setRoles(array('ROLE_SUPER_ADMIN'));
        $user->setEnabled(true);
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword('admin', $user->getSalt());
        $user->setPassword($password);
        $this->setReference('userAdmin', $user);
        $manager->updateUser($user);

        $user = $manager->createUser();
        $user->setUsername('test');
        $user->setEmail('test@example.com');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setEnabled(true);
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword('test', $user->getSalt());
        $user->setPassword($password);
        $this->setReference('userTest', $user);
        $manager->updateUser($user);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }


}