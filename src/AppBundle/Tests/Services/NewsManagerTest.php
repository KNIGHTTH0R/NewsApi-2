<?php
namespace AppBundle\Tests\Services;

use AppBundle\Entity\News;
use AppBundle\Entity\User;
use AppBundle\Services\NewsManager;
use AppBundle\Repository\NewsRepository;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class NewsManagerTest
 *
 * @coversDefaultClass AppBundle\Services\NewsManager
 */
class NewsManagerTest extends \PHPUnit_Framework_TestCase
{
    const NEWS_ID = 12;

    /** @var NewsManager */
    private $sut;

    /** @var NewsRepository | MockObject */
    private $newsRepositoryMock;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->newsRepositoryMock = $this->getMockBuilder('AppBundle\Repository\NewsRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new NewsManager($this->newsRepositoryMock);
    }

    /**
     * @covers ::get
     */
    public function testGet_ShouldReturnNews()
    {
        $news = new News();

        $this->newsRepositoryMock
            ->method('find')
            ->willReturn($news);

        $result = $this->sut->get(self::NEWS_ID);

        $this->assertEquals($news, $result);
    }

    /**
     * @covers ::get
     */
    public function testGet_ShouldThrowException_IfNewsNotFound()
    {
        $this->setExpectedException('AppBundle\Exception\NewsNotFoundException');

        $this->newsRepositoryMock
            ->method('find')
            ->willReturn(null);

        $this->sut->get(self::NEWS_ID);
    }

    /**
     * @covers ::getByUser
     */
    public function testGetByUser_ShouldReturnGivenUserNews()
    {
        $user = new User();

        $news = [new News(), new News()];

        $this->newsRepositoryMock
            ->method('findBy')
            ->with(['user'=>$user])
            ->willReturn($news);

        $result = $this->sut->getByUser($user);

        $this->assertEquals($news, $result);
    }

    /**
     * @covers ::getByStatus
     */
    public function testGetByStatus_ShouldReturnNewsWithGivenStatus()
    {
        $status = News::STATUS_NEW;
        $news = [new News()];

        $this->newsRepositoryMock
            ->method('findBy')
            ->with(['status'=>$status])
            ->willReturn($news);

        $result = $this->sut->getByStatus($status);
        $this->assertEquals($news, $result);
    }

    /**
     * @covers ::create
     */
    public function testCreate()
    {
        $news = new News();

        $this->newsRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($news);

        $this->sut->create($news);
    }

    /**
     * @covers ::create
     */
    public function testUpdate()
    {
        $news = new News();

        $this->newsRepositoryMock
            ->expects($this->once())
            ->method('update')
            ->with($news);

        $this->sut->update($news);
    }

    /**
     * @covers ::create
     */
    public function testDelete()
    {
        $news = new News();

        $this->newsRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($news);

        $this->newsRepositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with($news);

        $this->sut->delete($news);
    }
}