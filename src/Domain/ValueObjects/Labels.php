<?php

namespace PMac\DemoContentTemplates\Domain\ValueObjects;

/**
 * Class Labels
 *
 * @subpackage PMac\DemoContentTemplates\Domain\ValueObjects\Labels
 */
class Labels {

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $singular_name;

  /**
   * Constructor.
   *
   * @param $name
   * @param $singular_name
   */
  public function __construct($name, $singular_name) {
    $this->name          = $name;
    $this->singular_name = $singular_name;
  }

  /**
   * @return string
   */
  public function name() {
    return $this->name;
  }

  /**
   * @return string
   */
  public function singular_name() {
    return $this->singular_name;
  }


}