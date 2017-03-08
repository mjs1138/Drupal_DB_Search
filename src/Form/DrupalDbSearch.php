<?php

namespace Drupal\drupal_db_search\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Implements the SimpleForm form controller.
 *
 * This example demonstrates a simple form with a singe text input element. We
 * extend FormBase which is the simplest form base class used in Drupal.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class DrupalDbSearch extends FormBase
{

    /**
     * Build the simple form.
     *
     * A build form method constructs an array that defines how markup and
     * other form elements are included in an HTML form.
     *
     * @param array $form
     *   Default form array structure.
     * @param FormStateInterface $form_state
     *   Object containing current form state.
     *
     * @return array
     *   The render array defining the elements of the form.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['#tree'] = TRUE;

        $form['User'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('User'),
        ];

        $form['User']['FirstName'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
            '#description' => $this->t(''),
            '#required' => TRUE,
        ];

        $form['User']['LastName'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
            '#description' => $this->t(''),
            '#required' => TRUE,
        ];

        // Group submit handlers in an actions element with a key of "actions" so
        // that it gets styled correctly, and so that other modules may add actions
        // to the form. This is not required, but is convention.
        $form['actions'] = [
            '#type' => 'actions',
        ];

        // Add a submit button that handles the submission of the form.
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    /**
     * Getter method for Form ID.
     *
     * The form ID is used in implementations of hook_form_alter() to allow other
     * modules to alter the render array built by this form controller.  it must
     * be unique site wide. It normally starts with the providing module's name.
     *
     * @return string
     *   The unique ID of the form defined by this class.
     */
    public function getFormId()
    {
        return 'simpleForm_simple_form';
    }


    /**
     * Implements form validation.
     *
     * The validateForm method is the default method called to validate input on
     * a form.
     *
     * @param array $form
     *   The render array of the currently built form.
     * @param FormStateInterface $form_state
     *   Object describing the current state of the form.
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $title = $form_state->getValue('title');
        if (strlen($title) < 0) {
            // Set an error for the form element with a key of "title".
            $form_state->setErrorByName('title', $this->t('The title must be at least 5 characters long.'));
        }
    }

    /**
     * Implements a form submit handler.
     *
     * The submitForm method is the default method called for any submit elements.
     *
     * @param array $form
     *   The render array of the currently built form.
     * @param FormStateInterface $form_state
     *   Object describing the current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        /*
         * This would normally be replaced by code that actually does something
         * with the title.
         */
        $title = $form_state->getValue('title');
        $values = $form_state->getValues();

        ksm($form_state); //????
        $_SESSION['mjs'] = $form_state;
//       $url = Url::fromRoute('simpleForm.description'); // generate a url for route
//        $url = Url::fromRoute('simpleForm.basicPageRedirect'); // generate a url for route
        $url = Url::fromRoute('simpleForm.displayTable'); // generate a url for route



        $form_state->setRedirectUrl($url);
    }

}
