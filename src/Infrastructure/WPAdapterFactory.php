<?php

namespace PMac\DemoContentTemplates\Infrastructure;

/**
 * Class WPAdapterFactory
 *
 * @package PMac\DemoContentTemplates\Infrastructure
 * @subpackage PMac\DemoContentTemplates\Infrastructure\WPAdapterFactory
 */
class WPAdapterFactory {
    public static function make() {
      return new WP_Adapter(new WP_Template_Locator(), new Walker_Page_Checkbox(), WP_Persistent_Notices::Instance(), new WP_Page_Duplicator());
    }
}