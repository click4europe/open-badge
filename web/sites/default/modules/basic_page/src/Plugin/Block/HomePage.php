<?php

namespace Drupal\basic_page\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\blocchi\Utils\Blocchi;
use Drupal\basic_page\Traits\StepDataTrait;




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

        // Fetch step data using trait
        $stepData = $this->getStepData($lang, 6);
        $data = array_merge($data, $stepData);

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


    public function make__print_block($id = '', $account = NULL)
    {

        $rend = [];
        $block = \Drupal\block_content\Entity\BlockContent::load($id);
        $rend['titolo'] = $block->get('field_titolo')->getValue();
        $rend['sub_title'] = $block->get('field_sub_title')->getValue();
        if (strcmp('1', $account->id()) === 0) {
            $options = ['absolute' => TRUE, 'query' => ['destination' => 'home'], 'attributes' => ['class' => ['button']]];
            $url = Url::fromUri('internal:/admin/content/block/' . $id, $options);
            $rend['link_edit'] = Link::fromTextAndUrl('Configura Blocco', $url);
        }

        return $rend;
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
