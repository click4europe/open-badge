<?php

namespace Drupal\plugin_type_example\Attribute;

use Drupal\Component\Plugin\Attribute\Plugin;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines a Sandwich attribute class.
 *
 * Provides an example of how to define a new attribute type for use in
 * defining a plugin type. Demonstrates documenting the arguments for the class
 * constructor to help developers understand how to configure plugin instances.
 *
 * When defining attributes for a plugin type start by extending the existing
 * Drupal\Component\Plugin\Attribute\Plugin class. Then create a __construct()
 * method whose arguments are the configuration options for the plugin type.
 *
 * Classes that extend the Plugin class should always accept $id, $deriver
 * arguments. Developers can add any additional required, or optional, arguments
 * necessary to configure their specific plugin type.
 *
 * The #[\Attribute(\Attribute::TARGET_CLASS)] line says that the class, Block,
 * defines a #[Block()] attribute, and that the block attribute can only be used
 * on classes. Or put another way, the code that the #[Block()] attribute is
 * annotating must be a class, or it won’t validate.
 *
 * @see \Drupal\plugin_type_example\SandwichPluginManager
 * @see plugin_api
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Sandwich extends Plugin {

  /**
   * Construct a sandwich attribute.
   *
   * @param string $id
   *   The plugin ID.
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|null $description
   *   (optional) Description of the sandwich.
   * @param float|null $calories
   *   (optional) Number of calories in the sandwich.
   * @param class-string|null $deriver
   *   (optional) The deriver class.
   */
  public function __construct(
    public readonly string $id,
    public readonly ?TranslatableMarkup $description = NULL,
    public readonly ?float $calories = NULL,
    public readonly ?string $deriver = NULL,
  ) {}

}
