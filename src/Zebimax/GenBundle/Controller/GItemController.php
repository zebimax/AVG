<?php

namespace Zebimax\GenBundle\Controller;

use AppBundle\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GItemController extends Controller
{
    /**
     *
     * @param Request $request
     *
     * @Route("/gitem/list", name="gen_gitem_list")
     * @Template
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $issues = $this->get('app.services.issue')->getIssuesList(
            $user,
            $request->query->get($this->container->getParameter('app.page_name'), 1),
            $this->container->getParameter('app.services.issue.list_limit')
        );

        return ['issues' => $issues];
    }
}
