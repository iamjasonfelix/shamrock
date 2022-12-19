<?php
namespace Drupal\shamrock\Plugin\WebformHandler;

use Drupal\Core\Annotation\Translation;
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
   * Handler for our Lead generations utilizing the Sales field type.
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   *
   * @return void
   */
  public function preSave(WebformSubmissionInterface $webform_submission) {
    // Get the webform submission data.
    $data = $webform_submission->getData();
    // Get the form.
    $form = $this->getWebform();
    // Get the elements and their structure.
    $elements = $form->getElementsInitialized();
    // Loop through data and get key and values.
    foreach($data as $key => $val) {
      // Check reg ex for a token.
      if (preg_match('/^\[.*:.*\]$/', $val)) {
        // Assign new value if the regex matched with the
        // replacement_value field in the Sales field type
        // or give it an empty string.
        $new_val = (@$elements[$key]['#replacement_value']) ?: '';
        // Assign the new value to the form.
        $data[$key] = $new_val;
      }
    }
    // Save the new data to the form.
    $webform_submission->setData($data);
  }

}
