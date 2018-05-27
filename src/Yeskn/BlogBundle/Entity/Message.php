<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-27 04:27:16
 */

namespace Yeskn\BlogBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="Yeskn\BlogBundle\Repository\MessageRepository")
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Yeskn\BlogBundle\Entity\User", inversedBy="sentMessages")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $sender;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Yeskn\BlogBundle\Entity\User",inversedBy="receivedMessages")
     * @ORM\JoinColumn(name="receiver_id" , referencedColumnName="id")
     */
    private $receiver;

    /**
     * @var
     * @ORM\Column(name="content", type="string", length=250)
     */
    private $content;

    /**
     * @var boolean
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     *
     * @return Message
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Message
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set sender
     *
     * @param \Yeskn\BlogBundle\Entity\User $sender
     *
     * @return Message
     */
    public function setSender(\Yeskn\BlogBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \Yeskn\BlogBundle\Entity\User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param \Yeskn\BlogBundle\Entity\User $receiver
     *
     * @return Message
     */
    public function setReceiver(\Yeskn\BlogBundle\Entity\User $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \Yeskn\BlogBundle\Entity\User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
}
