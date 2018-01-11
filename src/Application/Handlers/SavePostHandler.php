<?php

namespace PMac\DemoContentTemplates\Application\Handlers;

use PMac\DemoContentTemplates\Infrastructure\WP_Adapter;

/**
 * Class SavePostHandler
 *
 * @package PMac\DemoContentTemplates\Application\Handlers
 * @subpackage PMac\DemoContentTemplates\Application\Handlers\SavePostHandler
 */
class SavePostHandler {

  /**
   * @var WP_Adapter
   */
  protected $wp_adapter;

  /**
   * @var string
   */
  protected $post_type_name;

  /**
   * Constructor.
   *
   * @param WP_Adapter $wp_adapter
   * @param string $post_type_name
   */
  public function __construct(WP_Adapter $wp_adapter, $post_type_name) {
    $this->wp_adapter     = $wp_adapter;
    $this->post_type_name = $post_type_name;
  }

  /**
   * @param int $post_id
   */
  public function handle($post_id) {
    $this->update_meta($post_id);
  }

  /**
   * @param int $post_id
   */
  protected function update_meta($post_id) {
    if (!$this->wp_adapter->is_editing_post() || $this->wp_adapter->wp_is_post_revision($post_id) || $this->wp_adapter->get_post_type($post_id) !== $this->post_type_name) {
      return;
    }
    if ($this->wp_adapter->get_post_type($post_id) === $this->post_type_name) {
      foreach ($_POST['page_meta'] as $page_meta_key => $page_meta_value) {
        $page_meta_key = $this->wp_adapter->sanitize_text($page_meta_key);
        $page_meta_value = $this->wp_adapter->sanitize_text($page_meta_value);
        $this->wp_adapter->update_post_meta($post_id, $page_meta_key, $page_meta_value);
      }
      $this->wp_adapter->update_post_meta($post_id, '_wp_page_template', $_POST['_wp_page_template']);
    }
  }
}