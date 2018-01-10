<?php

namespace PMac\DemoContentTemplates\Infrastructure;

/**
 * Class WP_Template_Locator
 *
 * @package PMac\DemoContentTemplates\Infrastructure
 * @subpackage PMac\DemoContentTemplates\Infrastructure\WP_Template_Locator
 */
class WP_Template_Locator {
    public function locate() {

      if ( is_front_page()     && $template = get_front_page_template()     ) :
      elseif ( is_home()           && $template = get_home_template()           ) :
      elseif ( is_post_type_archive() && $template = get_post_type_archive_template() ) :
      elseif ( is_tax()            && $template = get_taxonomy_template()       ) :
      elseif ( is_attachment()     && $template = get_attachment_template()     ) :
        remove_filter('the_content', 'prepend_attachment');
      elseif ( is_single()         && $template = get_single_template()         ) :
      elseif ( is_page()           && $template = get_page_template()           ) :
      elseif ( is_singular()       && $template = get_singular_template()       ) :
      elseif ( is_category()       && $template = get_category_template()       ) :
      elseif ( is_tag()            && $template = get_tag_template()            ) :
      elseif ( is_author()         && $template = get_author_template()         ) :
      elseif ( is_date()           && $template = get_date_template()           ) :
      elseif ( is_archive()        && $template = get_archive_template()        ) :
      else :
        $template = get_index_template();
      endif;

      return $template;
    }
}