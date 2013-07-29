<?php


if(count($_)>0){

        echo('<table>');
        foreach($_ as $post){

                echo('<tr><td style="padding:5px;">');

                if($post['picture_50']<>''){
                        echo('<span class="contribook_microbloguserpicture"><a href="'.CONTRIBOOK_USER_URL.$post['user'].'"><img src="'.CONTRIBOOK_PHOTO_URL.$post['picture_50'].'" border="0" /></a></span>');
                }
                echo('</td><td style="padding:5px;">');
                echo('<span class="contribook_microbloguser"><a href="'.CONTRIBOOK_USER_URL.$post['user'].'">'.$post['name'].'</a></span> ');
                echo('<span class="contribook_microblogtime">'.date('F j, Y',$post['timestamp']).'</span><br />');

                echo('<a href="'.$post['url'].'"><span class="contribook_microblogmessage">'.$post['message'].'</span></a>');

                echo('</td></tr>');

        }
        echo('</table>');
}



