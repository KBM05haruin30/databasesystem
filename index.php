<!-- 문서가 HTML5 문서임을 지정하는 문서 유형 선언입니다. -->
<!DOCTYPE html> 
<!-- HTML문서의 루트 요소입니다. 여기에는 웹 페이지의 다른 모든 요소가 포함됩니다. -->
<html>
<!-- HTML문서의 헤드 섹션을 정의하는데 사용됩니다. -->
<head>
  <!-- 브라우저의 탭에 표시되는 웹 페이지의 제목을 설정합니다. -->
  <title>스케쥴링 도우미</title>
  <!-- 웹 페이지 내 HTML 요소의 스타일을 지정하기 위한 CSS 규칙을 정의하는 데 사용됩니다. -->
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
    
    p {
      margin-bottom: 20px;
    }
    
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }
    
    a {
      color: #337ab7;
      text-decoration: none;
    }
    
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<!-- HTML 문서의 주요 내용을 나타냅니다. -->
<body>
  <!-- 웹 페이지의 헤더 섹션 역할을 합니다. -->
  <header>
    <h2>스케쥴링 도우미</h2><!-- 섹션 제목에 사용되는 수준을 나타냅니다. 헤더 섹션 중앙에 "스케쥴링 도우미"라고 나타납니다. -->
  </header>
  
  <!-- HTML요소 집합을 그룹화하고 격리하는 일반 컨테이너입니다. -->
  <div class="container">
    <p>스케쥴링 도우미 홈페이지입니다!</p><!-- 텍스트 단락을 나타냅니다. -->
    <p><a href="login.php">로그인</a> 또는 <a href="register.php">회원 가입</a> 을 해주세요.</p>
	<!-- <a href="login.php">로그인</a>는 하이퍼링크를 정의합니다. href 속성은 링크가 연결되는 URL을 지정하는데 여기에서는 -->
	<!-- login.php와 register.php가 됩니다. 링크를 출력 시 설정된 페이지로 이동합니다. -->
  </div>
</body>
</html>