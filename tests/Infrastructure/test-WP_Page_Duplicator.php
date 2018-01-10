<?php

namespace PMac\DemoContentTemplates\Tests;

use PMac\DemoContentTemplates\Infrastructure\WP_Page_Duplicator;

/**
 * Class PostTypeConverterTest
 * @author: PMac
 * @subpackage PMac\DemoContentTemplates\PostTypeConverterTest
 */
class WP_Page_DuplicatorTest extends \WP_UnitTestCase {

  /**
   * @var WP_Page_Duplicator
   */
  protected $wp_page_duplicator;

  public function setUp() {
    $this->wp_page_duplicator = new WP_Page_Duplicator();
  }

  public function test_can_duplicate_meta() {
    $page1 = get_post($this->factory->post->create(array(
      'post_title' => 'Test Page1',
      'post_type'  => 'page'
    )));
    add_post_meta($page1->ID, 'meta_key', 'meta_value');

    $page2_id = $this->factory->post->create(array(
      'post_title' => 'Test Page2',
      'post_type'  => 'page'
    ));
    $this->wp_page_duplicator->duplicate_meta($page2_id, $page1);

    $this->assertEquals('meta_value', get_post_meta($page2_id, 'meta_key', TRUE));
  }

  public function test_can_duplicate_attachment() {
    $page1 = get_post($this->factory->post->create(array(
      'post_title' => 'Test Page1',
      'post_type'  => 'page'
    )));
    // this image is smaller than the thumbnail size so it won't have one
    $filename = (dirname(__FILE__) . '/../test-data/images/sample-image.jpg');
    $contents = file_get_contents($filename);

    $upload = wp_upload_bits(basename($filename), NULL, $contents);
    $this->assertTrue(empty($upload['error']));

    $id = $this->make_attachment($upload, $page1->ID);

    $page2_id = $this->factory->post->create(array(
      'post_title' => 'Test Page2',
      'post_type'  => 'page'
    ));
    $this->wp_page_duplicator->duplicate_attachments($page2_id, $page1);

    $page2_attachments = get_attached_media('image', $page2_id);

    $this->assertCount(1, $page2_attachments);

  }

  public function test_can_duplicate_page() {
    $page1 = get_post($this->factory->post->create(array(
      'post_title' => 'Test Page1',
      'post_type'  => 'page'
    )));
    $page2 = get_post($this->wp_page_duplicator->duplicate($page1, 'post'));

    $this->assertEquals('Test Page1', $page2->post_title);

  }

  protected function make_attachment($upload, $parent_post_id = 0) {

    $type = '';
    if (!empty($upload['type'])) {
      $type = $upload['type'];
    }
    else {
      $mime = wp_check_filetype($upload['file']);
      if ($mime) {
        $type = $mime['type'];
      }
    }

    $attachment = array(
      'post_title'     => basename($upload['file']),
      'post_content'   => '',
      'post_type'      => 'attachment',
      'post_parent'    => $parent_post_id,
      'post_mime_type' => $type,
      'guid'           => $upload['url'],
    );

    // Save the data
    $id = wp_insert_attachment($attachment, $upload['file'], $parent_post_id);
    wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $upload['file']));

    return $id;

  }

}