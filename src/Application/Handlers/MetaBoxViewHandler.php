<?php

namespace PMac\DemoContentTemplates\Application\Handlers;
use PMac\DemoContentTemplates\Infrastructure\Template_Renderer;
use PMac\DemoContentTemplates\Infrastructure\WP_Adapter;

/**
 * Class MetaBoxViewHandler
 *
 * @package PMac\DemoContentTemplates\Application\Handlers
 * @subpackage PMac\DemoContentTemplates\Application\Handlers\MetaBoxViewHandler
 */
class MetaBoxViewHandler {

  /**
   * @var WP_Adapter
   */
  protected $wp_adapter;

  /**
   * @var Template_Renderer
   */
  protected $template_renderer;

  /**
   * @var string
   */
  protected $template_path;


  /**
   * Constructor.
   *
   * @param \PMac\DemoContentTemplates\Infrastructure\WP_Adapter $wp_adapter
   * @param \PMac\DemoContentTemplates\Infrastructure\Template_Renderer $template_renderer
   * @param $template_path
   */
  public function __construct(WP_Adapter $wp_adapter, Template_Renderer $template_renderer, $template_path) {
    $this->wp_adapter        = $wp_adapter;
    $this->template_renderer = $template_renderer;
    $this->template_path     = $template_path;
  }

  public function handle() {
    $scope = [
      'meta_items' => $this->wp_adapter->get_post_meta($this->wp_adapter->post()->ID)
    ];

    $this->template_renderer->render_template($scope, $this->template_path);
  }
}