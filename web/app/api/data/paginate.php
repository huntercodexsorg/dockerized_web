<?php

$db = "csv";
$product_name = "";
$limit = 10;
$type = null;
$ratings = true;
$icons = true;
/*Backend Paginate*/
$paginate = false;
$items_show = 20;
$max_pager = 20;
$page = 1;

function getPaginateDataCSV($product_name, $limit, $type = "", $page): void
{
    global $ratings;
    global $icons;
    global $items_show;
    global $max_pager;
    $columns = [
        "id",
        "product_name",
        "country",
        "description",
        "price",
        "ratings",
        "warranty",
        "number_ref",
        "code_ref",
        "sap_number",
        "delivery",
        "payment",
        "status",
        "order",
        "customer",
        "address",
        "phone",
        "city",
        "postal_code",
        "extras",
        "additional",
        "restrict",
        "others",
        "send",
        "anchor",
        "created",
        "updated",
        "canceled"
    ];
    $tmp_result = [];
    $counter = 0;

    $fo = fopen("database.csv", "r");
    while (!feof($fo)) {
        $line = fgets($fo, 4096);
        if (preg_match("/^([0-9]+);{$product_name}(.*);/", $line, $m, PREG_OFFSET_CAPTURE)) {
            if ($counter >= (($page-1)*$items_show) && $counter < ($page*$items_show)) {
                array_push($tmp_result, $line);
            }

            if ($counter >= ($limit + (($page-1)*$items_show))) {
                break;
            }
            $counter += 1;
        }
    }
    fclose($fo);

    $result = [];
    for ($k = 0; $k < count($tmp_result); $k++) {
        $tmp_array = [];
        $exp = explode(";", $tmp_result[$k]);
        for ($c = 0; $c < count($columns); $c++) {
            $tmp_array[$columns[$c]] = $exp[$c];
        }
        array_push($result, $tmp_array);
    }

    /*Simulate Ratings*/
    if ($ratings == true) {
        for ($x = 0; $x < count($result); $x++) {
            $id = str_replace("#","", $result[$x]["id"]);
            $result[$x]["ratings"] = [
                "item_id" => $id,
                "stars" => [
                    1 => rand(1, 100),
                    2 => rand(1, 20),
                    3 => rand(35, 56),
                    4 => rand(10, 30),
                    5 => rand(1, 100)
                ]
            ];
        }
    }

    /*Simulate Icons*/
    if ($icons == true) {
        $ic = "brasil.png";
//        $ic = "./media/icon/brasil.png";
//        $ic = "icon/brasil.png";
//        $ic = "https://mundoconectado.com.br/uploads/chamadas/windows-11-wallpaper-chamada.jpg";
        $br = false;
        for ($x = 0; $x < count($result); $x++) {
            if ($x % 2) {
                $ic = "eua.png";
//                $ic = "./media/icon/eua.png";
//                $ic = "icon/eua.png";
//                $ic = "http://4.bp.blogspot.com/-3YwFT7wiDnE/VT5NLGC20qI/AAAAAAABC8Y/tQcMBoQkbEk/s1600/adffb142a07755f9fc4e1400e3491ae32.jpg";
            } elseif (($x + 1) % 2) {
                if ($br == false) {
//                    $ic = "https://mundoconectado.com.br/uploads/chamadas/windows-11-wallpaper-chamada.jpg";
                    $ic = "brasil.png";
//                    $ic = "./media/icon/brasil.png";
//                    $ic = "icon/brasil.png";
                    $br = true;
                } else {
                    $br = false;
                    $ic = "espanha.png";
//                    $ic = "./media/icon/espanha.png";
//                    $ic = "icon/espanha.png";
//                    $ic = "https://i1.wp.com/multarte.com.br/wp-content/uploads/2015/08/imagens-amor.jpg?fit=1680%2C1050&ssl=1";
                }
            }
            $result[$x]["country"] = $ic;
        }
    }

    /*file_put_contents("exemplo-estrutura-grid-com-ratings.txt", print_r($result, true));*/

    echo json_encode([
        "page" => ($page == 0) ? 1 : intval($page),
        "nextPage" => $page + 1,
        "prevPage" => (($page - 1 > 0) ? $page - 1 : 1),
        "counter" => $counter,
        "items_show" => $items_show,
        "max_pager" => $max_pager,
        "result" => $result
    ]);
}

function getDataCSV($product_name, $limit, $type = ""): void
{
    global $ratings;
    global $icons;
    $columns = [
        "id",
        "product_name",
        "country",
        "description",
        "price",
        "ratings",
        "warranty",
        "number_ref",
        "code_ref",
        "sap_number",
        "delivery",
        "payment",
        "status",
        "order",
        "customer",
        "address",
        "phone",
        "city",
        "postal_code",
        "extras",
        "additional",
        "restrict",
        "others",
        "send",
        "anchor",
        "created",
        "updated",
        "canceled"
    ];
    $tmp_result = [];
    $counter = 0;

    $fo = fopen("database.csv", "r");
    while (!feof($fo)) {
        $line = fgets($fo, 4096);
        if (preg_match("/^([0-9]+);{$product_name}(.*);/", $line, $m, PREG_OFFSET_CAPTURE)) {
            array_push($tmp_result, $line);
            $counter += 1;
            if ($counter >= $limit) {
                break;
            }
        }
    }
    fclose($fo);

    $result = [];
    for ($k = 0; $k < count($tmp_result); $k++) {
        $tmp_array = [];
        $exp = explode(";", $tmp_result[$k]);
        for ($c = 0; $c < count($columns); $c++) {
            $tmp_array[$columns[$c]] = $exp[$c];
        }
        array_push($result, $tmp_array);
    }

    /*Simulate Ratings*/
    if ($ratings == true) {
        for ($x = 0; $x < count($result); $x++) {
            $id = str_replace("#","", $result[$x]["id"]);
            $result[$x]["ratings"] = [
                "item_id" => $id,
                "stars" => [
                    1 => rand(1, 100),
                    2 => rand(1, 20),
                    3 => rand(35, 56),
                    4 => rand(10, 30),
                    5 => rand(1, 100)
                ]
            ];
        }
    }

    /*Simulate Icons*/
    if ($icons == true) {
        $ic = "brasil.png";
//        $ic = "./media/icon/brasil.png";
//        $ic = "icon/brasil.png";
//        $ic = "https://mundoconectado.com.br/uploads/chamadas/windows-11-wallpaper-chamada.jpg";
        $br = false;
        for ($x = 0; $x < count($result); $x++) {
            if ($x % 2) {
                $ic = "eua.png";
//                $ic = "./media/icon/eua.png";
//                $ic = "icon/eua.png";
//                $ic = "http://4.bp.blogspot.com/-3YwFT7wiDnE/VT5NLGC20qI/AAAAAAABC8Y/tQcMBoQkbEk/s1600/adffb142a07755f9fc4e1400e3491ae32.jpg";
            } elseif (($x + 1) % 2) {
                if ($br == false) {
//                    $ic = "https://mundoconectado.com.br/uploads/chamadas/windows-11-wallpaper-chamada.jpg";
                    $ic = "brasil.png";
//                    $ic = "./media/icon/brasil.png";
//                    $ic = "icon/brasil.png";
                    $br = true;
                } else {
                    $br = false;
                    $ic = "espanha.png";
//                    $ic = "./media/icon/espanha.png";
//                    $ic = "icon/espanha.png";
//                    $ic = "https://i1.wp.com/multarte.com.br/wp-content/uploads/2015/08/imagens-amor.jpg?fit=1680%2C1050&ssl=1";
                }
            }
            $result[$x]["country"] = $ic;
        }
    }

    /*file_put_contents("exemplo-estrutura-grid-com-ratings.txt", print_r($result, true));*/

    echo json_encode($result);
}

function getDataJS($product_name, $limit, $type = ""): void
{
}

function getDataPHP($product_name, $limit, $type = ""): void
{
    require_once "database.php";

    global $_data_;

    if ($final == 0) {
        $final = count($_data_);
    }

    $result = [];

    for ($k = $initial; $k < $final; $k++) {
        array_push($result, $_data_[$k]);
    }

    if ($type == "web") {
        echo json_encode($result);
        //var_dump('<pre>', $result, '</pre>');
    } else {
        var_dump($result);
    }
}

//"db=csv&product_name=Product&limit=10000"

if (isset($argv) && count($argv) > 0) {

    /*CLI*/

    $db = $argv[1] ?? "csv";
    $product_name = $argv[2] ?? "Product";
    $limit = $argv[2] ?? 10;
    $type = "cli";
} else {

    /*WEB*/

    if (isset($_GET) && count($_GET) > 0) {
        /*GET*/
        $db = $_GET['db'] ?? "csv";
        $product_name = $_GET['product_name'] ?? "Product";
        if (isset($_GET['limit']) && $_GET['limit'] != "" && !isset($_GET['page'])) {
            $limit = $_GET['limit'] ?? 10;
        } elseif (isset($_GET['page']) && $_GET['page'] != "" && isset($_GET['limit'])) {
            $paginate = true;
            $page = $_GET['page'] ?? 1;
            $limit = $_GET['limit'] ?? 10000;
        }
    } elseif (isset($_POST) && count($_POST) > 0) {
        /*POST*/
        $db = $_POST['db'] ?? "csv";
        $product_name = $_POST['product_name'] ?? "Product";
        $limit = $_POST['limit'] ?? 10;
    } else {
        /*JSON*/
        $decode = json_decode(file_get_contents("php://input"));
        $db = $decode->db;
        $product_name = $decode->product_name;
        $limit = $decode->limit;
    }

    $type = "web";
}

if ($db == "php") {
    global $_data_;
    getDataPHP($product_name, $limit, $type);
} elseif ($db == "js") {
    getDataJS($product_name, $limit, $type);
} elseif($db == "csv") {
    if ($paginate === false) {
        getDataCSV($product_name, $limit, $type);
    } else {
        getPaginateDataCSV($product_name, $limit, $type, $page);
    }
}

exit;