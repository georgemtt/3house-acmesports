<?php

/**
 * @file
 * Contains nfl_teams\nfl_teams.views.inc..
 * Provide a custom views field data. */


/**
* Implements hook_views_data().
*/
function nfl_teams_views_data()
{
    $data = [];

    /**
    * Base data.
    */
    $data['nflteam']['table']['group'] = t('NFL Team Information');
    $data['nflteam']['table']['base'] = [
        'title' => t('NFL Team'),
        'query_id' => 'nflteam',
        'help' => t('NFL team data provided by the AcmeSports API endpoint.'),
    ];

    /**
    * Fields.
    */

    $data['nflteam']['headline'] = [
        'title' => t('Headline'),
        'help' => t('Headline text.'),
        'field' => [
          'id' => 'standard',
        ],
    ];

    $data['nflteam']['fullname'] = [
        'title' => t('Team full name'),
        'help' => t('NFL Team full name.'),
        'field' => [
          'id' => 'standard',
        ],
        'filter' => [
            'id' => 'string',
        ],
        'sort' => [
            'id' => 'standard',
        ],
    ];

    /**
    * API pulled Fields.
    */

    $data['nflteam']['name'] = [
        'title' => t('Team name'),
        'help' => t('NFL Team city name.'),
        'field' => [
          'id' => 'standard',
        ],
        'filter' => [
            'id' => 'string',
        ],
        'sort' => [
            'id' => 'standard',
        ],
    ];

    $data['nflteam']['nickname'] = [
        'title' => t('Team nickname'),
        'help' => t('NFL Team nickname.'),
        'field' => [
          'id' => 'standard',
        ],
        'filter' => [
            'id' => 'string',
        ],
        'sort' => [
            'id' => 'standard',
        ],
    ];

    $data['nflteam']['display_name'] = [
        'title' => t('Team display name'),
        'help' => t('NFL Team city name.'),
        'field' => [
          'id' => 'standard',
        ],
        'filter' => [
            'id' => 'string',
        ],
        'sort' => [
            'id' => 'standard',
        ],
    ];

    $data['nflteam']['id'] = [
        'title' => t('Team id'),
        'help' => t('NFL Team id.'),
        'field' => [
          'id' => 'standard',
        ],
        'filter' => [
            'id' => 'string',
        ],
        'sort' => [
            'id' => 'standard',
        ],
    ];

    $data['nflteam']['conference'] = [
        'title' => t('Team conference'),
        'help' => t('NFL Team conference.'),
        'field' => [
          'id' => 'standard',
        ],
        'filter' => [
            'id' => 'string',
        ],
        'sort' => [
            'id' => 'standard',
        ],
    ];

    $data['nflteam']['division'] = [
        'title' => t('Team division'),
        'help' => t('NFL Team division.'),
        'field' => [
          'id' => 'standard',
        ],
        'filter' => [
            'id' => 'string',
        ],
        'sort' => [
            'id' => 'standard',
        ],
    ];

    return $data;
}
