<?php
namespace PMac\DemoContentTemplates\Tests;
use PMac\DemoContentTemplates\Domain\ValueObjects\Rewrite;

class RewriteTest extends \WP_UnitTestCase {

  function test_can_get_attributes() {
    $rewrite = new Rewrite('slug');
    $this->assertEquals('slug', $rewrite->slug());
  }
}