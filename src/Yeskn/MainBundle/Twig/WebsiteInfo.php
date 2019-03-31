<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 13:11:29
 */

namespace Yeskn\MainBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Yeskn\MainBundle\Entity\Announce;

class WebsiteInfo extends AbstractExtension
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var null|Request
     */
    private $request;

    public function __construct(EntityManagerInterface $em, RequestStack $request)
    {
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
    }

    public function websiteInfo()
    {
        static $return = [];

        if (empty($return)) {
            $res = $this->em->getRepository('YesknMainBundle:Options')->findAll();

            $return = [];

            foreach ($res as $value) {
                $return[$value->getName()] = $value->getValue();
            }

            $announce = $this->em->getRepository('YesknMainBundle:Announce')
                ->findOneBy(['show' => 1], ['id' => 'DESC']);

            $return['announce'] = $announce;
        }

        return $return;
    }

    public function hideAnnounceAlert()
    {
        $info = $this->websiteInfo();

        if (empty($info['announce'])) {
            return true;
        }

        /** @var Announce $announce */
        $announce = $info['announce'];

        $cookieId =  $this->request->cookies->get('_announce');

        if ($cookieId != $announce->getId()) {
            return false;
        }

        return true;
    }

    public function isPjax()
    {
        $request = $this->request;

        if ($request->get('_pjax')) {
            return true;
        }

        if ((bool)$request->headers->get('X-PJAX')) {
            return true;
        }

        return false;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('options', [$this, 'websiteInfo']),
            new \Twig_SimpleFunction('hideAnnounceAlert', [$this,'hideAnnounceAlert']),
            new \Twig_SimpleFunction('isPjax', [$this, 'isPjax']),
        ];
    }
}
