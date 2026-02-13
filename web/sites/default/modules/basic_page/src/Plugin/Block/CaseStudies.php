<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\basic_page\Utils\CasiStudio;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'CaseStudiesRender' block.
 *
 * @Block(
 *  id = "case_studies_render",
 *  admin_label = @Translation("Case Studies Render"),
 * )
 */
class CaseStudies extends BlockBase implements ContainerFactoryPluginInterface
{
    /**
     * @var \Drupal\Core\Session\AccountProxyInterface
     */
    protected $account;

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
        $data['title'] = 'Casi Studio';
        $data['host'] = \Drupal::request()->getSchemeAndHttpHost();
        $data['path'] = Url::fromUserInput(CasiStudio::all_get_path(), ['absolute' => TRUE])->toString();
        $data['page'] = !empty(\Drupal::request()->get('page')) ? \Drupal::request()->get('page') : '1';
        $data['titolo'] = !empty(\Drupal::request()->get('name')) ? \Drupal::request()->get('name') : '';
        $data['reset'] = CasiStudio::get_request('reset');
        if (strcmp($data['reset'], 'reset') === 0) {
            CasiStudio::drupal_goto($data['path']);
        }

        try {
            $pagination_args = [];
            if (!empty($data['titolo'])) $pagination_args['name'] = $data['titolo'];
            $start = CasiStudio::page_to_start($data['page'], 9);
            $data['casi_studio'] = CasiStudio::casi_studio_list('DESC', $start, 9, $data['titolo']);
            $data['pagination'] = CasiStudio::pager($data['page'], $data['casi_studio']['num'], 9, $data['path'], $pagination_args);
        } catch (\Exception $e) {
            \Drupal::logger('casi_studio')->debug($e);
        }

        // Admin edit link
        if ($this->account->id() == 1 || in_array('administrator', $this->account->getRoles())) {
            $options = [
                'absolute' => TRUE,
                'query' => ['type' => 'casi_studio'],
                'attributes' => ['class' => ['button text-slate-900 underline']],
            ];
            $url = Url::fromUri('internal:/admin/content', $options);
            $data['casi_studio_edit'] = \Drupal\Core\Link::fromTextAndUrl('Configura Casi Studio', $url);
        }

        $build = [];
        $build['#theme'] = 'case_studies_render';
        $build['#data'] = $data;
        $build['#title'] = '';

        return $build;
    }
}
