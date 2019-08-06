<?php
header('Content-type: application/json');
$dbhost = '127.0.0.1';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = 'root';          // mysql用户名密码
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

//$list = $_POST['list'];
$json = json_decode(file_get_contents("php://input"), true);

if(!$conn){
    $code = "2";
    $message = "请求失败";
}
else {
// 设置编码，防止中文乱码
    if (empty($json)) {
        $code = "3";
        $message = "参数缺失";
    }
    else{
        mysqli_set_charset($conn, 'utf8');
        mysqli_select_db($conn, 'zx19bg');

        $st1 = '';
        $st2 = '';
        $st3 = '';
        if(!empty($json['md1'])){
            $st1 = "1";
        }
        if(!empty($json['md2'])){
            $st2 = "1";
        }
        if(!empty($json['md3'])){
            $st3 = "1";
        }

        $tel = $json['tel'];
        $select = "SELECT * FROM  `zx19bg_member` WHERE `tel` =  '$tel'";
        $que = mysqli_query($conn , $select);
        $row = mysqli_fetch_array($que);

        if($que) {
            if (empty($row)) {

                $sql = 'INSERT INTO `zx19bg_member`(`name`, `sex`,`qq`, `yuan`, `class`, `tel`, `md1`, `st1` ,`md2`, `st2` , `md3`,`st3` ,`trod`, `cardID`, `companyName` ,`companyCardID`) VALUES ("' . $json['name'] . '", "' . $json['sex'] . '","' . $json['qq'] . '","' . $json['yuan'] . '","' . $json['class'] . '","' . $json['tel'] . '","' . $json['md1'] . '","' . $st1 . '","' . $json['md2'] . '","' . $st2 . '","' . $json['md3'] . '","' . $st3 . '","' . $json['trod'] . '","' . $json['cardID'] . '","' . $json['companyName'] . '" ,"' . $json['companyCardID'] . '" )';
                $retval = mysqli_query($conn, $sql);
                if ($retval) {
                    $code = "0";
                    $message = "无错误";
                } else {
                    $code = "1";
                    $message = "数据库操作有误";
                }
                mysqli_close($conn);
            } else {
                $code = "4";
                $message = "此手机号码已报名";
            }
        }
        else{
            $code = "1";
            $message = "数据库操作有误";
        }
    }
}
$result = array(
    'code' => $code,
    'message' => $message
);
echo json_encode($result);
?>


