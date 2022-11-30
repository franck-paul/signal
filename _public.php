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

class signalPublicBehaviors
{
    public static function publicBeforeCommentPreview($comment_preview)
    {
        dcCore::app()->blog->settings->addNameSpace('signal');
        if (dcCore::app()->blog->settings->signal->enabled && isset($_POST['c_signal'])) {
            // Keep signal checkbox state during preview
            $comment_preview['signal'] = $_POST['c_signal'];
        }
    }

    public static function publicCommentFormBeforeContent()
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

        if ((isset($_POST['c_signal']) || isset(dcCore::app()->ctx->comment_preview['signal'])) && $cur->comment_status == dcBlog::COMMENT_PUBLISHED) {
            // Move status from published to pending
            $cur->comment_status = dcBlog::COMMENT_PENDING;
        }
    }

    public static function publicBeforeCommentRedir()
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

dcCore::app()->addBehavior('publicCommentFormBeforeContent', [signalPublicBehaviors::class, 'publicCommentFormBeforeContent']);
dcCore::app()->addBehavior('publicBeforeCommentPreview', [signalPublicBehaviors::class, 'publicBeforeCommentPreview']);
dcCore::app()->addBehavior('publicBeforeCommentCreate', [signalPublicBehaviors::class, 'publicBeforeCommentCreate']);
dcCore::app()->addBehavior('publicBeforeCommentRedir', [signalPublicBehaviors::class, 'publicBeforeCommentRedir']);

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
