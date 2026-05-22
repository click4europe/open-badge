<?php

namespace Drupal\file_example;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\File\Event\FileUploadSanitizeNameEvent;
use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\file\FileRepositoryInterface;
use Drupal\file_example\Traits\DumperTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * A submit handler helper class for the file_example module.
 */
class FileExampleSubmitHandlerHelper {

  use DumperTrait;
  use stringTranslationTrait;

  //phpcs:disable Drupal.Files.LineLength.TooLong

  /**
   * Constructs a new \Drupal\file_example\FileExampleSubmitHandlerHelper instance.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   * @param \Drupal\file_example\FileExampleStateHelper $stateHelper
   *   The state helper.
   * @param \Drupal\file_example\FileExampleFileHelper $fileHelper
   *   The helper service.
   * @param \Drupal\file_example\FileExampleSessionHelperWrapper $sessionHelperWrapper
   *   The session helper wrapper.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   * @param \Drupal\file\FileRepositoryInterface $fileRepository
   *   The file repository.
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   *   The file system service.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   The event dispatcher.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   *
   * @see https://php.watch/versions/8.0/constructor-property-promotion
   */
  public function __construct(
    TranslationInterface $string_translation,
    protected FileExampleStateHelper $stateHelper,
    protected FileExampleFileHelper $fileHelper,
    protected FileExampleSessionHelperWrapper $sessionHelperWrapper,
    protected MessengerInterface $messenger,
    protected FileRepositoryInterface $fileRepository,
    protected FileSystemInterface $fileSystem,
    protected EventDispatcherInterface $eventDispatcher,
    protected ModuleHandlerInterface $moduleHandler,
  ) {
    $this->setStringTranslation($string_translation);
  }

  // phpcs:enable

  /**
   * Submit handler to write a managed file.
   *
   * A "managed file" is a file that Drupal tracks as a file entity.  It's the
   * standard way Drupal manages files in file fields and elsewhere.
   *
   * The key functions used here are:
   * - file_save_data(), which takes a buffer and saves it to a named file and
   *   also creates a tracking record in the database and returns a file object.
   *   In this function we use FileExists::Rename (the default) as the argument,
   *   which means that if there's an existing file, create a new non-colliding
   *   filename and use it.
   * - file_create_url(), which converts a URI in the form public://junk.txt or
   *   private://something/test.txt into a URL like
   *   http://example.com/sites/default/files/junk.txt.
   *    * @param array $form
   *   An associative array containing the structure of the form.
   *
   * @param array &$form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function handleManagedFile(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $data = $form_values['write_contents'];
    $uri = !empty($form_values['destination']) ? $form_values['destination'] : NULL;

    // Managed operations work with a file object.
    $file_object = $this->fileRepository->writeData($data, $uri, FileExists::Rename);

    if (!empty($file_object)) {
      $url = $this->fileHelper->getExternalUrl($file_object);
      $this->stateHelper->setDefaultFile($file_object->getFileUri());
      $file_data = $file_object->toArray();

      if ($url) {
        $this->messenger->addMessage(
          $this->t('Saved managed file: %file to destination %destination (accessible via <a href=":url">this URL</a>, actual uri=<span id="uri">@uri</span>)', [
            '%file' => print_r($file_data, TRUE),
            '%destination' => $uri,
            '@uri' => $file_object->getFileUri(),
            ':url' => $url->toString(),
          ])
        );
      }
      else {
        // The stream type does not support URLs, so we cannot give a link to
        // it.
        $this->messenger->addMessage(
          $this->t('Saved managed file: %file to destination %destination (no URL, since this stream type does not support it)', [
            '%file' => print_r($file_data, TRUE),
            '%destination' => $uri,
            '@uri' => $file_object->getFileUri(),
          ])
        );
      }
    }
    else {
      $this->messenger->addMessage($this->t('Failed to save the managed file'), 'error');
    }
  }

  /**
   * Submit handler to write an unmanaged file.
   *
   * An unmanaged file is a file that Drupal does not track.  A standard
   * operating system file, in other words.
   *
   * The key functions used here are:
   * - FileSystemInterface::saveData(), which takes a buffer and saves it to a
   *   named file, but does not create any kind of tracking record in the
   *   database. This example uses FileExists::Replace for the third argument,
   *   meaning that, if there's an existing file at this location, it should be
   *   replaced.
   * - file_create_url(), which converts a URI in the form public://junk.txt or
   *   private://something/test.txt into a URL like
   *   http://example.com/sites/default/files/junk.txt.
   *    * @param array $form
   *   An associative array containing the structure of the form.
   *
   * @param array &$form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function handleUnmanagedFile(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $data = $form_values['write_contents'];
    $destination = !empty($form_values['destination']) ? $form_values['destination'] : NULL;

    // With the unmanaged file we just get a filename back.
    $filename = $this->fileSystem->saveData($data, $destination, FileExists::Replace);

    if ($filename) {
      $url = $this->fileHelper->getExternalUrl($filename);
      $this->stateHelper->setDefaultFile($filename);

      if ($url) {
        $this->messenger->addMessage(
          $this->t('Saved file as %filename (accessible via <a href=":url">this URL</a>, uri=<span id="uri">@uri</span>)', [
            '%filename' => $filename,
            '@uri' => $filename,
            ':url' => $url->toString(),
          ])
        );
      }
      else {
        $this->messenger->addMessage(
          $this->t('Saved file as %filename (not accessible externally)', [
            '%filename' => $filename,
            '@uri' => $filename,
          ])
        );
      }
    }
    else {
      $this->messenger->addMessage($this->t('Failed to save the file'), 'error');
    }
  }

  /**
   * Submit handler to write an unmanaged file using plain PHP functions.
   *
   * The key functions used here are:
   * - FileSystemInterface::saveData(), which takes a buffer and saves it to a
   *   named file, but does not create any kind of tracking record in the
   *   database.
   * - file_create_url(), which converts a URI in the form public://junk.txt or
   *   private://something/test.txt into a URL like
   *   http://example.com/sites/default/files/junk.txt.
   * - drupal_tempnam() generates a temporary filename for use.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function handleUnmanagedPhp(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $data = $form_values['write_contents'];
    $destination = !empty($form_values['destination']) ? $form_values['destination'] : NULL;

    if (empty($destination)) {
      // If no destination has been provided, use a generated name.
      $destination = $this->fileSystem->tempnam('public://', 'file');
    }

    // With all traditional PHP functions we can use the stream wrapper notation
    // for a file as well.
    $fp = fopen($destination, 'w');

    // To demonstrate the fact that everything is based on streams, we'll do
    // multiple 5-character writes to put this to the file. We could easily
    // (and far more conveniently) write it in a single statement with
    // fwrite($fp, $data).
    $length = strlen($data);
    $write_size = 5;

    for ($i = 0; $i < $length; $i += $write_size) {
      $result = fwrite($fp, substr($data, $i, $write_size));

      if (!$result) {
        $this->messenger->addError($this->t('Failed writing to the file %file', ['%file' => $destination]));
        fclose($fp);
        return;
      }
    }

    $url = $this->fileHelper->getExternalUrl($destination);
    $this->stateHelper->setDefaultFile($destination);

    if ($url) {
      $this->messenger->addMessage(
        $this->t('Saved file as %filename (accessible via <a href=":url">this URL</a>, uri=<span id="uri">@uri</span>)', [
          '%filename' => $destination,
          '@uri' => $destination,
          ':url' => $url->toString(),
        ])
      );
    }
    else {
      $this->messenger->addMessage(
        $this->t('Saved file as %filename (not accessible externally)', [
          '%filename' => $destination,
          '@uri' => $destination,
        ])
      );
    }
  }

  /**
   * Submit handler for reading a stream wrapper.
   *
   * Drupal now has full support for PHP's stream wrappers, which means that
   * instead of the traditional use of all the file functions
   * ($fp = fopen("/tmp/some_file.txt");) far more sophisticated and generalized
   * (and extensible) things can be opened as if they were files. Drupal itself
   * provides the public:// and private:// schemes for handling public and
   * private files. PHP provides file:// (the default) and http://, so that a
   * URL can be read or written (as in a POST) as if it were a file. In
   * addition, new schemes can be provided for custom applications. The Stream
   * Wrapper Example, if installed, implements a custom 'session' scheme you can
   * test with this example.
   *
   * Here we take the stream wrapper provided in the form. We grab the content
   * with file_get_contents(). It is that simple:
   * file_get_contents("http://example.com") or
   * file_get_contents("public://example.txt") just works. Although not
   * necessary, we use FileSystemInterface::saveData() to save this file locally
   * and then find a local URL for it.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function handleFileRead(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $uri = $form_values['file_ops_file'];

    if (empty($uri) or !is_file($uri)) {
      $this->messenger->addMessage($this->t('The file "%uri" does not exist', ['%uri' => $uri]), 'error');
      return;
    }

    $basename = basename($uri);
    $event = new FileUploadSanitizeNameEvent($basename, 'txt');

    $this->eventDispatcher->dispatch($event);

    $dirname = $this->fileSystem->dirname($uri);
    $basename = $event->getFilename();
    $file_info = pathinfo($basename);
    $basename = $file_info['filename'];

    // Change the extension if it is not the allowed one.
    if (!empty($file_info['extension'])) {
      if ($file_info['extension'] !== 'txt') {
        $file_info['extension'] = 'txt';
      }

      $basename .= '.' . $file_info['extension'];
    }

    $separator = str_ends_with($dirname, '/') ? '' : '/';
    $filename = $dirname . $separator . $basename;
    $buffer = file_get_contents($filename);

    if ($buffer) {
      $source_name = $this->fileSystem->saveData($buffer, 'public://' . $event->getFilename());

      if ($source_name) {
        $url = $this->fileHelper->getExternalUrl($source_name);
        $this->stateHelper->setDefaultFile($source_name);

        if ($url) {
          $this->messenger->addMessage(
            $this->t('The file was read and copied to %filename which is accessible at <a href=":url">this URL</a>', [
              '%filename' => $source_name,
              ':url' => $url->toString(),
            ])
          );
        }
        else {
          $this->messenger->addMessage(
            $this->t('The file was read and copied to %filename (not accessible externally)', [
              '%filename' => $source_name,
            ])
          );
        }
      }
      else {
        $this->messenger->addMessage($this->t('Failed to save the file'));
      }
    }
    else {
      // We failed to get the contents of the requested file.
      $this->messenger->addMessage($this->t('Failed to retrieve the file %file', ['%file' => $uri]));
    }
  }

  /**
   * Submission handler to delete a file.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function handleFileDelete(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $uri = $form_values['file_ops_file'];

    // Since we don't know if the file is managed or not, look in the database
    // to see. Normally, code would be working with either managed or unmanaged
    // files, so this is not a typical situation.
    $file_object = $this->fileHelper->getManagedFile($uri);

    if (!empty($file_object)) {
      // While FALSE is expected to be returned on failure, an exception can be
      // thrown on certain cache states.
      try {
        $file_object->delete();
        $this->messenger->addMessage($this->t('Successfully deleted the managed file %uri', ['%uri' => $uri]));
        $this->stateHelper->setDefaultFile($uri);
      }
      catch (\Exception $e) {
        $this->messenger->addError($this->t('Failed deleting managed file %uri: @result', [
          '%uri' => $uri,
          '@result' => $e->getMessage(),
        ]));
      }
    }
    else {
      $result = $this->fileSystem->delete($uri);
      if ($result) {
        $this->messenger->addMessage($this->t('Successfully deleted unmanaged file %uri', ['%uri' => $uri]));
        $this->stateHelper->setDefaultFile($uri);
      }
      else {
        $this->messenger->addError($this->t('Failed deleting unmanaged file %uri', ['%uri' => $uri]));
      }
    }
  }

  /**
   * Submission handler to check existence of a file.
   */
  public function handleFileExists(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $uri = $form_values['file_ops_file'];

    if (is_file($uri)) {
      $this->messenger->addMessage($this->t('The file %uri exists.', ['%uri' => $uri]));
    }
    else {
      $this->messenger->addMessage($this->t('The file %uri does not exist.', ['%uri' => $uri]));
    }
  }

  /**
   * Submission handler for directory creation.
   *
   * We create a directory and set the proper permissions on it using
   * FileSystemInterface::prepareDirectory().
   */
  public function handleDirectoryCreate(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $directory = $form_values['directory_name'];

    // The options passed to FileSystemInterface::prepareDirectory() are a
    // bitmask, so we can specify either
    // FileSystemInterface::MODIFY_PERMISSIONS,
    // FileSystemInterface::CREATE_DIRECTORY, or both.
    // FileSystemInterface::MODIFY_PERMISSIONS will set the permissions of the
    // directory by default to 0755, or to the value of the variable
    // 'file_chmod_directory'.
    if (!$this->fileSystem->prepareDirectory($directory, FileSystemInterface::MODIFY_PERMISSIONS | FileSystemInterface::CREATE_DIRECTORY)) {
      $this->messenger->addError($this->t('Failed to create %directory.', ['%directory' => $directory]));
    }
    else {
      $this->messenger->addMessage($this->t('Directory %directory is ready for use.', ['%directory' => $directory]));
      $this->stateHelper->setDefaultDirectory($directory);
    }
  }

  /**
   * Submission handler for directory deletion.
   *
   * @see \Drupal\Core\File\FileSystemInterface::deleteRecursive()
   */
  public function handleDirectoryDelete(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $directory = $form_values['directory_name'];
    $result = $this->fileSystem->deleteRecursive($directory);

    if ($result) {
      $this->messenger->addMessage($this->t('Recursively deleted directory %directory.', ['%directory' => $directory]));
      $this->stateHelper->setDefaultDirectory($directory);
    }
    else {
      $this->messenger->addError($this->t('Failed to delete %directory.', ['%directory' => $directory]));
    }
  }

  /**
   * Submission handler to test directory existence.
   *
   * This actually just checks to see if the directory is writable.
   */
  public function handleDirectoryExists(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $directory = $form_values['directory_name'];
    $result = is_dir($directory);

    if ($result) {
      $this->messenger->addMessage($this->t('Directory %directory exists.', ['%directory' => $directory]));
    }
    else {
      $this->messenger->addError($this->t('Directory %directory does not exist.', ['%directory' => $directory]));
    }
  }

  /**
   * Submission handler to show the contents of $_SESSION.
   */
  public function handleShowSession(array &$form, FormStateInterface $form_state) {
    $dumper = $this->dumper();

    if ($this->isDevelDumper($dumper)) {
      // If the devel module is installed, use its nicer message format.
      $dumper->dump($this->sessionHelperWrapper->getStoredData(), $this->t('Entire $_SESSION["file_example"]'));
    }
    else {
      $this->messenger->addMessage(sprintf('<pre>%s</pre>', print_r($this->sessionHelperWrapper->getStoredData(), TRUE)));
    }
  }

  /**
   * Submission handler to reset the demo.
   *
   * @todo Note this does NOT clear any managed file references in the database.
   *   It might be a good idea to add this.
   *
   * @see https://www.drupal.org/project/examples/issues/2985471
   */
  public function handleResetSession(array &$form, FormStateInterface $form_state) {
    $this->stateHelper->deleteDefaultState();
    $this->sessionHelperWrapper->clearStoredData();
    $this->messenger->addMessage('Session reset.');
  }

  /**
   * Checks if the given object is an instance of DevelDumperInterface.
   *
   * @param object $object
   *   The object to check.
   *
   * @return bool
   *   TRUE if the object is an instance of DevelDumperInterface, FALSE
   *   otherwise.
   */
  protected function isDevelDumper(object $object): bool {
    if ($this->moduleHandler->moduleExists('devel')) {
      return in_array('DevelDumperInterface', class_implements($object));
    }

    return FALSE;
  }

}
