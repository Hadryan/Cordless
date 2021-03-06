<?
namespace Cordless;

require __DIR__ . '/Init.Application.inc.php';

header("Content-type: text/html; charset=utf-8");

define('HEADER', DIR_ELEMENT . 'Header.inc.php');
define('FOOTER', DIR_ELEMENT . 'Footer.inc.php');


if( !isset($baseHref) ) $baseHref = './';

$imageURL = URL_PLAYER . 'img/AppIcon.png';

$pageTitle = 'Cordless';

$css = array();
$css[] = URL_PLAYER . 'css/Shitfest.css';
$css[] = URL_PLAYER . 'css/Base.css';

$js = array();


session_start();
require DIR_SITE_SYSTEM . 'init/User.inc.php';


if( !defined('NO_LOGIN') || constant('NO_LOGIN') !== true )
{
	if( $User->isLoggedIn() !== true )
	{
		require DIR_ELEMENT . 'Login.inc.php';
		die();
	}

	if( !$User->hasPolicy('AllowCordlessAccess') )
	{
		header('HTTP/1.1 403 Forbidden');

		echo Element\Page\Message::error(
			_("Access Denied"),
			_("Sorry, this account can not access Cordless")
		);

		die();
	}
}