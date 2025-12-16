<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\basic_page\Utils\Notizie;
use Drupal\blocchi\Utils\Blocchi;
use Drupal\blocchi\Utils\ScontiData;
use Drupal\blocchi\Utils\Utils;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Theme\ThemeManager;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\rest_hub_client\Utils\DrupalRest;



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


    /**
     * {@inheritdoc}
     */
    public function build()
    {
        
        $data = [];
        $title = '';

        // In the new project we only need to render the static home-page layout
        // defined in home-page-render.html.twig. We avoid any dependencies on
        // EYCA-specific helper modules so this block always renders safely.
        $build = [];
        $build['#theme'] = 'home_page_render';
        $build['#data'] = $data;
        $build['#title'] = $title;
        return $build;
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
