<?php

/**
* Demo page. Just include the calls in your existing pages and adapt the templates.
*
* @author Frank Karlitschek
* @copyright 2013 Frank Karlitschek frank@karlitschek.de
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the License, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*
*/

?>

<html>
<head>
<title>Demo Page</title>
<link rel="stylesheet" href="style.css" type="text/css"  />
</head>

<body bgcolor="#EEEEEE">

<?php

  // makes it easier to debug
  error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

  // set the default timezone to use.
  date_default_timezone_set('Europe/Berlin');

  // you have to include lib_contribook.php on every page where you want to show some of the content.
  require('contribook/lib_contribook.php');

?>

here goes some website content

<br /><br />
<div style="width:800px;">
<?php

if(isset($_GET['user'])) {

  // show the profile of a user
  CONTRIBOOK_PROFILE::show($_GET['user']);
  
  // show the blog posts of the user
  echo('<br />The latest blogs<br />');
  CONTRIBOOK_BLOG::showuser($_GET['user'], 10);
  
  // show the twitter posts of the user
  echo('<br />The latest Twitter posts<br />');
  CONTRIBOOK_MICROBLOG::showuser($_GET['user'], 10);

  // show the github messages of the user
  echo('<br />The latest github messages<br />');
  CONTRIBOOK_GITHUB::show($_GET['user'], 10);

} else {


  // the latest twitter posts of all users
  echo('The latest twitter posts');
  CONTRIBOOK_MICROBLOG::show(10);
  
  // the latest news
  echo('The latest news');
  CONTRIBOOK_NEWS::show(10);

  // the latest posts from the forum
  echo('The latest from the forum');
  CONTRIBOOK_FORUM::show(10);

  // the latest content from an ocs server
  echo('<br />The latest apps<br />');
  CONTRIBOOK_OCS::show(1,10);

  // the latest blog posts of all users
  echo('<br />The latest blogs<br />');
  CONTRIBOOK_BLOG::show(10);

}

?>
</div>


<br /><br />
more content

</body>
</html>