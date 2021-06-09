<?php
namespace Drupal\nfl_teams;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\Exception\GuzzleException;

class NflTeamsClient
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * NflTeamsClient constructor.
     *
     * @param $http_client_factory \Drupal\Core\Http\ClientFactory
     */
    public function __construct($http_client_factory)
    {
        $config = \Drupal::config('nfl_teams.settings');
        $this->client = $http_client_factory->fromOptions([
          'base_uri' => $config->get('base_uri'),
        ]);
    }

    /**
     * Get API Response.
     *
     * @param string $endpoint
     * @param string $api_key
     *
     * @return array (IF GOOD)
     * @return string (IF BAD)
     */
    public function getTeams()
    {
        $config = \Drupal::config('nfl_teams.settings');
        try {
            $response = $this
                ->client
                ->get($config->get('endpoint'), [
                  'query' => [
                    'api_key' => $config->get('api_key')
                  ],
                ]);
            /* Succesful Response: Return api data */
            $data = Json::decode($response->getBody());
        } catch (GuzzleException $e) {
            /* Bad Response (All exception handling): Return Error */
            $data = 'Error';
        }
        return $data;
    }
}
