<?php

namespace PMac\DemoContentTemplates\Infrastructure;

/**
 * Class Template_Renderer
 *
 * @package PMac\DemoContentTemplates\Application
 * @subpackage PMac\DemoContentTemplates\Application\Template_Renderer
 */
class Template_Renderer {

  /**
   * @param array $scope
   * @param string $templatePath
   * @param bool $write
   *
   * @return string
   */
  public function render_template(array $scope, $templatePath, $write = TRUE) {
    extract($scope);
    ob_start();
    include($templatePath);
    $template = ob_get_clean();
    if ($write) {
      echo $template;
    }
    return $template;
  }
}