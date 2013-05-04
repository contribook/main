<?php


if(count($_)>0){
	
	echo('<ul>');
	foreach($_ as $post){
		echo('<li><a href="'.$post['url'].'"><span class="contribook_blogheadlinesmall">'.$post['title'].'</span></a> ');
		echo('<span class="contribook_blogtimesmall">'.date('F j, Y',$post['timestamp']).'</span></li>');
	}
	echo('</ul>');
	
}
