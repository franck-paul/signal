<?php

/**
 * @brief signal, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\signal;

use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Fieldset;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Legend;
use Dotclear\Helper\Html\Form\Note;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Html;

class BackendBehaviors
{
    public static function adminBlogPreferencesForm(): string
    {
        $settings = My::settings();

        // Add fieldset for plugin options
        echo
        (new Fieldset('signal'))
        ->legend((new Legend(__('Signal'))))
        ->fields([
            (new Para())->items([
                (new Checkbox('signal_enabled', $settings->enabled))
                    ->value(1)
                    ->label((new Label(__('Enable private comments to the author (or the moderator)'), Label::INSIDE_TEXT_AFTER))),
            ]),
            (new Para())->items([
                (new Input('signal_label'))
                    ->size(25)
                    ->maxlength(50)
                    ->value($settings->label)
                    ->label((new Label(__('User defined label:'), Label::INSIDE_TEXT_BEFORE))),
            ]),
            (new Note())
                ->class(['form-note', 'clear'])
                ->text(__('Leave empty to use the default one:') . ' "' . __('Private comment for the author (or the moderator)') . '"'),
        ])
        ->render();

        return '';
    }

    public static function adminBeforeBlogSettingsUpdate(): string
    {
        $settings = My::settings();

        $settings->put('enabled', !empty($_POST['signal_enabled']), 'boolean');
        $settings->put('label', empty($_POST['signal_label']) ? '' : Html::escapeHTML($_POST['signal_label']), 'string');

        return '';
    }
}
