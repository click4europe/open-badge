<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\basic_page\Utils\Notizie;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'NotiziaPage' block.
 *
 * @Block(
 *  id = "notizia_page",
 *  admin_label = @Translation("Blocco Pagina Notizia"),
 * )
 */
class NotiziaPage extends BlockBase implements ContainerFactoryPluginInterface
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
        $data['scheda'] = Notizie::document($node);
        $data['host'] = \Drupal::request()->getSchemeAndHttpHost();

        // Get related news (latest 2 news, excluding current one)
        $related_news = Notizie::notizie_list(0, 'DESC', 0, 2, '', '');
        if (!empty($related_news['row'])) {
            // Remove current news from related if it appears
            $data['related'] = array_filter($related_news['row'], function($item) use ($node) {
                return $item['id'] != $node->id();
            });
            // Re-index array
            $data['related'] = array_values($data['related']);
        } else {
            $data['related'] = [];
        }

        $params = array();
        $params['type'] = 'nodo';
        $params['id'] = $node->id();

        $build = [];
        $build['#theme'] = 'notizia_page_render';
        $build['#data'] = $data;
        $build['#title'] = '';
        $build['#attached'] = [
            'library' => [
                'basic_page/basic_page.accordion',
            ],
            'drupalSettings' => [
                'notiziaId' => $data['scheda']['id'],
            ],
        ];

        return $build;
    }
}
