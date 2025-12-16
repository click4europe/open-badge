<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Block\BlockBase;
use Drupal\blocchi\Utils\Utils;
use Drupal\blocchi\Utils\Page;
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
