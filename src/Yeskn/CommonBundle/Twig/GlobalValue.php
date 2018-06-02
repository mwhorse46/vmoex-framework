<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 2016/6/22
 * Time: 16:46
 */

namespace Yeskn\CommonBundle\Twig;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Yeskn\BlogBundle\Entity\User;

class GlobalValue extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GlobalValue constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * 判断用户今天是否签到
     *
     * @param User $user
     * @return int
     */
    public function signed(User $user)
    {
        try {
            $res =  (int) $this->em->getRepository('YesknBlogBundle:Sign')
                ->createQueryBuilder('p')
                ->select('COUNT(p)')
                ->where('p.user = :user')->setParameter('user', $user)
                ->andWhere('p.date = :date')->setParameter('date', new \DateTime(date('Y-m-d')))
                ->getQuery()
                ->getSingleScalarResult();

         return (bool) $res;
        } catch (NoResultException $exception) {
            return false;
        } catch (NonUniqueResultException $exception) {
            return true;
        }
    }

    public function ago(\DateTime $dateTime)
    {
        $current = time();
        $ts = $dateTime->getTimestamp();

        $diff = $current - $ts;

        if ($diff < 60) {
            return (intval($diff) ?: 1).'秒前';
        } else if ($diff <= 60*60){
            $m = intval($diff/60);
            $s = intval($diff%60);
            return $m.'分钟' . ($s ? $s.'秒' : '') . '前';
        } else if ($diff <= 24*60*60){
            $h = intval($diff/(60*60));
            $m = intval(($diff - $h*(60*60))/60);
            return $h . '小时' . ($m ? $m . '分钟' : '') .'前';
        } else {
            $d = intval($diff/(24*60*60));
            return $d . '天前';
        }
    }

    public function hotTags()
    {
        $tags = $this->em->getRepository('YesknBlogBundle:Tag')->findAll();
        return $tags;
    }

    public function hotPosts()
    {
        $posts = $this->em->getRepository('YesknBlogBundle:Post')->findBy(
            [], ['views' => 'DESC'], 8);
        return $posts;
    }

    public function hotComments()
    {
        $comments = $this->em->getRepository('YesknBlogBundle:Comment')
            ->findBy([], ['id' => 'DESC'], 5);

        return $comments;
    }

    /**
     * 显示2日内的活跃用户
     *
     * @return mixed
     */
    public function hotUsers()
    {
        $datetime = new \DateTime('-2 day');
        $actives = $this->em->getRepository('YesknBlogBundle:Active')
            ->createQueryBuilder('p')
            ->where('p.createdAt >= :yd')->setParameter('yd', $datetime, \Doctrine\DBAL\Types\Type::DATETIME)
            ->orderBy('p.val', 'DESC')
            ->groupBy('p.user')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();

        return $actives;
    }

    /**
     * @param User $user
     * @return array|\Yeskn\BlogBundle\Entity\Message[]
     */
    public function unReadMessages(User $user)
    {
        return $this->em->getRepository('YesknBlogBundle:Message')
            ->findBy(['receiver' => $user, 'isRead' => false], ['createdAt', 'DESC']);
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('signed', array($this,'signed')),
            new \Twig_SimpleFilter('ago', array($this,'ago')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('hotPosts',array($this,'hotPosts'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('hotTags',array($this,'hotTags'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('hotComments',array($this,'hotComments'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('unReadMessages',array($this,'unReadMessages'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('hotUsers',array($this,'hotUsers'),array('is_safe' => 'html'),array('needs_environment' => true))
        );
    }
}