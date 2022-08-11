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
if (!defined('DC_RC_PATH')) {
    return;
}

$this->registerModule(
    'Signal',                         // Name
    'Private comments to the author', // Description
    'Franck Paul',                    // Author
    '0.5',
    [
        'requires'    => [['core', '2.23']],                      // Dependencies
        'permissions' => 'admin',                                 // Permissions
        'type'        => 'plugin',                                // Type
        'settings'    => ['blog' => '#params.signal'],            // Settings

        'details'    => 'https://open-time.net/?q=signal',       // Details URL
        'support'    => 'https://github.com/franck-paul/signal', // Support URL
        'repository' => 'https://raw.githubusercontent.com/franck-paul/signal/master/dcstore.xml',
    ]
);
