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

dcCore::app()->addBehavior('publicCommentFormBeforeContent', ['signalPublicBehaviors', 'publicCommentFormBeforeContent']);
dcCore::app()->addBehavior('publicBeforeCommentPreview', ['signalPublicBehaviors', 'publicBeforeCommentPreview']);
dcCore::app()->addBehavior('publicBeforeCommentCreate', ['signalPublicBehaviors', 'publicBeforeCommentCreate']);
dcCore::app()->addBehavior('publicBeforeCommentRedir', ['signalPublicBehaviors', 'publicBeforeCommentRedir']);

dcCore::app()->tpl->addBlock('SysIfCommentPending', ['signalPublicTpl', 'SysIfCommentPending']);

class signalPublicBehaviors
{
    public static function publicBeforeCommentPreview($comment_preview)
    {
        dcCore::app()->blog->settings->addNameSpace('signal');
        if (dcCore::app()->blog->settings->signal->enabled) {
            // Keep signal checkbox state during preview
            if (isset($_POST['c_signal'])) {
                $comment_preview['signal'] = $_POST['c_signal'];
            }
        }
    }

    public static function publicCommentFormBeforeContent($core = null, $_ctx = null)
    {
        dcCore::app()->blog->settings->addNameSpace('signal');
        if (dcCore::app()->blog->settings->signal->enabled) {
            $checked = false;
            if (isset(dcCore::app()->ctx->comment_preview['signal'])) {
                // Restore signal checkbox if necessary
                $checked = true;
            }
            $label = dcCore::app()->blog->settings->signal->label != '' ?
                html::escapeHTML(dcCore::app()->blog->settings->signal->label) :
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
        dcCore::app()->blog->settings->addNameSpace('signal');
        if (!dcCore::app()->blog->settings->signal->enabled) {
            return;
        }

        if (isset($_POST['c_signal']) || isset(dcCore::app()->ctx->comment_preview['signal'])) {
            if ($cur->comment_status == 1) {
                // Move status from published to pending
                $cur->comment_status = -1;
            }
        }
    }

    public static function publicBeforeCommentRedir($cur)
    {
        dcCore::app()->blog->settings->addNameSpace('signal');
        if (!dcCore::app()->blog->settings->signal->enabled) {
            return;
        }

        if (isset($_POST['c_signal']) || isset(dcCore::app()->ctx->comment_preview['signal'])) {     // @phpstan-ignore-line
            return '&signal=1';
        }
    }
}

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
