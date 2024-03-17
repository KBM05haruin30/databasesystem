<?php
//세션을 시작하는 함수, 사용자 세션 데이터를 관리할 수 있다.
session_start();

// 사용자가 로그인 했는지 확인하는 코드, $_SESSION["UserID"]가 설정되어 있지 않은 경우 login.php로 리디렉션한다.
if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
}

// 데이터베이스 연결을 위한 설정
$host = "localhost";
$username = "root";
$password = "Haruin0530!";
$dbname = "scheduling_db";

// $conn 변수를 사용하고 new mysqli함수를 이용해 MySQL 데이터베이스에 연결한다.
$conn = new mysqli($host, $username, $password, $dbname);

// 데이터베이스에 연결되었는지 확인하는 함수
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 이벤트(일정)를 삭제하는 부분
//$_GET["delete"]가 설정된 경우
if (isset($_GET["delete"])) {
    $eventId = $_GET["delete"];

    // 해당 이벤트를 'event'테이블에서 삭제하는 쿼리 실행
    $deleteEventSql = "DELETE FROM event WHERE EventID = $eventId";
    $conn->query($deleteEventSql);

    // eventcategotyrelation 테이블에서 이벤트-카테고리 관계를 삭제한다.
    $deleteRelationSql = "DELETE FROM eventcategotyrelation WHERE EventID = $eventId";
    $conn->query($deleteRelationSql);
	//삭제 후 home.php로 리디렉션한다
    header("Location: home.php");
    exit();
}

// 이벤트(일정)를 수정하는 부분
//$_GET["edit"]가 설정된 경우
if (isset($_GET["edit"])) {
    $eventId = $_GET["edit"];

    // 'event'테이블에서 해당 이벤트의 세부 정보를 검색한다.
    $editEventSql = "SELECT * FROM event WHERE EventID = $eventId";
    $editEventResult = $conn->query($editEventSql);

    if ($editEventResult->num_rows > 0) {
        $eventRow = $editEventResult->fetch_assoc();
    }
}

// 이벤트(일정)의 제출을 처리하는 부분
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$_POST["editEvent"]로 설정된 경우 - 이벤트 수정
	if (isset($_POST["editEvent"])) {
        $eventId = $_POST["eventId"];
        $title = $_POST["title"];
        $startDateTime = $_POST["startDateTime"];
        $endDateTime = $_POST["endDateTime"];
        $location = $_POST["location"];
        $category = $_POST["category"];

        // 'event'테이블에서 해당 이벤트의 내용들을 업데이트하는 쿼리 실행
        $updateEventSql = "UPDATE event SET Title = '$title', StartDateTime = '$startDateTime', EndDateTime = '$endDateTime', Location = '$location' WHERE EventID = $eventId";
        if ($conn->query($updateEventSql) === TRUE) {
            // eventcategotyrelation테이블에서 이벤트-카테고리 관계를 업데이트하는 쿼리 실행
            $deleteRelationSql = "DELETE FROM eventcategotyrelation WHERE EventID = $eventId";
            $conn->query($deleteRelationSql);

            if (!empty($category)) {
                $categorySql = "INSERT INTO eventcategotyrelation (EventID, CategoryID) VALUES ('$eventId', '$category')";
                $conn->query($categorySql);
            }

            header("Location: home.php");
            exit();
        } else {
            echo "Error: " . $updateEventSql . "<br>" . $conn->error;
        }
    } else {//이벤트를 새로 생성하는 경우
        $userId = $_SESSION["UserID"];
        $title = $_POST["title"];
        $startDateTime = $_POST["startDateTime"];
        $endDateTime = $_POST["endDateTime"];
        $location = $_POST["location"];
        $category = $_POST["category"];

        // 'event'테이블에 새로운 이벤트를 삽입하는 쿼리 실행
        $sql = "INSERT INTO event (UserID, Title, StartDateTime, EndDateTime, Location)
            VALUES ('$userId', '$title', '$startDateTime', '$endDateTime', '$location')";
        if ($conn->query($sql) === TRUE) {
            $eventId = $conn->insert_id;
            // 'eventcategoryrelation테이블에서 이벤트-카테고리 관계를 업데이트하는 쿼리 실행
            if (!empty($category)) {
                $categorySql = "INSERT INTO eventcategotyrelation (EventID, CategoryID)
                            VALUES ('$eventId', '$category')";
                $conn->query($categorySql);
            }
			//성공한 경우 home.php를 리디렉션한다
            header("Location: home.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// 로그인한 사용자의 이벤트를 검색하는 쿼리 실행
// UserID를 사용하여 'event'테이블과 'eventcategoryrelation테이블을 조인하여 이벤트와 카테고리 정보를 가져온다.
$userId = $_SESSION["UserID"];
$sql = "SELECT e.EventID, e.Title, e.StartDateTime, e.EndDateTime, e.Location, c.CategoryName
        FROM event AS e
        LEFT JOIN eventcategotyrelation AS ec ON e.EventID = ec.EventID
        LEFT JOIN category AS c ON ec.CategoryID = c.CategoryID
        WHERE e.UserID = $userId";
$result = $conn->query($sql);

// 'category'테이블에서 카테고리 정보를 가져오는 쿼리 실행
$categorySql = "SELECT CategoryID, CategoryName FROM category";
$categoryResult = $conn->query($categorySql);
//데이터베이스 연결을 닫는다.
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>메인 화면</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            margin-top: 0;
        }

        h3 {
            margin-bottom: 10px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #337ab7;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #23527c;
        }

        p {
            margin: 0;
        }

        a {
            color: #337ab7;
            text-decoration: none;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ccc;
        }

        .event {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2><?php echo $_SESSION["Name"]; ?>님 어서오세요!</h2>

    <!-- 이벤트 추가 폼 -->
    <h3>일정 추가</h3>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="eventId" value="<?php echo isset($eventRow) ? $eventRow['EventID'] : ''; ?>">
        <label for="title">해야할 일</label>
        <input type="text" name="title" required value="<?php echo isset($eventRow) ? $eventRow['Title'] : ''; ?>">

        <label for="startDateTime">시작 날짜/시간</label>
        <input type="datetime-local" name="startDateTime" required value="<?php echo isset($eventRow) ? date('Y-m-d\TH:i', strtotime($eventRow['StartDateTime'])) : ''; ?>">

        <label for="endDateTime">종료 날짜/시간</label>
        <input type="datetime-local" name="endDateTime" required value="<?php echo isset($eventRow) ? date('Y-m-d\TH:i', strtotime($eventRow['EndDateTime'])) : ''; ?>">

        <label for="location">장소</label>
        <input type="text" name="location" required value="<?php echo isset($eventRow) ? $eventRow['Location'] : ''; ?>">

        <label for="category">종류</label>
        <select name="category">
            <option value="">None</option>
            <?php
            while ($categoryRow = $categoryResult->fetch_assoc()) {
                $categoryId = $categoryRow["CategoryID"];
                $categoryName = $categoryRow["CategoryName"];
                $selected = isset($eventRow) && $eventRow['CategoryName'] == $categoryName ? 'selected' : '';
                echo "<option value='$categoryId' $selected>$categoryName</option>";
            }
            ?>
        </select>

        <?php if (isset($eventRow)) : ?>
            <input type="submit" name="editEvent" value="일정 수정">
        <?php else : ?>
            <input type="submit" value="일정 추가">
        <?php endif; ?>
    </form>

    <!-- 이벤트 목록 -->
    <h3>일정</h3>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='event'>";
            echo "<p><strong>일정 : </strong> " . $row["Title"] . "</p>";
            echo "<p><strong>시작 날짜/시간 : </strong> " . $row["StartDateTime"] . "</p>";
            echo "<p><strong>종료 날짜/시간 : </strong> " . $row["EndDateTime"] . "</p>";
            echo "<p><strong>장소 : </strong> " . $row["Location"] . "</p>";
            echo "<p><strong>종류 : </strong> " . $row["CategoryName"] . "</p>";
            echo "<p><a href='home.php?edit=" . $row["EventID"] . "'>수정</a> | <a href='home.php?delete=" . $row["EventID"] . "'>삭제</a></p>";
            echo "</div>";
        }
    } else {
        echo "<p>일정이 없습니다.</p>";
    }
    ?>
	<!-- 사용자를 로그아웃하는 페이지(logout.php)로 이동하는 링크를 로그아웃에 부여 -->
    <a href="logout.php">로그아웃</a>
</body>
</html>