<?php

namespace Drupal\image_example\Plugin\ImageEffect;

use Drupal\Component\Utility\Color;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Image\ImageInterface;
use Drupal\image\ConfigurableImageEffectBase;

/**
 * Colorizes an image resource.
 *
 * @ImageEffect(
 *   id = "image_example_colorize",
 *   label = @Translation("Colorize"),
 *   description = @Translation("The colorize effect will first remove all color from the source image and then tint the image using the color specified.")
 * )
 *
 * @ingroup image_example
 */
class ColorizeImageEffect extends ConfigurableImageEffectBase {

  /**
   * {@inheritdoc}
   */
  public function applyEffect(ImageInterface $image) {
    $data = $this->configuration;

    // Verify that Drupal is using the PHP GD library for image manipulations
    // since this effect depends on functions implemented by the GD extension.
    if ($image->getToolkitId() != 'gd') {
      $this->logger->info(
        'Image colorize failed on %file. The image does not use the GD toolkit.',
        ['%file' => $image->getSource()]
      );
      return FALSE;
    }

    // phpcs:disable Drupal.Commenting.InlineComment.InvalidEndChar
    // Not all GD installations are created equal. It is a good idea to check
    // for the existence of image manipulation functions before using them.
    // PHP installations using non-bundled GD do not have imagefilter(). See the
    // @link http://www.php.net/manual/en/book.image.php PHP manual @endlink for
    // more information about image manipulation functions.
    // phpcs:enable
    if (!function_exists('imagefilter')) {
      $this->logger->error(
        'The image %image could not be colorized because the imagefilter() function is not available in this PHP installation.',
        ['%file' => $image->getSource()]
      );
      return FALSE;
    }

    if (!Color::validateHex($data['color'])) {
      // Use the default value, if the stored value is invalid.
      $data['color'] = '#FF6633';
    }

    $rgb = Color::hexToRgb($data['color']);

    // $source contains the \GdImage instance passed to GD functions.
    // This line only works with the GD toolkit implemented by Drupal core.
    // Other toolkits could not implement getImage(), or that method could
    // return a different value.
    $source = $image->getToolkit()->getImage();

    // First desaturate the image, and then apply the new color.
    imagefilter($source, IMG_FILTER_GRAYSCALE);
    imagefilter($source, IMG_FILTER_COLORIZE, $rgb['red'], $rgb['green'], $rgb['blue']);

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {

    if (Color::validateHex($this->configuration['color'])) {
      $color = $this->configuration['color'];
    }
    else {
      // Use the default value, if the stored value is invalid.
      $color = '#FF6633';
    }

    $summary = [
      '#markup' => $color,
    ];

    $summary += parent::getSummary();

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['color' => '#FF6633'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    // You do not need to worry about saving/updating/deleting the collected
    // data. The Image module will automatically serialize and store all the
    // data associated with an effect.
    $form['color'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Color'),
      '#description' => $this->t(
        'The color to use when colorizing the image. Use web-style hex colors, for example %long-value, or %short-value.',
        ['%long-value' => '#FF6633', '%short-value' => 'F63']
      ),
      '#default_value' => $this->configuration['color'] ?? '',
      '#size' => 7,
      '#max_length' => 7,
      '#required' => TRUE,
    ];

    return $form;
  }

}
