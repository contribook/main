<?php


if(count($_)>0){

	echo('<ul>');
	foreach($_ as $user){

		echo('<li>');
			
		if($user['picture_50']<>''){
			echo('<span class="contribook_microbloguserpicture"><a href="'.CONTRIBOOK_USER_URL.$user['userid'].'"><img src="'.CONTRIBOOK_PHOTO_URL.$user['picture_50'].'" border="0" /></a></span>');
		}
		echo('<span class="contribook_profile_listname"><a href="'.CONTRIBOOK_USER_URL.$user['userid'].'">'.$user['name'].'</a></span> ');

		echo('<span class="contribook_profile_listrole">'.$user['role'].'</span><br />');
		echo('<span class="contribook_profile_listlocation">'.$user['city'].' '.$user['country'].'</span> ');
		echo('<br />');
		echo('</li>');

	}
	echo('</ul>');
}



