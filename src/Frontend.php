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

use Dotclear\App;
use Dotclear\Core\Process;

class Frontend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // Don't do things in frontend if plugin disabled
        $settings = My::settings();
        if (!(bool) $settings->enabled) {
            return false;
        }

        App::behavior()->addBehaviors([
            'publicCommentFormBeforeContent' => FrontendBehaviors::publicCommentFormBeforeContent(...),
            'publicBeforeCommentPreview'     => FrontendBehaviors::publicBeforeCommentPreview(...),
            'publicBeforeCommentCreate'      => FrontendBehaviors::publicBeforeCommentCreate(...),
            'publicBeforeCommentRedir'       => FrontendBehaviors::publicBeforeCommentRedir(...),
        ]);

        App::frontend()->template()->addBlock('SysIfCommentPending', FrontendTemplate::SysIfCommentPending(...));

        return true;
    }
}
