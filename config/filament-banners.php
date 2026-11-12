<?php

declare(strict_types=1);

return [
    'name' => 'Banners',
    'navigation_group' => null,
    'navigation_sort' => 5,
    'locations' => [
        'home' => [
            'label' => 'Home',
            'files' => [
                'desktop' => [
                    'visible' => true,
                    'width' => 1920,
                    'height' => 1080,
                    'media' => '(min-width: 1400px)',
                ],
                'notebook' => [
                    'visible' => true,
                    'width' => 1440,
                    'height' => 810,
                    'media' => '(min-width: 768px)',
                ],
                'mobile' => [
                    'visible' => true,
                    'width' => 720,
                    'height' => 1280,
                    'media' => '(max-width: 767px)',
                ],
                'video' => [
                    'visible' => false,
                ],
                'mobile_video' => [
                    'visible' => false,
                ],
            ],
            'meta' => [
                //                [
                //                    'type' => Meta::Text,
                //                    'label' => 'Título',
                //                    'name' => 'title',
                //                ],
                //                [
                //                    'type' => Meta::Text,
                //                    'label' => 'Subtítulo',
                //                    'name' => 'subtitle',
                //                ],
                //                [
                //                    'type' => Meta::Text,
                //                    'label' => 'Descrição',
                //                    'name' => 'description',
                //                ],
                //                [
                //                    'type' => Meta::Select,
                //                    'label' => 'Status',
                //                    'name' => 'status',
                //                    'options' => [
                //                        'breve-lancamento' => 'Breve lançamento',
                //                        'em-obras' => 'Em obras',
                //                        'pronto' => 'Pronto',
                //                    ],
                //                ],
                //                [
                //                    'type' => Meta::Repeater,
                //                    'label' => 'Detalhes',
                //                    'name' => 'details',
                //                ],
            ],
        ],
    ],
];
