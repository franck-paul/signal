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

use dcBlog;
use dcCore;
use Dotclear\Helper\Html\Html;

class FrontendBehaviors
{
    public static function publicBeforeCommentPreview($comment_preview)
    {
        if (My::settings()->enabled && isset($_POST['c_signal'])) {
            // Keep signal checkbox state during preview
            $comment_preview['signal'] = $_POST['c_signal'];
        }
    }

    public static function publicCommentFormBeforeContent()
    {
        $settings = My::settings();
        if ($settings->enabled) {
            $checked = false;
            if (isset(dcCore::app()->ctx->comment_preview['signal'])) {
                // Restore signal checkbox if necessary
                $checked = true;
            }
            $label = $settings->label != '' ?
                Html::escapeHTML($settings->label) :
                __('Private comment for the author (or the moderator)');
            echo
                '<p class="signal">' .
                '<input name="c_signal" id="c_signal" type="checkbox" ' . ($checked ? 'checked="checked"' : '') . ' /> ' .
                '<label for="c_signal">' . $label . '</label>' .
                '</p>';
        }
    }

    public static function publicBeforeCommentCreate($cur)
    {
        if (!My::settings()->enabled) {
            return;
        }

        if ((isset($_POST['c_signal']) || isset(dcCore::app()->ctx->comment_preview['signal'])) && $cur->comment_status == dcBlog::COMMENT_PUBLISHED) {
            // Move status from published to pending
            $cur->comment_status = dcBlog::COMMENT_PENDING;
        }
    }

    public static function publicBeforeCommentRedir()
    {
        if (!My::settings()->enabled) {
            return;
        }

        if (isset($_POST['c_signal']) || isset(dcCore::app()->ctx->comment_preview['signal'])) {     // @phpstan-ignore-line
            return '&signal=1';
        }
    }
}
