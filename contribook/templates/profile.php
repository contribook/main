<?php

echo('<table><tr><td valign="top">');

if($_['picture_200']<>''){
	echo('<span class="bloguserpicture"><img src="'.CONTRIBOOK_PHOTO_URL.$_['picture_200'].'" border="1" /></span>');
}
	
echo('</td><td valign="top">');
	
echo('<span class="contribook_profile_name">'.$_['name'].'</span><br />');
if($_['role']<>'') echo('<span class="contribook_profile_role">'.$_['role'].'</span><br />');

if($_['city']<>'') echo('<span class="contribook_profile_location">'.$_['city'].'</span>');
if($_['city']<>'' and $_['country']<>'') echo(', ');
if($_['country']<>'') echo('<span class="contribook_profile_location">'.$_['country'].'</span>');
echo('<br />');

if($_['description']<>'') echo('<br /><span class="contribook_profile_description">'.$_['description'].'</span><br /><br /><br />');

if($_['blogurl']<>'') echo('<a href="'.$_['blogurl'].'"><span class="contribook_profile_blog">[blog]</span></a> ');
if($_['twitter']<>'') echo('<a href="https://twitter.com/'.$_['twitter'].'"><span class="contribook_profile_twitter">[twitter]</span></a>');
if($_['identica']<>'') echo('<a href="http://identi.ca/'.$_['twitter'].'"><span class="contribook_profile_identica">[identi.ca]</span></a>');
if($_['facebook']<>'') echo('<a href="https://www.facebook.com/'.$_['facebook'].'"><span class="contribook_profile_facebook">[facebook]</span></a>');
if($_['opendesktop']<>'') echo('<a href="http://opendesktop.org/usermanager/search.php?username='.$_['opendesktop'].'"><span class="contribook_profile_opendesktop">[opendesktop]</span></a>');
if($_['github']<>'') echo('<a href="https://github.com/'.$_['github'].'"><span class="contribook_profile_github">[github]</span></a>');



echo('</td></tr></table>');



