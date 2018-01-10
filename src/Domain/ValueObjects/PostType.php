<?php

namespace PMac\DemoContentTemplates\Domain\ValueObjects;

/**
 * Class PostType
 *
 * @package PMac\DemoContentTemplates\Entities
 * @subpackage PMac\DemoContentTemplates\Entities\PostType
 */
class PostType {
  /**
   * @var Labels
   */
  protected $labels;

  /**
   * @var string
   */
  protected $name;
  /**
   * @var boolean
   */
  protected $is_public;

  /**
   * @var boolean
   */
  protected $has_archive;

  /**
   * @var array
   */
  protected $rewrite;
  /**
   * @var boolean
   */
  protected $is_hierarchical;
  /**
   * @var string
   */
  protected $menu_icon;
  /**
   * @var array
   */
  protected $supports;

  /**
   * Constructor.
   *
   * @param Labels $labels
   * @param string $name
   * @param bool $is_public
   * @param bool $has_archive
   * @param Rewrite $rewrite
   * @param bool $is_hierarchical
   * @param string $menu_icon
   * @param array $supports
   */
  public function __construct(
    Labels $labels,
    $name,
    $is_public,
    $has_archive,
    Rewrite $rewrite,
    $is_hierarchical,
    $menu_icon,
    array $supports
  ) {
    $this->labels          = $labels;
    $this->name            = $name;
    $this->is_public       = $is_public;
    $this->has_archive     = $has_archive;
    $this->rewrite         = $rewrite;
    $this->is_hierarchical = $is_hierarchical;
    $this->menu_icon       = $menu_icon;
    $this->supports        = $supports;
  }

  /**
   * @return Labels
   */
  public function labels() {
    return $this->labels;
  }

  /**
   * @return string
   */
  public function name() {
    return $this->name;
  }

  /**
   * @return bool
   */
  public function is_public() {
    return $this->is_public;
  }

  /**
   * @return bool
   */
  public function has_archive() {
    return $this->has_archive;
  }

  /**
   * @return Rewrite
   */
  public function rewrite() {
    return $this->rewrite;
  }

  /**
   * @return bool
   */
  public function is_hierarchical() {
    return $this->is_hierarchical;
  }

  /**
   * @return string
   */
  public function menu_icon() {
    return $this->menu_icon;
  }

  /**
   * @return array
   */
  public function supports() {
    return $this->supports;
  }

}
