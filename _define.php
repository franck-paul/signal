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

$this->registerModule(
	/* Name */			"Signal",
	/* Description*/		"Private comments to the author",
	/* Author */			"Franck Paul",
	/* Version */			'0.2',
	array(
		/* Dependencies */	'requires' =>		array(array('core','2.9')),
		/* Permissions */	'permissions' =>	'admin',
		/* Type */			'type' =>			'plugin',
		/* Settings */		'settings' => 		array('blog' => '#params.signal')
	)
);
