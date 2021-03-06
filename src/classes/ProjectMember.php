<?php

class ProjectMember extends DatabaseObject
{
    public $id;
    public $userID;
    public $projectID;
    public $isAdmin;
    public $ownerID;
    public $username;

    protected static $tableName = 'projectmembers';
    protected static $dbFields = array('id', 'userID', 'projectID', 'isAdmin');

    /**
     * Returns whether a user is a member of a specified project id
     */
    public static function isProjectMember($db, $projectID, $userID)
    {
        $sql = 'SELECT * FROM projectmembers ';
        $sql .= 'WHERE projectID = '.(int) $projectID.' ';
        $sql .= 'AND userID = '.(int) $userID.' ';
        $sql .= 'LIMIT 1';

        $result = self::findBySQL($db, $sql);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns whether a user is a member of a specified project name
     */
    public static function isProjectMemberByProjectName($db, $projectName, $userID)
    {
        $sql = 'SELECT * FROM projectmembers ';
        $sql .= 'INNER JOIN projects ON projectID = projects.id ';
        $sql .= 'WHERE projectName = ? AND userID = '.(int) $userID.' ';
        $sql .= 'LIMIT 1';

        $paramArray = array($projectName);

        $result = self::findBySQL($db, $sql, $paramArray);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns whether a user is an admin of a specified project name
     */
    public static function isProjectAdmin($db, $projectName, $userID)
    {
        $sql = 'SELECT isAdmin ';
        $sql .= 'FROM projectmembers INNER JOIN projects ON projectID = projects.id ';
        $sql .= 'WHERE projectName = ? AND userID = '.(int) $userID.' ';
        $sql .= 'LIMIT 1';
        $paramArray = array($projectName);
        $result = self::findBySQL($db, $sql, $paramArray);
        if ($result) {
            if ($result[0]->isAdmin == true) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Returns all project members for a project from a specified project name.
     */
    public static function findProjectMembersByProjectName($db, $projectName, $adminFirst = true)
    {
        $sql = 'SELECT ownerID, userID, username, isAdmin ';
        $sql .= 'FROM projectmembers INNER JOIN projects ON projects.id = projectID ';
        $sql .= 'INNER JOIN users ON userID = users.id ';
        $sql .= 'WHERE projectName = ? ';
        $sql .= 'ORDER BY ';

        if ($adminFirst) {
            $sql .= 'isAdmin DESC,';
        }

        $sql .= ' username ASC';

        $paramArray = array($projectName);
        $results = self::findBySQL($db, $sql, $paramArray);
        if ($results) {
            return $results;
        } else {
            return false;
        }
    }

    /**
     * Returns a project member from a specified project name and username
     */
    public static function findProjectMemberByProjectNameAndUsername($db, $projectName, $username)
    {
        $sql = 'SELECT projectmembers.id as id, userID, projectID, isAdmin, username ';
        $sql .= 'FROM projectmembers INNER JOIN users ON userID = users.id ';
        $sql .= 'INNER JOIN projects ON projectID = projects.id ';
        $sql .= 'WHERE projectName = ? AND username = ? LIMIT 1';
        $paramArray = array($projectName, $username);

        $result = self::findBySQL($db, $sql, $paramArray);
        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }
}
