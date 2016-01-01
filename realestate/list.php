<?php
require_once('../lib.php');
require_once('../db.php');
require_once('lib.php');
header_print('家ログ', array());

$fliplink = flip_link('realestate');
echo <<<HEADER
      <h2>物件一覧</h2>
      $fliplink
      <table class="list">
        <thead>
          <tr>
            <th>No.</th>
            <th>詳細</th>
            <th>オーナー</th>
            <th>外観</th>
            <th>評価と概要</th>
            <th>契約形態</th>
            <th>住所</th>
          </tr>
        </thead>
        <tbody>
HEADER;
$rate = 0; /* XXX */
$rateimg = "../images/star$rate.png"; /* XXX */
$rows = 0;

$rs = db_records_get('realestate');
foreach ($rs as $id => $r) {
	$rowmod = $rows++ % 2;
	$estatepic = realestate_image_top_url($r);
	$ownerimg = realestate_image_owner_url($r);
	if (user_is_loggedin())
		$link = 'href="view.php?id=' . $id . '"';
	else
		$link = 'href="#" class="require-login"';
	$payment = realestate_payment_name($r['payment']);
	echo <<<RECORD
          <tr class="list-row$rowmod">
            <td rowspan="2">$id</td>
            <td rowspan="2"><a $link>詳細</a></td>
            <td rowspan="2"><img class="list-pic" alt="owner" src="{$ownerimg}" /></td>
            <td rowspan="2"><img class="list-pic" alt="estate" src="{$estatepic}" /></td>
            <td class="list-rate"><img alt="zero" src="{$rateimg}" /></td>
            <td rowspan="2">{$payment}</td>
            <td rowspan="2">{$r['prefecture']}{$r['city']}{$r['address']}</td>
          </tr>

          <tr class="list-row$rowmod">
            <td class="list-abstract">{$r['abstract']}</td>
          </tr>

RECORD;
}
echo <<<FOOTER
        </tbody>
      </table>
      $fliplink
FOOTER;
footer_print();
?>
