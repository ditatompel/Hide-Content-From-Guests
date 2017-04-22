<?php

// Plugin : Hide content from guest 2.0
// Author : Harshit Shrivastava

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
$plugins->add_hook("postbit", "hidecontent_postbit");
$plugins->add_hook("printthread_post", "hidecontent_print");
$plugins->add_hook("archive_thread_post", "hidecontent_archive");

function hidecontent_info()
{
	return array(
		"name"			=> "Hide content from guest",
		"description"	=> "Hide your thread content from guests",
		"website"		=> "http://mybb.com",
		"author"		=> "Harshit Shrivastava",
		"authorsite"	=> "mailto:harshit_s21@rediffmail.com",
		"version"		=> "2.0",
		"guid" 			=> "dcda923d29ec5dfb852a993160ca8356",
		"compatibility" => "18*,16*"
	);
}

function hidecontent_validate($fid)
{
	global $mybb;
	if($mybb->settings['hidecontent_exclude'])
	{
		$fids = explode(",", $mybb->settings['hidecontent_exclude']);
		if(in_array($fid, $fids))
		{
			return False;
		}
	}
	return True;
}
$postCount=0;
function hidecontent_postbit(&$post)
{
	global $mybb,$db, $lang,$redirect_url,$username,$postCount;
	if ($mybb->settings['hidecontent_show'] == 1)
	{
		$userAgents = array("Googlebot", "Slurp", "MSNBot", "ia_archiver", "Yandex", "Rambler","bingbot","GurujiBot","Baiduspider","facebook");

		if($mybb->user['uid'] == 0 && hidecontent_validate($post['fid']) && !(preg_match('/' . strtolower(implode('|', $userAgents)) . '/i', strtolower($_SERVER['HTTP_USER_AGENT']))))
		{
				$temp = $mybb->settings['hidecontent_code'];
				if($mybb->settings['hidecontent_showlogin'] == 1)
				{
					$postCount++;
					$lang->load("member");
					
					$temp .= '<center><form action="member.php" method="post">
<table border="0" cellspacing="'.$theme['borderwidth'].'" cellpadding="'.$theme['tablespace'].'" class="tborder" style="width:60%;">
<tr>
<td class="trow1"><strong>'.$lang->username.'</strong></td>
<td class="trow1"><input type="text" class="textbox" name="username" size="25" style="width: 200px;" value="'.$username.'" /></td>
</tr>
<tr>
<td class="trow2"><strong>'.$lang->password.'</strong><br /><span class="smalltext">'.$lang->pw_note.'</span></td>
<td class="trow2"><input type="password" class="textbox" name="password" size="25" style="width: 200px;" value="'.$password.'" /> (<a href="member.php?action=lostpw">'.$lang->lostpw_note.'</a>)</td>
</tr>
<tr>
<td class="trow1" colspan="2" align="center"><label title="'.$lang->remember_me_desc.'"><input type="checkbox" class="checkbox" name="remember" checked="checked" value="yes" /> '.$lang->remember_me.'</label></td>
</tr>
</table>
<br />
<div align="center"><input type="submit" class="button" name="submit" value="'.$lang->login.'" /></div>
<input type="hidden" name="action" value="do_login" />
<input type="hidden" name="url" value="'.htmlspecialchars_uni($_SERVER['REQUEST_URI']).'" />
</form></center>';
					
			}
			if(($mybb->settings['hidecontent_hidemode'] == "post" && $postCount == 1) || ($mybb->settings['hidecontent_hidemode'] == "replies" && $postCount > 1) || ($mybb->settings['hidecontent_hidemode'] == "both"))
				$post['message'] = $temp;
		}
	}
}
function hidecontent_print()
{
	global $postrow, $mybb,$db, $lang,$redirect_url,$username,$postCount;
	
	if ($mybb->settings['hidecontent_show'] == 1)
	{
		$userAgents = array("Googlebot", "Slurp", "MSNBot", "ia_archiver", "Yandex", "Rambler","bingbot","GurujiBot","Baiduspider","facebook");

		if($mybb->user['uid'] == 0 && hidecontent_validate($post['fid']) && !(preg_match('/' . strtolower(implode('|', $userAgents)) . '/i', strtolower($_SERVER['HTTP_USER_AGENT']))))
		{
			$temp = $mybb->settings['hidecontent_code'];
			if($mybb->settings['hidecontent_showlogin'] == 1)
			{
				$postCount++;
				$lang->load("member");
				
				
			}
			if(($mybb->settings['hidecontent_hidemode'] == "post" && $postCount == 1) || ($mybb->settings['hidecontent_hidemode'] == "replies" && $postCount > 1) || ($mybb->settings['hidecontent_hidemode'] == "both"))
				$postrow['message'] = $temp;
		}
	}
}
function hidecontent_archive()
{
	global $post, $mybb,$db, $lang,$redirect_url,$username,$postCount;
	
	if ($mybb->settings['hidecontent_show'] == 1)
	{
		$userAgents = array("Googlebot", "Slurp", "MSNBot", "ia_archiver", "Yandex", "Rambler","bingbot","GurujiBot","Baiduspider","facebook");

		if($mybb->user['uid'] == 0 && hidecontent_validate($post['fid']) && !(preg_match('/' . strtolower(implode('|', $userAgents)) . '/i', strtolower($_SERVER['HTTP_USER_AGENT']))))
		{
			$temp = $mybb->settings['hidecontent_code'];
			if($mybb->settings['hidecontent_showlogin'] == 1)
			{
				$postCount++;
				$lang->load("member");
				
				
			}
			if(($mybb->settings['hidecontent_hidemode'] == "post" && $postCount == 1) || ($mybb->settings['hidecontent_hidemode'] == "replies" && $postCount > 1) || ($mybb->settings['hidecontent_hidemode'] == "both"))
				$post['message'] = $temp;
		}
	}
}

function hidecontent_activate()
{
global $db;
$hidecontent_group = array(
        'gid'    => 'NULL',
        'name'  => 'hidecontent',
        'title'      => 'Hide content from guests',
        'description'    => 'Hide your thread content from guests',
        'disporder'    => "1",
        'isdefault'  => "0",
    ); 
$db->insert_query('settinggroups', $hidecontent_group);
$gid = $db->insert_id(); 
// Enable / Disable
$hidecontent_setting1 = array(
        'sid'            => 'NULL',
        'name'        => 'hidecontent_show',
        'title'            => 'Show on board',
        'description'    => 'If you set this option to yes, this plugin will hide content from the posts.',
        'optionscode'    => 'yesno',
        'value'        => '1',
        'disporder'        => 1,
        'gid'            => intval($gid),
    );
$hidecontent_setting2 = array(
        'sid'            => 'NULL',
        'name'        => 'hidecontent_code',
        'title'            => 'Enter Code',
        'description'    => 'Enter HTML Code',
        'optionscode'    => 'textarea',
        'value'        => '<center><a href="member.php?action=register">Register</a> or <a href="member.php?action=login">login</a> to view the content</b></center>',
        'disporder'        => 2,
        'gid'            => intval($gid),
    );
$hidecontent_setting3 = array(
        'sid'            => 'NULL',
        'name'        => 'hidecontent_showlogin',
        'title'            => 'Show Login Box',
        'description'    => 'If you set this option to yes, this plugin will show a login box as well with your message.',
        'optionscode'    => 'yesno',
        'value'        => '1',
        'disporder'        => 3,
        'gid'            => intval($gid),
    );
$hidecontent_setting4 = array(
        'sid'            => 'NULL',
        'name'        => 'hidecontent_exclude',
        'title'            => 'Forum ID without this mod',
        'description'    => 'If you do not want to use this mod on a forum or forums put ID separated by comma. Ex. 2,5,7',
        'optionscode'    => 'text',
        'value'        => '0',
        'disporder'        => 4,
        'gid'            => intval($gid),
    );
$hidecontent_setting5 = array(
        'sid'            => 'NULL',
        'name'        => 'hidecontent_hidemode',
        'title'            => 'Post Hide Mode',
        'description'    => 'Select the mode to hide the post.',
        'optionscode'    => 'select
both=Hide both post & replies
post=Hide only post
replies=Hide only replies
',
        'value'        => '1',
        'disporder'        => 5,
        'gid'            => intval($gid),
    );
$db->insert_query('settings', $hidecontent_setting1);
$db->insert_query('settings', $hidecontent_setting2);
$db->insert_query('settings', $hidecontent_setting3);
$db->insert_query('settings', $hidecontent_setting4);
$db->insert_query('settings', $hidecontent_setting5);
  rebuild_settings();
}
function hidecontent_deactivate()
{
  global $db;
  $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name = 'hidecontent_show'");
  $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name = 'hidecontent_code'");
  $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name = 'hidecontent_showlogin'");
  $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name = 'hidecontent_exclude'");
  $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name = 'hidecontent_hidemode'");
  $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='hidecontent'");
  rebuild_settings();
}
?>
