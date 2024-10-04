<?php include "header.php" ?>
<div class="container">
  <div id="header">
    <button id="call-button"><i class="fas fa-phone"></i></button>
    <h1>الدردشة</h1>
  </div>
  <div id="chat-area">
    <div class="message confirmation-message">
      <h2>تم قبول طلبكم</h2>
        <p><strong>أيام الفصل:</strong> Sunday, Tuesday, Thursday</p>
        <p><strong>وقت الفصل:</strong> من الساعة 6:29 صباحاً إلى 3:30 مساءً</p>
        <p><strong>التكلفة:</strong> 750 ريال</p>
    </div>
  </div>
  <div id="input-area">
    <input type="text" id="message-input" placeholder="اكتب رسالتك هنا...">
    <button class="icon-button"><i class="fas fa-clock"></i></button>
    <button class="icon-button"><i class="fas fa-sticky-note"></i></button>
    <button class="icon-button"><i class="fas fa-map-marker-alt"></i></button>
    <button class="icon-button"><i class="fas fa-arrow-right"></i></button>
    <button class="icon-button"><i class="fas fa-arrow-left"></i></button>
  </div>
</div>

<style>
  #header {
    background-color: #7A52B3;
    color: #FFFFFF;
    padding: 10px;
    text-align: center;
    position: relative;
  }
  #call-button {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #FFFFFF;
    font-size: 24px;
    cursor: pointer;
  }
#chat-area {
    flex-grow: 1;
    overflow-y: auto;
    padding: 20px;
    padding-right: 5px;
    border: 1px solid #7A52B3;
    border-bottom: none;
}
  .message {
    margin-bottom: 20px;
  }
  .message:before {
      content:"";
      position:absolute;
  }
.confirmation-message {
    background-color: lavender;
    border: 2px solid #D3D3D3;
    padding: 20px;
    border-radius: 10px;
    width: 40%;
    text-align: center;
}
  .confirmation-message h2 {
    color: #7A52B3;
    margin-top: 0;
  }
  .confirmation-message p {
    font-size: 16px;
    color: #333;
    margin: 10px 0;
  }
  .btn-primary {
    background-color: #7A52B3;
    color: #FFFFFF;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
  }
  #input-area {
    display: flex;
    padding: 10px;
    background-color: #FFFFFF;
    border-top: 1px solid #7A52B3;
  }
  #message-input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #7A52B3;
    border-radius: 4px;
    margin-right: 10px;
  }
  .icon-button {
    background: none;
    border: none;
    font-size: 20px;
    margin: 0 5px;
    cursor: pointer;
    color: #7A52B3;
  }
</style>
    
<?php include "fotter.php" ?>