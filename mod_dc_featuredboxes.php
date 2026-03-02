<?php

/**
 * @package     DC Featured Boxes (mod_dc_featuredboxes)
 * @copyright   Copyright (C) 2026. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Registry\Registry;

$boxesRaw = $params->get('boxes', []);

if (is_string($boxesRaw)) {
	$decoded = json_decode($boxesRaw, true);
	$boxes   = is_array($decoded) ? $decoded : [];
} elseif (is_object($boxesRaw)) {
	$boxes = (array) $boxesRaw;
} elseif (is_array($boxesRaw)) {
	$boxes = $boxesRaw;
} else {
	$boxes = [];
}

// Normalize to sequential array and ensure each item is array/object (not Registry)
$boxes = array_values($boxes);
$boxes = array_map(function ($item) {
	if ($item instanceof Registry) {
		return $item->toArray();
	}
	if (is_object($item)) {
		return (array) $item;
	}
	return is_array($item) ? $item : [];
}, $boxes);

require ModuleHelper::getLayoutPath('mod_dc_featuredboxes', $params->get('layout', 'default'));
