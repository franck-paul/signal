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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

// dead but useful code, in order to have translations
__('Signal') . __('Private comments to the author');

class signalBehaviors
{
    public static function adminBlogPreferencesForm($settings)
    {
        $settings->addNameSpace('signal');
        echo
        '<div class="fieldset" id="signal"><h4>' . __('Signal') . '</h4>' .
        '<p><label class="classic">' .
        form::checkbox('signal_enabled', '1', $settings->signal->enabled) .
        __('Enable private comments to the author (or the moderator)') . '</label></p>' .
        '<p><label>' .
        __('User defined label:') . ' ' .
        form::field('signal_label', 25, 50, $settings->signal->label) .
        '</label></p>' . "\n" .
        '<p class="form-note">' . __('Leave empty to use the default one:') . ' "' . __('Private comment for the author (or the moderator)') . '"</p>' . "\n" .
            '</div>';
    }

    public static function adminBeforeBlogSettingsUpdate($settings)
    {
        $settings->addNameSpace('signal');
        $settings->signal->put('enabled', !empty($_POST['signal_enabled']), 'boolean');
        $settings->signal->put('label', empty($_POST['signal_label']) ? '' : html::escapeHTML($_POST['signal_label']), 'string');
    }
}

dcCore::app()->addBehavior('adminBlogPreferencesFormV2', [signalBehaviors::class, 'adminBlogPreferencesForm']);
dcCore::app()->addBehavior('adminBeforeBlogSettingsUpdate', [signalBehaviors::class, 'adminBeforeBlogSettingsUpdate']);
