<?php

namespace AppBundle\Tests\Unit\EventListener;

use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\User;
use AppBundle\EventListener\Event\IssueActivityEvent;
use AppBundle\EventListener\IssueActivityListener;
use PHPUnit_Framework_MockObject_MockObject;

class IssueActivityListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueActivityListener
     */
    protected $object;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $mailMock;

    protected function setUp()
    {
        $this->mailMock = $this->getMockBuilder('AppBundle\Service\MailService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new IssueActivityListener($this->mailMock);
    }

    /**
     * @covers AppBundle\EventListener\IssueActivityListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertSame(
            [IssueActivityEvent::ISSUE_ACTIVITY => 'onAppIssueActivity'],
            $this->object->getSubscribedEvents()
        );
    }

    /**
     * @covers AppBundle\EventListener\IssueActivityListener::onAppIssueActivity
     */
    public function testOnAppIssueActivity()
    {
        $issueActivity = new IssueActivity(new Issue(), new User());
        $event = new IssueActivityEvent($issueActivity);
        $this->mailMock
            ->expects($this->once())
            ->method('sendIssueActivityMail')
            ->with($issueActivity);
        $this->object->onAppIssueActivity($event);
    }
}
