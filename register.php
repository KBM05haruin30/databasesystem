<!-- 회원가입 php 파일 -->
<?php
// 데이터베이스에 연결하기 위한 세팅
$host = "localhost";
$username = "root";
$password = "Haruin0530!";
$dbname = "scheduling_db";

// $conn 변수를 사용하고 new mysqli함수를 이용해 MySQL 데이터베이스에 연결한다.
$conn = new mysqli($host, $username, $password, $dbname);

// 데이터베이스에 연결되었는지 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자가 폼을 제출했는지 확인하는 조건문
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // 데이터베이스에서 이메일이 이미 존재하는지 확인하는 SQL 쿼리를 실행한다.
    $emailExistsQuery = "SELECT * FROM user WHERE Email = '$email'";
    $result = $conn->query($emailExistsQuery);

    if ($result->num_rows > 0) {
        // 쿼리 결과의 행 수가 0보다 큰 경우, 이미 해당 이메일이 데이터베이스에 존재하는 것으로 판단하여 오류 메시지를 출력
        echo "이미 존재하는 이메일입니다.";
    } else {
        // 쿼리 결과의 행 수가 0인 경우, 새로운 사용자 정보를 'user'테이블에 삽입하는 SQL 쿼리 실행
        $sql = "INSERT INTO user (Name, Email, Password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            // 쿼리 실행이 성공한 경우, 회원가입이 성공한 것으로 판단하여 index.php로 리디렉션한다.
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
//데이터베이스 연결을 닫는다.
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>회원가입</title>
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
    
    input[type="text"],
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
    <h2>회원가입</h2>
  </header>
  
  <div class="container">
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <label for="name">이름</label>
      <input type="text" name="name" required><br>

      <label for="email">이메일</label>
      <input type="email" name="email" required><br>

      <label for="password">비밀번호</label>
      <input type="password" name="password" required><br>

      <input type="submit" value="회원가입">
    </form>
  </div>
</body>
</html>