<?php

namespace PMac\DemoContentTemplates\Tests;

use PMac\DemoContentTemplates\Application\Services\ConversionService;
use PMac\DemoContentTemplates\Domain\ValueObjects\Labels;
use PMac\DemoContentTemplates\Domain\ValueObjects\PostType;
use PMac\DemoContentTemplates\Domain\ValueObjects\Rewrite;
use PMac\DemoContentTemplates\Infrastructure\WPAdapterFactory;
use \PMac\DemoContentTemplates\Infrastructure\WP_Adapter;

class ConversionServiceTest extends \WP_UnitTestCase {

  /**
   * @var ConversionService
   */
  protected $conversion_service;

  /**
   * @var WP_Adapter
   */
  protected $wp;

  /**
   * @var PostType
   */
  protected $post_type;

  public function setUp() {
    $this->wp                 = WPAdapterFactory::make();
    $this->conversion_service = new ConversionService($this->wp);
    $this->post_type          = new PostType(new Labels('Demo Templates', 'Demo Template'), 'dct_demo_template', TRUE, TRUE, new Rewrite('dct-theme-templates'), TRUE, 'dashicons-book', array());
  }


  public function test_convert_to_template() {
    $page1 = get_post($this->factory->post->create(array(
      'post_title' => 'Test Page1',
      'post_type'  => 'page'
    )));

    $input = [
      'convert_from' => 'page',
      'convert_to' => $this->post_type->name(),
      'page_ids'               => [
        $page1->ID => $page1->ID
      ],
      'flatten_page_hierarchy' => FALSE
    ];
    $converted_ids = $this->conversion_service->convert($input['page_ids'], $input['convert_from'], $input['convert_to'], $input['flatten_page_hierarchy'], $this->post_type->name(), TRUE);
    $content_template = $this->wp->get_post($converted_ids[0]);
    $this->assertEquals($this->post_type->name(), $content_template->post_type);
  }

  public function test_convert_to_template_hierarchical() {
    $page1 = get_post($this->factory->post->create(array(
      'post_title' => 'Test Page1',
      'post_type'  => 'page'
    )));

    $child_page = get_post($this->factory->post->create(array(
      'post_title' => 'Test Page1',
      'post_type'  => 'page',
      'post_parent' => $page1->ID
    )));

    $input = [
      'convert_from' => 'page',
      'convert_to' => $this->post_type->name(),
      'page_ids'               => [
        $page1->ID => $page1->ID,
        $child_page->ID => $child_page->ID
      ],
      'flatten_hierarchy' => FALSE
    ];
    $converted_ids = $this->conversion_service->convert($input['page_ids'], $input['convert_from'], $input['convert_to'], $input['flatten_hierarchy'], $this->post_type->name(), TRUE);
    $content_template_parent = $this->wp->get_post($converted_ids[0]);
    $content_template_child = $this->wp->get_post($converted_ids[1]);

    $this->assertEquals($content_template_parent->ID, $content_template_child->post_parent);
  }

  public function test_convert_to_page_hierarchical() {
    $page1 = get_post($this->factory->post->create(array(
      'post_title' => 'Test Template1',
      'post_type'  => $this->post_type->name()
    )));

    $child_page = get_post($this->factory->post->create(array(
      'post_title' => 'Test Child Template1',
      'post_type'  => $this->post_type->name(),
      'post_parent' => $page1->ID
    )));

    $input = [
      'convert_from' => $this->post_type->name(),
      'convert_to' => 'page',
      'page_ids'               => [
        $page1->ID => $page1->ID,
        $child_page->ID => $child_page->ID
      ],
      'flatten_hierarchy' => FALSE
    ];
    $converted_ids = $this->conversion_service->convert($input['page_ids'], $input['convert_from'], $input['convert_to'], $input['flatten_hierarchy'], $this->post_type->name(), TRUE);
    $page_parent = $this->wp->get_post($converted_ids[0]);
    $page_child = $this->wp->get_post($converted_ids[1]);

    $this->assertEquals($page_parent->ID, $page_child->post_parent);
  }

  public function test_convert_to_page() {
    $content_template1 = get_post($this->factory->post->create(array(
      'post_title' => 'Content Template1',
      'post_type'  => 'dct_theme_template'
    )));

    $input = [
      'convert_from' =>  $this->post_type->name(),
      'convert_to' => 'page',
      'page_ids'                   => [
        $content_template1->ID => $content_template1->ID
      ],
      'flatten_hierarchy' => FALSE
    ];
    $converted_ids = $this->conversion_service->convert($input['page_ids'], $input['convert_from'], $input['convert_to'], $input['flatten_hierarchy'], $this->post_type->name(), TRUE);
    $page = $this->wp->get_post($converted_ids[0]);

    $this->assertEquals('page', $page->post_type);
  }


}
