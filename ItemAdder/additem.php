<?php
//error_reporting(E_ERROR);
if(empty($_POST["username"]) or empty($_POST["password"])) {
    echo "Please fill in all fields";
}
else {
    $username = $_POST["username"];
    $password = $_POST["password"];

    spl_autoload_register(function($strClass){
        require_once sprintf('Penguin/%s.php', $strClass);
    });

    $objPenguin = new Penguin();
    global $itemid;
    $objPenguin->addListener("jr", function($packet) use ($objPenguin) {
        $objPenguin->addItem($_POST["itemid"]);

    });

    $objPenguin->addListener("ai", function($packet) {
        echo "Successfully added item ", "\n";
        die();
    });

    $objPenguin->addListener("e", function($packet) use ($objPenguin) {
        die($objPenguin->arrErrors[$packet[3]]["Description"]);
    });

    try {
        $objPenguin->login($username, $password );
        $objPenguin->joinServer('Sled');
    } catch(ConnectionException $objException){
        die();
    }

    $objPenguin->joinRoom(805);
    while(true){
        $strData = $objPenguin->recv();

        if(XTParser::IsValid($strData)){
            // echo $strData, chr(10);
        }
    }
}

?>
