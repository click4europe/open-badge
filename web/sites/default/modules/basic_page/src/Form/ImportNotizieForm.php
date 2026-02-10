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
 * - image_path: (optional) Relative path inside /sites/default/files/ or
 *               absolute URL. Leave empty to skip.
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

    $form['#attributes'] = ['class' => ['max-w-3xl', 'mx-auto']];

    $form['description'] = [
      '#markup' => '<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm text-blue-800">'
        . '<p class="font-semibold mb-1">Formato CSV richiesto:</p>'
        . '<p><code>title | subtitle | body | image_path | published</code></p>'
        . '<ul class="list-disc pl-5 mt-2 space-y-1">'
        . '<li><strong>title</strong> – (obbligatorio) Titolo della notizia</li>'
        . '<li><strong>subtitle</strong> – (opzionale) Sottotitolo</li>'
        . '<li><strong>body</strong> – (opzionale) Testo HTML</li>'
        . '<li><strong>image_path</strong> – (opzionale) Nome del file immagine caricato sotto (es. <code>foto.jpg</code>)</li>'
        . '<li><strong>published</strong> – (opzionale) 1 = pubblicato, 0 = bozza. Default 1</li>'
        . '</ul>'
        . '<p class="mt-2">La prima riga del CSV deve contenere le intestazioni. Separatore: <strong>virgola</strong> o <strong>punto e virgola</strong>.</p>'
        . '</div>',
    ];

    $form['csv_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('File CSV'),
      '#description' => $this->t('Carica un file CSV con le notizie da importare.'),
      '#upload_location' => 'public://import',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
        'file_validate_size' => [5 * 1024 * 1024],
      ],
      '#required' => TRUE,
    ];

    $form['images'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Immagini'),
      '#description' => $this->t('Carica le immagini da associare alle notizie. Nel CSV inserisci solo il nome del file (es. foto.jpg).'),
      '#upload_location' => 'public://notizie-import',
      '#upload_validators' => [
        'file_validate_extensions' => ['jpg jpeg png gif webp svg'],
        'file_validate_size' => [10 * 1024 * 1024],
      ],
      '#multiple' => TRUE,
      '#required' => FALSE,
    ];

    $form['delimiter'] = [
      '#type' => 'select',
      '#title' => $this->t('Separatore'),
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

    // Build a map of uploaded images: filename => File entity.
    $uploaded_images = [];
    $image_fids = $form_state->getValue('images');
    if (!empty($image_fids)) {
      foreach ($image_fids as $fid) {
        $img_file = File::load($fid);
        if ($img_file) {
          $img_file->setPermanent();
          $img_file->save();
          $filename = strtolower(basename($img_file->getFileUri()));
          $uploaded_images[$filename] = $img_file;
        }
      }
    }

    // Read file content and strip BOM if present (Excel adds BOM to CSV).
    $content = file_get_contents($path);
    $bom = pack('H*', 'EFBBBF');
    $content = preg_replace("/^$bom/", '', $content);
    // Write cleaned content to a temp file.
    $tmp = tmpfile();
    fwrite($tmp, $content);
    rewind($tmp);
    $handle = $tmp;

    // Read header row.
    $header = fgetcsv($handle, 0, $delimiter);
    if (!$header) {
      fclose($handle);
      $this->messenger()->addError($this->t('Il file CSV è vuoto.'));
      return;
    }

    // Normalise header keys.
    $header = array_map(function ($col) {
      return strtolower(trim($col));
    }, $header);

    $title_index = array_search('title', $header);
    if ($title_index === FALSE) {
      fclose($handle);
      $this->messenger()->addError($this->t('La colonna "title" è obbligatoria nel CSV.'));
      return;
    }

    $imported = 0;
    $skipped = 0;
    $errors = [];
    $row_num = 1; // header was row 1

    while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
      $row_num++;

      // Map columns by header.
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
        $values = [
          'type' => 'notizie',
          'title' => $title,
          'status' => isset($data['published']) && $data['published'] !== '' ? (int) $data['published'] : 1,
          'langcode' => \Drupal::languageManager()->getCurrentLanguage()->getId(),
        ];

        $node = Node::create($values);

        // Body.
        if (!empty($data['body'])) {
          $node->set('body', [
            'value' => $data['body'],
            'format' => 'full_html',
          ]);
        }

        // Subtitle.
        if (!empty($data['subtitle']) && $node->hasField('field_sotto_titolo')) {
          $node->set('field_sotto_titolo', $data['subtitle']);
        }

        $node->save();

        // Image (attach after save so node has an ID).
        if (!empty($data['image_path'])) {
          $img_error = $this->attachImage($node, $data['image_path'], $uploaded_images);
          if ($img_error) {
            $errors[] = $this->t('Riga @num: immagine - @msg', ['@num' => $row_num, '@msg' => $img_error]);
          }
        }
        $imported++;
      }
      catch (\Exception $e) {
        $skipped++;
        $errors[] = $this->t('Riga @num: @msg', [
          '@num' => $row_num,
          '@msg' => $e->getMessage(),
        ]);
      }
    }

    fclose($handle);

    $this->messenger()->addStatus($this->t('@count notizie importate con successo.', ['@count' => $imported]));
    if ($skipped > 0) {
      $this->messenger()->addWarning($this->t('@count righe saltate.', ['@count' => $skipped]));
      foreach ($errors as $err) {
        $this->messenger()->addWarning($err);
      }
    }
  }

  /**
   * Attach a local image to a notizie node.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node entity.
   * @param string $image_path
   *   Relative path inside public:// (e.g. "notizie/my-image.jpg").
   */
  protected function attachImage(Node $node, string $image_path, array $uploaded_images = []) {
    // Determine the image field.
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

    // Clean up the image_path value.
    $image_path = trim($image_path, '"\' ');
    $image_path = str_replace('\\', '/', $image_path);
    // Extract just the filename for matching.
    $lookup_name = strtolower(basename($image_path));

    // 1. First try to match from uploaded images by filename.
    if (!empty($uploaded_images) && isset($uploaded_images[$lookup_name])) {
      $file = $uploaded_images[$lookup_name];
    }
    // 2. Try as URL.
    elseif (filter_var($image_path, FILTER_VALIDATE_URL)) {
      $file_data = @file_get_contents($image_path);
      if ($file_data === FALSE) {
        return 'Impossibile scaricare immagine da URL: ' . $image_path;
      }
      $filename = basename(parse_url($image_path, PHP_URL_PATH));
      $destination = 'public://notizie-import/' . $filename;
      $dir = 'public://notizie-import';
      \Drupal::service('file_system')->prepareDirectory(
        $dir,
        \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY
      );
      $file = \Drupal::service('file.repository')->writeData($file_data, $destination);
    }
    // 3. Try as local absolute path (works on same machine only).
    elseif (preg_match('/^[a-zA-Z]:/', $image_path) || strpos($image_path, '/') === 0) {
      if (!file_exists($image_path)) {
        return 'File non trovato: ' . $image_path . ' — Hai caricato le immagini nel campo "Immagini"?';
      }
      $filename = basename($image_path);
      $dir = 'public://notizie-import';
      \Drupal::service('file_system')->prepareDirectory(
        $dir,
        \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY
      );
      $destination = 'public://notizie-import/' . $filename;
      $dest_realpath = \Drupal::service('file_system')->realpath($dir);
      if (!@copy($image_path, $dest_realpath . '/' . $filename)) {
        return 'Impossibile copiare il file: ' . $image_path;
      }
      $file = File::create([
        'uri' => $destination,
        'status' => 1,
      ]);
      $file->save();
    }
    else {
      // No match found.
      return 'Immagine "' . $lookup_name . '" non trovata tra i file caricati. Carica l\'immagine nel campo "Immagini".';
    }

    if ($file) {
      $node->set($image_field, [
        'target_id' => $file->id(),
        'alt' => $node->getTitle(),
      ]);
      $node->save();
      return NULL;
    }

    return 'Errore sconosciuto durante il salvataggio dell\'immagine.';
  }

}
