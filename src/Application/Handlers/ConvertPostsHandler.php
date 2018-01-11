<?php

namespace PMac\DemoContentTemplates\Application\Handlers;

use PMac\DemoContentTemplates\Application\Services\ConversionService;
use PMac\DemoContentTemplates\Infrastructure\WP_Adapter;

/**
 * Class ConvertToTemplate
 *
 * @subpackage PMac\DemoContentTemplates\Application\Handlers
 */
class ConvertPostsHandler {

  /**
   * @var WP_Adapter
   */
  protected $wp_adapter;

  /**
   * @var ConversionService
   */
  protected $conversion_service;

  /**
   * @var string
   */
  protected $post_type_name;

  /**
   * @var string
   */
  protected $redirect_to;

  /**
   * Constructor.
   *
   * @param WP_Adapter $wp_adapter
   * @param ConversionService $conversion_service
   * @param string $post_type_name
   * @param string $redirect_to
   */
  public function __construct(WP_Adapter $wp_adapter, ConversionService $conversion_service, $post_type_name, $redirect_to) {
    $this->wp_adapter         = $wp_adapter;
    $this->conversion_service = $conversion_service;
    $this->post_type_name     = $post_type_name;
    $this->redirect_to        = $redirect_to;
  }

  public function handle() {
    $this->wp_adapter->verify_nonce('dct_convert_post');
    $input = $this->sanitize($_POST);
    try {
      $add_postfix = $input['convert_to'] === $this->post_type_name;
      $this->conversion_service->convert($input['post_ids'], $input['convert_from'], $input['convert_to'], $input['flatten_hierarchy'], '-' . $this->post_type_name, $add_postfix);
      $this->wp_adapter->add_notice([
        'type'        => 'success',
        'message'     => 'Conversion Successful',
        'dismissible' => TRUE
      ]);
    } catch (\Exception $e) {
      $this->wp_adapter->add_notice([
        'type'        => 'error',
        'message'     => $e->getMessage(),
        'dismissible' => TRUE
      ]);
    }
    $this->wp_adapter->admin_redirect($this->redirect_to);
  }

  protected function sanitize($posted) {
    $input = [
      'convert_from'      => $this->wp_adapter->sanitize_text($posted['convert_to']),
      'convert_to'        => $this->wp_adapter->sanitize_text($posted['convert_to']),
      'flatten_hierarchy' => isset($posted['flatten_hierarchy']) ? $this->wp_adapter->sanitize_text($posted['flatten_hierarchy']): false,
      'post_ids' => array_map( 'absint', array_keys($posted['post_ids'] ))
    ];

    return $input;
 }
}