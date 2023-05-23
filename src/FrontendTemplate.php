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

class FrontendTemplate
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
