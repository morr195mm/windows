<?php

require_once "singletonPDO.php";

if (isset($_GET["do"]) && !empty($_GET["do"])) {
    //通過單例方法獲取全域唯一的 pdo 對象
    $pdo = SingletonPDO::getPdo();

    switch ($_GET["do"]) {
        case 'create':
            $data = [
                $_POST["cName"],
                $_POST["cSex"],
                $_POST["cBirthday"],
                $_POST["cEmail"],
                $_POST["cPhone"],
                $_POST["cAddr"],
                $_POST["cHeight"],
                $_POST["cWeight"],
            ];
            $sql = "INSERT INTO `students`(`cName`,`cSex`,`cBirthday`,`cEmail`,`cPhone`,`cAddr`,`cHeight`,`cWeight`) values(?,?,?,?,?,?,?,?)";
            //打包成半成品的 SQL 子句，回傳的內容 PDOStatement
            $stmt = $pdo->prepare($sql);
            try {
                //執行 PDOStatement 物件的 execute() 方法，執行半成品 SQL 子句同時將資料傳入參數產生完整SQL 子句執行結果
                if ($stmt->execute($data)) {
                    echo "新增成功";
                } else {
                    echo "新增失敗";
                }
            } catch (PDOException $e) {
                echo "新增失敗";
            }

            break;
        case 'delete':
            // 法1
            // $sql = "DELETE FROM `students` WHERE cID=16";
            // //$pdo->exec($sql);
            // if($pdo->exec($sql)){
            //   echo "success";
            // }else{
            //   echo "fail";
            // }
            // 法2
            $sql = "DELETE FROM `students` WHERE cID=?";
            $stmt = $pdo->prepare($sql);
            $data = [$_POST["cID"]];
            try {
                //執行 PDOStatement 物件的 execute() 方法，執行半成品 SQL 子句同時將資料傳入參數產生完整SQL 子句執行結果
                if ($stmt->execute($data)) {
                    echo "刪除成功";
                } else {
                    echo "刪除失敗";
                }
            } catch (PDOException $e) {
                echo "刪除失敗";
            }

            break;
        case 'update':
            $sql = "UPDATE `students` SET cWeight=? WHERE cID=?";
            $stmt = $pdo->prepare($sql);
            $data = [50, 7];
            // $data = [$_POST["cWeight"],$_POST["cID"] ];
            try {
                //執行 PDOStatement 物件的 execute() 方法，執行半成品 SQL 子句同時將資料傳入參數產生完整SQL 子句執行結果
                if ($stmt->execute($data)) {
                    echo "更新成功";
                } else {
                    echo "更新失敗";
                }
            } catch (PDOException $e) {
                echo "更新失敗";
            }

            break;
        case 'select':
            //方法1: 使用 PDO::query() 做查詢
            if ($_POST["method"] == "1") {
                $sql = "SELECT * FROM `students`";
                $result = $pdo->query($sql);
                //指定提取模式: 數字索引方式
                $result->setFetchMode(PDO::FETCH_NUM);
                while ($row = $result->fetch()) {
                    print_r($row);
                    echo "<br>";
                }
            } elseif ($_POST["method"] == "2") {

            }
            //方法2: 使用PDO::prepare() 預處理執行查詢
            $sql = "SELECT * FROM `students`";
            $result = $pdo->prepare($sql);
            $result->execute();
            while ($row = $result->fetch()) {
                print_r($row);
                echo "<br>";
            }
            break;
    }
}
