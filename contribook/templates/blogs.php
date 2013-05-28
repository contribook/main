<?php

foreach($_ as $post){

	echo('<div class="box">');
	if($post['picture']<>''){
		echo('<span class="contribook_bloguserpicture"><a href="'.CONTRIBOOK_USER_URL.$post['user'].'"><img src="'.CONTRIBOOK_PHOTO_URL.$post['picture'].'" align="right" border="0" /></a></span>');
	}
	if(CONTRIBOOK_USER_URL=='') {
		echo('<span class="contribook_bloguser">'.$post['name'].'</span><br />');
	}else{
		echo('<span class="contribook_bloguser"><a href="'.CONTRIBOOK_USER_URL.$post['user'].'">'.$post['name'].'</a></span><br />');
	}
	echo('<a href="'.$post['url'].'"><span class="contribook_blogheadline">'.$post['message'].'</span></a><br />');
	echo('<span class="contribook_blogtime">'.date('F j, Y',$post['timestamp']).'</span><br /><br />');

	echo($post['content']);
	echo('<b><a href="'.$post['url'].'"><p class="contribook_blognavibutton">read more</p></a></b><br /><br />');
	
	echo('</div>');
	
}


