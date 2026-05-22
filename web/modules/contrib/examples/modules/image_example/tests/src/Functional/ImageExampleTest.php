<?php

namespace Drupal\Tests\image_example\Functional;

use Drupal\Core\File\FileExists;
use Drupal\image\Entity\ImageStyle;
use Drupal\Tests\BrowserTestBase;

/**
 * Functional tests for the Image Example module.
 *
 * @group image_example
 * @group examples
 *
 * @ingroup image_example
 */
class ImageExampleTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['image', 'image_example'];

  /**
   * Tests the Colorize image effect.
   */
  public function testColorizeImageEffect() {
    // Create the test image style with a Colorize effect.
    $image_style = ImageStyle::create([
      'name' => 'image_example_test',
      'label' => 'Image Example Test',
    ]);
    $this->assertEquals(SAVED_NEW, $image_style->save());
    $image_style->addImageEffect([
      'id' => 'image_example_colorize',
      'weight' => 0,
      'data' => [
        // Colorize the image in emerald.
        'color' => '#50CD6F',
      ],
    ]);
    $this->assertEquals(SAVED_UPDATED, $image_style->save());

    // Create a copy of a test image file in root.
    $test_uri = 'public://image-test-colorize-image-effect.png';
    \Drupal::service('file_system')->copy('core/tests/fixtures/files/image-test.png', $test_uri, FileExists::Replace);
    $this->assertFileExists($test_uri);

    // Execute the image style on the test image via a GET request.
    $derivative_uri = 'public://styles/image_example_test/public/image-test-colorize-image-effect.png';
    $this->assertFileDoesNotExist($derivative_uri);
    $url = \Drupal::service('file_url_generator')->transformRelative($image_style->buildUrl($test_uri));
    $this->drupalGet($this->getAbsoluteUrl($url));
    $this->assertSession()->statusCodeEquals(200);
    $this->assertFileExists($derivative_uri);
  }

}
