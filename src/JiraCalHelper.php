<?php

namespace Josevh\JiraCal;

use Carbon\Carbon;

class JiraCalHelper {

    /**
    * Return Jira client
    * @return Obj Jira api client
    */
    public static function jiraApi($username = null, $password = null) {
        if (null === $username) {
            $username = session('jira-username');
        }
        if (null === $password) {
            $password = \Crypt::decrypt(session('jira-password'));
        }
        return new \chobie\Jira\Api(
            config('jiracal.jira_url'),
            new \chobie\Jira\Api\Authentication\Basic($username, $password)
        );
    }

    /**
     * Format Jira issue date
     * @param  String $time Date, time, or timestamp
     * @param  String $type Indicates type of format $time is in
     * @return String       Formatted datetime
     */
    public static function jiraDateFormat($time, $type) {
        if (is_null($time) || is_null($type)) {
            return '';
        }
        switch ($type) {
            case 'timestamp':
                $formatIn = 'Y-m-d*H:i:s.***P';
                break;
            case 'duedate':
                $formatIn = 'Y-m-d';
                break;
        }
        $formatOut = 'F m, Y';
        try {
            return Carbon::createFromFormat($formatIn, $time)->format($formatOut);
        } catch (Exception $e) {
            return '**Error formatting (' . $time . ')**';
        }
    }
    /**
     * Get Carbon datetime object from Jira issue date
     * @param  String $time Date, time, or timestamp
     * @param  String $type Indicates type of format $time is in
     * @return Object       Carbon datetime object
     */
    public static function jiraDateObj($time, $type) {
        if (is_null($time) || is_null($type)) {
            return '';
        }
        switch ($type) {
            case 'timestamp':
                $formatIn = 'Y-m-d*H:i:s.***P';
                break;
            case 'duedate':
                $formatIn = 'Y-m-d';
                break;
        }
        $formatOut = 'F m, Y';
        try {
            return Carbon::createFromFormat($formatIn, $time);
        } catch (Exception $e) {
            return false;
        }
    }
    /**
     * Get issues in range
     * @param  Integer $year    Year number
     * @param  Integer $month   Month number
     * @param  Integer $day     Day number
     * @param  String $range    Range for JQL search
     * @return Array            Array with days as key with issues on specific days
     */
    public static function jiraIssues($key = null, $year = null, $month = null, $day = null, $range = null) {
        if (null === $key) { return false; }
        if (null === $year) { return false; }
        if (null === $month) { return false; }
        if (null === $day) { $day = 01; }
        if (null === $range) { $range = 'month'; }
        $api = JiraCalHelper::jiraApi();
        $jFormat = 'Y/m/d';
        if ($range == 'month') {
            $rangeBegin = Carbon::create($year, $month, 1, 0)->format($jFormat);
            $rangeEnd = Carbon::create($year, $month, 1, 0)->addMonth()->format($jFormat);
            $fields = "created,summary";
        } else {
            $rangeBegin = Carbon::create($year, $month, $day, 0)->format($jFormat);
            $rangeEnd = Carbon::create($year, $month, $day, 0)->addDay()->format($jFormat);
            $fields = "*navigable";
        }
        $jql = 'project = ' . $key . ' and created >= "' . $rangeBegin . '" and created <= "' . $rangeEnd . '"';
        $result = $api->search($jql,0,1000, $fields);
        $payload = [];
        if (!empty($result->getTotal())) {
            $issues = $result->getIssues();
            foreach ($issues as $key => $issue) {
                $created = JiraCalHelper::jiraDateObj($issue->getCreated(), 'timestamp');
                if (isset($payload[$created->day])) {
                    array_push($payload[$created->day], $issue);
                } else {
                    $payload[$created->day] = [$issue];
                }
                unset($created);
            }
        }
        return $payload;
    }

    private static function jiraProjectID($key){
        $api = JiraCalHelper::jiraApi();
        return $api->getProject($key)['id'];
    }

    public static function jiraCreateIssueLink($key){        
        return config('jiracal.jira_url') . 'secure/CreateIssue!default.jspa?pid=' . JiraCalHelper::jiraProjectID($key);;
    }

}
