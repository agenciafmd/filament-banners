<?php

declare(strict_types=1);

return [
    'name' => 'Banners',
    'locations' => [
        'home' => [
            'label' => 'Home',
            'files' => [
                'desktop' => [
                    'visible' => true,
                    'width' => 1920,
                    'height' => 1080,
                    'ratio' => [
                        '16:9',
                    ],
                ],
                'notebook' => [
                    'visible' => true,
                    'width' => 1920,
                    'height' => 1080,
                    'ratio' => [
                        '16:9',
                    ],
                ],
                'mobile' => [
                    'visible' => true,
                    'width' => 720,
                    'height' => 1280,
                    'ratio' => [
                        '9:16',
                    ],
                ],
                'video' => [
                    'visible' => true,
                ],
            ],
            'meta' => [
                //                [
                //                    'type' => Meta::TEXT,
                //                    'label' => 'Título',
                //                    'name' => 'title',
                //                ],
                //                [
                //                    'type' => Meta::TEXT,
                //                    'label' => 'Subtítulo',
                //                    'name' => 'subtitle',
                //                ],
                //                [
                //                    'type' => Meta::TEXT,
                //                    'label' => 'Descrição',
                //                    'name' => 'description',
                //                ],
                //                [
                //                    'type' => Meta::SELECT,
                //                    'label' => 'Status',
                //                    'name' => 'status',
                //                    'options' => [
                //                        'breve-lancamento' => 'Breve lançamento',
                //                        'em-obras' => 'Em obras',
                //                        'pronto' => 'Pronto',
                //                    ],
                //                ],
                //                [
                //                    'type' => Meta::REPEATER,
                //                    'label' => 'Detalhes',
                //                    'name' => 'details',
                //                ],
            ],
        ],
    ],
];
