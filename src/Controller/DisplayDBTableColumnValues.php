<?php

namespace Drupal\drupal_db_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Driver\mysql\Connection;

/**
 * Simple page controller for drupal.
 */
class DisplayDBTableColumnValues extends ControllerBase
{

    public function displayDbTableColumnValues()
    {

        $targetTable = $_SESSION['DrupalDbSearch']['FormValues']['table'];
        $targetColumn = $_SESSION['DrupalDbSearch']['FormValues']['column'];

        $dbList = $this->_getListOfDbS();
//        $targetDbS = $this->_getListOfTargetDbS($dbList, $targetTable);





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

    function _getListOfDbS()
    {
        $connection = \Drupal::database();
        $databaseS = $connection->query('SHOW DATABASES')->fetchAll();
        return $databaseS;
    }

    function _getListOfTargetDbS($dbList, $targetTable)
    {
        $count = 0;
        $dbTargetDbList = array();
        $pdo = new PDO(sprintf('mysql:host=%s', DB_HOST), DB_USER, DB_PASS); //PDO without selecting DB
        foreach ($dbList as $db) {
            $pdo->query('USE ' . $db); // Select DB
            if ($result = $pdo->query('SHOW TABLES FROM ' . $db)) {
                $tableS = $result->fetchAll();
                foreach ($tableS as $table) {
                    if ($table[0] == $targetTable) {
                        $dbTargetDbList[] = $db;
                    }
                    $count++;
                }
            }
        }
        return $dbTargetDbList;
    }


}
