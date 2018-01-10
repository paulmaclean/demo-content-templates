<?php
namespace PMac\DemoContentTemplates\Tests;
use PMac\DemoContentTemplates\Domain\ValueObjects\AdminSubMenu;

/**
 * Class PostTypeConverterTest
 *
 * @subpackage PMac\DemoContentTemplates\PostTypeConverterTest
 */
class AdminSubMenuTest extends \WP_UnitTestCase {

  function test_can_get_attributes() {
    $admin_menu = new AdminSubMenu('parent-slug', 'Page Title', 'Menu Title', 'capability', 'menu_slug','template_path');
    $this->assertEquals('parent-slug', $admin_menu->parent_slug());
    $this->assertEquals('Page Title', $admin_menu->page_title());
    $this->assertEquals('Menu Title', $admin_menu->menu_title());
    $this->assertEquals('capability', $admin_menu->capability());
    $this->assertEquals('menu_slug', $admin_menu->menu_slug());
    $this->assertEquals('template_path', $admin_menu->template_path());
  }
}