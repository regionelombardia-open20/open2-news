<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

return [
    'params' => [
        'img-default' => '/img/defaultProfilo.png',
        'site_publish_enabled' => false,
        'site_featured_enabled' => false,
        //active the search
        'searchParams' => [
            'news' => [
                'enable' => true,
            ]
        ],

        //active the order
        'orderParams' => [
            'news' => [
                'enable' => true,
                'fields' => [
                    'titolo',
                    'data_pubblicazione'
                ],
                'default_field' => 'data_pubblicazione',
                'order_type' => SORT_DESC
            ]
        ],

        //active the introduction
        'introductionParams' => [
            'news' => [
                'enable' => true,
            ]
        ]
    ]
];
