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

use ArrayObject;
use Dotclear\App;
use Dotclear\Database\Cursor;
use Dotclear\Helper\Html\Html;

class FrontendBehaviors
{
    /**
     * @param      ArrayObject<string, string>   $comment_preview  The comment preview
     *
     * @return     string
     */
    public static function publicBeforeCommentPreview(ArrayObject $comment_preview): string
    {
        if (My::settings()->enabled && isset($_POST['c_signal'])) {
            // Keep signal checkbox state during preview
            $comment_preview['signal'] = $_POST['c_signal'];
        }

        return '';
    }

    public static function publicCommentFormBeforeContent(): string
    {
        $settings = My::settings();
        if ($settings->enabled) {
            $checked = false;
            if (isset(App::frontend()->context()->comment_preview['signal'])) {
                // Restore signal checkbox if necessary
                $checked = true;
            }

            $label = $settings->label != '' ?
                Html::escapeHTML($settings->label) :
                __('Private comment for the author (or the moderator)');
            echo
                '<p class="signal"><input name="c_signal" id="c_signal" type="checkbox" ' . ($checked ? 'checked="checked"' : '') . ' /> ' .
                '<label for="c_signal">' . $label . '</label>' .
                '</p>';
        }

        return '';
    }

    public static function publicBeforeCommentCreate(Cursor $cur): string
    {
        if (!My::settings()->enabled) {
            return '';
        }

        if ((isset($_POST['c_signal']) || isset(App::frontend()->context()->comment_preview['signal'])) && $cur->comment_status == App::blog()::COMMENT_PUBLISHED) {
            // Move status from published to pending
            $cur->comment_status = App::blog()::COMMENT_PENDING;
        }

        return '';
    }

    public static function publicBeforeCommentRedir(): string
    {
        if (!My::settings()->enabled) {
            return '';
        }

        if (isset($_POST['c_signal']) || isset(App::frontend()->context()->comment_preview['signal'])) {
            return '&signal=1';
        }

        return '';
    }
}
