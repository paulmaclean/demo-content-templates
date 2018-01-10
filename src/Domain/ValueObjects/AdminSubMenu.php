<?php

namespace PMac\DemoContentTemplates\Domain\ValueObjects;

/**
 * Class AdminSubMenu
 *
 * @package PMac\DemoContentTemplates
 * @subpackage PMac\DemoContentTemplates\AdminSubMenu
 */
class AdminSubMenu {

  /**
   * @var string
   */
  protected $parent_slug;
  /**
   * @var string
   */
  protected $page_title;
  /**
   * @var string
   */
  protected $menu_title;
  /**
   * @var string
   */
  protected $capability;
  /**
   * @var string
   */
  protected $menu_slug;
  /**
   * @var string
   */
  protected $template_path;

  /**
   * Constructor.
   *
   * @param string $parent_slug
   * @param string $page_title
   * @param string $menu_title
   * @param string $capability
   * @param string $menu_slug
   * @param string $template_path
   */
  public function __construct($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $template_path) {
    $this->parent_slug   = $parent_slug;
    $this->page_title    = $page_title;
    $this->menu_title    = $menu_title;
    $this->capability    = $capability;
    $this->menu_slug     = $menu_slug;
    $this->template_path = $template_path;
  }

  /**
   * @return string
   */
  public function parent_slug() {
    return $this->parent_slug;
  }

  /**
   * @return string
   */
  public function page_title() {
    return $this->page_title;
  }

  /**
   * @return string
   */
  public function menu_title() {
    return $this->menu_title;
  }

  /**
   * @return string
   */
  public function capability() {
    return $this->capability;
  }

  /**
   * @return string
   */
  public function menu_slug() {
    return $this->menu_slug;
  }

  /**
   * @return string
   */
  public function template_path() {
    return $this->template_path;
  }

}