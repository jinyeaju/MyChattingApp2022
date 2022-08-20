<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Chat - chatting</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/chat.css">
</head>
<body>
<?php
  session_start();
  if(isset($_SESSION["userid"])){
    $userid = $_SESSION["userid"];
  }else{
      $userid = "";
      echo ("
        <script>
            alert('로그인이 필요한 페이지입니다. 로그인 후 이용 바랍니다.');
            location.href = './main.html';
        </script>
      ");
  }
  if(isset($_SESSION["username"])){
    $username = $_SESSION["username"];
  }else{
      $username = "";
  }
?>
  <span class="hide" id="user_id"><?=$userid?></span>
  <span class="hide" id="user_name"><?=$username?></span>


  <header>
    <div class="logo">
      <a href=""><img src="./img/chat_logo.svg" alt="mychat logo"></a>

      <img class="logout_btn" src="./img/logout.svg" title="logout" alt="logout" onclick="location.href='logout.php'">
    </div>
  </header>

  <section>
    <!-- 채팅 내용과 입력상자를 담을 장소 -->
    <article>
      <ul id="messages">
        
      </ul>
    </article>
    <form id="chat_msg" name="chat_msg" onsubmit="return sendMessage();"> <!--후처리 함수-->
      <textarea name="txtmsg" id="message" placeholder="메세지를 입력하세요" autocomplete="off"></textarea>
      <input type="submit" value="전송">
    </form>
  </section>
  


  <script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-database.js"></script>
  <script>
    // Import the functions you need from the SDKs you need
    // import { initializeApp } from "https://www.gstatic.com/firebasejs/9.8.4/firebase-app.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    const firebaseConfig = {
      apiKey: "AIzaSyBZ64jRbJhQq7Kt5Xy6OtNY3bdMjBlldfI",
      authDomain: "mychatting2022.firebaseapp.com",
      databaseURL: "https://mychatting2022-default-rtdb.asia-southeast1.firebasedatabase.app",
      //databaseURL은 서버 설정시 반드시 작성해야 함
      projectId: "mychatting2022",
      storageBucket: "mychatting2022.appspot.com",
      messagingSenderId: "801320465083",
      appId: "1:801320465083:web:893ad89890ad616891ef9e",
      measurementId: "G-JKZHCDCP86"
    };

    // Initialize Firebase
    // const app = initializeApp(firebaseConfig);

    firebase.initializeApp(firebaseConfig);

    // console.log(app);
  
    const userId = document.querySelector("#user_id").innerText; //사용자 아이디 가져오기
    const userName = document.querySelector("#user_name").innerText; //사용자 이름 가져오기

    console.log(userId);
    console.log(userName);

    //#0. 브라우저 화면이 열리면서 포커싱을 입력상자에 맞춘다.
    document.querySelector("#message").focus();

    //#1. 메세지 보내기
    //#1-1. 전송버튼 클릭시 메세지 보내기 진행
    function sendMessage(){
      const message = document.querySelector("#message").value;
      
      if(message.trim() == ""){
        console.log("메세지 없음");
        alert("메세지 입력란에 메세지를 입력해주세요.");
      }else{
        firebase.database().ref("messages").push().set({
          "senderId" : userId,
          "senderName" : userName,
          "message" : message
        });
      }
      
      document.querySelector("#message").value = "";
      document.querySelector("#message").focus();
      
      return false;
    }
    
    //#1-2. 입력상자에서 입력 후 엔터 키보드 이벤트를 통해, 메세지 보내기 진행
    document.querySelector("#message").addEventListener("keydown", (evt) => {
      if(evt.keyCode == "13"){
        sendMessage();
        setTimeout(() => {
          document.querySelector("#message").value = "";
          document.querySelector("#message").focus();
        }, 10);
      }
    });

    //#2. 메세지 리스트 가져오기 (내가 보낸 메세지와 상대방들이 보낸 메세지에 대한 구분)
    firebase.database().ref("messages").on("child_added", function(snapshot){ //child_added는 공식 명칭
      console.log(snapshot);
      console.log(snapshot.key); //고유넘버
      console.log(snapshot.val().senderId); //전송한 사람의 아이디
      console.log(snapshot.val().senderName); //전송한 사람의 이름
      console.log(snapshot.val().message); //전송 메세지 내용

      if(snapshot.val().senderId == userId){ //내가 작성한 메세지일 때
        let html = `
        <li class="mine" id="message-${snapshot.key}">
          <p>${snapshot.val().senderName}</p>
          <span>${snapshot.val().message}
            <button data-id="${snapshot.key}" onclick="deleteMessage(this)">×</button>
          </span>
        </li>
        `;
        document.querySelector("#messages").innerHTML += html;
      }else{ //타인이 작성한 메세지
        let html = `
        <li class="other" id="message-${snapshot.key}">
        <p>${snapshot.val().senderName}</p>
        <span>${snapshot.val().message}</span>
        </li>
        `;
        document.querySelector("#messages").innerHTML += html;
      }
      //신규 입력 메세지가 리스트 공간의 하단에 위치함과 동시에 스크롤바를 하단까지 내리는 역할
      const chatScroll = document.querySelector("#messages");
      chatScroll.scrollTop = chatScroll.scrollHeight;
    });

    //#3. 메시지 삭제 : 삭제 기능 함수문(내가 보낸 메시지 중에서 "×" 버튼 클릭시)
    function deleteMessage(self){
      const messageId = self.getAttribute("data-id");
      firebase.database().ref("messages").child(messageId).remove();
    }

    //#4. 삭제된 메시지에 대한 표현 넣기(문구 표현은  "삭제된 메시지입니다.")
    firebase.database().ref("messages").on("child_removed", function(snapshot){
      //문서상에 삭제된 항목을 대체 문구로 변경
      if(snapshot.val().senderId == userId){
        document.querySelector(`#message-${snapshot.key}`).innerHTML = `
          <li class="mine"><span class="deleteMsg">삭제된 메시지입니다.</span></li>
        `;
      }else{
        document.querySelector(`#message-${snapshot.key}`).innerHTML = `
          <li class="other"><span class="deleteMsg">삭제된 메시지입니다.</span></li>
        `;
      }
    });

  </script>


  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- function -->
  <script src="./js/chat.js"></script>
</body>
</html>