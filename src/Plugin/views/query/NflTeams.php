<?php
namespace Drupal\nfl_teams\Plugin\views\query;

use Drupal\Core\Form\FormStateInterface;
use Drupal\nfl_teams\NflTeamsClient;
use Drupal\views\Plugin\views\query\QueryPluginBase;
use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * NFL Team views query plugin which wraps calls to the NFL Team API in order to
 * expose the results to views.
 *
 * @ViewsQuery(
 *   id = "nflteam",
 *   title = @Translation("NFL Team"),
 *   help = @Translation("Query against the NFL Team API.")
 * )
 */
class NflTeams extends QueryPluginBase
{

    /**
     * NFL Teams client.
     *
     * @var \Drupal\nfl_teams\NflTeamsClient
     */
    protected $nflTeamsClient;

    /**
     * Collection of filter criteria.
     *
     * @var array
     */
    protected $where;

    /**
     * Collection of sort criteria.
     *
     * @var array
     */
    protected $orderby;

    /**
     * NflTeams constructor.
     *
     * @param array $configuration
     * @param $plugin_id
     * @param $plugin_definition
     * @param $nfl_teams_client\Drupal\nfl_teams\NflTeamsClient
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, $nfl_teams_client)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->nflTeamsClient = $nfl_teams_client;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static ($configuration, $plugin_id, $plugin_definition, $container->get('nfl_teams_client'));
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ViewExecutable $view)
    {

        /*****************************************************************
         * Filter Check: Retreive View filters and Initialize filter vars
         *****************************************************************/
        if (isset($this->where)) {
            foreach ($this->where as $where_group => $where) {
                foreach ($where['conditions'] as $condition) {
                    // Remove dot from begining of the string.
                    $field_name = ltrim($condition['field'], '.');
                    $filters[$field_name] = $condition['value'];
                }
            }
        }

        /** Unused Filters */
        $fullname = isset($filters['fullname']) ? $filters['fullname'] : null;
        $name = isset($filters['name']) ? $filters['name'] : null;
        $nickname = isset($filters['nickname']) ? $filters['nickname'] : null;
        $display_name = isset($filters['display_name']) ? $filters['display_name'] : null;
        $id = isset($filters['id']) ? $filters['id'] : null;

        /** Used Filters */
        $conference = isset($filters['conference']) ? $filters['conference'] : null;
        $division = isset($filters['division']) ? $filters['division'] : null;

        /*****************************************************************
         * Sorter Check: Retreive View sorters and Initialize sorter vars
         *****************************************************************/

        if (isset($this->orderby)) {
            $field_name = $this->orderby[0]['field'];
            $sorters[$field_name] = $this->orderby[0]['direction'];
        }
        $name_order = isset($sorters['name']) ? $sorters['name'] : null;
        $nickname_order = isset($sorters['nickname']) ? $sorters['nickname'] : null;
        $display_name_order = isset($sorters['display_name']) ? $sorters['display_name'] : null;
        $id_order = isset($sorters['id']) ? $sorters['id'] : null;

        /** Used Sorters */
        $fullname_order = isset($sorters['fullname']) ? $sorters['fullname'] : null;
        $division_order = isset($sorters['division']) ? $sorters['division'] : null;

        /**********************************************
         * API call using NFL Teams service
         * If successful, Display content from API
         * If unsuccessful, Display message telling why
         **********************************************/

        if ($api_response = $this
            ->nflTeamsClient
            ->getTeams()) {
            
            /** Index Initialization */
            $index = 0;

            if ($api_response === 'Error') {

                /*******************************************************
                 * Bad API Response / Error: Exception and Error Handling
                 *******************************************************/
                $config = \Drupal::config('nfl_teams.settings');
                $row['fullname'] = $config->get('outage_msg');
                $row['index'] = $index;
                $view->result[] = new ResultRow($row);

            } else {

                /***********************************
                 * Good API Response: Data processing
                 ************************************/

                /** Initializations */
                $nfl_teams = $api_response['results']['data']['team'];
                $columns = $api_response['results']['columns'];
                $teams = 'Teams';

                foreach ($nfl_teams as $nfl_team) {
                    if ($conference === $nfl_team['conference'] || !isset($conference)) {
                        if (isset($division) && !isset($conference)) {
                            // No conference selected
                            $row['headline'] = 'Please select a Conference.';
                            $row['index'] = $index++;
                            $view->result[] = new ResultRow($row);
                            break;
                        } elseif ($division === $nfl_team['division'] || !isset($division)) {
                            // LA and NY Full name Exemptions
                            strpos($nfl_team['name'], $nfl_team['nickname']) ? $row['fullname'] = $nfl_team['name'] : $row['fullname'] = $nfl_team['name'] . ' ' . $nfl_team['nickname'];
                            $row['name'] = $nfl_team['name'];
                            $row['nickname'] = $nfl_team['nickname'];
                            $row['display_name'] = $nfl_team['display_name'];
                            $row['id'] = $nfl_team['id'];
                            $row['conference'] = $nfl_team['conference'];
                            $row['division'] = $nfl_team['division'];
                            // Required 'index' key.
                            $row['index'] = $index++;
                            $view->result[] = new ResultRow($row);
                        }
                    }
                }

                /*********************************************************************************
                 *
                 *  Sorting done manually since Data is not stored in database (can not be queried)
                 *
                 ********************************************************************************/

                /************************************************************
                 * Sorting by Full Name based on View Selection (Default ASC)
                 ************************************************************/

                if ($fullname_order === 'ASC' || !isset($fullname_order)) {
                    // Ascending order
                    if (!empty($view->result)) {
                        usort($view->result, function ($a, $b) {
                            if ($a->fullname < $b->fullname) {
                                return -1;
                            } elseif ($a->fullname > $b->fullname) {
                                return 1;
                            } else {
                                return 0;
                            }
                        });

                        // Re-index array
                        $index = 0;
                        foreach ($view->result as & $row) {
                            $row->index = $index++;
                            if ($row->index === 0) {
                                if (isset($conference)) {
                                    $conference === 'American Football Conference' ? $conf = 'American Football Conference (AFC)' : $conf = 'National Football Conference (NFC)';
                                    $row->headline = $conf . ' ' . $teams;
                                    if (isset($division)) {
                                        $row->headline = $conf . ' ' . $division . ' ' . $teams;
                                    }
                                } else {
                                    $row->headline = 'All Teams';
                                    if (isset($division)) {
                                        $row->headline = 'Please select a Conference.';
                                    }
                                }
                            } else {
                                $row->headline = '';
                            }
                        }
                    }
                } elseif ($fullname_order === 'DESC') {
                    // Descending order
                    if (!empty($view->result)) {
                        usort($view->result, function ($a, $b) {
                            if ($a->fullname < $b->fullname) {
                                return 1;
                            } elseif ($a->fullname > $b->fullname) {
                                return -1;
                            } else {
                                return 0;
                            }
                        });

                        // Re-index array
                        $index = 0;
                        foreach ($view->result as & $row) {
                            $row->index = $index++;
                            if ($row->index === 0) {
                                if (isset($conference)) {
                                    $conference === 'American Football Conference' ? $conf = 'American Football Conference (AFC)' : $conf = 'National Football Conference (NFC)';
                                    $row->headline = $conf . ' ' . $teams;
                                    if (isset($division)) {
                                        $row->headline = $conf . ' ' . $division . ' ' . $teams;
                                    }
                                } else {
                                    $row->headline = 'All Teams';
                                    if (isset($division)) {
                                        $row->headline = 'Please select a Conference.';
                                    }
                                }
                            } else {
                                $row->headline = '';
                            }
                        }
                    }
                }

                /***********************************************
                 * Sorting by Division based on View Selection
                 ***********************************************/

                if ($division_order === 'ASC') {
                    if (!empty($view->result)) {
                        usort($view->result, function ($a, $b) {
                            if ($a->division < $b->division) {
                                return -1;
                            } elseif ($a->division > $b->division) {
                                return 1;
                            } else {
                                return 0;
                            }
                        });

                        // Re-index array
                        $index = 0;
                        foreach ($view->result as & $row) {
                            $row->index = $index++;
                        }
                    }
                } elseif ($division_order === 'DESC') {
                    if (!empty($view->result)) {
                        usort($view->result, function ($a, $b) {
                            if ($a->division < $b->division) {
                                return 1;
                            } elseif ($a->division > $b->division) {
                                return -1;
                            } else {
                                return 0;
                            }
                        });

                        // Re-index array
                        $index = 0;
                        foreach ($view->result as & $row) {
                            $row->index = $index++;
                        }
                    }
                }
            }
        }
    }

    /*******************************************************************************
     * Start of methods necessary to replicate the interface of Views' default SQL
     * query plugin backend to simplify the Views integration of the AcmeSports API.
     *******************************************************************************/

    /**
     * Adds a field to the table. In our case, the AcmeSports API has no
     * notion of limiting the fields that come back, so tracking a list
     * of fields to fetch is irrellevant for us. Hence this function body is more
     * or less empty and it serves only to satisfy handlers that may assume an
     * addField method is present b/c they were written against Views' default SQL
     * backend.
     *
     * This replicates the interface of Views' default SQL backend to simplify
     * the Views integration of the AcmeSports API.
     *
     * @param string $table
     *   NULL in most cases, we could probably remove this altogether.
     * @param string $field
     *   The name of the metric/dimension/field to add.
     * @param string $alias
     *   Probably could get rid of this too.
     * @param array $params
     *   Probably could get rid of this too.
     *
     * @return string
     *   The name that this field can be referred to as.
     *
     * @see \Drupal\views\Plugin\views\query\Sql::addField()
     */
    public function addField($table, $field, $alias = '', $params = array())
    {
        return $field;
    }

    /**
     * Adds a simple condition to the query. Collect data on the configured filter
     * criteria so that we can appropriately apply it in the query() and execute()
     * methods.
     *
     * @param $group
     *   The WHERE group to add these to; groups are used to create AND/OR
     *   sections. Groups cannot be nested. Use 0 as the default group.
     *   If the group does not yet exist it will be created as an AND group.
     * @param $field
     *   The name of the field to check.
     * @param $value
     *   The value to test the field against. In most cases, this is a scalar. For more
     *   complex options, it is an array. The meaning of each element in the array is
     *   dependent on the $operator.
     * @param $operator
     *   The comparison operator, such as =, <, or >=. It also accepts more
     *   complex options such as IN, LIKE, LIKE BINARY, or BETWEEN. Defaults to =.
     *   If $field is a string you have to use 'formula' here.
     *
     * @see \Drupal\Core\Database\Query\ConditionInterface::condition()
     * @see \Drupal\Core\Database\Query\Condition
     */
    public function addWhere($group, $field, $value = null, $operator = null)
    {
        // Ensure all variants of 0 are actually 0. Thus '', 0 and NULL are all
        // the default group.
        if (empty($group)) {
            $group = 0;
        }

        // Check for a group.
        if (!isset($this->where[$group])) {
            $this->setWhereGroup('AND', $group);
        }

        $this->where[$group]['conditions'][] = ['field' => $field, 'value' => $value, 'operator' => $operator, ];
    }

    /**
     * Ensures a table exists in the query.
     *
     * This replicates the interface of Views' default SQL backend to simplify
     * the Views integration of the AcmeSports API. Since the AcmeSports API has no
     * concept of "tables", this method implementation does nothing. If you are
     * writing AcmeSports API-specific Views code, there is therefore no reason at all
     * to call this method.
     * See https://www.drupal.org/node/2484565 for more information.
     *
     * @return string
     *   An empty string.
     */
    public function ensureTable($table, $relationship = null)
    {
        return '';
    }

    /**
     * Add an ORDER BY clause in order to order the Fields in ASC or DESC order.
     *
     * @param $table
     *   The table this field is part of. If a formula, enter NULL.
     *   If you want to orderby random use "rand" as table and nothing else.
     * @param $field
     *   The field or formula to sort on. If already a field, enter NULL
     *   and put in the alias.
     * @param $order
     *   Either ASC or DESC.
     * @param $alias
     *   The alias to add the field as. In SQL, all fields in the order by
     *   must also be in the SELECT portion. If an $alias isn't specified
     *   one will be generated for from the $field; however, if the
     *   $field is a formula, this alias will likely fail.
     * @param $params
     *   Any params that should be passed through to the addField.
     */

    public function addOrderBy($table, $field = null, $order = 'ASC', $alias = '', $params = [])
    {

        // Only ensure the table if it's not the special random key.
        // @todo: Maybe it would make sense to just add an addOrderByRand or something similar.
        if ($table && $table != 'rand') {
            $this->ensureTable($table);
        }

        // Only fill out this aliasing if there is a table;
        // otherwise we assume it is a formula.
        if (!$alias && $table) {
            $as = $table . '_' . $field;
        } else {
            $as = $alias;
        }
        if ($field) {
            $as = $this->addField($table, $field, $as, $params);
        }
        $this->orderby[] = ['field' => $as, 'direction' => strtoupper($order) , ];
    }

    /*******************************************************************************
     * End of methods necessary to replicate the interface of Views' default SQL
     * query plugin backend to simplify the Views integration of the AcmeSports API.
     *******************************************************************************/
}
