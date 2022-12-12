<?php

namespace Drupal\shamrock\Plugin\WebformElement;

use Drupal\webform\WebformInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElement\Textarea;

/**
 * Provides a 'sales' element.
 *
 * @WebformElement(
 *   id = "sales",
 *   api = "https://api.drupal.org/api/drupal/core!lib!Drupal!Core!Render!Element!Hidden.php/class/Hidden",
 *   label = @Translation("Sales"),
 *   description = @Translation("Provides a form element for an HTML 'hidden' input element with extra settings."),
 *   category = @Translation("Shamrock"),
 * )
 */
class Sales extends Textarea {

  /**
   * {@inheritdoc}
   */
  protected function defineDefaultProperties() {
    // Include only the access-view-related base properties.
    $access_properties = $this->defineDefaultBaseProperties();
    $access_properties = array_filter($access_properties, function ($access_default, $access_key) {
      return strpos($access_key, 'access_') === 0;
    }, ARRAY_FILTER_USE_BOTH);

    return [
        // Element settings.
        'title' => '',
        'default_value' => '',
        'replacement_value' => '',
        // Administration.
        'prepopulate' => FALSE,
        'private' => FALSE,
      ] + $access_properties;
  }

  /* ************************************************************************ */

  /**
   * {@inheritdoc}
   */
  public function preview() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getTestValues(array $element, WebformInterface $webform, array $options = []) {
    // Hidden elements should never get a test value.
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    // Remove the default section under the advanced tab.
    unset($form['default']);

    // Add the default value textarea to the element's main settings.
    $form['element']['default_value'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Default value'),
      '#description' => $this->t('The default value of the webform element.'),
      '#maxlength' => NULL,
    ];
    $form['element']['replacement_value'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Replacement value'),
      '#description' => $this->t('The value of the webform element if the default value token doesn\'t exist.'),
      '#maxlength' => NULL,
    ];

    return $form;
  }

}
