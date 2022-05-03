<?php

/**
 * Plugin Name:       Q 102014
 * Description:       Example static block scaffolded with Create Block tool.
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       q102014
 *
 * @package           create-block
 */

function q102014_custom_render($attributes, $content)
{
	$response = wp_remote_get("https://www.wikidata.org/wiki/Special:EntityData/Q72.json", array());
	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, true);
	$claims = $data['entities']['Q72']['claims']['P1082'];
	$found = null;
	foreach ($claims as $key => $value) {
		if ($value['rank'] === 'preferred') {
			$found = $value;
			break;
		}
	}
	if ($found !== null) {
		$value = intval($found['mainsnak']['datavalue']['value']['amount']);
		return str_replace("[Q72]", $value, $content);
	} else {
		return $content;
	}
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_q_102014_block_init()
{
	//register_block_type(__DIR__ . '/build');
	register_block_type('core/paragraph2', array(
		'render_callback' => 'q102014_custom_render'
	));
}

add_filter('register_block_type_args', 'core_image_block_type_args', 10, 3);

function core_image_block_type_args($args, $name)
{
	if ($name == 'core/paragraph') {
		$args['render_callback'] = 'q102014_custom_render';
	}
	return $args;
}

add_action('init', 'create_block_q_102014_block_init');
