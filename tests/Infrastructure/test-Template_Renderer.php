<?php

namespace PMac\DemoContentTemplates\Tests;

use PMac\DemoContentTemplates\Infrastructure\Template_Renderer;

class Template_RendererTest extends \WP_UnitTestCase {

  public function test_renders_template() {
    $template_renderer = new Template_Renderer();
    $template       = $template_renderer->render_template(['key' => 'value'], dirname(__FILE__) . '/../test-data/templates/test-template.php', false);
    $this->assertContains('value', $template);
  }

}
