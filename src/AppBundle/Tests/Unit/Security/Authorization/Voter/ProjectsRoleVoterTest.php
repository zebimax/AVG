<?php

namespace AppBundle\Tests\Unit\Security\Authorization\Voter;

use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\ProjectsRoleVoter;
use AppBundle\Entity\Role;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ProjectsRoleVoterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectsRoleVoter
     */
    protected $object;
    /**
     * @var array
     */
    protected $managerSupportedAttributes = [
        ProjectsRoleVoter::PROJECTS_ADD,
        ProjectsRoleVoter::PROJECTS_EDIT,
        ProjectsRoleVoter::PROJECTS_MEMBERS_LIST,
        ProjectsRoleVoter::PROJECTS_MEMBERS_ADD,
        ProjectsRoleVoter::PROJECTS_MEMBERS_DELETE,
    ];
    /**
     * @var array
     */
    protected $operatorSupportedAttributes = [
        ProjectsRoleVoter::PROJECTS,
        ProjectsRoleVoter::PROJECTS_LIST,
    ];

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $operatorRole = $this->getMock('AppBundle\Entity\Role');
        $operatorRole->expects($this->any())->method('getRole')->willReturn(Role::OPERATOR);
        $managerRole = $this->getMock('AppBundle\Entity\Role');
        $managerRole->expects($this->any())->method('getRole')->willReturn(Role::MANAGER);
        $adminRole = $this->getMock('AppBundle\Entity\Role');
        $adminRole->expects($this->any())->method('getRole')->willReturn(Role::ADMINISTRATOR);
        $invalidRole = $this->getMock('AppBundle\Entity\Role');
        $invalidRole->expects($this->any())->method('getRole')->willReturn('ROLE_INVALID');
        $roles = [
            Role::OPERATOR => [$operatorRole],
            Role::MANAGER => [$operatorRole, $managerRole],
            Role::ADMINISTRATOR => [$operatorRole, $managerRole, $adminRole],
            'ROLE_INVALID' => [$invalidRole],
        ];
        $roleHierarchy = $this->getMock('Symfony\Component\Security\Core\Role\RoleHierarchyInterface');
        $roleHierarchy
            ->expects($this->any())
            ->method('getReachableRoles')
            ->with($this->anything())
            ->will(
                $this->returnCallback(
                    function ($value) use ($roles) {
                        $role = $value[0];

                        return $roles[$role->getRole()];
                    }
                )
            );
        $this->object = new ProjectsRoleVoter($roleHierarchy);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @param $roleName
     * @param array $attributes
     * @param $expected
     * @covers AppBundle\Security\Authorization\Voter\ProjectsRoleVoter::vote
     * @dataProvider voteDataProvider
     */
    public function testVote($roleName, array $attributes, $expected)
    {
        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->disableOriginalConstructor()->getMock();
        $role = (new Role())->setRole($roleName);
        $user = new User();
        $user->addRole($role);
        if ($expected === VoterInterface::ACCESS_GRANTED) {
            $token->expects($this->at(0))->method('getUser')->willReturn($user);
            $token->expects($this->at(1))->method('getUser')->willReturn($user);
        }
        $this->assertEquals($expected, $this->object->vote($token, null, $attributes));
    }

    /**
     * Vote data provider.
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function voteDataProvider()
    {
        $data = [
            'access abstain not supported attribute' => [
                'roleName' => Role::OPERATOR,
                'attributes' => ['edit'],
                'expected' => VoterInterface::ACCESS_ABSTAIN,
            ],
        ];
        foreach ($this->operatorSupportedAttributes as $attribute) {
            $data[$attribute.' access granted operator'] = [
                'roleName' => Role::OPERATOR,
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ];
            $data[$attribute.' access granted manager'] = [
                'roleName' => Role::MANAGER,
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ];
            $data[$attribute.' access granted administrator'] = [
                'roleName' => Role::ADMINISTRATOR,
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ];
            $data[$attribute.' access denied invalid role'] = [
                'roleName' => 'ROLE_INVALID',
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_DENIED,
            ];
        }

        foreach ($this->managerSupportedAttributes as $attribute) {
            $data[$attribute.' access denied operator'] = [
                'roleName' => Role::OPERATOR,
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_DENIED,
            ];
            $data[$attribute.' access granted manager'] = [
                'roleName' => Role::MANAGER,
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ];
            $data[$attribute.' access granted administrator'] = [
                'roleName' => Role::ADMINISTRATOR,
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ];
            $data[$attribute.' access denied invalid role'] = [
                'roleName' => 'ROLE_INVALID',
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_DENIED,
            ];
        }

        return $data;
    }
}
