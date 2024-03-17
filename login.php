<!-- 로그인php파일 -->
<?php
session_start();
/*
session_start();는 세션을 시작하는 함수입니다. 세션은 서버 측에서 사용자의 상태를 유지하고 정보를 저장하는 데 사용되는 매커니즘입니다.
*/

/*
데이터베이스 연결 설정을 위한 변수들을 선언한 것입니다. host는 호스트 주소, username은 데이터베이스 사용자 이름, password는 데이터베이스 암호
dbname은 사용할 데이터베이스 이름입니다. 이 설정은 MySQL 데이터베이스에 연결하기 위해 사용됩니다.
*/
$host = "localhost";
$username = "root";
$password = "Haruin0530!";
$dbname = "scheduling_db";

// $conn 변수를 사용하여 new mysqli함수를 이용해 MySQL 데이터베이스에 연결합니다.
$conn = new mysqli($host, $username, $password, $dbname);

// 데이터베이스 연결이 성공적으로 이루어졌는지 확인하는 코드입니다. 연결에 실패한 경우 오류 메세지인 "Connection failed"를 출력하고 프로그램을 종료합니다.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/*
사용자가 폼을 제출했는지 확인하는 조건문입니다.
$_SERVER["REQUEST_METHOD"]는 HTTP요청 방법을 나타내며, POST방식으로 폼이 제출된 경우에만 실행됩니다.
여기에서 email과 password가 POST방식으로 제출되었습니다.
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    //사용자가 입력한 이메일과 비밀번호를 사용하여 'user'테이블에서 해당 사용자 정보를 가져오는 SQL 쿼리를 실행합니다.
    $sql = "SELECT * FROM user WHERE Email='$email' AND Password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
		/*
		쿼리 결과의 행 수가 1인 경우, 로그인이 성공한 것으로 판단합니다.
        $row 변수를 사용하여 사용자 정보를 가져온 후, 세션 변수를 설정합니다. 그리고 사용자를 header()를 이용해 home.php로 리디렉션합니다.
		*/
		$row = $result->fetch_assoc();
        $_SESSION["UserID"] = $row["UserID"];
        $_SESSION["Name"] = $row["Name"];
        header("Location: home.php");
    } else {
		//쿼리 결과의 행 수가 1이 아닌 경우, 로그인이 실패한 것으로 판단하고 밑의 오류 메시지를 출력합니다.
        echo "유효하지 않은 이메일이거나 비밀번호가 틀렸습니다. 다시 시도해주세요.";
    }
}
//데이터베이스 연결을 닫습니다.
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>로그인</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    
    header {
      background-color: #f5f5f5;
      padding: 20px;
      text-align: center;
    }
    
    h2 {
      margin-top: 0;
    }
    
    .container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    
    label {
      display: block;
      margin-bottom: 8px;
    }
    
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
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
  </style>
</head>
<body>
  <header>
    <h2>로그인</h2>
  </header>
  
  <div class="container">
    <!-- <form>은 사용자 입력을 받기 위한 폼입니다. 'method="POST"는 폼 데이터를 서버로 제출할 때 POST 방식을 사용함을 나타냅니다. -->
	<!-- 'action' 속성은 폼이 제출되었을 때 데이터가 전송될 URL을 지정합니다. -->
	<!-- 'htmlspecialchars($_SERVER["PHP_SELF"])'는 현재 파일의 경로를 나타냅니다. -->
	<!-- 즉, action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>는 폼 데이터가 현재 페이지로 전송되도록 설정합니다. -->
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <label for="email">이메일</label>
      <input type="email" name="email" required><br>

      <label for="password">비밀번호</label>
      <input type="password" name="password" required><br>

      <input type="submit" value="로그인">
	  <!-- <input>은 사용자의 입력을 받는 입력 필드입니다. 'type="email"'은 이메일 형식의 데이터를 입력받는 것이고 -->
	  <!-- 'type=:password"'는 비밀번호를 입력받는 것입니다. required 속성은 필수 입력 필드를 나타냅니다. -->
	  <!-- '<input type="submit">은 폼을 제출하는 버튼을 나타냅니다. 이 버튼을 클릭하면 폼 데이터가 서버로 전송됩니다. -->
	  <!-- value="로그인"은 버튼에 표시되는 것을 "로그인"으로 표시합니다. -->
    </form>
  </div>
</body>
</html>