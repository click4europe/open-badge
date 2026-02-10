<?php

namespace Drupal\basic_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * Form for importing notizie from a CSV file.
 *
 * Expected CSV columns:
 *   title | subtitle | body | image_path | published
 *
 * - title:      (required) The news title.
 * - subtitle:   (optional) Maps to field_sotto_titolo.
 * - body:       (optional) HTML body text.
 * - image_path: (optional) Filename only (e.g. foto.jpg). Upload images
 *               via the images field or place them in sites/default/files/notizie-import/.
 * - published:  (optional) 1 = published, 0 = unpublished. Default 1.
 */
class ImportNotizieForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'import_notizie_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['#attributes'] = [
      'class' => ['max-w-3xl', 'mx-auto'],
      'enctype' => 'multipart/form-data',
    ];

    $form['description'] = [
      '#markup' => '<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm text-blue-800">'
        . '<p class="font-semibold mb-2">Come funziona:</p>'
        . '<ol class="list-decimal pl-5 space-y-1">'
        . '<li>Scarica il template CSV e compilalo in Excel</li>'
        . '<li>Nella colonna <strong>image_path</strong> scrivi solo il <strong>nome del file</strong> (es. <code>foto.jpg</code>)</li>'
        . '<li>Carica il CSV e le immagini qui sotto, poi clicca Importa</li>'
        . '</ol>'
        . '<p class="mt-2 text-xs">Colonne: <code>title</code> (obbligatorio) | <code>subtitle</code> | <code>body</code> | <code>image_path</code> | <code>published</code> (1/0)</p>'
        . '</div>',
    ];

    $form['csv_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('1. File CSV'),
      '#description' => $this->t('Carica il file CSV con le notizie.'),
      '#upload_location' => 'public://import',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
        'file_validate_size' => [5 * 1024 * 1024],
      ],
      '#required' => TRUE,
    ];

    $form['images_wrapper'] = [
      '#type' => 'details',
      '#title' => $this->t('2. Immagini'),
      '#open' => TRUE,
      '#description' => $this->t('Seleziona tutte le immagini dal tuo PC. Il nome file deve corrispondere a quello scritto nel CSV.'),
    ];

    $form['images_wrapper']['images_upload'] = [
      '#type' => 'file',
      '#title' => $this->t('Seleziona immagini'),
      '#attributes' => [
        'multiple' => 'multiple',
        'accept' => 'image/*',
      ],
      '#description' => $this->t('Puoi selezionare più file contemporaneamente (Ctrl+click o Shift+click).'),
    ];

    $form['delimiter'] = [
      '#type' => 'select',
      '#title' => $this->t('Separatore CSV'),
      '#options' => [
        ',' => $this->t('Virgola ( , )'),
        ';' => $this->t('Punto e virgola ( ; )'),
      ],
      '#default_value' => ';',
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Importa Notizie'),
      '#attributes' => [
        'class' => [
          'px-6', 'py-3', 'bg-slate-900', 'text-white', 'rounded-lg',
          'hover:bg-slate-800', 'font-semibold', 'text-sm', 'cursor-pointer',
        ],
      ],
    ];

    $form['actions']['download_template'] = [
      '#type' => 'link',
      '#title' => $this->t('Scarica template CSV'),
      '#url' => \Drupal\Core\Url::fromRoute('basic_page.import_notizie_template'),
      '#attributes' => [
        'class' => [
          'px-6', 'py-3', 'border', 'border-slate-300', 'text-slate-700',
          'rounded-lg', 'hover:bg-slate-50', 'font-semibold', 'text-sm',
          'inline-block', 'ml-3',
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $fids = $form_state->getValue('csv_file');
    if (empty($fids)) {
      $form_state->setErrorByName('csv_file', $this->t('Carica un file CSV.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fids = $form_state->getValue('csv_file');
    $file = File::load(reset($fids));

    if (!$file) {
      $this->messenger()->addError($this->t('Impossibile caricare il file.'));
      return;
    }

    $uri = $file->getFileUri();
    $path = \Drupal::service('file_system')->realpath($uri);
    $delimiter = $form_state->getValue('delimiter');

    // ── Upload images from the raw file input ──
    $uploaded_images = $this->processImageUploads();
    $this->messenger()->addStatus($this->t('@count immagini caricate.', ['@count' => count($uploaded_images)]));

    // ── Read CSV ──
    $content = file_get_contents($path);
    $bom = pack('H*', 'EFBBBF');
    $content = preg_replace("/^$bom/", '', $content);
    $tmp = tmpfile();
    fwrite($tmp, $content);
    rewind($tmp);
    $handle = $tmp;

    $header = fgetcsv($handle, 0, $delimiter);
    if (!$header) {
      fclose($handle);
      $this->messenger()->addError($this->t('Il file CSV è vuoto.'));
      return;
    }

    $header = array_map(function ($col) {
      return strtolower(trim($col));
    }, $header);

    if (array_search('title', $header) === FALSE) {
      fclose($handle);
      $this->messenger()->addError($this->t('La colonna "title" è obbligatoria nel CSV.'));
      return;
    }

    $imported = 0;
    $skipped = 0;
    $errors = [];
    $row_num = 1;

    while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
      $row_num++;
      $data = [];
      foreach ($header as $i => $col) {
        $data[$col] = isset($row[$i]) ? trim($row[$i]) : '';
      }

      $title = $data['title'] ?? '';
      if (empty($title)) {
        $skipped++;
        $errors[] = $this->t('Riga @num: titolo vuoto, saltata.', ['@num' => $row_num]);
        continue;
      }

      try {
        $node = Node::create([
          'type' => 'notizie',
          'title' => $title,
          'status' => isset($data['published']) && $data['published'] !== '' ? (int) $data['published'] : 1,
          'langcode' => \Drupal::languageManager()->getCurrentLanguage()->getId(),
        ]);

        if (!empty($data['body'])) {
          $node->set('body', [
            'value' => $data['body'],
            'format' => 'full_html',
          ]);
        }

        if (!empty($data['subtitle']) && $node->hasField('field_sotto_titolo')) {
          $node->set('field_sotto_titolo', $data['subtitle']);
        }

        $node->save();

        if (!empty($data['image_path'])) {
          $img_error = $this->attachImage($node, $data['image_path'], $uploaded_images);
          if ($img_error) {
            $errors[] = $this->t('Riga @num: @msg', ['@num' => $row_num, '@msg' => $img_error]);
          }
        }
        $imported++;
      }
      catch (\Exception $e) {
        $skipped++;
        $errors[] = $this->t('Riga @num: @msg', ['@num' => $row_num, '@msg' => $e->getMessage()]);
      }
    }

    fclose($handle);

    $this->messenger()->addStatus($this->t('@count notizie importate con successo.', ['@count' => $imported]));
    if ($skipped > 0) {
      $this->messenger()->addWarning($this->t('@count righe saltate.', ['@count' => $skipped]));
    }
    foreach ($errors as $err) {
      $this->messenger()->addWarning($err);
    }
  }

  /**
   * Process uploaded images from the raw file input.
   *
   * @return array
   *   Map of lowercase filename => File entity.
   */
  protected function processImageUploads() {
    $uploaded = [];

    // Ensure destination directory exists.
    $dir = 'public://notizie-import';
    \Drupal::service('file_system')->prepareDirectory(
      $dir,
      \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY | \Drupal\Core\File\FileSystemInterface::MODIFY_PERMISSIONS
    );

    // Read from PHP's $_FILES superglobal directly.
    $files = \Drupal::request()->files->get('files');
    if (empty($files) || empty($files['images_upload'])) {
      return $uploaded;
    }

    $file_uploads = $files['images_upload'];

    // Normalise to array (single file vs multiple).
    if (!is_array($file_uploads)) {
      $file_uploads = [$file_uploads];
    }

    foreach ($file_uploads as $uploaded_file) {
      if (!$uploaded_file || $uploaded_file->getError() !== UPLOAD_ERR_OK) {
        continue;
      }

      $original_name = $uploaded_file->getClientOriginalName();
      $destination = $dir . '/' . $original_name;

      // Move the uploaded file into Drupal's public files.
      $moved = \Drupal::service('file_system')->moveUploadedFile(
        $uploaded_file->getRealPath(),
        \Drupal::service('file_system')->realpath($dir) . '/' . $original_name
      );

      if ($moved) {
        $file = File::create([
          'uri' => $destination,
          'filename' => $original_name,
          'status' => 1,
        ]);
        $file->save();
        $uploaded[strtolower($original_name)] = $file;
      }
    }

    return $uploaded;
  }

  /**
   * Attach an image to a notizie node.
   *
   * Lookup order:
   * 1. Uploaded images (matched by filename)
   * 2. Files already in public://notizie-import/ folder
   * 3. URL download
   */
  protected function attachImage(Node $node, string $image_path, array $uploaded_images = []) {
    $image_field = NULL;
    if ($node->hasField('field_immagine')) {
      $image_field = 'field_immagine';
    }
    elseif ($node->hasField('field_image')) {
      $image_field = 'field_image';
    }

    if (!$image_field) {
      return 'Nessun campo immagine trovato sul content type notizie.';
    }

    $file = NULL;
    $image_path = trim($image_path, '"\' ');
    $lookup_name = strtolower(basename(str_replace('\\', '/', $image_path)));

    // 1. Match from uploaded images.
    if (isset($uploaded_images[$lookup_name])) {
      $file = $uploaded_images[$lookup_name];
    }

    // 2. Check if file already exists in notizie-import folder.
    if (!$file) {
      $dir = 'public://notizie-import';
      $check_uri = $dir . '/' . basename(str_replace('\\', '/', $image_path));
      $real_path = \Drupal::service('file_system')->realpath($check_uri);
      if ($real_path && file_exists($real_path)) {
        $file = File::create([
          'uri' => $check_uri,
          'filename' => basename($image_path),
          'status' => 1,
        ]);
        $file->save();
      }
    }

    // 3. Try as URL.
    if (!$file && filter_var($image_path, FILTER_VALIDATE_URL)) {
      $file_data = @file_get_contents($image_path);
      if ($file_data !== FALSE) {
        $filename = basename(parse_url($image_path, PHP_URL_PATH));
        $destination = 'public://notizie-import/' . $filename;
        $dir = 'public://notizie-import';
        \Drupal::service('file_system')->prepareDirectory(
          $dir,
          \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY
        );
        $file = \Drupal::service('file.repository')->writeData($file_data, $destination);
      }
      else {
        return 'Impossibile scaricare: ' . $image_path;
      }
    }

    if (!$file) {
      return 'Immagine "' . $lookup_name . '" non trovata. Caricala nel campo Immagini.';
    }

    $node->set($image_field, [
      'target_id' => $file->id(),
      'alt' => $node->getTitle(),
    ]);
    $node->save();
    return NULL;
  }

}
