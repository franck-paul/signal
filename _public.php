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

use Dotclear\Helper\Html\Html;

class signalPublicBehaviors
{
    public static function publicBeforeCommentPreview($comment_preview)
    {
        if (dcCore::app()->blog->settings->signal->enabled && isset($_POST['c_signal'])) {
            // Keep signal checkbox state during preview
            $comment_preview['signal'] = $_POST['c_signal'];
        }
    }

    public static function publicCommentFormBeforeContent()
    {
        if (dcCore::app()->blog->settings->signal->enabled) {
            $checked = false;
            if (isset(dcCore::app()->ctx->comment_preview['signal'])) {
                // Restore signal checkbox if necessary
                $checked = true;
            }
            $label = dcCore::app()->blog->settings->signal->label != '' ?
                Html::escapeHTML(dcCore::app()->blog->settings->signal->label) :
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
        if (!dcCore::app()->blog->settings->signal->enabled) {
            return;
        }

        if ((isset($_POST['c_signal']) || isset(dcCore::app()->ctx->comment_preview['signal'])) && $cur->comment_status == dcBlog::COMMENT_PUBLISHED) {
            // Move status from published to pending
            $cur->comment_status = dcBlog::COMMENT_PENDING;
        }
    }

    public static function publicBeforeCommentRedir()
    {
        if (!dcCore::app()->blog->settings->signal->enabled) {
            return;
        }

        if (isset($_POST['c_signal']) || isset(dcCore::app()->ctx->comment_preview['signal'])) {     // @phpstan-ignore-line
            return '&signal=1';
        }
    }
}

dcCore::app()->addBehaviors([
    'publicCommentFormBeforeContent' => [signalPublicBehaviors::class, 'publicCommentFormBeforeContent'],
    'publicBeforeCommentPreview'     => [signalPublicBehaviors::class, 'publicBeforeCommentPreview'],
    'publicBeforeCommentCreate'      => [signalPublicBehaviors::class, 'publicBeforeCommentCreate'],
    'publicBeforeCommentRedir'       => [signalPublicBehaviors::class, 'publicBeforeCommentRedir'],
]);

class signalPublicTpl
{
    /*dtd
    <!ELEMENT tpl:SysIfCommentPending - - -- Container displayed if comment is pending after submission -->
     */
    public static function SysIfCommentPending($attr, $content)
    {
        // Void code, used by translation tool
        __('Your private comment has been submitted.');

        return
            '<?php if (isset($_GET[\'pub\']) && $_GET[\'pub\'] == 0) : ?>' . "\n" .
            '  <?php if (isset($_GET[\'signal\']) && $_GET[\'signal\'] == 1) : ?>' . "\n" .
            '    <p class="message" id="pr">' . "<?php echo __('Your private comment has been submitted.'); ?>" . '</p>' . "\n" .
            '  <?php else: ?>' . "\n" .
            $content .
            '  <?php endif; ?>' . "\n" .
            '<?php endif; ?>';
    }
}

dcCore::app()->tpl->addBlock('SysIfCommentPending', [signalPublicTpl::class, 'SysIfCommentPending']);
