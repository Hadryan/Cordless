<?
namespace Cordless;

require DIR_SITE_INCLUDE . 'ServeFilePartial.inc.php';
require DIR_SITE_INCLUDE . 'TrackPrepare.inc.php';

function APIMethod($User, $params)
{
	$UserTrack = getUserTrack($params);

	if( !$UserTrack->isAccessible($User) )
		throw new APIException("UserTrack access denied");

	$forceDownload = isset($params['download']);

	$playFormat = $User->getSetting('Stream_Play_Format');
	$downloadFormat = $User->getSetting('Stream_Play_Format');


	if( isset($params['format']) )
		$format = $params['format'];

	elseif( $forceDownload && $downloadFormat )
		$format = $downloadFormat;

	elseif( $playFormat )
		$format = $playFormat;

	// Try to identify what format to send by the ACCEPT header
	elseif( isset($_SERVER['HTTP_ACCEPT']) && preg_match('%audio/(ogg|mp3)%', $_SERVER['HTTP_ACCEPT'], $match) )
		$format = $match[1];

	// If Opera, send ogg
	elseif( isset($_SERVER['HTTP_USER_AGENT']) && preg_match('%^Opera%', $_SERVER['HTTP_USER_AGENT'], $match) )
	{
		unset($_SERVER['HTTP_RANGE']);
		$format = 'ogg';
	}
	else
		$format = 'mp3';



	switch($format)
	{
		case 'ogg':
			$format = 'ogg';
			$contentType = 'audio/ogg';
			$ext = 'ogg';
		break;

		case 'mp3':
			$format = 'mp3';
			$contentType = 'audio/mp3';
			$ext = 'mp3';
		break;

		default:
			throw New \Exception(sprintf('Unknown format "%s"', $format));
	}


	$fileName = trackPrepare($UserTrack, $format);

	$fileTitle = sprintf('%s.%s', $UserTrack, $ext);

	header("X-Cordless-Artist: " . $UserTrack->artist);
	header("X-Cordless-Title: " . $UserTrack->title);

	serveFilePartial($fileName, $fileTitle, $contentType);
}