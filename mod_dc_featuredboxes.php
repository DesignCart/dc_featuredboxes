<?php

	/**
     * @package     DC Featuredboxes
     * @subpackage  Content Plugin
     * @author      Design Cart
     * @copyright   Copyright (C) 2025 Design Cart. All rights reserved.
     * @license     GNU General Public License version 3 or later; see LICENSE.txt
     *
     * This file is part of DC Featuredboxes.
     *
     * DC Featuredboxes is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 3 of the License, or
     * (at your option) any later version.
     *
     * DC Featuredboxes is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     * GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License
     * along with DC Featuredboxes. If not, see <https://www.gnu.org/licenses/>.
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
