<?php

namespace Drupal\image_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\file\Entity\File;
use Drupal\file\FileUsage\FileUsageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for interacting with image styles.
 *
 * @ingroup image_example
 */
class StyleForm extends FormBase {

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The file usage service.
   *
   * @var \Drupal\file\FileUsage\FileUsageInterface
   */
  protected $fileUsage;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = new static();

    $instance->setState($container->get('state'));
    $instance->setFileUsage($container->get('file.usage'));
    $instance->setMessenger($container->get('messenger'));
    $instance->setStringTranslation($container->get('string_translation'));

    return $instance;
  }

  /**
   * Sets the state service.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  protected function setState(StateInterface $state) {
    $this->state = $state;
  }

  /**
   * Sets the file usage service.
   *
   * @param \Drupal\file\FileUsage\FileUsageInterface $file_usage
   *   The file usage service.
   */
  protected function setFileUsage(FileUsageInterface $file_usage) {
    $this->fileUsage = $file_usage;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'image_example_style_form';
  }

  /**
   * Form for uploading and displaying an image using selected style.
   *
   * This page provides a form that allows the user to upload an image and
   * choose a style from the list of defined image styles to use when displaying
   * the the image. This serves as an example of integrating image styles with
   * your module and as a way to demonstrate that the styles and effects defined
   * by this module are available via Drupal's image handling system.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $image_fid = $this->state->get('image_example.image_fid');

    // If there is already an uploaded image display the image here.
    if ($image_fid) {
      $image = File::load($image_fid);
      $style = $this->state->get('image_example.style_name', 'thumbnail');
      $form['image'] = [
        '#theme' => 'image_style',
        '#style_name' => $style,
        '#uri' => $image->getFileUri(),
      ];
    }

    // Use the #managed_file FAPI element to upload an image file.
    $form['image_fid'] = [
      '#title' => $this->t('Image'),
      '#type' => 'managed_file',
      '#description' => $this->t('The uploaded image will be displayed on this page using the image style set in the next form field.'),
      '#default_value' => $image_fid,
      '#upload_location' => 'public://image_example_images/',
    ];

    // Provide a select field for choosing an image style to use when displaying
    // the image.
    $form['style_name'] = [
      '#title' => $this->t('Image style'),
      '#type' => 'select',
      '#description' => $this->t('Choose an image style to use when displaying this image.'),
      // The image_style_options() function returns an array of all available
      // image styles both the key and the value of the array are the image
      // style's name. The function takes one parameter, a Boolean flag that is
      // TRUE when the array should include a <none> option.
      '#options' => image_style_options(TRUE),
      '#default_value' => $this->state->get('image_example.style_name', 'thumbnail'),
    ];

    // Submit Button.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * Verifies that the user supplied an image with the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!$form_state->hasValue('image_fid') || !is_numeric($form_state->getValue('image_fid'))) {
      $form_state->setErrorByName('image_fid', $this->t('Please select an image to upload.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // When using the #managed_file form element the file is automatically
    // uploaded an saved to the {file} table. The value of the corresponding
    // form element is set to the ID of the new file. If the file ID is not
    // zero, we have a valid file.
    if (($fid = $form_state->getValue('image_fid')) != 0) {
      // The new file's status is set to 0 or temporary and in order to ensure
      // that the file is not removed after 6 hours, we need to change its
      // status to 1. Save the ID of the uploaded image for later use.
      if ($file = File::load($fid)) {
        $file->setPermanent();
        $file->save($file);

        // When a module is managing a file, it must manage the usage count.
        $this->fileUsage->add($file, 'image_example', 'sample_image');

        $this->messenger->addMessage(
          $this->t('The image @image_name was uploaded and saved. Its ID is @fid and it will be displayed using the image style @style.',
          [
            '@image_name' => $file->getFilename(),
            '@fid' => $fid,
            '@style' => $form_state->getValue('style_name'),
          ]
        ));
      }
      else {
        $fid = NULL;
        $this->messenger->addWarning($this->t('The image could not be saved.'));
      }
    }

    // Delete the previously uploaded image.
    $old_fid = $this->state->get('image_example.image_fid');

    if (!empty($old_fid) && ($old_file = File::load($old_fid))) {
      $image_name = $old_file->getFilename();

      // When a module is managing a file, it must manage the usage count.
      $this->fileUsage->delete($old_file, 'image_example', 'sample_image');

      // File::delete() verifies the file is used by any other modules. If it
      // is, the file will not be deleted. The module still needs to update
      // its reference.
      $old_file->delete();

      $this->messenger->addMessage(
        $this->t('The image @image_name was removed.', ['@image_name' => $image_name])
      );
    }

    // Usually, a form submission handler stores the data in a configuration
    // object, or in a custom database table, but in this case we use the state
    // service.
    $this->state->set('image_example.image_fid', $fid);
    $this->state->set('image_example.style_name', $form_state->getValue('style_name'));
  }

}
