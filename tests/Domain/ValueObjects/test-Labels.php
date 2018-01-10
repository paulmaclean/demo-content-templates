<?php
namespace PMac\DemoContentTemplates\Tests;
use PMac\DemoContentTemplates\Domain\ValueObjects\Labels;

class LabelsTest extends \WP_UnitTestCase {

  function test_can_get_attributes() {
    $labels = new Labels('labels', 'label');
    $this->assertEquals('labels', $labels->name());
    $this->assertEquals('label', $labels->singular_name());
  }
}