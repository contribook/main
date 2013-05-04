<?php



if(count($_)>0){

	echo('<ul>');
	foreach($_ as $post){

		echo('<li>');
			
		echo('<a href="'.$post['url'].'"><span class="contribook_microblogmessage">'.$post['message'].'</span></a> ');
		echo('<span class="contribook_microblogtime">'.date('F j, Y',$post['timestamp']).'</span>');

		echo('</li>');

	}
	echo('</ul>');
}



