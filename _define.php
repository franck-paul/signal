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
    '2.1.1',
    [
        'requires'    => [['core', '2.27'], ['php', '8.1']],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_ADMIN,
        ]),
        'type'     => 'plugin',
        'settings' => [
            'blog' => '#params.signal',
        ],

        'details'    => 'https://open-time.net/?q=signal',
        'support'    => 'https://github.com/franck-paul/signal',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/signal/master/dcstore.xml',
    ]
);
