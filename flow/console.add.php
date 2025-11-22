<?php

security_check();

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (!validate_blank($_POST['name']) || !validate_blank($_POST['url']))
    {
        message_set('QR Code Error', 'There was an error with the provided QR code.', 'red');
        header_redirect('/console/add');
    }

    $hash = string_hash(3);

    $qr_code= new QrCode('https://qr.brickmmo.com/'.$hash);
    $png = new PngWriter();
    $image = $png->write($qr_code)->getDataUri();

    // Save QR code details to the database
    $query = 'INSERT INTO qrs (
            name, 
            url, 
            image, 
            hash,
            user_id,
            created_at,
            updated_at
        ) VALUES (
            "'.addslashes($_POST['name']).'",
            "'.addslashes($_POST['url']).'", 
            "'.$image.'",
            "'.$hash.'",
            '.$_user['id'].',
            NOW(),
            NOW()
        )';
    mysqli_query($connect, $query);

    message_set('QR Success', 'QR code has been successfully created.');
    header_redirect('/console/dashboard');
}

define('APP_NAME', 'QR Codes');
define('PAGE_TITLE', 'Add QR Code');
define('PAGE_SELECTED_SECTION', 'qr-codes');
define('PAGE_SELECTED_SUB_PAGE', '/console/add');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

?>

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/qr.png"
        height="50"
        style="vertical-align: top"
    />
    QR Codes
</h1>
<p>
    <a href="/console/dashboard">Qr Codes</a> / 
    Add QR Code
</p>

<hr>

<h2>Add QR Code</h2>

<form
    method="post"
    novalidate
    id="main-form"
>

    <input  
        name="name" 
        class="w3-input w3-border" 
        type="text" 
        id="name" 
        autocomplete="off"
    />
    <label for="name" class="w3-text-gray">
        Name <span id="name-error" class="w3-text-red"></span>
    </label>

    <input  
        name="url" 
        class="w3-input w3-border w3-margin-top" 
        type="text" 
        id="url" 
        autocomplete="off"
    />
    <label for="url" class="w3-text-gray">
        URL <span id="url-error" class="w3-text-red"></span>
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateMainForm();">
        <i class="fa-solid fa-tag fa-padding-right"></i>
        Add QR Code
    </button>

</form>

<script>

    function validateMainForm() {
        let errors = 0;

        let name = document.getElementById("name");
        let name_error = document.getElementById("name-error");
        name_error.innerHTML = "";
        if (name.value == "") {
            name_error.innerHTML = "(name is required)";
            errors++;
        }

        let url = document.getElementById("url");
        let url_error = document.getElementById("url-error");
        url_error.innerHTML = "";
        if (url.value == "") {
            url_error.innerHTML = "(URL is required)";
            errors++;
        }

        if (errors) return false;
    }

</script>

<?php

include('../templates/main_footer.php');
include('../templates/html_footer.php');

?>