<?php

namespace PMac\DemoContentTemplates\Infrastructure;

/**
 * Class PageDuplicator
 *
 * @package PMac\DemoContentTemplates\Infrastructure
 * @subpackage PMac\DemoContentTemplates\Infrastructure\PageDuplicator
 */
class WP_Page_Duplicator {

  /**
   * @param \WP_Post $post
   * @param string $post_type
   * @param int $parent_id
   * @param string $post_name_postfix
   * @param bool $add_post_name_postfix
   * @param string $status
   *
   * @return int|\WP_Error
   */
  public function duplicate(\WP_Post $post, $post_type, $parent_id = 0, $post_name_postfix = '', $add_post_name_postfix = FALSE, $status = 'publish') {

    $new_post = array(
      'menu_order'            => $post->menu_order,
      'comment_status'        => $post->comment_status,
      'ping_status'           => $post->ping_status,
      'post_author'           => $this->post_author($post),
      'post_content'          => $post->post_content,
      'post_content_filtered' => $post->post_content_filtered,
      'post_excerpt'          => $post->post_excerpt,
      'post_mime_type'        => $post->post_mime_type,
      'post_parent'           => $parent_id,
      'post_password'         => $post->post_password,
      'post_status'           => $status,
      'post_title'            => $this->title($post),
      'post_type'             => $post_type,
      'post_name'             => $this->post_name($post, $post_type, $parent_id, $post_name_postfix, $add_post_name_postfix),
    );

    $new_post_id = wp_insert_post(wp_slash($new_post));

    if ($new_post_id !== 0 && !is_wp_error($new_post_id)) {
      self::duplicate_attachments($new_post_id, $post);
      self::duplicate_meta($new_post_id, $post);
    }

    return $new_post_id;
  }

  /**
   * @param $new_id
   * @param \WP_Post $post
   */
  public function duplicate_meta($new_id, \WP_Post $post) {
    $meta_keys = get_post_custom_keys($post->ID);
    if (empty($meta_keys)) {
      return;
    }

    foreach ($meta_keys as $meta_key) {
      $meta_values = get_post_custom_values($meta_key, $post->ID);
      foreach ($meta_values as $meta_value) {
        $meta_value = maybe_unserialize($meta_value);
        add_post_meta($new_id, $meta_key, $this->duplicate_post_wp_slash($meta_value));
      }
    }
  }

  /**
   * @param $new_id
   * @param $post
   *
   * @throws \Exception
   */
  public function duplicate_attachments($new_id, $post) {
    // get thumbnail ID
    $old_thumbnail_id = get_post_thumbnail_id($post->ID);
    // get children
    $children = get_posts(array(
      'post_type'   => 'any',
      'numberposts' => -1,
      'post_status' => 'any',
      'post_parent' => $post->ID
    ));
    // clone old attachments
    foreach ($children as $child) {
      if ($child->post_type != 'attachment') {
        continue;
      }

      $new_attachment_id = $this->duplicate_attachment($new_id, $child);

      if (is_wp_error($new_attachment_id)) {
        throw new \Exception('Error attaching file: ' . $new_attachment_id);
        continue;
      }

      $alt_title = get_post_meta($child->ID, '_wp_attachment_image_alt', TRUE);
      if ($alt_title) {
        update_post_meta($new_attachment_id, '_wp_attachment_image_alt', wp_slash($alt_title));
      }

      // if we have cloned the post thumbnail, set the copy as the thumbnail for the new post
      if ($old_thumbnail_id == $child->ID) {
        set_post_thumbnail($new_id, $new_attachment_id);
      }

    }
  }

  /**
   * @param $new_id
   * @param $child
   *
   * @return int|\WP_Error
   */
  protected function duplicate_attachment($new_id, $child) {
    $filename      = get_attached_file($child->ID);
    $filetype      = wp_check_filetype(basename($filename), NULL);
    $wp_upload_dir = wp_upload_dir();

    $attachment        = array(
      'guid'           => $wp_upload_dir['url'] . '/' . basename($filename),
      'post_mime_type' => $filetype['type'],
      'post_title'     => $child->post_title,
      'post_excerpt'   => $child->post_excerpt,
      'post_content'   => '',
      'post_status'    => $child->post_status,
      'post_author'    => wp_get_current_user()->ID
    );
    $new_attachment_id = wp_insert_attachment($attachment, $filename, $new_id);
    return $new_attachment_id;
  }

  /**
   * @param $value
   *
   * @return string
   */
  public function duplicate_post_addslashes_to_strings_only($value) {
    return is_string($value) ? addslashes($value) : $value;
  }

  /**
   * @param $value
   *
   * @return array|mixed|string
   */
  public function duplicate_post_wp_slash($value) {
    if (function_exists('map_deep')) {
      return map_deep($value, array(
        $this,
        'duplicate_post_addslashes_to_strings_only'
      ));
    }
    else {
      return wp_slash($value);
    }
  }

  /**
   * @param $post
   *
   * @return string
   */
  protected function title($post) {
    $title = $post->post_title;
    if ($post->post_type != 'attachment') {
      if ($title == '') {
        // empty title
        $title = __('Untitled');
      }
    }
    return $title;
  }


  /**
   * @param $post
   *
   * @return int
   */
  protected function post_author($post) {
    $new_post_author    = wp_get_current_user();
    $new_post_author_id = $new_post_author->ID;
    // check if the user has the right capability
    if (is_post_type_hierarchical($post->post_type)) {
      if (current_user_can('edit_others_pages')) {
        $new_post_author_id = $post->post_author;
      }
    }
    else {
      if (current_user_can('edit_others_posts')) {
        $new_post_author_id = $post->post_author;
      }
    }
    return $new_post_author_id;
  }

  /**
   * @param $post
   * @param $post_type
   * @param $post_parent
   * @param $post_name_postfix
   * @param $add_post_name_postfix
   *
   * @return mixed|string
   */
  public function post_name($post, $post_type, $post_parent, $post_name_postfix, $add_post_name_postfix) {
    if ($add_post_name_postfix) {
      $post_name = $post->post_name . $post_name_postfix;
    }
    else {
      $post_name = str_replace($post_name_postfix, '', $post->post_name);
    }

    if (self::the_slug_exists($post_name, $post_type)) {
      $post_name = wp_unique_post_slug($post_name, $post->ID, $post->post_status, $post_type, $post_parent);
    }

    return $post_name;
  }

  /**
   * @param $post_name
   * @param $post_type
   *
   * @return bool
   */
  public function the_slug_exists($post_name, $post_type) {
    global $wpdb;
    $posts = $wpdb->get_row($wpdb->prepare("SELECT post_name FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status= %s", $post_name, $post_type, 'publish'));
    if ($posts) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}