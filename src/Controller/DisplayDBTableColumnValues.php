<?php

namespace Drupal\drupal_db_search\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Simple page controller for drupal.
 */
class DisplayDBTableColumnValues extends ControllerBase
{

    public function displayDbTableColumnValues()
    {

//        $dbList = $this->_buildDatabaseListz();
//        $connection = \Drupal::database();
//        $result = $connection->query('SHOW DATABASES');

        $query = \Drupal::database()->select('users_field_data', 'u');
        $query->fields('u', ['uid', 'name', 'mail']);
        $results = $query->execute()->fetchAll();

        $header = [
            'userid' => t('User id'),
            'Username' => t('username'),
            'email' => t('Email'),
        ];

        // Populate the rows.
        $rows = array();
        foreach ($results as $row) {
            $rows[] = array('data' => array(
                'userid' => $row->uid,     // 'userid' was the key used in the header
                'Username' => $row->name, // 'Username' was the key used in the header
                'email' => $row->mail,    // 'email' was the key used in the header
            ));
        }

        $form['table'] = [
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#empty' => t('No users found!!!'),
        ];


        return $form;
    }

    function _buildDatabaseListz()
    {
        $a = 1;
        return $a;
    }
}
