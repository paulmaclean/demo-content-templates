<?php

namespace PMac\DemoContentTemplates\Domain\ValueObjects;

/**
 * Class Rewrite
 *
 * @package PMac\DemoContentTemplates\Domain\ValueObjects
 * @subpackage PMac\DemoContentTemplates\Domain\ValueObjects\Rewrite
 */
class Rewrite {
  /**
   * @var string
   */
  protected $slug;

  /**
   * Constructor.
   *
   * @param $slug
   */
  public function __construct($slug) {
    $this->slug = $slug;
  }

  /**
   * @return string
   */
  public function slug() {
    return $this->slug;
  }


}