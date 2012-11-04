<?php

/**
 * @file
 * Definition of Drupal\comment\Tests\CommentTranslationUITest.
 */

namespace Drupal\comment\Tests;

use Drupal\translation_entity\Tests\EntityTranslationUITest;

/**
 * Tests the Comment Translation UI.
 */
class CommentTranslationUITest extends EntityTranslationUITest {

  /**
   * The subject of the test comment.
   */
  protected $subject;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('language', 'translation_entity', 'node', 'comment');

  public static function getInfo() {
    return array(
      'name' => 'Comment translation UI',
      'description' => 'Tests the basic comment translation UI.',
      'group' => 'Comment',
    );
  }

  /**
   * Overrides \Drupal\simpletest\WebTestBase::setUp().
   */
  function setUp() {
    $this->entityType = 'comment';
    $this->nodeBundle = 'article';
    $this->bundle = 'comment_node_' . $this->nodeBundle;
    $this->testLanguageSelector = FALSE;
    $this->subject = $this->randomName();
    parent::setUp();
  }

  /**
   * Overrides \Drupal\translation_entity\Tests\EntityTranslationUITest::setupBundle().
   */
  function setupBundle() {
    parent::setupBundle();
    $this->drupalCreateContentType(array('type' => $this->nodeBundle, 'name' => $this->nodeBundle));
  }

  /**
   * Overrides \Drupal\translation_entity\Tests\EntityTranslationUITest::getTranslatorPermission().
   */
  function getTranslatorPermissions() {
    return array('post comments', 'administer comments', "translate $this->entityType entities", 'edit original values');
  }

  /**
   * Overrides \Drupal\translation_entity\Tests\EntityTranslationUITest::setupTestFields().
   */
  function setupTestFields() {
    parent::setupTestFields();
    $field = field_info_field('comment_body');
    $field['translatable'] = TRUE;
    field_update_field($field);
  }

  /**
   * Overrides \Drupal\translation_entity\Tests\EntityTranslationUITest::createEntity().
   */
  protected function createEntity($values, $langcode) {
    $node = $this->drupalCreateNode(array('type' => $this->nodeBundle));
    $values['nid'] = $node->nid;
    $values['uid'] = $node->uid;
    return parent::createEntity($values, $langcode);
  }

  /**
   * Overrides \Drupal\translation_entity\Tests\EntityTranslationUITest::getNewEntityValues().
   */
  protected function getNewEntityValues($langcode) {
    // Comment subject is not translatable hence we use a fixed value.
    return array(
      'subject' => $this->subject,
      'comment_body' => array(array('value' => $this->randomString(16))),
    ) + parent::getNewEntityValues($langcode);
  }

}
