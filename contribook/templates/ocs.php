<?php

echo('<table class="ocs">');
foreach($_ as $item){
	  echo('<tr><td>');

      if(isset($item['preview']) and !empty($item['preview'])) echo('<a href="'.$item['url'].'"><img src="'.$item['preview'].'" alt="'.$item['name'].'" title="'.$item['name'].'" /></a>');

      echo('</td><td>');

      echo('<span class=""><a href="'.$item['url'].'">'.$item['name'].'</a></span><br />');
      if(!empty($item['description'])) echo(''.substr($item['description'],0,200).' ...');

      echo('</td></tr>');

}
echo('</table>');

