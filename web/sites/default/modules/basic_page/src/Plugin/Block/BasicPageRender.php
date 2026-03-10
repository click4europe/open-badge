<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Block\BlockBase;
use Drupal\basic_page\Utility\Page;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Provides a 'BasicPageRender' block.
 *
 * @Block(
 *  id = "basic_page_render",
 *  admin_label = @Translation("Basic page render"),
 * )
 */
class BasicPageRender extends BlockBase implements ContainerFactoryPluginInterface
{

    /**
     * @var $account \Drupal\Core\Session\AccountProxyInterface
     */
    protected $account;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $configuration
     * @param string $plugin_id
     * @param mixed $plugin_definition
     *
     * @return static
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('current_user'),
        );
    }

    /**
     * @param array $configuration
     * @param string $plugin_id
     * @param mixed $plugin_definition
     * @param \Drupal\Core\Session\AccountProxyInterface $account
     * @param \Drupal\social\Utils\Social $Social
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountProxyInterface $account)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->account = $account;
    }


    /**
     * {@inheritdoc}
     */
    public function build()
    {

        $data = array();
        $node = \Drupal::routeMatch()->getParameter('node');
        $data['scheda'] = Page::document($node);
        $data['host'] = \Drupal::request()->getSchemeAndHttpHost();

        $params = array();
        $params['type'] = 'nodo';
        $params['id'] = $node->id();
        //$data['pdf_data'] = $base_url . '/pdf-data?'. UrlHelper::buildQuery($params);
        
        // Add admin action URLs for logged-in users with permissions
        $current_user = \Drupal::currentUser();
        if ($current_user->isAuthenticated() && $node->access('update', $current_user)) {
            $data['admin_actions'] = [
                'view' => Url::fromRoute('entity.node.canonical', ['node' => $node->id()])->toString(),
                'edit' => Url::fromRoute('entity.node.edit_form', ['node' => $node->id()])->toString(),
                'delete' => Url::fromRoute('entity.node.delete_form', ['node' => $node->id()])->toString(),
                'revisions' => Url::fromRoute('entity.node.version_history', ['node' => $node->id()])->toString(),
            ];
        }

        // Get header and footer blocks
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
        
        // Fetch header block (blocco_header_pagina_interne)
        $header_blocks = \Drupal\blocchi\Utils\Blocchi::make_query_blocchi('blocco_header_pagina_interne', $lang, TRUE, 'ASC', 0, 1);
        if (!empty($header_blocks)) {
            $data['header_id'] = $header_blocks[0]->id;
        }
        
        // Fetch footer block
        $footer_blocks = \Drupal\blocchi\Utils\Blocchi::make_query_blocchi('blocco_footer', $lang, TRUE, 'ASC', 0, 1);
        if (!empty($footer_blocks)) {
            $data['footer_id'] = $footer_blocks[0]->id;
        }

        $build = [];
        $build['#theme'] = 'basic_page_render';
        $build['#data'] = $data;
        $build['#title'] = '';
        $build['#attached'] = [
            'library' => [
                'basic_page/basic_page.accordion',
            ],
            'drupalSettings' => [
                'documentazione_faq' => [],
            ],
        ];

        return $build;
    }
}
