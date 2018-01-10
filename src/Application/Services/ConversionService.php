<?php

namespace PMac\DemoContentTemplates\Application\Services;

use PMac\DemoContentTemplates\Infrastructure\WP_Adapter;

/**
 * Class PostConversionService
 *
 * @subpackage PMac\DemoContentTemplates\Application\Services\PostConversionService
 */
class ConversionService {

  protected $wp;

  /**
   * Constructor.
   *
   * @param WP_Adapter $wp_adapter
   */
  public function __construct(WP_Adapter $wp_adapter) {
    $this->wp = $wp_adapter;
  }


  /**
   * @param array $post_ids
   * @param string $convert_from
   * @param string $convert_to
   * @param bool $flatten_hierarchy
   * @param string $post_name_postfix
   * @param bool $add_post_name_postfix
   * @param string $status
   *
   * @return array|\WP_Error
   */
  public function convert($post_ids, $convert_from, $convert_to, $flatten_hierarchy = FALSE, $post_name_postfix = '', $add_post_name_postfix = FALSE, $status = 'publish') {
    $converted_ids = [];
    foreach ($post_ids as $post_id) {
      $post      = $this->wp->get_post($post_id);
      $parent_id = $this->parent_id($post, $convert_to, $flatten_hierarchy);
      $converted_ids[] = $this->wp->duplicate($post, $convert_to, $parent_id, $post_name_postfix, $add_post_name_postfix, $status);
    }
    return $converted_ids;
  }

  /**
   * @param \WP_Post $post
   * @param string $post_type
   * @param bool $flatten_hierarchy
   *
   * @return int
   * @throws \Exception
   */
  protected function parent_id($post, $post_type, $flatten_hierarchy) {
    $parent_id = 0;
    if ($flatten_hierarchy) {
      return $parent_id;
    }
    if ($post->post_parent) {
      $parent          = $this->wp->get_post($post->post_parent);
      $existing_parent = $this->wp->get_post_by_title($parent->post_title, $post_type);
      if ($existing_parent) {
        $parent_id = $existing_parent->ID;
      }
      else {
        throw new \Exception('Parent for ' . $post->post_title . ' not found. Please add the parent or choose the flatten hierarchy option.');
      }
    }
    return $parent_id;
  }

}