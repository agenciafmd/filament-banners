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
            //            'meta' =>  [
            //                [
            //                    'label' => 'tipo',
            //                    'name' => 'type',
            //                    'options' => [
            //                        [
            //                            'value' => '',
            //                            'label' => '-',
            //                        ],
            //                        [
            //                            'value' => 'Plantas Baixas',
            //                            'label' => 'Plantas Baixas',
            //                        ],
            //                        [
            //                            'value' => 'Implantações',
            //                            'label' => 'Implantações',
            //                        ]
            //                    ],
            //                ],
            //                [
            //                    'label' => 'título',
            //                    'name' => 'title',
            //                ],
            //                [
            //                    'label' => 'subtítulo',
            //                    'name' => 'subtitle',
            //                ],
            //            ]
        ],
    ],
];
