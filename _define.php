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

if (!defined('DC_RC_PATH')) {return;}

$this->registerModule(
    "Signal",                         // Name
    "Private comments to the author", // Description
    "Franck Paul",                    // Author
    '0.3',                            // Version
    [
        'requires'    => [['core', '2.13']],                      // Dependencies
        'permissions' => 'admin',                                 // Permissions
        'type'        => 'plugin',                                // Type
        'details'     => 'https://open-time.net/?q=signal',       // Details URL
        'support'     => 'https://github.com/franck-paul/signal', // Support URL
        'settings'    => ['blog' => '#params.signal']            // Settings
    ]
);
