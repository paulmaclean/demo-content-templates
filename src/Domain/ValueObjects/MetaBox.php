<?php

namespace PMac\DemoContentTemplates\Domain\ValueObjects;

/**
 * Class MetaBox
 *
 * @package PMac\DemoContentTemplates\Domain\ValueObjects
 * @subpackage PMac\DemoContentTemplates\Domain\ValueObjects\MetaBox
 */
class MetaBox {
  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $title;

  /**
   * @var callable
   */
  protected $callable;

  /**
   * @var string
   */
  protected $screen;

  /**
   * @var string
   */
  protected $context;
  
  /**
   * @var string
   */
  protected $priority;

  /**
   * Constructor.
   *
   * @param string $id
   * @param string $title
   * @param callable $callable
   * @param string $screen
   * @param string $context
   * @param string $priority
   */
  public function __construct($id, $title, callable $callable, $screen, $context, $priority) {
    $this->id       = $id;
    $this->title    = $title;
    $this->callable = $callable;
    $this->screen   = $screen;
    $this->context  = $context;
    $this->priority = $priority;
  }

  /**
   * @return string
   */
  public function id() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function title() {
    return $this->title;
  }

  /**
   * @return callable
   */
  public function callableMethod() {
    return $this->callable;
  }

  /**
   * @return string
   */
  public function screen() {
    return $this->screen;
  }

  /**
   * @return string
   */
  public function context() {
    return $this->context;
  }

  /**
   * @return string
   */
  public function priority() {
    return $this->priority;
  }

}