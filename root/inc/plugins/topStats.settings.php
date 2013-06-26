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

class topStatsInstaller
{
    public static function install()
    {
        global $db,$lang, $mybb;
        self::uninstall();
        
        $result = $db->simple_select('settinggroups', 'MAX(disporder) AS max_disporder');
        $max_disporder = $db->fetch_field($result, 'max_disporder');
        $disporder = 1;

        $settings_group = array(
            'gid' => 'NULL',
            'name' => 'topStats',
            'title' => $db->escape_string($lang->topStats),
            'description' => $db->escape_string($lang->topStats_desc),
            'disporder' => $max_disporder + 1,
            'isdefault' => '0'
        );
        $db->insert_query('settinggroups', $settings_group);
        $gid = (int) $db->insert_id();
		
		$setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Status_All',
            'title' => $db->escape_string($lang->topStats_onoff),
            'description' => $db->escape_string($lang->topStats_onoff_desc),
            'optionscode' => 'onoff',
            'value' => '1',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
		
		$setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Status_LastThreads',
            'title' => $db->escape_string($lang->topStats_Status_LastThreads),
            'description' =>  $db->escape_string($lang->topStats_Status_LastThreadsDesc),
            'optionscode' => 'onoff',
            'value' => '1',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
		
        $setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Limit_LastThreads',
            'title' => $db->escape_string($lang->topStats_Limit_LastThreads),
            'description' =>  $db->escape_string($lang->topStats_Limit_LastThreadsDesc),
            'optionscode' => 'text',
            'value' => '5',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
 
		$setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Status_MostViews',
            'title' => $db->escape_string($lang->topStats_Status_MostViews),
            'description' =>  $db->escape_string($lang->topStats_Status_MostViewsDesc),
            'optionscode' => 'onoff',
            'value' => '1',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
		
        $setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Limit_MostViews',
            'title' => $db->escape_string($lang->topStats_Limit_MostViews),
            'description' =>  $db->escape_string($lang->topStats_Limit_MostViewsDesc),
            'optionscode' => 'text',
            'value' => '5',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
        
		$setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Status_Posters',
            'title' => $db->escape_string($lang->topStats_Status_Posters),
            'description' =>  $db->escape_string($lang->topStats_Status_PostersDesc),
            'optionscode' => 'onoff',
            'value' => '1',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
		
        $setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Limit_Posters',
            'title' => $db->escape_string($lang->topStats_Limit_Posters),
            'description' =>  $db->escape_string($lang->topStats_Limit_PostersDesc),
            'optionscode' => 'text',
            'value' => '5',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
    
    	
		$setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Status_Reputation',
            'title' => $db->escape_string($lang->topStats_Status_Reputation),
            'description' =>  $db->escape_string($lang->topStats_Status_ReputationDesc),
            'optionscode' => 'onoff',
            'value' => '1',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
		
        $setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Limit_Reputation',
            'title' => $db->escape_string($lang->topStats_Limit_Reputation),
            'description' =>  $db->escape_string($lang->topStats_Limit_ReputationDesc),
            'optionscode' => 'text',
            'value' => '5',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
        
		$setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Status_Online',
            'title' => $db->escape_string($lang->topStats_Status_Online),
            'description' =>  $db->escape_string($lang->topStats_Status_OnlineDesc),
            'optionscode' => 'onoff',
            'value' => '1',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
		
        $setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Limit_Online',
            'title' => $db->escape_string($lang->topStats_Limit_Online),
            'description' =>  $db->escape_string($lang->topStats_Limit_OnlineDesc),
            'optionscode' => 'text',
            'value' => '5',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
        
		$setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Status_NewestUsers',
            'title' => $db->escape_string($lang->topStats_Status_NewestUsers),
            'description' =>  $db->escape_string($lang->topStats_Status_NewestUsersDesc),
            'optionscode' => 'onoff',
            'value' => '1',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
		
        $setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Limit_NewestUsers',
            'title' => $db->escape_string($lang->topStats_Limit_NewestUsers),
            'description' =>  $db->escape_string($lang->topStats_Limit_NewestUsersDesc),
            'optionscode' => 'text',
            'value' => '5',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
        
        $setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_Status_Avatar',
            'title' =>  $db->escape_string($lang->topStats_Status_Avatar),
            'description' =>   $db->escape_string($lang->topStats_Status_Avatar_desc),
            'optionscode' => 'onoff',
            'value' => '1',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
        
        $setting = array(
            'sid' => 'NULL',
            'name' => 'topStats_AvatarWidth',
            'title' =>  $db->escape_string($lang->topStats_AvatarWidth),
            'description' =>  $db->escape_string($lang->topStats_AvatarWidth_desc),
            'optionscode' => 'text',
            'value' => '32',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
    }
    
    public static function uninstall()
    {
        global $db;
        
        $result = $db->simple_select('settinggroups', 'gid', "name = 'topStats'");
        $gid = (int) $db->fetch_field($result, "gid");
        
        if ($gid > 0)
        {
            $db->delete_query('settings', "gid = '{$gid}'");
        }
        $db->delete_query('settinggroups', "gid = '{$gid}'");
    }
}
