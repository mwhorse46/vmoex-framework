<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-06-10 12:05:03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TabsController
 * @package Yeskn\WebBundle\Controller
 *
 * @Route("/tabs")
 */
class TabsController extends Controller
{
    /**
     * @Route("", name="tabs_index")
     * @throws
     */
    public function indexAction()
    {
        $count = $this->getDoctrine()->getRepository('YesknWebBundle:Tab')
            ->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

        $tabs = $this->getDoctrine()->getRepository('YesknWebBundle:Tab')->findAll();


        return $this->render('@YesknWeb/tabs.html.twig', [
            'tabs' => $tabs,
            'tab_count' => $count
        ]);
    }
}