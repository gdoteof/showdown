<?php
require_once 'lib/limonade.php';

$db_user = 'root';
$db_pass = 'x';

$db_r = mysql_connect('localhost', $db_user, $db_pass);

if (!$db_r || !mysql_select_db($db_name)) {
    echo "Could not connect to server\n";
    trigger_error(mysql_error(), E_USER_ERROR);
} else {
  ;
}


function _card_img($resource){
  return '<img src="' . $resource['photo'] . '"/>';
}

function vote($id){
  return mysql_query('UPDATE avacynrestored SET votes=votes+1 where id=' . $id);
}

function impress($id){
  return mysql_query('UPDATE avacynrestored SET impressions=impressions+1 where id=' . $id);
}

function _div($inner){
  return '<div>' . $inner . '</div>';
}

function _link($href, $title){
  return "<a href='$href'>$title</a>";
}

dispatch('/', 'hello');
dispatch('/vote/*/*/*', 'vote_r');  //card1 card2 -- winner
dispatch('/impression/*', 'impression_r');

function hello() {
    $results = mysql_query('SELECT * FROM avacynrestored ORDER BY RAND() LIMIT 2');
    $cards = array();
    while ($result = mysql_fetch_assoc($results)){
      $cards[] =$result;
    }
      $id1 = $cards[0]['id'];
      $id2 = $cards[1]['id'];
      print _card_img($cards[0]);
      print _div('Votes: ' . $cards[0]['votes'] . ' ');
      print _div('Impressions: ' . $cards[0]['impressions'] . ' ');
      print '<!-- <pre>'; print_r ($cards[0]); print '</pre> -->';
      print _div(_link("?u=vote/$id1/$id2/$id1", "Vote for " . $cards[0]['title']));

      print _card_img($cards[1]);
      print _div('Votes: ' . $cards[1]['votes'] . ' ');
      print _div('Impressions: ' . $cards[1]['impressions'] . ' ');
      print '<!-- <pre>'; print_r ($cards[1]); print '</pre> -->';
      print _div(_link("?u=vote/$id1/$id2/$id2", "Vote for " . $cards[1]['title']));
}

function vote_r() {
  $id_1 = params(0);
  $id_2 =params(1);
  $id_winner =params(2);
  impress($id_1);
  impress($id_2);
  vote($id_winner);
  header('Location: index.php');

}



run();


