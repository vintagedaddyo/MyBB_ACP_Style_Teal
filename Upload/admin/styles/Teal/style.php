<?php
/**
 *
 * MyBB: Teal - Admin CP
 *
 * Filename: style.php
 *
 * Style Author: Wage & Vintagedaddyo
 *
 * W Site: http://community.mybb.com/user-78269.html
 * V Site: http://community.mybb.com/user-6029.html
 *
 * MyBB Version: 1.8.x
 *
 * Style Version: 1.1
 * 
 */

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

class Page extends DefaultPage
{
	function _generate_breadcrumb()
	{
		if(!is_array($this->_breadcrumb_trail))
		{
			return false;
		}
		$trail = "";
		foreach($this->_breadcrumb_trail as $key => $crumb)
		{
			if(isset($this->_breadcrumb_trail[$key+1]))
			{
				$trail .= "<a href=\"".$crumb['url']."\">".$crumb['name']."</a>";
				if(isset($this->_breadcrumb_trail[$key+2]))
				{
					$trail .= " &raquo; ";
				}
			}
			else
			{
				$trail .= " &raquo; <span class=\"active\">".$crumb['name']."</span>";
			}
		}
		return $trail;
	}

	/**
	 * Output the page header.
	 *
	 * 
	 */

	function output_header($title="")
	{
		global $mybb, $admin_session, $lang, $plugins;

		$args = array(
			'this' => &$this,
			'title' => &$title,
		);

		$plugins->run_hooks("admin_page_output_header", $args);

		if(!$title)
		{
			$title = $lang->mybb_admin_panel;
		}

		$rtl = "";
		if($lang->settings['rtl'] == 1)
		{
			$rtl = " dir=\"rtl\"";
		}

		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\"{$rtl}>\n";
		echo "<head profile=\"http://gmpg.org/xfn/1\">\n";
		echo "	<title>".$title."</title>\n";
        echo "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
		echo "  <link rel=\"Shortcut icon\" href=\"styles/".$this->style."/images/favicon.ico\" />\n";        		
		echo "	<meta name=\"author\" content=\"MyBB Group\" />\n";
		echo "	<meta name=\"copyright\" content=\"Copyright ".COPY_YEAR." MyBB Group.\" />\n";
		echo "	<link rel=\"stylesheet\" href=\"styles/".$this->style."/main.css?ver=1813\" type=\"text/css\" />\n";
		echo "	<link rel=\"stylesheet\" href=\"styles/".$this->style."/modal.css?ver=1813\" type=\"text/css\" />\n";

		// Load stylesheet for this module if it has one
		if(file_exists(MYBB_ADMIN_DIR."styles/{$this->style}/{$this->active_module}.css"))
		{
			echo "	<link rel=\"stylesheet\" href=\"styles/{$this->style}/{$this->active_module}.css\" type=\"text/css\" />\n";
		}

		echo "	<script type=\"text/javascript\" src=\"../jscripts/jquery.js?ver=1823\"></script>\n";
		echo "	<script type=\"text/javascript\" src=\"../jscripts/jquery.plugins.min.js?ver=1821\"></script>\n";
		echo "	<script type=\"text/javascript\" src=\"../jscripts/general.js?ver=1821\"></script>\n";
		echo "	<script type=\"text/javascript\" src=\"./jscripts/admincp.js?ver=1821\"></script>\n";
		echo "	<script type=\"text/javascript\" src=\"./jscripts/tabs.js\"></script>\n";

		echo "	<link rel=\"stylesheet\" href=\"jscripts/jqueryui/css/redmond/jquery-ui.min.css\" />\n";
		echo "	<link rel=\"stylesheet\" href=\"jscripts/jqueryui/css/redmond/jquery-ui.structure.min.css\" />\n";
		echo "	<link rel=\"stylesheet\" href=\"jscripts/jqueryui/css/redmond/jquery-ui.theme.min.css\" />\n";
		echo "	<script src=\"jscripts/jqueryui/js/jquery-ui.min.js?ver=1813\"></script>\n";

		// Stop JS elements showing while page is loading (JS supported browsers only)
		echo "  <style type=\"text/css\">.popup_button { display: none; } </style>\n";
		echo "  <script type=\"text/javascript\">\n".
				"//<![CDATA[\n".
				"	document.write('<style type=\"text/css\">.popup_button { display: inline; } .popup_menu { display: none; }<\/style>');\n".
                "//]]>\n".
                "</script>\n";

        // ADD Progressbar
        echo " <link rel=\"stylesheet\" href=\"styles/".$this->style."/inc/progbar/nprogress.css\"/>\n";
        echo" <script type=\"text/javascript\" src=\"styles/".$this->style."/inc/progbar/nprogress.js\"></script>\n";

        echo "<script>
            NProgress.configure({ showSpinner: false });
            $(document).ready(function() {
            NProgress.start();
            NProgress.done();
            }); 
            </script>\n";                 

		echo "	<script type=\"text/javascript\">
//<![CDATA[
var loading_text = '{$lang->loading_text}';
var cookieDomain = '{$mybb->settings['cookiedomain']}';
var cookiePath = '{$mybb->settings['cookiepath']}';
var cookiePrefix = '{$mybb->settings['cookieprefix']}';
var cookieSecureFlag = '{$mybb->settings['cookiesecureflag']}';
var imagepath = '../images';

lang.unknown_error = \"{$lang->unknown_error}\";
lang.saved = \"{$lang->saved}\";
//]]>
</script>\n";
		echo $this->extra_header;
		echo "</head>\n";
		echo "<body>\n";
		echo "<div id=\"container\">\n";
		echo "	<div id=\"logo\"><h1><span class=\"invisible\">{$lang->mybb_admin_cp}</span></h1></div>\n";
		$username = htmlspecialchars_uni($mybb->user['username']);
		echo "	<div id=\"welcome\"><span class=\"logged_in_as\">{$lang->logged_in_as} <a href=\"index.php?module=user-users&amp;action=edit&amp;uid={$mybb->user['uid']}\" class=\"username\">{$username}</a></span> | <a href=\"{$mybb->settings['bburl']}\" target=\"_blank\" class=\"forum\">{$lang->view_board}</a> | <a href=\"index.php?action=logout&amp;my_post_key={$mybb->post_code}\" class=\"logout\">{$lang->logout}</a></div>\n";
		echo $this->_build_menu();
		echo "	<div id=\"page\">\n";
		echo "		<div id=\"left_menu\">\n";
		echo $this->submenu;
		echo $this->sidebar;
		echo "		</div>\n";
		echo "		<div id=\"content\">\n";
		echo "			<div class=\"breadcrumb\">\n";
		echo $this->_generate_breadcrumb();
		echo "			</div>\n";
		echo "           <div id=\"inner\">\n";
		if(isset($admin_session['data']['flash_message']) && $admin_session['data']['flash_message'])
		{
			$message = $admin_session['data']['flash_message']['message'];
			$type = $admin_session['data']['flash_message']['type'];
			echo "<div id=\"flash_message\" class=\"{$type}\">\n";
			echo "{$message}\n";
			echo "</div>\n";
			update_admin_session('flash_message', '');
		}

		if(!empty($this->extra_messages) && is_array($this->extra_messages))
		{
			foreach($this->extra_messages as $message)
			{
				switch($message['type'])
				{
					case 'success':
					case 'error':
						echo "<div id=\"flash_message\" class=\"{$message['type']}\">\n";
						echo "{$message['message']}\n";
						echo "</div>\n";
						break;
					default:
						$this->output_error($message['message']);
						break;
				}
			}
		}

		if($this->show_post_verify_error == true)
		{
			$this->output_error($lang->invalid_post_verify_key);
		}
	}

    function show_login($message="", $class="success")
    {
        global $plugins, $lang, $cp_style, $mybb;

        $args = array(
            'this' => &$this,
            'message' => &$message,
            'class' => &$class
        );

        $plugins->run_hooks('admin_page_show_login_start', $args);

        $copy_year = COPY_YEAR;

        $login_container_width = "";
        $login_label_width = "";

        // If the language string for "Username" is too cramped then use this to define how much larger you want the gap to be (in px)
        if(isset($lang->login_field_width))
        {
            $login_label_width = " style=\"width: ".((int)$lang->login_field_width+100)."px;\"";
            $login_container_width = " style=\"width: ".(410+((int)$lang->login_field_width))."px;\"";
        }

        $login_page .= <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head profile="http://gmpg.org/xfn/1">
<title>{$lang->mybb_admin_login}</title>
<meta name="author" content="MyBB Group" />
<meta name="copyright" content="Copyright {$copy_year} MyBB Group." />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="./styles/{$cp_style}/login.css" type="text/css" />
<script type="text/javascript" src="../jscripts/jquery.js?ver=1823"></script>
<script type="text/javascript" src="../jscripts/general.js?ver=1821"></script>
<script type="text/javascript" src="./jscripts/admincp.js?ver=1821"></script>
<script type="text/javascript">
//<![CDATA[
    loading_text = '{$lang->loading_text}';
//]]>
</script>
</head>
<body>
<div id="container"{$login_container_width}>
    <div id="header">
        <div id="logo">
            <h1><a href="../" title="{$lang->return_to_forum}"><span class="invisible">{$lang->mybb_acp}</span></a></h1>

        </div>
    </div>
    <div id="content">
        <h2>{$lang->please_login}</h2>
EOF;
        if($message)
        {
            $login_page .= "<p id=\"message\" class=\"{$class}\"><span class=\"text\">{$message}</span></p>";
        }
        // Make query string nice and pretty so that user can go to his/her preferred destination
        $query_string = '';
        if($_SERVER['QUERY_STRING'])
        {
            $query_string = '?'.preg_replace('#adminsid=(.{32})#i', '', $_SERVER['QUERY_STRING']);
            $query_string = preg_replace('#my_post_key=(.{32})#i', '', $query_string);
            $query_string = str_replace('action=logout', '', $query_string);
            $query_string = preg_replace('#&+#', '&', $query_string);
            $query_string = str_replace('?&', '?', $query_string);
            $query_string = htmlspecialchars_uni($query_string);
        }
        switch($mybb->settings['username_method'])
        {
            case 0:
                $lang_username = $lang->username;
                break;
            case 1:
                $lang_username = $lang->username1;
                break;
            case 2:
                $lang_username = $lang->username2;
                break;
            default:
                $lang_username = $lang->username;
                break;
        }

        // Secret PIN
        global $config;
        if(isset($config['secret_pin']) && $config['secret_pin'] != '')
        {
            $secret_pin = "<div class=\"label\"{$login_label_width}><label for=\"pin\">{$lang->secret_pin}</label></div>
            <div class=\"field\"><input type=\"password\" name=\"pin\" id=\"pin\" class=\"text_input\" /></div>";
        }
        else
        {
            $secret_pin = '';
        }

        $login_lang_string = $lang->enter_username_and_password;

        switch($mybb->settings['username_method'])
        {
            case 0: // Username only
                $login_lang_string = $lang->sprintf($login_lang_string, $lang->login_username);
                break;
            case 1: // Email only
                $login_lang_string = $lang->sprintf($login_lang_string, $lang->login_email);
                break;
            case 2: // Username and email
            default:
                $login_lang_string = $lang->sprintf($login_lang_string, $lang->login_username_and_password);
                break;
        }

        $this_file = htmlspecialchars_uni($_SERVER['SCRIPT_NAME']);

        $login_page .= <<<EOF
        <p>{$login_lang_string}</p>
        <form method="post" action="{$this_file}{$query_string}">
        <div class="form_container">

            <div class="label"{$login_label_width}><label for="username">{$lang_username}</label></div>

            <div class="field"><input type="text" name="username" id="username" class="text_input initial_focus" /></div>

            <div class="label"{$login_label_width}><label for="password">{$lang->password}</label></div>
            <div class="field"><input type="password" name="password" id="password" class="text_input" /></div>
            {$secret_pin}
        </div>
        <p class="submit">
            <span class="forgot_password">
                <a href="../member.php?action=lostpw">{$lang->lost_password}</a>
            </span>

            <input type="submit" value="{$lang->login}" />
            <input type="hidden" name="do" value="login" />
        </p>
        </form>
    </div>
</div>
</body>
</html>
EOF;

        $args = array(
            'this' => &$this,
            'login_page' => &$login_page
        );

        $plugins->run_hooks('admin_page_show_login_end', $args);

        echo $login_page;
        exit;
    }

    function show_2fa()
    {
        global $lang, $cp_style, $mybb;

        $copy_year = COPY_YEAR;

        $mybb2fa_page = <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head profile="http://gmpg.org/xfn/1">
<title>{$lang->my2fa}</title>
<meta name="author" content="MyBB Group" />
<meta name="copyright" content="Copyright {$copy_year} MyBB Group." />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="./styles/{$cp_style}/login.css" type="text/css" />
<script type="text/javascript" src="../jscripts/jquery.js?ver=1823"></script>
<script type="text/javascript" src="../jscripts/general.js?ver=1821"></script>
<script type="text/javascript" src="./jscripts/admincp.js?ver=1821"></script>
<script type="text/javascript">
//<![CDATA[
    loading_text = '{$lang->loading_text}';
//]]>
</script>
</head>
<body>
<div id="container">
    <div id="header">
        <div id="logo">
            <h1><a href="../" title="{$lang->return_to_forum}"><span class="invisible">{$lang->mybb_acp}</span></a></h1>
        </div>
    </div>
    <div id="content">
        <h2>{$lang->my2fa}</h2>
EOF;
        // Make query string nice and pretty so that user can go to his/her preferred destination
        $query_string = '';
        if($_SERVER['QUERY_STRING'])
        {
            $query_string = '?'.preg_replace('#adminsid=(.{32})#i', '', $_SERVER['QUERY_STRING']);
            $query_string = preg_replace('#my_post_key=(.{32})#i', '', $query_string);
            $query_string = str_replace('action=logout', '', $query_string);
            $query_string = preg_replace('#&+#', '&', $query_string);
            $query_string = str_replace('?&', '?', $query_string);
            $query_string = htmlspecialchars_uni($query_string);
        }
        $mybb2fa_page .= <<<EOF
        <p>{$lang->my2fa_code}</p>
        <form method="post" action="index.php{$query_string}">
        <div class="form_container">
            <div class="label"><label for="code">{$lang->my2fa_label}</label></div>
            <div class="field"><input type="text" name="code" id="code" class="text_input initial_focus" /></div>
        </div>
        <p class="submit">
            <input type="submit" value="{$lang->login}" />
            <input type="hidden" name="do" value="do_2fa" />
        </p>
        </form>
    </div>
</div>
</body>
</html>
EOF;
        echo $mybb2fa_page;
        exit;
    }

    /**
     * Generate the lockout page
     *
     */
    function show_lockedout()
    {
        global $lang, $mybb, $cp_style;

        $copy_year = COPY_YEAR;
        $allowed_attempts = (int)$mybb->settings['maxloginattempts'];
        $lockedout_message = $lang->sprintf($lang->error_mybb_admin_lockedout_message, $allowed_attempts);

        print <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head profile="http://gmpg.org/xfn/1">
<title>{$lang->mybb_admin_cp} - {$lang->error_mybb_admin_lockedout}</title>
<meta name="author" content="MyBB Group" />
<meta name="copyright" content="Copyright {$copy_year} MyBB Group." />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="./styles/{$cp_style}/login.css" type="text/css" />
</head>
<body>
<div id="container">
    <div id="header">
        <div id="logo">
            <h1><a href="../" title="{$lang->return_to_forum}"><span class="invisible">{$lang->mybb_acp}</span></a></h1>

        </div>
    </div>
    <div id="content">
        <h2>{$lang->error_mybb_admin_lockedout}</h2>
        <div class="alert">{$lockedout_message}</div>
    </div>
</div>
</body>
</html>
EOF;
    exit;
    }

    /**
     * Generate the lockout unlock page
     *
     * @param string $message The any message to output on the page if there is one.
     * @param string $class The class name of the message (defaults to success)
     */
    function show_lockout_unlock($message="", $class="success")
    {
        global $lang, $mybb, $cp_style;

        $copy_year = COPY_YEAR;

        $login_label_width = "";

        // If the language string for "Username" is too cramped then use this to define how much larger you want the gap to be (in px)
        if(isset($lang->login_field_width))
        {
            $login_label_width = " style=\"width: ".((int)$lang->login_field_width+100)."px;\"";
        }

        switch($mybb->settings['username_method'])
        {
            case 0:
                $lang_username = $lang->username;
                break;
            case 1:
                $lang_username = $lang->username1;
                break;
            case 2:
                $lang_username = $lang->username2;
                break;
            default:
                $lang_username = $lang->username;
                break;
        }

        if($message)
        {
            $message = "<p id=\"message\" class=\"{$class}\"><span class=\"text\">{$message}</span></p>";
        }

        print <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head profile="http://gmpg.org/xfn/1">
<title>{$lang->mybb_admin_cp} - {$lang->lockout_unlock}</title>
<meta name="author" content="MyBB Group" />
<meta name="copyright" content="Copyright {$copy_year} MyBB Group." />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="./styles/{$cp_style}/login.css" type="text/css" />
</head>
<body>
<div id="container">
    <div id="header">
        <div id="logo">
            <h1><a href="../" title="{$lang->return_to_forum}"><span class="invisible">{$lang->mybb_acp}</span></a></h1>

        </div>
    </div>
    <div id="content">
        <h2>{$lang->lockout_unlock}</h2>
        {$message}
        <p>{$lang->enter_username_and_token}</p>
        <form method="post" action="index.php">
        <div class="form_container">

            <div class="label"{$login_label_width}><label for="username">{$lang_username}</label></div>

            <div class="field"><input type="text" name="username" id="username" class="text_input initial_focus" /></div>

            <div class="label"{$login_label_width}><label for="token">{$lang->unlock_token}</label></div>
            <div class="field"><input type="text" name="token" id="token" class="text_input" /></div>
        </div>
        <p class="submit">
            <span class="forgot_password">
                <a href="../member.php?action=lostpw">{$lang->lost_password}</a>
            </span>

            <input type="submit" value="{$lang->unlock_account}" />
            <input type="hidden" name="action" value="unlock" />
        </p>
        </form>
    </div>
</div>
</body>
</html>
EOF;
    exit;
    }		

	/**
	 * Output the page footer.
	 */
	
	function output_footer($quit=true)
	{
		global $mybb, $maintimer, $db, $lang, $plugins;

		$args = array(
			'this' => &$this,
			'quit' => &$quit,
		);

		$plugins->run_hooks("admin_page_output_footer", $args);

		$memory_usage = get_friendly_size(get_memory_usage());

		$totaltime = format_time_duration($maintimer->stop());
		$querycount = $db->query_count;

		if(my_strpos(getenv("REQUEST_URI"), "?"))
		{
			$debuglink = htmlspecialchars_uni(getenv("REQUEST_URI")) . "&amp;debug=1#footer";
		}
		else
		{
			$debuglink = htmlspecialchars_uni(getenv("REQUEST_URI")) . "?debug=1#footer";
		}

		echo "			</div>\n";
		echo "		</div>\n";
		echo "	<br style=\"clear: both;\" />";
		echo "	<br style=\"clear: both;\" />";
		echo "	</div>\n";
		//echo "<div id=\"footer\"><p class=\"generation\">".$lang->sprintf($lang->generated_in, $totaltime, $debuglink, $querycount, $memory_usage)."</p><p class=\"powered\">Powered By <a href=\"http://www.mybb.com/\" target=\"_blank\">MyBB</a>, &copy; 2002-".COPY_YEAR." <a href=\"http://www.mybb.com/\" target=\"_blank\">MyBB Group</a>.</p></div>\n";
		echo "<div id=\"footer\"><p class=\"generation\">".$lang->sprintf($lang->generated_in, $totaltime, $debuglink, $querycount, $memory_usage)."</p><p class=\"powered\">Powered By <a href=\"http://www.mybb.com/\" target=\"_blank\">MyBB</a>, &copy; 2002-".COPY_YEAR." <a href=\"http://www.mybb.com/\" target=\"_blank\">MyBB Group</a> All Rights Reserved.&nbsp;&nbsp;Theme \"Teal ACP\" created by <a href=\"https://github.com/vintagedaddyo/MyBB_ACP_Style_Teal\" target=\"_blank\"><b>Vintagedaddyo</b></a>&nbsp;&amp;&nbsp;<a href=\"http://community.mybb.com/user-78269.html\" target=\"_blank\"><b>Wage</b></a>.</p></div>\n";
		if($mybb->debug_mode)
		{
			echo $db->explain;
		}
		echo "</div>\n";
		echo "</body>\n";
		echo "</html>\n";

		if($quit != false)
		{
			exit;
		}
	}
}

		
		
	

class SidebarItem extends DefaultSidebarItem
{
}

class PopupMenu extends DefaultPopupMenu
{
}

class Table extends DefaultTable
{
}

class Form extends DefaultForm
{
}

class FormContainer extends DefaultFormContainer
{
}
