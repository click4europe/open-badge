<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\blocchi\Utils\Blocchi;
use Drupal\basic_page\Traits\StepDataTrait;
use Drupal\basic_page\Traits\BloccoHomeTrait;
use Drupal\basic_page\Utils\Notizie;




/**
 * Provides a 'HomePageRender' block.
 *
 * @Block(
 *  id = "home_page_render",
 *  admin_label = @Translation("Home page render"),
 * )
 */
class HomePage extends BlockBase
{
    use StepDataTrait;
    use BloccoHomeTrait;

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $data = [];
        $title = '';
        $account = \Drupal::currentUser();
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();

        // fetch first 3 blocchi box home (blocco_vantaggi)
        $data['vantaggi'] = Blocchi::make_query_blocchi('blocco_vantaggi', $lang, TRUE, 'ASC', 0, 3);
        // Add edit link for admin (user ID 1)
        if ($account->id() == 1) {
            $options = ['absolute' => TRUE, 'query' => ['type' => 'blocco_vantaggi'], 'attributes' => ['class' => ['button text-slate-900 underline']]];
            $url = Url::fromUri('internal:/admin/content/block', $options);
            $data['boxs_edit'] = Link::fromTextAndUrl('Configura Blocchi Vantaggi', $url);
        }

        // fetch Blocco Home (home section1)
        $home_blocks = Blocchi::make_query_blocchi('blocco_home', $lang, TRUE, 'ASC', 0, 1);
        if (!empty($home_blocks)) {
            $data['home_section'] = $this->makePrintBlock($home_blocks[0]->id, $account);
        }
        // Add edit link for home section
        if ($account->id() == 1 && !empty($home_blocks)) {
            $options = ['absolute' => TRUE, 'query' => ['type' => 'blocco_home'], 'attributes' => ['class' => ['button text-slate-900 underline']]];
            $url = Url::fromUri('internal:/admin/content/block', $options);
            $data['home_edit'] = Link::fromTextAndUrl('Configura Blocco Home', $url);
        }

        // Fetch CTA ACCOUNT blocco_home by name using BloccoHomeTrait
        $cta_data = $this->fetchBloccoHome('CTA ACCOUNT', $account);
        if ($cta_data) {
            $data['cta_account'] = $cta_data;
        }

        // Fetch step data using trait
        $stepData = $this->getStepData($lang, 6);
        $data = array_merge($data, $stepData);

        // Fetch Integrare gli ob blocco_home by name using BloccoHomeTrait
        $integrazione_data = $this->fetchBloccoHome('Integrare gli ob', $account);
        if ($integrazione_data) {
            $data['integrazione'] = $integrazione_data;
        }

        // Fetch latest 6 notizie for the homepage
        $notizie_data = Notizie::notizie_list(0, 'DESC', 0, 6, '', '');
        $data['notizie'] = !empty($notizie_data['row']) ? $notizie_data['row'] : [];
        
        // Add edit link for notizie section (admin only)
        if ($account->id() == 1) {
            $options = ['absolute' => TRUE, 'query' => ['type' => 'notizie'], 'attributes' => ['class' => ['button text-slate-900 underline']]];
            $url = Url::fromUri('internal:/admin/content', $options);
            $data['notizie_edit'] = Link::fromTextAndUrl('Configura Notizie', $url);

            $import_options = ['absolute' => TRUE, 'attributes' => ['class' => ['button text-slate-900 underline ml-4']]];
            $import_url = Url::fromRoute('basic_page.import_notizie', [], $import_options);
            $data['notizie_import'] = Link::fromTextAndUrl('Importa Notizie CSV', $import_url);
        }

        // Render the home-page layout defined in home-page-render.html.twig
        $build = [];
        $build['#theme'] = 'home_page_render';
        $build['#data'] = $data;
        $build['#title'] = $title;
        return $build;
    }

    /**
     * Get blocks by type - returns array of objects with 'id' property.
     * This mimics EYCA's Blocchi::make_query_blocchi() behavior.
     * 
     * The template uses drupal_entity('block_content', item.id) to render each block,
     * which then uses the block-content--blocco-vantaggi.html.twig template.
     */
    protected function getBlocksByType($block_type, $limit = 3)
    {
        $results = [];
        
        $query = \Drupal::entityQuery('block_content')
            ->condition('type', $block_type)
            ->condition('status', 1)
            ->sort('changed', 'DESC')
            ->range(0, $limit)
            ->accessCheck(TRUE);

        $ids = $query->execute();

        if (empty($ids)) {
            return $results;
        }

        // Return objects with 'id' property like EYCA does
        // Template will use: {% for item in data.boxs %}{{ drupal_entity('block_content', item.id) }}{% endfor %}
        foreach ($ids as $id) {
            $obj = new \stdClass();
            $obj->id = $id;
            $results[] = $obj;
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function blockForm($form, FormStateInterface $form_state)
    {
        $form = parent::blockForm($form, $form_state);

        $config = $this->getConfiguration();

        $form['titolo'] = array(
            '#type' => 'textfield',
            '#title' => t('Title'),
            '#description' => t('Enter the title of Website max 128 chars'),

            '#default_value' => isset($config['titolo']) ? $config['titolo'] : ''
        );

        $form['descri'] = array(
            '#type' => 'textfield',
            '#title' => t('Descrizione Sito'),
            '#description' => t('Enter the Descri of Website max 255 chars'),
            '#maxlength' => 255,
            '#default_value' => isset($config['descri']) ? $config['descri'] : ''
        );


        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state)
    {

        $config = $this->getConfiguration();

        $this->setConfigurationValue('titolo', $form_state->getValue('titolo'));
        $this->setConfigurationValue('descri', $form_state->getValue('descri'));
    }


    /**
     * {@inheritdoc}
     *
     */
    public function defaultConfiguration()
    {
        return [
            'titolo' => '',
            'descri' => '',
        ];
    }
}
