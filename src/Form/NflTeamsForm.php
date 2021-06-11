<?php
/**
 * @file
 * Contains Drupal\nfl_teams\Form\MessagesForm.
 */
namespace Drupal\nfl_teams\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class NflTeamsForm extends ConfigFormBase
{

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['nfl_teams.settings', ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'nfl_teams_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('nfl_teams.settings');

        // Fieldset for credentials
        
        $form['nfl_teams_api_creds'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('NFL Team API Credentials'),
            '#collapsible' => false,
        ];

        $form['nfl_teams_api_creds']['base_uri'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Base URI'),
            '#required' => true,
            '#default_value' => $config->get('base_uri'),
            '#description' => $this
            ->t('Example: http://delivery.chalk247.com'),
        ];

        $form['nfl_teams_api_creds']['endpoint'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Endpoint'),
            '#required' => true,
            '#default_value' => $config->get('endpoint'),
            '#description' => $this
            ->t('Example: team_list/NFL.JSON'),
        ];

        $form['nfl_teams_api_creds']['api_key'] = [
            '#type' => 'textfield',
            '#title' => $this->t('API Key'),
            '#required' => true,
            '#default_value' => $config->get('api_key'),
            '#description' => $this
            ->t('Valid API Key for retreiving resource.'),
        ];

        $form['nfl_teams_api_creds']['outage_msg'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Outage Message'),
            '#required' => true,
            '#default_value' => 'Unable to Access ACME Sports API currently. Please try again later.',
            '#description' => $this
            ->t('Outage Message in the event of a bad response.'),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array & $form, FormStateInterface $form_state)
    {
        $this->config('nfl_teams.settings')
            ->set('base_uri', $form_state->getValue('base_uri'))
            ->set('endpoint', $form_state->getValue('endpoint'))
            ->set('api_key', $form_state->getValue('api_key'))
            ->set('outage_msg', $form_state->getValue('outage_msg'))
            ->save();

        $this->messenger()
            ->addStatus($this->t('Your credentials have been saved'));
    }
}
