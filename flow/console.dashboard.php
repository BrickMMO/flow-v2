<?php

security_check();


define('APP_NAME', 'QR Codes');
define('PAGE_TITLE', 'Dashboard');
define('PAGE_SELECTED_SECTION', 'qr-codes');
define('PAGE_SELECTED_SUB_PAGE', '/console/dashboard');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

/*
$query = 'SELECT *
    FROM qr_logs
    INNER JOIN qrs 
    ON qrs.id = qr_logs.qr_id
    WHERE user_id = '.$_user['id'].'';
$result = mysqli_query($connect, $query);
$log_count = mysqli_num_rows($result);

$query = 'SELECT qrs.*,(
        SELECT COUNT(*)
        FROM qr_logs
        WHERE qrs.id = qr_logs.qr_id
    ) AS scans
    FROM qrs 
    WHERE user_id = '.$_user['id'].'
    ORDER BY name';
$result = mysqli_query($connect, $query);
$qr_count = mysqli_num_rows($result);
*/

?>

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/qr.png"
        height="50"
        style="vertical-align: top"
    />
    QR Codes
</h1>

<hr>

<p>
    Total QR Codes: <span class="w3-tag w3-blue"><?=$qr_count?></span> | 
    Total Scans: <span class="w3-tag w3-blue"><?=$log_count?></span> 
</p>

<hr>

<h3>QR Codes</h3>

<table class="w3-table w3-bordered w3-striped w3-margin-bottom">
    <tr>
        <th class="bm-table-icon"></th>
        <th>Name</th>
        <th class="bm-table-number">Scans</th>
        <th class="bm-table-icon"></th>
        <th class="bm-table-icon"></th>
    </tr>

    <?php while ($record = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>
                <img src="<?=$record['image'] ?>" alt="" width="70">
            </td>
            <td>
                <?=$record['name'] ?>
                <small>
                    <br>
                    Scan URL: <a href="<?=$record['url'] ?>"><?=$record['url'] ?></a>
                    <br>
                    Redirect URL: <a href="<?=ENV_DOMAIN?>/<?=$record['hash'] ?>">https://qr.brickmmo.com/<?=$record['hash'] ?></a>
                </small>
            </td>
            <td class="bm-table-number">
                <a href="/console/logs/<?=$record['id']?>"><?=$record['scans']?></a>
            </td>
            <td>
                <a href="/console/edit/<?=$record['id'] ?>">
                    <i class="fa-solid fa-pencil"></i>
                </a>
            </td>
            <td>
                <a href="#" onclick="return confirmModal('Are you sure you want to delete the QR code <?=$record['name'] ?>?', '/console/dashboard/delete/<?=$record['id'] ?>');">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>

</table>

<a
    href="/console/add"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-pen-to-square fa-padding-right"></i> Add QR Code
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');