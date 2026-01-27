<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\basic_page\Utils\Notizie;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'NotiziePage' block.
 *
 * @Block(
 *  id = "notizie_page",
 *  admin_label = @Translation("Blocco Pagina Notizie"),
 * )
 */
class NotiziePage extends BlockBase implements ContainerFactoryPluginInterface
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
        $params = [];
        $data['title'] = 'Notizie';
        $data['theme'] = 'notizie_page';
        $data['visitato'] = '';
        $data['host'] = \Drupal::request()->getSchemeAndHttpHost();
        $data['path'] = Url::fromUserInput(Notizie::all_get_path(), ['absolute' => TRUE])->toString();
        $data['page'] = !empty(\Drupal::request()->get('page')) ? \Drupal::request()->get('page') : '1';
        $data['titolo'] = !empty(\Drupal::request()->get('name')) ? \Drupal::request()->get('name') : '';
        $data['category'] = !empty(\Drupal::request()->get('category')) ? \Drupal::request()->get('category') : '';
        $data['categories'] = Notizie::field_allowed_categories();
        $data['reset'] = Notizie::get_request('reset');
        if (strcmp($data['reset'], 'reset') === 0) {
            Notizie::drupal_goto($data['path']);
        }

        try {
            $pagination_args = array();
            if (!empty($data['titolo'])) $pagination_args['name'] = $data['titolo'];
            if (!empty($data['category'])) $pagination_args['category'] = $data['category'];
            $start = Notizie::page_to_start($data['page'], 9);
            $data['notizie'] = Notizie::notizie_list(0, 'DESC', $start, 9, $data['category'], $data['titolo']);
            $data['pagination'] = Notizie::pager($data['page'], $data['notizie']['num'], 9, $data['path'], $pagination_args);
        } catch (\Exception $e) {
            \Drupal::logger('notizie')->debug($e);
        }

        $build = [];
        $build['#theme'] = $data['theme'];
        $build['#data'] = $data;
        $build['#title'] = '';

        return $build;
    }
}
