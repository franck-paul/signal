<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of signal, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) { return; }

$core->addBehavior('publicCommentFormBeforeContent',array('signalPublicBehaviors','publicCommentFormBeforeContent'));
$core->addBehavior('publicBeforeCommentPreview',array('signalPublicBehaviors','publicBeforeCommentPreview'));
$core->addBehavior('publicBeforeCommentCreate',array('signalPublicBehaviors','publicBeforeCommentCreate'));

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

	public static function publicCommentFormBeforeContent($core,$_ctx)
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
				'<p class="signal">'.
			    	'<input name="c_signal" id="c_signal" type="checkbox" '.($checked ? 'checked="checked"' : '').' /> '.
		        	'<label for="c_signal">'.$label.'</label>'.
				'</p>';
		}
	}

	public static function publicBeforeCommentCreate($cur)
	{
		global $core,$_ctx;

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
}
