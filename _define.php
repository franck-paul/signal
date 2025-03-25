<?php

/**
 * @brief signal, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
$this->registerModule(
    'Signal',
    'Private comments to the author',
    'Franck Paul',
    '6.1',
    [
        'date'     => '2025-03-25T08:49:00+0100',
        'requires' => [
            ['core', '2.34'],
            ['TemplateHelper'],
        ],
        'permissions' => 'My',
        'type'        => 'plugin',
        'settings'    => [
            'blog' => '#params.signal',
        ],

        'details'    => 'https://open-time.net/?q=signal',
        'support'    => 'https://github.com/franck-paul/signal',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/signal/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
