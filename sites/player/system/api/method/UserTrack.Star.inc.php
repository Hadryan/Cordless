<?
namespace Cordless;

function APIMethod($User, $params)
{
	$isModify = isset($params->isStarred);
	$lastFM_wasPropagated = null;

	$UserTrack = getUserTrack($params, $isModify ? $User : null, true, true);

	if( $isModify )
	{
		$lastFM_wasPropagated = false;

		if( $params->isStarred )
		{
			$UserTrack->star();

			if( $User->last_fm_love_starred_tracks && $LastFM = getLastFM() )
			{
				$lastFM_wasPropagated = true;
				$xml = $LastFM->trackLove($User->last_fm_key, $UserTrack->artist, $UserTrack->title);
			}
		}
		else
		{
			$UserTrack->unstar();

			if( $User->last_fm_unlove_unstarred_tracks && $LastFM = getLastFM() )
			{
				$lastFM_wasPropagated = true;
				$xml = $LastFM->trackUnlove($User->last_fm_key, $UserTrack->artist, $UserTrack->title);
			}
		}
	}

	return array(
		'userTrackID' => $UserTrack->userTrackID,
		'isStarred' => $UserTrack->isStarred,
		'lastFM_wasPropagated' => $lastFM_wasPropagated);
}