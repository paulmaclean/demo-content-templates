<?php

namespace PMac\DemoContentTemplates\Infrastructure;

/**
 * Class PageCheckBoxWalker
 *
 * @subpackage PMac\DemoContentTemplates\View\PageCheckBoxWalker
 */
class Walker_Page_Checkbox extends \Walker_Page {

  public function start_lvl(&$output, $depth = 0, $args = array()) {
    $item_spacings = isset($args['item_spacing']) ? $args['item_spacing'] : '';
    $spacings = $this->spacing($item_spacings, $depth);
    $output   .= "{$spacings['newline']}{$spacings['tab']}<ul class='children'>{$spacings['newline']}";
  }

  public function end_lvl(&$output, $depth = 0, $args = array()) {
    $item_spacings = isset($args['item_spacing']) ? $args['item_spacing'] : '';
    $spacings = $this->spacing($item_spacings, $depth);
    $output   .= "{$spacings['tab']}</ul>{$spacings['newline']}";
  }

  public function start_el(&$output, $page, $depth = 0, $args = array(), $current_page = 0) {
    $item_spacings = isset($args['item_spacing']) ? $args['item_spacing'] : '';
    $spacings    = $this->spacing($item_spacings, $depth);
    $css_classes = $this->css_classes($page, $depth, $args);

    if ('' === $page->post_title) {
      /* translators: %d: ID of a post */
      $page->post_title = sprintf(__('#%d (no title)'), $page->ID);
    }

    $attrs = [
      'data-parentid' => 'data-parentid=' . $page->post_parent
    ];

    $output .= $spacings['tab'] . sprintf(
        '<li class="%2$s"><input id="%1$s" name="post_ids[%1$s]" type="checkbox"%4$s></input><label  for="%1$s">%3$s</label>',
        $page->ID,
        $css_classes,
        apply_filters('the_title', $page->post_title, $page->ID),
        implode(' ', $attrs)
      );

  }

  public function end_el(&$output, $page, $depth = 0, $args = array()) {
    $item_spacings = isset($args['item_spacing']) ? $args['item_spacing'] : '';
    $spacings = $this->spacing($item_spacings, $depth);
    $output   .= "</li>{$spacings['newline']}";
  }

  /**
   * @param string $item_spacing
   * @param int $depth
   *
   * @return array
   */
  protected function spacing($item_spacing, $depth) {
    if (isset($args['item_spacing']) && 'preserve' === $item_spacing) {
      $t = "\t";
      $n = "\n";
    }
    else {
      $t = '';
      $n = '';
    }
    $tab = str_repeat($t, $depth);

    return [
      'tab'     => $tab,
      'newline' => $n
    ];
  }

  /**
   * @param \WP_Post $page
   * @param int $depth
   * @param array $args
   *
   * @return string
   */
  protected function css_classes($page, $depth, $args, $current_page = FALSE) {
    $css_class = array('page_item', 'page-item-' . $page->ID);

    if (isset($args['pages_with_children'][$page->ID])) {
      $css_class[] = 'page_item_has_children';
    }

    if (!empty($current_page)) {
      $_current_page = get_post($current_page);
      if ($_current_page && in_array($page->ID, $_current_page->ancestors)) {
        $css_class[] = 'current_page_ancestor';
      }
      if ($page->ID == $current_page) {
        $css_class[] = 'current_page_item';
      }
      elseif ($_current_page && $page->ID == $_current_page->post_parent) {
        $css_class[] = 'current_page_parent';
      }
    }
    elseif ($page->ID == get_option('page_for_posts')) {
      $css_class[] = 'current_page_parent';
    }
    $css_classes = implode(' ', apply_filters('page_css_class', $css_class, $page, $depth, $args, $current_page));
    return $css_classes;
  }

}