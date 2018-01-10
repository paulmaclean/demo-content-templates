<?php

namespace PMac\DemoContentTemplates\Application\Handlers;

use PMac\DemoContentTemplates\Infrastructure\Template_Renderer;
use PMac\DemoContentTemplates\Infrastructure\WP_Adapter;

/**
 * Class ViewSubmenuHandler
 *
 * @package PMac\DemoContentTemplates\Application\Handlers
 * @subpackage PMac\DemoContentTemplates\Application\Handlers\ViewSubmenuHandler
 */
class SubMenuViewHandler {
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
   * @var string
   */
  protected $template_post_type_name;

  /**
   * @var array
   */
  protected $form_actions;

  /**
   * Constructor.
   *
   * @param WP_Adapter $wp_adapter
   * @param Template_Renderer $template_renderer
   * @param string $template_path
   * @param string $template_post_type_name
   */
  public function __construct(WP_Adapter $wp_adapter, Template_Renderer $template_renderer, $template_path, $template_post_type_name, $form_actions) {
    $this->wp_adapter              = $wp_adapter;
    $this->template_renderer       = $template_renderer;
    $this->template_path           = $template_path;
    $this->template_post_type_name = $template_post_type_name;
    $this->form_actions            = $form_actions;
  }

  public function handle() {
    $scope = [
      'page_checkboxes'             => $this->make_checkboxes('page'),
      'content_template_checkboxes' => $this->make_checkboxes($this->template_post_type_name, 'ASC'),
      'form_actions'                => $this->form_actions
    ];

    $this->template_renderer->render_template($scope, $this->template_path);
  }

  protected function make_checkboxes($post_type, $order = 'DESC') {
    $args = array(
      'post_type'   => $post_type,
      'numberposts' => -1,
      'order'       => $order
    );
    return $this->wp_adapter->walker_page_checkboxes($this->wp_adapter->get_posts($args), 4);
  }
}