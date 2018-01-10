<?php
namespace PMac\DemoContentTemplates\Infrastructure;

/**
 * Class WP_Post_Type_Spoof
 *
 * @package PMac\DemoContentTemplates\Application
 * @subpackage PMac\DemoContentTemplates\Application\WP_Post_Type_Spoof
 */
class WP_Post_Type_Spoof {

  /**
   * @var WP_Adapter
   */
  protected $wp_adapter;

  /**
   * @var string
   */
  protected $post_type_name;

  /**
   * @var string
   */
  protected $slug;

  /**
   * Constructor.
   *
   * @param WP_Adapter $wp_adapter
   * @param string $post_type_name
   * @param $slug
   */
  public function __construct(WP_Adapter $wp_adapter, $post_type_name, $slug) {
    $this->wp_adapter = $wp_adapter;
    $this->post_type_name = $post_type_name;
    $this->slug = $slug;
  }

  public function spoof_existing_template($template) {
    if (strpos($_SERVER['REQUEST_URI'], $this->post_type_name . '/') === -1) {
      return $template;
    }
    $original_query = clone $this->wp_adapter->wp_query();

    $this->rewrite_request_uri();
    $this->wp_adapter->parse_and_query();
    $template = $this->wp_adapter->locate_template();
    if (!$this->wp_adapter->wp_query()->post_count) {
      $original_query->is_page = true;
      $original_query->is_single = false;
      $this->wp_adapter->set_wp_query($original_query);
    }

    return $template;
  }

  public function rewrite_request_uri() {
    $_SERVER['REQUEST_URI'] = str_replace("$this->slug/", '', $_SERVER['REQUEST_URI']);
    $_SERVER['REQUEST_URI'] = str_replace("-" . $this->post_type_name, '', $_SERVER['REQUEST_URI']);
  }
}