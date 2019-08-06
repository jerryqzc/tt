<?php
header("Content-Type:text/html;charset=utf-8");
$dbhost = '127.0.0.1';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = 'root';          // mysql用户名密码
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

//$select = $_POST['select'];
$json = json_decode(file_get_contents("php://input"), true);
//用户参数

$name = $json['name'];
$tel = $json['tel'];
$data = null;

if(!$conn){
    $code = "2";
    $message = "请求失败";
}
else {
// 设置编码，防止中文乱码
    if (empty($name)||empty($tel)) {
        $code = "3";
        $message = "参数缺失";
    }
    else{
        mysqli_set_charset($conn, 'utf8');
        mysqli_select_db($conn, 'zx19bg');

        $sql = "SELECT * FROM  `zx19bg_member` WHERE (`name` =  '$name') AND (`tel` =  '$tel')";
        $que = mysqli_query($conn , $sql);
        $row = mysqli_fetch_array($que);
        if ($que) {

            if(empty($row)){
                $code = "4";
                $message = "数据库中查无此数据";
            }
            else{
                $code = "0";
                $message = "无错误";
                $data = $row[8];
                if($row[8]<$row[10]){
                    $data = $row[10];
                    if($row[10]<$row[12]){
                        $data = $row[12];
                    }
                }
            }

        } else {
            $code = "1";
            $message = "数据库操作有误";
        }
        mysqli_close($conn);
    }
}

$result = array(
    'code' => $code,
    'message' => $message,
    'data' => $data
);
echo json_encode($result);
?>