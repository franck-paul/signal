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

if (!defined('DC_CONTEXT_ADMIN')) { return; }

// dead but useful code, in order to have translations
__('Signal').__('Private comments to the author');

$core->addBehavior('adminBlogPreferencesForm',array('signalBehaviors','adminBlogPreferencesForm'));
$core->addBehavior('adminBeforeBlogSettingsUpdate',array('signalBehaviors','adminBeforeBlogSettingsUpdate'));

class signalBehaviors
{
	public static function adminBlogPreferencesForm($core,$settings)
	{
		$settings->addNameSpace('signal');
		echo
		'<div class="fieldset"><h4>'.__('Signal').'</h4>'.
		'<p><label class="classic">'.
		form::checkbox('signal_enabled','1',$settings->signal->enabled).
		__('Enable private comments to the author (or the moderator)').'</label></p>'.
		'</div>';
	}

	public static function adminBeforeBlogSettingsUpdate($settings)
	{
		$settings->addNameSpace('signal');
		$settings->signal->put('enabled',!empty($_POST['signal_enabled']),'boolean');
	}
}
