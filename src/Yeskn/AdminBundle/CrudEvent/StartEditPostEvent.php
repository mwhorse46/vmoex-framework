<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-17 20:14:19
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\HttpFoundation\File\File;
use Yeskn\MainBundle\Entity\Post;

class StartEditPostEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Post
     */
    protected $entity;

    public static $oldProperty;

    public function execute()
    {
        $entityObj = $this->entity;

        if ($oldCover = $entityObj->getCover()) {
            $entityObj->setCover(new File($oldCover, false));
        } else {
            $oldCover = null;
        }

        return self::$oldProperty = [
            'cover' => $oldCover
        ];
    }
}
