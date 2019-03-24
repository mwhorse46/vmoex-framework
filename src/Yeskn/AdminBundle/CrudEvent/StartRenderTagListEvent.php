<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-18 19:49:13
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Yeskn\MainBundle\Entity\Tag;
use Yeskn\MainBundle\Twig\GlobalValue;

class StartRenderTagListEvent extends AbstractCrudListEvent
{
    /**
     * @var Tag[]
     */
    protected $list;

    private $globalValue;

    private $translator;

    public function __construct(RouterInterface $router, GlobalValue $globalValue, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->globalValue = $globalValue;
        $this->translator = $translator;
    }

    public function execute()
    {
        $ids = $result = [];

        foreach ($this->list as $tag) {
            $ids[] = $tag->getId();

            $result[] = [
                $tag->getId(),
                $tag->getName(),
                $tag->getSlug(),
                $this->globalValue->ago($tag->getCreatedAt()),
                $this->translator->trans($tag->getStatus() ? '启用' : '不启用')
            ];
        }

        return [
            'columns' => ['ID', '名称', '别名', '创建时间', '状态'],
            'list' => $result,
            'ids' => $ids
        ];
    }
}
