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

class topStatsActivator
{
    private static $tpl = array();
    private static function getTpl()
    {
		global $db;
   
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_LastThreads',
            "template" => $db->escape_string('
            <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
            <tr><td class="thead" colspan="1"><strong>{$lang->topStats_LastThreads}</strong></td></tr>
            {$tpl[\'row\']}
            </table><br />
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_LastThreadsRow',
            "template" => $db->escape_string('
            <tr><td class="trow1">
            {$tpl[\'avatar\']}
            <a href="{$tpl[\'subjectlink\']}">{$tpl[\'subject\']}</a><br />
            {$tpl[\'profilelink\']}<span style="float: right;widthmargin-right: 5px;">{$tpl[\'date\']}</span>
            </td></tr>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_LastThreadsAvatar',
            "template" => $db->escape_string('
            <img src="{$useravatar[\'image\']}" alt="" style="float: left;margin-right: 5px;" {$useravatar[\'width_height\']}/>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
        
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_LastPosts',
            "template" => $db->escape_string('
            <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
            <tr><td class="thead" colspan="1"><strong>{$lang->topStats_LastPosts}</strong></td></tr>
            {$tpl[\'row\']}
            </table><br />
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_LastPostsRow',
            "template" => $db->escape_string('
            <tr><td class="trow1">
            {$tpl[\'avatar\']}
            <a href="{$tpl[\'subjectlink\']}">{$tpl[\'subject\']}</a><br />
            {$tpl[\'profilelink\']}<span style="float: right;widthmargin-right: 5px;">{$tpl[\'date\']}</span>
            </td></tr>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_LastPostsAvatar',
            "template" => $db->escape_string('
            <img src="{$useravatar[\'image\']}" alt="" style="float: left;margin-right: 5px;" {$useravatar[\'width_height\']}/>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		
		        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_LastActiveThreads',
            "template" => $db->escape_string('
            <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
            <tr><td class="thead" colspan="1"><strong>{$lang->topStats_LastActiveThreads}</strong></td></tr>
            {$tpl[\'row\']}
            </table><br />
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_LastActiveThreadsRow',
            "template" => $db->escape_string('
            <tr><td class="trow1">
            {$tpl[\'avatar\']}
            <a href="{$tpl[\'subjectlink\']}">{$tpl[\'subject\']}</a><br />
            {$tpl[\'profilelink\']}<span style="float: right;widthmargin-right: 5px;">{$tpl[\'date\']}</span>
            </td></tr>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_LastActiveThreadsAvatar',
            "template" => $db->escape_string('
            <img src="{$useravatar[\'image\']}" alt="" style="float: left;margin-right: 5px;" {$useravatar[\'width_height\']}/>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_MostViews',
            "template" => $db->escape_string('
            <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
            <tr><td class="thead" colspan="1"><strong>{$lang->topStats_MostViews}</strong></td>
            </tr>{$tpl[\'row\']}</table><br />
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_MostViewsRow',
            "template" => $db->escape_string('
            <tr><td class="trow1">
            {$tpl[\'avatar\']}
            <a href="{$tpl[\'subjectlink\']}">{$tpl[\'subject\']}</a>
			<span style="float: right;widthmargin-right: 5px;">{$tpl[\'views\']}</span><br />
            {$tpl[\'profilelink\']}<span style="float: right;widthmargin-right: 5px;">{$tpl[\'date\']}</span>
            </td></tr>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_MostViewsAvatar',
            "template" => $db->escape_string('
            <img src="{$useravatar[\'image\']}" alt="" style="float: left;margin-right: 5px;" {$useravatar[\'width_height\']}/>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_Posters',
            "template" => $db->escape_string('
            <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
            <tr><td class="thead" colspan="1"><strong>{$lang->topStats_Top} {$tpl[\'limit\']} {$lang->topStats_Posters}</strong></td></tr>
            {$tpl[\'row\']}</table><br />
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_PostersRow',
            "template" => $db->escape_string('
            <tr><td class="trow1">
            {$tpl[\'avatar\']}
            <span style="margin-top: 7px;float: left;">{$tpl[\'profilelink\']}</span>
            <span style="float: right;margin-right: 5px;margin-top: 7px;">{$tpl[\'postnum\']}</span>
            </td></tr>  
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_PostersAvatar',
            "template" => $db->escape_string('
            <img src="{$useravatar[\'image\']}" alt="" style="float: left;margin-right: 5px;" {$useravatar[\'width_height\']}/>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_Reputation',
            "template" => $db->escape_string('
            <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
            <tr><td class="thead" colspan="1"><strong>{$lang->topStats_Top} {$tpl[\'limit\']} {$lang->topStats_Reputation}</strong></td></tr>
            {$tpl[\'row\']}</table><br />     
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_ReputationRow',
            "template" => $db->escape_string('
            <tr><td class="trow1">
            {$tpl[\'avatar\']}
            <span style="margin-top: 7px;float: left;">{$tpl[\'profilelink\']}</span>
            <span style="float: right;margin-right: 5px;margin-top: 7px;">{$tpl[\'reputation\']}</span>
            </td></tr>  
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_ReputationAvatar',
            "template" => $db->escape_string('
            <img src="{$useravatar[\'image\']}" alt="" style="float: left;margin-right: 5px;" {$useravatar[\'width_height\']}/>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_Referrals',
            "template" => $db->escape_string('
            <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
            <tr><td class="thead" colspan="1"><strong>{$lang->topStats_Top} {$tpl[\'limit\']} {$lang->topStats_Referrals}</strong></td></tr>
            {$tpl[\'row\']}</table><br />     
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_ReferralsRow',
            "template" => $db->escape_string('
            <tr><td class="trow1">
            {$tpl[\'avatar\']}
            <span style="margin-top: 7px;float: left;">{$tpl[\'profilelink\']}</span>
            <span style="float: right;margin-right: 5px;margin-top: 7px;">{$tpl[\'referrals\']}</span>
            </td></tr>  
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_ReferralsAvatar',
            "template" => $db->escape_string('
            <img src="{$useravatar[\'image\']}" alt="" style="float: left;margin-right: 5px;" {$useravatar[\'width_height\']}/>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_Timeonline',
            "template" => $db->escape_string('
            <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
            <tr><td class="thead" colspan="1"><strong>{$lang->topStats_Top} {$tpl[\'limit\']} {$lang->topStats_Online}</strong></td></tr>
            {$tpl[\'row\']}</table><br />      		
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_TimeonlineRow',
            "template" => $db->escape_string('
            <td class="trow1">
            {$tpl[\'avatar\']}
            {$tpl[\'profilelink\']}<br />{$lang->topStats_OnlineTime}: {$tpl[\'time\']}  
            </td></tr>       
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_TimeonlineAvatar',
            "template" => $db->escape_string('
            <img src="{$useravatar[\'image\']}" alt="" style="float: left;margin-right: 5px;" {$useravatar[\'width_height\']}/>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_NewestUsers',
            "template" => $db->escape_string('
            <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
            <tr><td class="thead" colspan="1"><strong>{$tpl[\'limit\']} {$lang->topStats_NewestUsers}</strong></td></tr>
            {$tpl[\'row\']}</table><br /> 
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
        self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_NewestUsersRow',
            "template" => $db->escape_string('
            <tr><td class="trow1">
            {$tpl[\'avatar\']}
			{$tpl[\'profilelink\']}<br />{$lang->topStats_NewestUsersJoin}: {$tpl[\'date\']}
            </td></tr>  
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
		
		self::$tpl[] = array(
            "tid" => 0,
            "title" => 'topStats_NewestUsersAvatar',
            "template" => $db->escape_string('
            <img src="{$useravatar[\'image\']}" alt="" style="float: left;margin-right: 5px;" {$useravatar[\'width_height\']}/>
            '),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );

    }
    
    public static function activate()
    {
        global $db;
        self::deactivate();
        
        for ($i = 0; $i < sizeof(self::$tpl); $i++)
        {
            $db->insert_query('templates', self::$tpl[$i]);
        }
    }
	
    public static function deactivate()
    {
        global $db;
        self::getTpl();

        for ($i = 0; $i < sizeof(self::$tpl); $i++)
        {
            $db->delete_query('templates', "title = '" . self::$tpl[$i]['title'] . "'");
        }
    }
}
