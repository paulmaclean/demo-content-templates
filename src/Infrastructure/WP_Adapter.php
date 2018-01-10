<?php

namespace PMac\DemoContentTemplates\Infrastructure;

use PMac\DemoContentTemplates\Domain\ValueObjects\AdminSubMenu;
use PMac\DemoContentTemplates\Domain\ValueObjects\MetaBox;
use PMac\DemoContentTemplates\Domain\ValueObjects\PostType;

/**
 * Class WP_Adaptor
 *
 * @package PMac\DemoContentTemplates\Infrastructure
 * @subpackage PMac\DemoContentTemplates\Infrastructure\WP_Adaptor
 */
class WP_Adapter {

  /**
   * @var WP_Template_Locator
   */
  protected $wp_template_locator;

  /**
   * @var Walker_Page_Checkbox
   */
  protected $walker_page_checkbox;

  /**
   * @var WP_Persistent_Notices
   */
  protected $wp_persistent_notices;

  /**
   * @var WP_Page_Duplicator
   */
  protected $wp_page_duplicator;

  /**
   * Constructor.
   *
   * @param WP_Template_Locator $wp_template_locator
   * @param Walker_Page_Checkbox $walker_page_checkbox
   * @param WP_Persistent_Notices $wp_persistent_notices
   * @param WP_Page_Duplicator $wp_page_duplicator
   */
  public function __construct(
    WP_Template_Locator $wp_template_locator,
    Walker_Page_Checkbox $walker_page_checkbox,
    WP_Persistent_Notices $wp_persistent_notices,
    WP_Page_Duplicator $wp_page_duplicator
  ) {
    $this->wp_template_locator   = $wp_template_locator;
    $this->wp_persistent_notices = $wp_persistent_notices;
    $this->walker_page_checkbox  = $walker_page_checkbox;
    $this->wp_page_duplicator    = $wp_page_duplicator;
  }

  public function register_post_type(PostType $post_type) {
    register_post_type($post_type->name(),
      // CPT Options
      array(
        'labels'       => array(
          'name'          => __($post_type->labels()->name()),
          'singular_name' => __($post_type->labels()->singular_name())
        ),
        'public'       => $post_type->is_public(),
        'has_archive'  => $post_type->has_archive(),
        'rewrite'      => array('slug' => $post_type->rewrite()->slug()),
        'hierarchical' => $post_type->is_hierarchical(),
        'menu_icon'    => $post_type->menu_icon(),
        'supports'     => $post_type->supports()
      )
    );
  }

  public function add_meta_box(MetaBox $metabox) {

    add_action('add_meta_boxes_dct_template', function () use($metabox){
      add_meta_box(
        $metabox->id(),
        $metabox->title(),
        $metabox->callableMethod(),
        $metabox->screen(),
        $metabox->context(),
        $metabox->priority()
      );
    });
  }

  /**
   * @param string $filter_name
   * @param callable $callable
   * @param int $priority
   */
  public function add_filter($filter_name, callable $callable, $priority = 99) {
    add_filter($filter_name, $callable, $priority);
  }

  /**
   * @param string $action_name
   * @param callable $callable
   * @param int $priority
   */
  public function add_action($action_name, callable $callable, $priority = 99) {
    add_action($action_name, $callable, $priority);
  }

  public function parse_and_query() {
    global $wp;
    $wp->parse_request();
    $wp->query_posts();
  }

  public function locate_template() {
    return $this->wp_template_locator->locate();
  }

  public function wp_query() {
    global $wp_query;
    return $wp_query;
  }

  public function set_wp_query($query) {
    global $wp_query;
    $wp_query = $query;
  }

  /**
   * @param AdminSubMenu $admin_sub_menu
   * @param callable $callable
   */
  public function add_submenu(AdminSubMenu $admin_sub_menu, callable $callable) {
    add_action('admin_menu', function () use ($admin_sub_menu, $callable) {
      add_submenu_page($admin_sub_menu->parent_slug(), $admin_sub_menu->page_title(), $admin_sub_menu->menu_title(), $admin_sub_menu->capability(), $admin_sub_menu->menu_slug(), $callable);
    });
  }

  /**
   * @param string $action_name
   * @param callable $callable
   */
  public function add_action_admin_post($action_name, callable $callable) {
    add_action('admin_post_' . $action_name, $callable);
  }

  public function add_notice($notice) {
    $this->wp_persistent_notices->add_notice($notice);
  }

  public function duplicate(\WP_Post $post, $post_type, $parent_id = 0, $post_name_postfix = '', $add_post_name_postfix = FALSE, $status = 'publish') {
    return $this->wp_page_duplicator->duplicate($post, $post_type, $parent_id, $post_name_postfix, $add_post_name_postfix, $status);
  }

  public function get_post($post = NULL, $output = OBJECT, $filter = 'raw') {
    return get_post($post, $output, $filter);
  }

  public function get_posts($args = array()) {
    return get_posts($args);
  }

  public function get_pages($args = array()) {
    return get_pages($args);
  }

  public function get_post_by_title($page_title, $post_type = 'post', $output = OBJECT) {
    global $wpdb;
    $posts = $wpdb->get_results($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type= %s AND post_status= %s", $page_title, $post_type, 'publish'));
    if ($posts) {
      return get_post(end($posts), $output);
    }

    return NULL;
  }

  public function admin_redirect($slug) {
    wp_redirect(admin_url('admin.php?page=' . $slug));
  }

  public function walker_page_checkboxes($elements, $max_depth = 4) {
    return $this->walker_page_checkbox->walk($elements, $max_depth);
  }

  public function add_admin_scripts($page_hook, $css_files) {
    add_action('admin_enqueue_scripts', function ($hook) use ($page_hook, $css_files) {
      if ($page_hook !== $hook) {
        return;
      }

      foreach ($css_files as $css_file) {
        wp_enqueue_style($css_file['name'], $css_file['path']);
        wp_enqueue_style($css_file['name'], $css_file['path']);
      }

    });

  }

  public function wp_is_post_revision($post_id) {
    return wp_is_post_revision($post_id);
  }

  public function get_post_type($post_id) {
    return get_post_type($post_id);
  }

  public function update_post_meta($post_id, $page_meta_key, $page_meta_value) {
    return update_post_meta($post_id, $page_meta_key, $page_meta_value);
  }

  public function page_template_dropdown_options($post_id, $post_type = 'page') {
    $default   = get_post_meta($post_id, '_wp_page_template', TRUE);
    $options   = '';
    $templates = get_page_templates(NULL, $post_type);
    ksort($templates);
    foreach (array_keys($templates) as $template) {
      $selected = selected($default, $templates[$template], FALSE);
      $options  .= "\n\t<option value='" . esc_attr($templates[$template]) . "' $selected>" . esc_html($template) . "</option>";
    }
    return $options;
  }

  public function get_post_meta($post_id, $key = '', $single = FALSE) {
    return get_post_meta($post_id, $key, $single);
  }

  public function post() {
    global $post;
    return $post;
  }

  public function is_editing_post() {
    return isset($_POST['action']) && $_POST['action'] === 'editpost';
  }
}