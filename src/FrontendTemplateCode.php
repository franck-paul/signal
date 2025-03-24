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

class FrontendTemplateCode
{
    /**
     * PHP code for tpl:SysIfCommentPending block
     */
    public static function SysIfCommentPending(
        string $_content_HTML,
    ): void {
        if (isset($_GET['pub']) && (int) $_GET['pub'] === 0) :
            if (isset($_GET['signal']) && (int) $_GET['signal'] === 1) : ?>
                <p class="message" id="pr"><?= __('Your private comment has been submitted.') ?></p>
            <?php else : ?>
                $_content_HTML
            <?php endif;
        endif;
    }
}
