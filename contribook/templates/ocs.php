<?php

echo('<table class="ocs">');
foreach($_ as $item){
	  echo('<tr><td>');

      if(isset($item['preview']) and !empty($item['preview'])) echo('<a href="'.$item['url'].'"><img src="'.$item['preview'].'" alt="'.htmlentities($item['name']).'" title="'.htmlentities($item['name']).'" /></a>');

      echo('</td><td style="padding:10px;">');

      echo('<span class=""><a href="'.$item['url'].'">'.$item['name'].'</a></span><br />');
      if(!empty($item['description'])) echo(htmlentities(''.substr($item['description'],0,200).' ...'));

      echo('</td></tr>');

}
echo('</table>');

