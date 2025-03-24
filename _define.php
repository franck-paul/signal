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
    '5.0',
    [
        'date'     => '2003-08-13T13:42:00+0100',
        'requires' => [
            ['core', '2.33'],
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
