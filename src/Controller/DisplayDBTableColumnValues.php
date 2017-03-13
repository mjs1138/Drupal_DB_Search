<?php

namespace Drupal\drupal_db_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
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
        $targetDbS = $this->_getListOfTargetDbS($dbList, $targetTable);
        $dBValueS = $this->_getColumnValueS($targetDbS, $targetTable, $targetColumn);

        foreach ($dBValueS as $dbName => $dbColDataS) {
            foreach ($dbColDataS as $data) {
                $tableRowS[] = array($dbName, $data);
            }
        }

        $table = array(
            '#theme' => 'table',
            '#header' => array(t('Databse'), t('Table: ' . $targetTable)),
            '#rows' => $tableRowS,
        );

        return $table;

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

//        $pdo = new \PDO(sprintf('mysql:host=%s', DB_HOST), DB_USER, DB_PASS); //PDO without selecting DB

        $connection = \Drupal::database();
        foreach ($dbList as $db) {
            $dbName = $db->Database;

            $connection->query("USE  `$db->Database`"); // Select DB

            if ($result = $connection->query("SHOW TABLES FROM `$db->Database`")) {
//                $tableS = $result->fetchCol();
//                $tableS = $result->fetchAllKeyed("fun");
//                $tableS = $result->fetchAll();
//                $tableS = $result->fetchAll(\PDO::FETCH_ASSOC);
                $tableS = $result->fetchAll(\PDO::FETCH_BOTH);
//                $tableS = $result->fetch();
                foreach ($tableS as $key => $table) {
                    if ($table[0] == $targetTable) {
                        $dbTargetDbList[] = $db->Database;
                    }
                    $count++;
                }
            }
        }
        return $dbTargetDbList;
    }

    function _getColumnValueS($targetDbS, $targetTable, $targetColumn)
    {
        $count = 0;
        $colValueS = array();

        $connection = \Drupal::database();
        foreach ($targetDbS as $db) {
//            $dbName = $db->Database;
            $connection->query("USE  `$db`"); // Select DB
            $result = $connection->query("SELECT $targetColumn FROM $targetTable");
            $valueS = $result->fetchCol();

            foreach ($valueS as $value) {
                $colValueS[$db][] = $value;
            }
        }
        return $colValueS;
    }

}
