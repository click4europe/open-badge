<?php

namespace Drupal\plugin_type_example\Plugin\Sandwich;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\plugin_type_example\Attribute\Sandwich;
use Drupal\plugin_type_example\SandwichBase;

/**
 * Provides a ham sandwich.
 *
 * Because the plugin manager class for our plugins uses attributes for class
 * discovery, our ham sandwich only needs to exist within the Plugin\Sandwich
 * namespace, and provide a Sandwich attribute to be declared as a plugin.
 * This is defined in
 * \Drupal\plugin_type_example\SandwichPluginManager::__construct().
 *
 * Following this comment is the plugin attribute. This is parsed using the
 * PHP Reflection API and used to generate the plugin definition. Any values
 * defined here will be available in the configuration passed to plugin
 * instances when they are instantiated from the plugin manager.
 *
 * This should be used for metadata that is specifically required to instantiate
 * the plugin, or for example data that might be needed to display a list of all
 * available plugins where the user selects one. This means many plugin
 * attributes can be reduced to a plugin ID, a label and perhaps a description.
 *
 * @Sandwich(
 *   id = "ham_sandwich",
 *   description = @Translation("Ham, mustard, rocket, sun-dried tomatoes."),
 *   calories = 426
 * )
 */
#[Sandwich(
  id: "ham_sandwich",
  description: new TranslatableMarkup('Ham, mustard, rocket, sun-dried tomatoes.'),
  calories: 426
)]
class ExampleHamSandwich extends SandwichBase {

  /**
   * Place an order for a sandwich.
   *
   * This is just an example method on our plugin that we can call to get
   * something back.
   *
   * @param array $extras
   *   Array of extras to include with this order.
   *
   * @return string
   *   A description of the sandwich ordered.
   */
  public function order(array $extras) {
    $ingredients = ['ham, mustard', 'rocket', 'sun-dried tomatoes'];
    $sandwich = array_merge($ingredients, $extras);
    return 'You ordered an ' . implode(', ', $sandwich) . ' sandwich. Enjoy!';
  }

}
