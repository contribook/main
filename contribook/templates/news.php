<?php

echo('<ul>');
	
foreach($_ as $post){
	echo('<li><a href="'.$post['url'].'">'.htmlentities($post['title']).'</a></li>');
}

echo('</ul>');

