<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Super Chat</title>
  <link rel="stylesheet" href="css/main.css">

</head>

<body>

  <div id="mainWrapper">
    
      <div id="leftWrapper">
        <div id="outputWrapper"></div>
    
        <div id="inputWrapper">
            <input id="textInput" type="text"><input id="sendButton" type="button" value="Wyślij">
        </div>
      </div>

      <div id="rightWrapper">

        <div id="activeUsersWrapper">
          <h1>Aktywni użyktownicy</h1>
          <ul>
            <li>końik</li>
            <li>mentos18</li>
          </ul>
        </div>

      </div>

  </div>


  <?php 

    set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');

    $messages = require_once 'messages/load_old_messages.php';
    echo "<template id='old_mes_data'>$messages</template>";

    require_once 'accounts/verify_guest.php';

    echo "<template id='guest_data'>$result[0]%$result[1]</template>";

  ?>


  <script defer src="js/global/s1_global.js"></script>
  <script defer src="js/global/s2_cookies.js"></script>

  <script defer src="js/messages/s1_messages_global.js"></script>
  <script defer src="js/messages/s2_load_old_messages.js"></script>
  <script defer src="js/messages/s3_send_message.js"></script>
  <script defer src="js/messages/s4_load_new_messages.js"></script>

</body>

</html>