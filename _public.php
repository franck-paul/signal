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

$core->addBehavior('publicCommentFormBeforeContent', ['signalPublicBehaviors', 'publicCommentFormBeforeContent']);
$core->addBehavior('publicBeforeCommentPreview', ['signalPublicBehaviors', 'publicBeforeCommentPreview']);
$core->addBehavior('publicBeforeCommentCreate', ['signalPublicBehaviors', 'publicBeforeCommentCreate']);
$core->addBehavior('publicBeforeCommentRedir', ['signalPublicBehaviors', 'publicBeforeCommentRedir']);

$core->tpl->addBlock('SysIfCommentPending', ['signalPublicTpl', 'SysIfCommentPending']);

class signalPublicBehaviors
{
    public static function publicBeforeCommentPreview($comment_preview)
    {
        global $core;

        $core->blog->settings->addNameSpace('signal');
        if ($core->blog->settings->signal->enabled) {
            // Keep signal checkbox state during preview
            if (isset($_POST['c_signal'])) {
                $comment_preview['signal'] = $_POST['c_signal'];
            }
        }
    }

    public static function publicCommentFormBeforeContent($core, $_ctx)
    {
        $core->blog->settings->addNameSpace('signal');
        if ($core->blog->settings->signal->enabled) {
            $checked = false;
            if (isset($_ctx->comment_preview['signal'])) {
                // Restore signal checkbox if necessary
                $checked = true;
            }
            $label = $core->blog->settings->signal->label != '' ?
                html::escapeHTML($core->blog->settings->signal->label) :
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
        global $core, $_ctx;

        $core->blog->settings->addNameSpace('signal');
        if (!$core->blog->settings->signal->enabled) {
            return;
        }

        if (isset($_POST['c_signal']) || isset($_ctx->comment_preview['signal'])) {
            if ($cur->comment_status == 1) {
                // Move status from published to pending
                $cur->comment_status = -1;
            }
        }
    }

    public static function publicBeforeCommentRedir($cur)
    {
        global $core;

        $core->blog->settings->addNameSpace('signal');
        if (!$core->blog->settings->signal->enabled) {
            return;
        }

        if (isset($_POST['c_signal']) || isset($_ctx->comment_preview['signal'])) {
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
