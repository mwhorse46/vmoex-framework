<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 18:40:37
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Form\Type\DatetimeRangeType;
use Yeskn\MainBundle\Form\Type\ImageInputType;
use Yeskn\Support\ParameterBag;

class OptionsBasic extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            $builder->create('siteLogo', ImageInputType::class, [
                'label' => '网站LOGO',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        );
        $builder->add('siteSince', DatetimeRangeType::class, [
            'label' => '成立时间',
            'field_type' => 'date',
            'date_range' => false
        ]);
        $builder->add('siteVersion', null, ['label' => '网站版本']);

        $builder->add('githubClientId', null, ['label' => 'github clientId']);
        $builder->add('githubClientSecret', null, ['label' => 'github clientSecret']);
        $builder->add('githubRedirectUrl', null, ['label' => 'github 跳转地址']);

        $builder->add('baiduTransAppId', null, ['label' => '百度翻译AppId']);
        $builder->add('baiduTransKey', null, ['label' => '百度翻译Key']);

        $builder->add('siteAnnounce', CheckboxType::class, [
            'label' => '开启公告',
            'required' => false,
            'attr' => [
                'help' => '请在翻译管理中编辑词条banner_announce来修改公告内容'
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ParameterBag::class
        ));
    }
}
