<?php

namespace PMac\DemoContentTemplates\Tests;

use PMac\DemoContentTemplates\Domain\ValueObjects\Labels;
use PMac\DemoContentTemplates\Domain\ValueObjects\PostType;
use PMac\DemoContentTemplates\Domain\ValueObjects\Rewrite;

class PostTypeTest extends \WP_UnitTestCase {

  function test_can_get_attributes() {
    $post_type = new PostType(new Labels('name', 'singular_name'), 'name', TRUE, TRUE, new Rewrite('slug'), TRUE, 'menu-icon', array('support'));
    $this->assertInstanceOf(Labels::class, $post_type->labels());
    $this->assertEquals('name', $post_type->name());
    $this->assertTrue($post_type->is_public());
    $this->assertTrue($post_type->has_archive());
    $this->assertInstanceOf(Rewrite::class, $post_type->rewrite());
    $this->assertTrue($post_type->is_hierarchical());
    $this->assertEquals('menu-icon', $post_type->menu_icon());
    $this->assertContains('support', $post_type->supports());
  }
}