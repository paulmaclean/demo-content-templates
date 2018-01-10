<?php
/*
Plugin Name: Demo Content Templates
Plugin URI: https://github.com/paulmaclean/demo-content-templates

Description: Store demo page content for later use.

Version: 1.0

Author: pmac8

Text Domain: demo-content-templates
*/

namespace PMac\DemoContentTemplates;

use PMac\DemoContentTemplates\Application\Handlers\ConvertPostsHandler;
use PMac\DemoContentTemplates\Application\Handlers\MetaBoxViewHandler;
use PMac\DemoContentTemplates\Application\Handlers\PageAttributesViewHandler;
use PMac\DemoContentTemplates\Application\Handlers\SavePostHandler;
use PMac\DemoContentTemplates\Application\Handlers\SubMenuViewHandler;
use PMac\DemoContentTemplates\Application\Services\ConversionService;
use PMac\DemoContentTemplates\Domain\ValueObjects\MetaBox;
use PMac\DemoContentTemplates\Infrastructure\Template_Renderer;
use PMac\DemoContentTemplates\Infrastructure\WP_Post_Type_Spoof;
use PMac\DemoContentTemplates\Domain\ValueObjects\PostType;
use PMac\DemoContentTemplates\Domain\ValueObjects\Labels;
use PMac\DemoContentTemplates\Domain\ValueObjects\Rewrite;
use PMac\DemoContentTemplates\Domain\ValueObjects\AdminSubMenu;
use PMac\DemoContentTemplates\Infrastructure\WP_Adapter;
use PMac\DemoContentTemplates\Infrastructure\WPAdapterFactory;

include(plugin_dir_path(__FILE__) . 'vendor/autoload.php');

add_action('init', 'PMac\DemoContentTemplates\dct_init', 99);

function dct_init() {
  $wp_adapter         = WPAdapterFactory::make();
  $template_renderer  = new Template_Renderer();
  $post_type_supports = array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions', 'page-attributes', 'post-formats');
  $post_type          = new PostType(new Labels('Demo Templates', 'Demo Template'), 'dct_template', TRUE, TRUE, new Rewrite('dct-templates'), TRUE, 'dashicons-book', $post_type_supports);
  $sub_menu           = new AdminSubMenu('tools.php', 'Demo Template Settings', 'Demo Template Settings', 'administrator', 'dct_admin', 'dct_admin_menu');

  bootstrap_template_post($wp_adapter, $post_type);
  bootstrap_submenu_page($wp_adapter, $sub_menu, $post_type, $template_renderer);
  bootstrap_submenu_processing($wp_adapter, $sub_menu, $post_type);
  bootstrap_page_attributes($wp_adapter, $template_renderer);
  boostrap_meta_box($wp_adapter, $template_renderer, $post_type);
}

/**
 * @param $wp_adapter
 * @param $template_renderer
 * @param $post_type
 */
function boostrap_meta_box(WP_Adapter $wp_adapter, Template_Renderer $template_renderer, PostType $post_type) {
  $template_path = plugin_dir_path(__FILE__) . 'src/View/templates/dct_metabox.php';
  $meta_box_handler = new MetaBoxViewHandler($wp_adapter, $template_renderer, $template_path);

  $meta_box = new MetaBox('other_custom_meta', 'Other Custom Meta', array(
    $meta_box_handler,
    'handle'
  ), $post_type->name(), 'normal', 'default');

  $wp_adapter->add_meta_box($meta_box);
}

/**
 * @param $wp_adapter
 * @param $template_renderer
 *
 * @return string
 */
function bootstrap_page_attributes(WP_Adapter $wp_adapter, Template_Renderer $template_renderer) {
  $template_path           = plugin_dir_path(__FILE__) . 'src/View/templates/dct_template_dropdown.php';
  $page_attributes_handler = new PageAttributesViewHandler($wp_adapter, $template_renderer, $template_path);

  add_action('page_attributes_misc_attributes', array(
    $page_attributes_handler,
    'handle'
  ));

}

function bootstrap_template_post(WP_Adapter $wp_adapter, PostType $post_type) {
  $wp_adapter->register_post_type($post_type);
  $wp_post_type_spoof = new WP_Post_Type_Spoof($wp_adapter, $post_type->name(), $post_type->rewrite()
                                                                                          ->slug());
  $wp_adapter->add_filter('template_include', array(
    $wp_post_type_spoof,
    'spoof_existing_template'
  ));

  $save_post_handler = new SavePostHandler($wp_adapter, $post_type->name());
  $wp_adapter->add_action('save_post', array($save_post_handler, 'handle'));
}

function bootstrap_submenu_page(WP_Adapter $wp_adapter, AdminSubMenu $sub_menu, PostType $post_type, Template_Renderer $template_renderer) {
  $form_actions          = ['dct_convert' => 'dct_convert'];
  $path                  = plugin_dir_path(__FILE__) . 'src/';
  $url                   = plugin_dir_url(__FILE__) . 'src/';
  $template_path         = $path . 'View/templates/' . $sub_menu->template_path() . '.php';
  $sub_menu_view_handler = new SubMenuViewHandler($wp_adapter, $template_renderer, $template_path, $post_type->name(), $form_actions);
  $wp_adapter->add_submenu($sub_menu, array($sub_menu_view_handler, 'handle'));

  $css_files = [
    ['name' => 'pure-css', 'path' => $url . '/View/assets/pure-min.css'],
    [
      'name' => 'dtc-custom-admin',
      'path' => $url . '/View/assets/admin-style.css'
    ],
  ];

  $wp_adapter->add_admin_scripts('tools_page_dct_admin', $css_files);
}

function bootstrap_submenu_processing(WP_Adapter $wp_adapter, AdminSubMenu $sub_menu, PostType $post_type) {
  $conversion_service = new ConversionService($wp_adapter);

  $wp_post_conversion_handler = new ConvertPostsHandler($wp_adapter, $conversion_service, $post_type->name(), $sub_menu->menu_slug());

  $wp_adapter->add_action_admin_post('dct_convert', array(
    $wp_post_conversion_handler,
    'handle'
  ));
}
