<?php



if(count($_)>0){

	echo('<ul>');
	foreach($_ as $post){

		echo('<li>');
			
		echo('<a href="'.$post['url'].'"><span class="contribook_githubmessage">'.$post['message'].'</span></a> ');
		echo('<span class="contribook_githubtime">'.date('F j, Y',$post['timestamp']).'</span>');

		echo('</li>');

	}
	echo('</ul>');
}



