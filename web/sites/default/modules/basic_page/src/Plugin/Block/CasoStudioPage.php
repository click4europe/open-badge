<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\basic_page\Utils\CasiStudio;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'CasoStudioPage' block.
 *
 * @Block(
 *  id = "caso_studio_page",
 *  admin_label = @Translation("Blocco Pagina Caso Studio"),
 * )
 */
class CasoStudioPage extends BlockBase implements ContainerFactoryPluginInterface
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
        $data = [];
        $node = \Drupal::routeMatch()->getParameter('node');
        $data['scheda'] = CasiStudio::document($node);
        $data['host'] = \Drupal::request()->getSchemeAndHttpHost();

        // Get related case studies (latest 3, excluding current one)
        $related = CasiStudio::casi_studio_list('DESC', 0, 4, '');
        if (!empty($related['row'])) {
            $data['related'] = array_filter($related['row'], function($item) use ($node) {
                return $item['id'] != $node->id();
            });
            $data['related'] = array_values($data['related']);
            // Limit to 3
            $data['related'] = array_slice($data['related'], 0, 3);
        } else {
            $data['related'] = [];
        }

        $params = array();
        $params['type'] = 'nodo';
        $params['id'] = $node->id();

        $build = [];
        $build['#theme'] = 'caso_studio_page_render';
        $build['#data'] = $data;
        $build['#title'] = '';

        return $build;
    }
}
