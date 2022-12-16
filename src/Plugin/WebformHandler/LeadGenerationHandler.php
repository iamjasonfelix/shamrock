<?php
namespace Drupal\shamrock\Plugin\WebformHandler;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Annotation\WebformHandler;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;

/**
* Form submission handler.
*
* @WebformHandler(
*   id = "lead_generation_handler",
*   label = @Translation("Lead Generation"),
*   category = @Translation("settings"),
*   description = @Translation("Converts lead generation Sales field type values from a no value token to an empty string or the replacement value in the type "),
*   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
*   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
* )
*/

class LeadGenerationHandler extends WebformHandlerBase {

  /**
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   *
   * @return void
   */
  public function preSave(WebformSubmissionInterface $webform_submission) {
    $data = $webform_submission->getData();
    foreach($data as $key => $val) {
      if (preg_match('/^\[.*:.*\]$/', $val)) {
        $form = $this->getWebform();
        $elements = $form->getElementsInitialized();
        $new_val = (@$elements[$key]['#replacement_value']) ?: '';
        $data[$key] = $new_val;
      }
    }
    $webform_submission->setData($data);
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['trigger'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Trigger'),
    ];
    $form['trigger']['execute'] = [
      '#type' => 'radios',
      '#title' => $this->t('Execute'),
      '#options' => [
        'before' => '..before submission data is saved',
        'after' => '..after submission data is saved'
      ],
    ];

    return $form;
  }

}
