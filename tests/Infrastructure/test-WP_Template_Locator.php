<?php

namespace PMac\DemoContentTemplates\Tests;

use PMac\DemoContentTemplates\Infrastructure\WP_Template_Locator;

class WP_Template_LocatorTest extends \WP_UnitTestCase {

  public function test_locate() {
    $wp_template_locator = new WP_Template_Locator();
    $this->go_to('/');
    $template = $wp_template_locator->locate();
    $this->assertContains('index.php', $template);
  }

}
