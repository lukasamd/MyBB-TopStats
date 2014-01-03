<?php
/**
 * This file is part of Top Stats plugin for MyBB.
 * Copyright (C) 2010-2013 baszaR & LukasAMD & Supryk
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */ 

/**
 * Disallow direct access to this file for security reasons
 * 
 */
if (!defined("IN_MYBB")) exit;

/**
 * Create plugin object
 * 
 */
$plugins->objects['topStats'] = new topStats();

/**
 * Standard MyBB info function
 * 
 */
function topStats_info() 
{
    global $lang;
    $lang->load("topStats");
	return array(
        'name'          => $lang->topStats_Name,
        'description'   => $lang->topStats_NameDesc,
		'website'		=> 'http://mybboard.pl/',
		'author'		=> 'baszaR & LukasAMD & Supryk',
		'authorsite'	=> 'http://mybboard.pl/',
		'version'		=> '2.1',
		'guid'			=> '',
		'compatibility' => '16*'
	);
}

/**
 * Standard MyBB installation functions 
 * 
 */
function topStats_install()
{
    require_once('topStats.settings.php');
    topStatsInstaller::install();
    rebuildsettings();

}

function topStats_is_installed()
{

    global $mybb;
    return (isset($mybb->settings['topStats_Status_LastThreads']));
}

function topStats_uninstall()
{

    require_once('topStats.settings.php');
    topStatsInstaller::uninstall();
    rebuildsettings();
} 
  
/**
 * Standard MyBB activation functions 
 * 
 */
function topStats_activate()
{

    require_once('topStats.tpl.php');
    topStatsActivator::activate();
}

function topStats_deactivate()
{
    require_once('topStats.tpl.php');
    topStatsActivator::deactivate();
}



/**
 * Plugin Class 
 * 
 */
class topStats
{
    /**
     * Constructor - add plugin hooks
     *      
     */
    public function __construct()
    {
        global $plugins;

        $plugins->hooks["global_start"][10]["topStats_addHooks"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->addHooks();'));
    }

    /**
     * Add all needed hooks
     *      
     */
    public function addHooks()
    {
        global $lang, $mybb, $plugins, $templatelist, $topStats;

        $topStats = array(
            'LastThreads'   => '',
            'MostViews'     => '',
            'Posters'       => '',
            'Reputation'    => '',
            'Timeonline'    => '',
            'NewestUsers'   => '',
        );

    	if (!$this->getConfig('Status_All'))
        {
            return;
        }

        $lang->load("topStats");
    	if ($this->getConfig('Status_LastThreads'))
        {
            $plugins->hooks["index_start"][10]["topStats_LastThreads"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_LastThreads();'));
            $templatelist .= ',topStats_LastThreads,topStats_LastThreadsRow';    
        }
    	if ($this->getConfig('Status_MostViews'))
        {
            $plugins->hooks["index_start"][10]["topStats_MostViews"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_MostViews();'));
            $templatelist .= ',topStats_MostViews,topStats_MostViewsRow';    
        }
    	if ($this->getConfig('Status_Posters'))
        {
            $plugins->hooks["index_start"][10]["topStats_Posters"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_Posters();'));
            $templatelist .= ',topStats_Posters,topStats_PostersRow';    
        }
    	if ($this->getConfig('Status_Reputation'))
        {
            $plugins->hooks["index_start"][10]["topStats_Reputation"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_Reputation();'));
            $templatelist .= ',topStats_Reputation,topStats_ReputationRow';    
        }
    	if ($this->getConfig('Status_Timeonline'))
        {
            $plugins->hooks["index_start"][10]["topStats_Timeonline"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_Timeonline();'));
            $templatelist .= ',topStats_Timeonline,topStats_TimeonlineRow';    
        }
    	if ($this->getConfig('Status_NewestUsers'))
        {
            $plugins->hooks["index_start"][10]["topStats_NewestUsers"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_NewestUsers();'));
            $templatelist .= ',topStats_NewestUsers,topStats_NewestUsersRow';    
        }   
    }
    
    /**
     * Widget with last threads list
     *   
     */ 
    public function widget_LastThreads()
    {   
        global $db, $lang, $mybb, $templates, $topStats;

        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth');
        $tpl['limit'] = (int) $this->getConfig('Limit_LastThreads');
		$tpl['ignore_forums'] = $this->getConfig('IgnoreForums_LastThreads');
		if($tpl['ignore_forums'] == '')
		{
			$tpl['ignore_forums'] = '99999999';
		}		
        $tpl['row'] = '';
    
        $sql = "SELECT t.*, u.usergroup, u.displaygroup, u.avatar 
                FROM ".TABLE_PREFIX."threads AS t
                INNER JOIN ".TABLE_PREFIX."users AS u USING (uid) 
                WHERE " . $this->buildThreadsWhere() ." AND fid NOT IN(". $tpl['ignore_forums']. ")
                ORDER BY tid DESC LIMIT {$tpl['limit']}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))
        {
            $tpl['subject'] = (my_strlen($row['subject']) > 30) ? my_substr($row['subject'], 0, 30) . "..." : $row['subject'];
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
            $tpl['date'] = my_date($mybb->settings['dateformat'] . " " . $mybb->settings['timeformat'], $row['dateline']);
    		$tpl['subjectlink'] = get_thread_link($row['tid']);
            $tpl['avatar'] = (!$this->getConfig('Status_Avatar')) ? '' : $row['avatar']; 
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_LastThreadsRow") . "\";");
        }
        eval("\$topStats['LastThreads'] = \"" . $templates->get("topStats_LastThreads") . "\";");
    }
    
    /**
     * Widget with most views threads
     *   
     */ 
    public function widget_MostViews()
    {
        global $db, $lang, $mybb, $templates, $topStats;

        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth');
        $tpl['limit'] = (int) $this->getConfig('Limit_MostViews');
		$tpl['ignore_forums'] = $this->getConfig('IgnoreForums_MostViews');
		if($tpl['ignore_forums'] == '')
		{
			$tpl['ignore_forums'] = '99999999';
		}		
        $tpl['row'] = '';
    
        $sql = "SELECT t.*, u.usergroup, u.displaygroup, u.avatar 
                FROM ".TABLE_PREFIX."threads AS t
                INNER JOIN ".TABLE_PREFIX."users AS u USING (uid) 
                WHERE " . $this->buildThreadsWhere() ." AND fid NOT IN(". $tpl['ignore_forums']. ")
                ORDER BY views DESC LIMIT {$tpl['limit']}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))
        {
            $tpl['subject'] = (my_strlen($row['subject']) > 30) ? my_substr($row['subject'], 0, 30) . "..." : $row['subject'];
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
            $tpl['date'] = my_date($mybb->settings['dateformat'] . " " . $mybb->settings['timeformat'], $row['dateline']);
    		$tpl['subjectlink'] = get_thread_link($row['tid']);
            $tpl['avatar'] = (!$this->getConfig('Status_Avatar')) ? '' : $row['avatar']; 
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_MostViewsRow") . "\";");
        }
        eval("\$topStats['MostViews'] = \"" . $templates->get("topStats_MostViews") . "\";");
    }
    
    /**
     * Widget with most posters list
     *   
     */ 
    public function widget_Posters()
    {
        global $db, $lang, $mybb, $templates, $topStats;

        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth');
        $tpl['limit'] = (int) $this->getConfig('Limit_Posters');
		$tpl['ignore_groups'] = $this->getConfig('IgnoreGroups_Posters');
		if($tpl['ignore_groups'] == '')
		{
			$tpl['ignore_groups'] = '99999999';
		}		
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, postnum, uid, avatar 
                FROM ".TABLE_PREFIX."users 
				WHERE usergroup NOT IN(". $tpl['ignore_groups'] . ")
                ORDER BY postnum DESC 
                LIMIT {$tpl['limit']}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['postnum'] = $row['postnum'];
            $tpl['avatar'] = (!$this->getConfig('Status_Avatar')) ? '' : $row['avatar']; 
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_PostersRow") . "\";");
        }
        eval("\$topStats['Posters'] = \"" . $templates->get("topStats_Posters") . "\";");
    }
    /**
     * Widget with reputation list
     *   
     */ 
    public function widget_Reputation()
    {
        global $db, $lang, $mybb, $templates, $topStats;

        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth');
        $tpl['limit'] = (int) $this->getConfig('Limit_Reputation');
		$tpl['ignore_groups'] = $this->getConfig('IgnoreGroups_Reputation');
		if($tpl['ignore_groups'] == '')
		{
			$tpl['ignore_groups'] = '99999999';
		}
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, reputation, uid, avatar 
                FROM ".TABLE_PREFIX."users 
				WHERE usergroup NOT IN(". $tpl['ignore_groups'] . ")
                ORDER BY reputation DESC 
                LIMIT {$tpl['limit']}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['reputation'] = $row['reputation'];
            $tpl['avatar'] = (!$this->getConfig('Status_Avatar')) ? '' : $row['avatar'];     
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_ReputationRow") . "\";");
        }
        eval("\$topStats['Reputation'] = \"" . $templates->get("topStats_Reputation") . "\";");
    }
    
    /**
     * Widget with newest users
     *   
     */ 
    public function widget_NewestUsers()
    {
        global $db, $lang, $mybb, $templates, $topStats;

        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth');
        $tpl['limit'] = (int) $this->getConfig('Limit_NewestUsers');
		$tpl['ignore_groups'] = $this->getConfig('IgnoreGroups_NewestUsers');
		if($tpl['ignore_groups'] == '')
		{
			$tpl['ignore_groups'] = '99999999';
		}
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, regdate, postnum, uid, avatar 
                FROM ".TABLE_PREFIX."users 
				WHERE usergroup NOT IN(". $tpl['ignore_groups'] . ")
                ORDER BY regdate DESC 
                LIMIT {$tpl['limit']}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['date'] = my_date($mybb->settings['dateformat'] . " " . $mybb->settings['timeformat'], $row['regdate'], NULL, 1);
            $tpl['avatar'] = (!$this->getConfig('Status_Avatar')) ? '' : $row['avatar']; 
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_NewestUsersRow") . "\";");
        }
        eval("\$topStats['NewestUsers'] = \"" . $templates->get("topStats_NewestUsers") . "\";");
    }

    /**
     * Widget with users online time
     *   
     */ 
    public function widget_Timeonline()
    {
        global $db, $lang, $mybb, $templates, $topStats;

        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth');
        $tpl['limit'] = (int) $this->getConfig('Limit_Timeonline');
		$tpl['ignore_groups'] = $this->getConfig('IgnoreGroups_Timeonline');
		if($tpl['ignore_groups'] == '')
		{
			$tpl['ignore_groups'] = '99999999';
		}
        $tpl['row'] = '';

        $sql = "SELECT username, usergroup, displaygroup, timeonline, uid, avatar 
                FROM ".TABLE_PREFIX."users 
				WHERE usergroup NOT IN(". $tpl['ignore_groups'] . ")
                ORDER BY timeonline DESC 
                LIMIT {$tpl['limit']}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['time'] = ($row['timeonline'] > 0) ? $this->getFriendlyTime($row['timeonline']) : $lang->none_registered;;
            $tpl['avatar'] = (!$this->getConfig('Status_Avatar')) ? '' : $row['avatar']; 
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_TimeonlineRow") . "\";");
        }
        eval("\$topStats['Timeonline'] = \"" . $templates->get("topStats_Timeonline") . "\";");
    }
    
    /**
     * Get friendly "during time"
     * 
     * @param int $stamp Timestamp to calculate         
     * @return string WHERE statement   
     */  
    private function getFriendlyTime($stamp = 0)
    {
        $nicetime = array();
        
    	$days = floor($stamp / 86400);
    	$stamp %= 86400;
        
    	$hours = floor($stamp / 3600);
    	$stamp %= 3600;
        
    	$minutes = floor($stamp / 60);
    	$stamp %= 60;
    	$seconds = $stamp;

    	if ($days == 1)
    	{
    		$nicetime['days'] = "1"."d";
    	}
    	else if ($days > 1)
    	{
    		$nicetime['days'] = $days."d";
    	}
    
		if ($hours == 1)
		{
			$nicetime['hours'] = "1"."g";
		}
		else if ($hours > 1)
		{
			$nicetime['hours'] = $hours."g";
		}

		if ($minutes == 1)
		{
			$nicetime['minutes'] = "1"."m";
		}
		else if ($minutes > 1)
		{
			$nicetime['minutes'] = $minutes."m";
		}

		if ($seconds == 1)
		{
			$nicetime['seconds'] = "1"."s";
		}
		else if ($seconds > 1)
		{
			$nicetime['seconds'] = $seconds."s";
		}
    
    	if (count($nicetime))
    	{
    		return implode(" ", $nicetime);
    	}
    }
    
    /**
     * Build search query except threads/fids
     *     
     * @return string WHERE statement   
     */     
    private function buildThreadsWhere()
    {
        static $where;
    
        if ($where != '')
        {
            return $where;
        }
        $where = "t.visible = 1 AND t.closed NOT LIKE 'moved|%'";
        $onlyusfids = array();
        $group_permissions = forum_permissions();
        foreach ($group_permissions as $fid => $forum_permissions)
        {
            if ($forum_permissions['canonlyviewownthreads'] == 1)
            {
                $onlyusfids[] = $fid;
            }
        }
        if (!empty($onlyusfids))
        {
            $where .= " AND ((t.fid IN(" . implode(',', $onlyusfids) . ") AND t.uid='{$mybb->user['uid']}') OR t.fid NOT IN(" . implode(',', $onlyusfids) . "))";
        }
        if (!function_exists('get_unsearchable_forums'))
        {        
            if (THIS_SCRIPT == 'index.php')
            {
                global $permissioncache;
                $permissioncache = false;
            } 
            require_once MYBB_ROOT."inc/functions_search.php";   
            $unsearchforums = get_unsearchable_forums();
            if ($unsearchforums)
            {                              
                $where .= " AND t.fid NOT IN ($unsearchforums)";
            }   
        }
        $inactiveforums = get_inactive_forums();
        if ($inactiveforums)
        {
            $where .= " AND t.fid NOT IN ($inactiveforums)";
        } 
        return $where;
    }
    
    /**
     * Helper function to get variable from config
     * 
     * @param string $name Name of config to get
     * @return string Data config from MyBB Settings
     */
    private function getConfig($name)
    {
        global $mybb;
    
        return $mybb->settings["topStats_{$name}"];
    }

}
