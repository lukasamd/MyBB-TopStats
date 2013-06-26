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
$plugins->objects['unreadPosts'] = new unreadPosts();

/**
 * Standard MyBB info function
 * 
 */
function topStats_info() 
{
    global $lang;
    $lang->load("topStats");
	return array(
        'name' => $lang->topStats_name,
        'description' => $lang->topStats_name_desc,
		'website'		=> 'http://mybboard.pl/',
		'author'		=> 'baszaR & LukasAMD & Supryk',
		'authorsite'	=> 'http://mybboard.pl/',
		'version'		=> '2.0',
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
    return (isset($mybb->settings['topStats_users']));
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
     * Template data
     *   
     */ 
    public $template = array(); 


    /**
     * Add all needed hooks
     *      
     */
    public function addHooks()
    {
        global $mybb, $plugins, $topStats;

        $this->template = array(
            'LastThreads'   => '',
            'MostViews'     => '',
            'Posters'       => '',
            'Reputation'    => '',
            'Timeonline'    => '',
            'NewsetUsers'   => '',
        );
        $topStats =& $this->template;

    	if (!$this->getConfig('Status_All'))
        {
            return;
        }

        $lang->load("topStats");
        $plugins->hooks["index_start"][10]["topStats_LastThreads"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_LastThreads();'));
        $plugins->hooks["index_start"][10]["topStats_MostViews"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_MostViews();'));
        $plugins->hooks["index_start"][10]["topStats_Posters"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_Posters();'));
        $plugins->hooks["index_start"][10]["topStats_Reputation"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_Reputation();'));
        $plugins->hooks["index_start"][10]["topStats_Timeonline"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_Timeonline();'));
        $plugins->hooks["index_start"][10]["topStats_NewsetUsers"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_NewsetUsers();'));
    }
    
    /**
     * Widget with last threads list
     *   
     */ 
    public function widget_LastThreads()
    {   
        global $db, $lang, $mybb, $templates;
        
    	if (!$this->getConfig('Status_LastThreads'))
        {
            return;
        }
    
        $tpl['avatar_width'] = (int) $this->getConfig('avatar_width'];
        $limit = (int) $this->getConfig('Limit_LastThreads');
        $tpl['row'] = '';
    
        $sql = "SELECT t.*, u.usergroup, u.displaygroup, u.avatar 
                FROM ".TABLE_PREFIX."threads AS t
                INNER JOIN ".TABLE_PREFIX."users AS u USING (uid) 
                WHERE " . $this->buildThreadsWhere() ."
                ORDER BY tid DESC LIMIT {$limit}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))
        {
            $tpl['subject'] = (my_strlen($row['subject']) > 30) ? my_substr($row['subject'], 0, 30) . "..." : $row['subject'];
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
            $tpl['date'] = my_date($mybb->settings['dateformat'] . " " . $mybb->settings['timeformat'], $row['dateline']);
    		$tpl['subjectlink'] = get_thread_link($row['tid']);
            $tpl['avatar'] = (!$this->getConfig('avatar')) ? '' : $row['avatar']; 
            $tpl['row'] = eval($templates->get("topstats_LastThreadsRow"));                  .
        }
    
        $this->template['LastThreads'] = eval($templates->get("topstats_LastThreads"));
    }
    
    /**
     * Widget with most views threads
     *   
     */ 
    public function widget_MostViews()
    {
        global $db, $lang, $mybb, $templates;
          
    	if (!$this->getConfig('Status_MostViews'))
        {
            return;
        }
        
        global $db, $lang, $mybb, $templates;
        
    	if (!$this->getConfig('Status_LastThreads'))
        {
            return;
        }
    
        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth'];
        $limit = (int) $this->getConfig('Limit_MostViews');
        $tpl['row'] = '';
    
        $sql = "SELECT t.*, u.usergroup, u.displaygroup, u.avatar 
                FROM ".TABLE_PREFIX."threads AS t
                INNER JOIN ".TABLE_PREFIX."users AS u USING (uid) 
                WHERE " . $this->buildThreadsWhere() ."
                ORDER BY views DESC LIMIT {$limit}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))
        {
            $tpl['subject'] = (my_strlen($row['subject']) > 30) ? my_substr($row['subject'], 0, 30) . "..." : $row['subject'];
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
            $tpl['date'] = my_date($mybb->settings['dateformat'] . " " . $mybb->settings['timeformat'], $row['dateline']);
    		$tpl['subjectlink'] = get_thread_link($row['tid']);
            $tpl['avatar'] = (!$this->getConfig('avatar')) ? '' : $row['avatar']; 
            $tpl['row'] = eval($templates->get("topstats_MostViewsRow"));                  .
        }
    
        $this->template['MostViews'] = eval($templates->get("topstats_MostViews"));
    }
    
    /**
     * Widget with most posters list
     *   
     */ 
    public function widget_Poster()
    {
        global $db, $lang, $mybb, $templates;

    	if (!$this->getConfig('Status_Posters'))
        {
            return;
        }
    
        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth'];
        $limit = (int) $this->getConfig('Limit_Posters');
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, postnum, uid, avatar 
                FROM ".TABLE_PREFIX."users 
                ORDER BY postnum DESC 
                LIMIT {$limit}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['postnum'] = $row['postnum'];
            $tpl['avatar'] = (!$this->getConfig('avatar')) ? '' : $row['avatar']; 
            $tpl['row'] = eval($templates->get("topstats_PostersRow"));                  .
        }
        $this->template['Posters'] = eval($templates->get("topstats_Posters"));
    }
    /**
     * Widget with reputation list
     *   
     */ 
    public function widget_Reputation()
    {
        global $db, $lang, $mybb, $templates;
        
    	if (!$this->getConfig('Status_Reputation'))
        {
            return;
        }
	
        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth'];
        $limit = (int) $this->getConfig('Limit_Reputation');
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, postnum, uid, avatar 
                FROM ".TABLE_PREFIX."users 
                ORDER BY reputation DESC 
                LIMIT {$limit}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['reputation'] = $row['reputation'];
            $tpl['avatar'] = (!$this->getConfig('avatar')) ? '' : $row['avatar']; 
            $tpl['row'] = eval($templates->get("topstats_ReputationRow"));      
        }
        $this->template['Reputation'] = eval($templates->get("topstats_Reputation"));
    }
    
    /**
     * Widget with newest users
     *   
     */ 
    public function widget_NewestUsers()
    {
        global $db, $lang, $mybb, $templates;
        
    	if (!$this->getConfig('NewsetUsers'))
        {
            return;
        }
        
        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth'];
        $limit = (int) $this->getConfig('Limit_NewestUsers');
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, postnum, uid, avatar 
                FROM ".TABLE_PREFIX."users 
                ORDER BY NewestUsers DESC 
                LIMIT {$limit}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['date'] = my_date($mybb->settings['dateformat'] . " " . $mybb->settings['timeformat'], $row['dateline'], NULL, 1);
            $tpl['avatar'] = (!$this->getConfig('avatar')) ? '' : $row['avatar']; 
            $tpl['row'] = eval($templates->get("topstats_NewestUsersRow"));      
        }
        $this->template['NewestUsers'] = eval($templates->get("topstats_NewestUsers"));
    }

    /**
     * Widget with users online time
     *   
     */ 
    public function widget_Timeonline()
    {
    	if (!$this->getConfig('Status_Timeonline'))
        {
            return;
        }
    
        $tpl['avatar_width'] = (int) $this->getConfig('AvatarWidth'];
        $limit = (int) $this->getConfig('Limit_Timeonline');
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, postnum, uid, avatar 
                FROM ".TABLE_PREFIX."users 
                ORDER BY timeonline DESC 
                LIMIT {$limit}";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['time'] = ($timeonline['time'] > 0) ? $this->getFriendlyTime($timeonline['time']) : $lang->none_registered;;
            $tpl['avatar'] = (!$this->getConfig('avatar')) ? '' : $row['avatar']; 
            $tpl['row'] = eval($templates->get("topstats_TimeonlineRow"));      
        }
        $this->template['Timeonline'] = eval($templates->get("topstats_Timeonline"));
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
    
        return $mybb->settings["topStats{$name}"];
    }

}
