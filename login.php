<?php

session_start();
include "config.php";

if (isset($_SESSION['user'])) {
    header("Location: " . ($_SESSION['user']['perfil'] == 'admin' ? "admin.php" : "dashboard.php"));
    exit();
}


$msgErro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM utilizadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user'] = $user;
        header("Location: " . ($user['perfil'] == 'admin' ? "admin.php" : "dashboard.php"));
        exit();
    } else {
        $msgErro = "Login inválido.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login com Peixes</title>
    <style>
       @import url('https://fonts.googleapis.com/css?family=Oswald|Roboto');

@keyframes btn {
  0%, 100% {
    transform: scale(1);
  }
  70% {
    transform: scale(1);
  }
  80% {
    transform: scale(1.04);
  }
  90% {
    transform: scale(0.96);
  }
}

@keyframes fish {
  0%, 70% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

html, body {
  height: 100%;
  margin: 0;
  font-family: 'Roboto', sans-serif;
  color: #515a6e;
  background-color: #d5eafc;
  overflow: hidden;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  display: flex;
  align-items: center;
  justify-content: center;
}

a {
  cursor: pointer;
  color: #000;
  text-decoration: none;
  transition: all 0.3s;
}

a:hover {
  color: rgba(255, 80, 80, 1); /* $color */
}

.fish, .fish-shadow {
  width: 600px;
  height: auto;
  position: absolute;
  top: -120px;
  left: -143px;
  animation: fish 3s forwards ease-in-out;
}

.fish path {
  fill: #fff;
  transition: fill 1,6s;
}

/* .fish path:hover {
  fill: #ff6100; /* $colorSecondary */
/* } */ 

.line { 
  fill: none;
}

.fish-shadow-con {
  opacity: 0.1;
  filter: blur(10px);
  position: absolute;
  top: 0;
  left: 0;
}

.fish-shadow {
  top: -0px;
  left: -134px;
  opacity: 0.4;
  filter: url("#goo");
  -webkit-filter: url("#goo");
}

.fish-shadow path {
  fill: #211922;
  transition: fill 0.4s;
}

.fish-shadow path:hover {
  fill: #211922;
}

.container {
  position: relative;
  background-color: #fafafe;
  border-radius: 10px;
  margin: 400px;
  padding: 25px 20px 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  width: 320px;
  box-sizing: border-box;
}

.container::before {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  right: 0;
  height: 60%;
  background-color: #fafafe;
  border-radius: 10px;
  z-index: 2;
}

.card {
  position: relative;
  z-index: 2;
}

.card_title {
  font-size: 24px;
  margin: 0;
}

.card_title-info {
  font-size: 14px;
  margin: 7px 0 10px;
}

.card_button {
  border-radius: 4px;
  border: none;
  outline: none;
  width: 100%;
  padding: 0 15px;
  font-size: 18px;
  line-height: 36px;
  font-weight: 500;
  margin: 25px 0 10px;
  color: #fff;
  background: linear-gradient(#ff6100, rgba(255, 80, 80, 1));
  box-shadow: 0 2px 12px -3px rgba(255, 80, 80, 1);
  animation: btn 6.0s 3s infinite ease-in-out;
  opacity: 0.9;
  transition: all 0.3s;
}

.card_button:hover {
  opacity: 1;
  box-shadow: 0 2px 2px -3px rgba(255, 80, 80, 1);
}

.card_info {
  font-size: 14px;
}

.input {
  display: flex;
  flex-direction: column-reverse;
  position: relative;
  padding-top: 10px;
}

.input + .input {
  margin-top: 10px;
}

.input_label {
  color: #8597a3;
  position: absolute;
  top: 20px;
  transition: all 0.3s;
}

.input_field {
  border: 0;
  padding: 0;
  z-index: 1;
  background-color: transparent;
  border-bottom: 2px solid #eee;
  font: inherit;
  font-size: 14px;
  line-height: 30px;
}

.input_field:focus,
.input_field:valid {
  outline: 0;
  border-bottom-color: #665856;
}

.input_field:focus + .input_label,
.input_field:valid + .input_label {
  color: #665856;
  transform: translateY(-25px);
  transition: all 0.3s;
}

.input_eye {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 36px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.input_eye svg {
  width: 24px;
  height: auto;
  stroke: #8597a3;
}

.link {
  position: absolute;
  bottom: 20px;
  right: 20px;
  z-index: 3;
}

.rabbit {
  width: 50px;
  height: 50px;
  fill: #fff;
}

    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1 class="card_title">Faça login na sua conta</h1>
        <p class="card_title-info">By Alexandre João/Tomas Marques</p>
        <form class="card_form" action="" method="POST">
    <div class="input">
        <input type="text" class="input_field" name="email" required />
        <label class="input_label">Email</label>
    </div>
    <div class="input">
        <input type="password" class="input_field" name="senha" required />
        <label class="input_label">Password</label>
        <span class="input_eye">
            <!-- ... SVG ... -->
        </span>
    </div>
    <button class="card_button" type="submit">Login</button>
</form>

        <div class="card_info">
            <p>Not registered? <a href="1_register.php">Create an account</a></p>
        </div>
    </div>
    <div class="fish-shadow-con">
        <svg class="fish-shadow" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMin slice" viewBox="0 0 743 645">



            <g id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="square">
            <path d="M177.367 337.5
         L182.709 357.739
         C190 390 220 420 260 440
         L310 460
         C370 470 440 450 480 410
         C520 370 540 320 520 270
         C500 220 460 190 420 180
         L375 178.946
         C360 175 340 170 320 165
         C250 140 180 220 177.367 337.568
         Z"
      class="line" id="Line" />


                <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Group" transform="translate(-103.000000, 3.000000)" fill="#F2AFAF">
                        <g id="l-1" transform="translate(118.396084, 37.985234) rotate(-89.000000) translate(-118.396084, -37.985234) translate(104.396084, 23.985234)">
                            <path d="M14.1723611,27.5145257 C19.9713509,27.5145257 24.6723611,22.8135155 24.6723611,17.0145257 C24.6723611,15.8258883 28.1981217,2.09701504 27.8341336,1.03166708 C26.4223375,-3.10048431 18.7827136,6.51452565 14.1723611,6.51452565 C9.87015746,6.51452565 1.67467528,-2.67194974 0.0523652038,1.03166708 C-0.512055182,2.32019808 3.67236107,15.5177394 3.67236107,17.0145257 C3.67236107,22.8135155 8.3733712,27.5145257 14.1723611,27.5145257 Z" />
                        </g>
                    </g>
                    <animateMotion dur="6s" begin="0s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                        <mpath xlink:href="#Line" />
                    </animateMotion>
                </g>

                <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Group" transform="translate(-71.000000, 3.000000)">
                        <g id="l-2" transform="translate(96.916890, 37.437926) rotate(-89.000000) translate(-96.916890, -37.437926) translate(60.416890, 13.437926)">
                            <ellipse id="Oval" cx="36.5" cy="24" rx="16.5" ry="24" />
                            <path d="M52.8409492,28.4193415 C56.9830848,28.4193415 67.8409492,23.8671728 67.8409492,17.7920406 C67.8409492,16.3338966 72.7364131,8.06546148 72.3851131,6.79204055 C71.2727759,2.75994931 63.4888957,6.79204055 60.3409492,6.79204055 C56.1988135,6.79204055 52.8409492,11.7169083 52.8409492,17.7920406 C52.8409492,23.8671728 48.6988135,28.4193415 52.8409492,28.4193415 Z" id="Oval" />
                            <path d="M1.84094917,28.4193415 C5.98308479,28.4193415 16.8409492,23.8671728 16.8409492,17.7920406 C16.8409492,16.3338966 21.7364131,8.06546148 21.3851131,6.79204055 C20.2727759,2.75994931 12.4888957,6.79204055 9.34094917,6.79204055 C5.19881354,6.79204055 1.84094917,11.7169083 1.84094917,17.7920406 C1.84094917,23.8671728 -2.30118646,28.4193415 1.84094917,28.4193415 Z" id="Oval" transform="translate(10.701577, 16.709671) scale(-1, 1) translate(-10.701577, -16.709671) " />
                        </g>
                    </g>
                    <animateMotion dur="6s" begin="0.1s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                        <mpath xlink:href="#Line" />
                    </animateMotion>
                </g>

                <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Group" transform="translate(-58.000000, 3.000000)" fill="#D8D8D8">
                        <g id="l-3-" transform="translate(83.924588, 36.883456) rotate(-89.000000) translate(-83.924588, -36.883456) translate(69.424588, 12.883456)">
                            <path d="M14.1723611,48 C19.4148996,48 23.903645,41.8034457 25.7601702,33.016917 C26.3483828,30.2330353 28.8341336,32.1407168 28.8341336,28.9515567 C28.8341336,15.6967227 21.0759204,1.42108547e-14 14.1723611,1.42108547e-14 C7.2688017,1.42108547e-14 3.55271368e-15,13.745166 3.55271368e-15,27 C3.55271368e-15,29.9816317 1.95554677,29.8362716 2.47309478,32.4701788 C4.25630479,41.5452976 8.82173488,48 14.1723611,48 Z" id="l-3" />
                        </g>
                    </g>
                    <animateMotion dur="6s" begin="0.2s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                        <mpath xlink:href="#Line" />
                    </animateMotion>
                </g>

                <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Group" transform="translate(-48.000000, 3.000000)" fill="#D8D8D8">
                        <g id="l-4-" transform="translate(73.917498, 37.202362) rotate(-89.000000) translate(-73.917498, -37.202362) translate(61.417498, 13.202362)">
                            <path d="M12.2878333,48 C16.8332608,48 20.7251285,41.8034457 22.3347878,33.016917 C22.8447845,30.2330353 25,32.1407168 25,28.9515567 C25,15.6967227 18.2734123,1.42108547e-14 12.2878333,1.42108547e-14 C6.30225431,1.42108547e-14 0,13.745166 0,27 C0,29.9816317 1.69551372,29.8362716 2.14424232,32.4701788 C3.69033525,41.5452976 7.64869079,48 12.2878333,48 Z" id="l-4" />
                        </g>
                    </g>
                    <animateMotion dur="6s" begin="0.3s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                        <mpath xlink:href="#Line" />
                    </animateMotion>
                </g>

                <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Group" transform="translate(-32.000000, 3.000000)" fill="#D8D8D8">
                        <g id="l-5-" transform="translate(58.922677, 36.774735) rotate(-89.000000) translate(-58.922677, -36.774735) translate(49.422677, 12.774735)">
                            <path d="M9.33875331,48 C12.7932782,48 15.7510977,41.8034457 16.9744387,33.016917 C17.3620362,30.2330353 19,32.1407168 19,28.9515567 C19,15.6967227 13.8877933,1.42108547e-14 9.33875331,1.42108547e-14 C4.78971327,1.42108547e-14 0,13.745166 0,27 C0,29.9816317 1.28859043,29.8362716 1.62962417,32.4701788 C2.80465479,41.5452976 5.813005,48 9.33875331,48 Z" id="l-4" />
                        </g>
                    </g>
                    <animateMotion dur="6s" begin="0.4s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                        <mpath xlink:href="#Line" />
                    </animateMotion>
                </g>

                <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Group" transform="translate(-1.000000, -2.000000)" fill="#D8D8D8">
                        <g id="l-6" transform="translate(23.080623, 42.228667) rotate(-89.000000) translate(-23.080623, -42.228667) translate(-17.919377, 20.728667)">
                            <path d="M41.7607406,42.4419194 C43.7607286,42.4419194 43.2381119,38.329711 43.2310921,38.0763828 C43.1415394,34.8446384 44.2788431,34.4419194 41.6451883,34.4419194 C39.0115336,34.4419194 39.7260112,34.2957436 39.8222402,37.76842 C39.82926,38.0217482 39.7195178,42.4419194 41.7607406,42.4419194 Z" id="Oval" />
                            <path d="M23.6342467,37.7478622 C27.3113017,37.7478622 36.9500408,30.5559331 36.9500408,20.9578882 C36.9500408,18.65418 41.2958401,5.59095627 40.9839843,3.57909038 C39.9965407,-2.7911731 32.569021,0.0196920288 29.4031545,6.69804407 C26.912494,11.9520586 23.6342467,11.3598432 23.6342467,20.9578882 C23.6342467,30.5559331 19.9571917,37.7478622 23.6342467,37.7478622 Z" id="Oval" transform="translate(31.500000, 18.873931) scale(-1, 1) translate(-31.500000, -18.873931) " />
                            <path d="M43.2310921,38.0763828 C46.9081472,38.0763828 56.5468863,30.8844538 56.5468863,21.2864088 C56.5468863,18.9827007 60.8926856,5.91947694 60.5808297,3.90761104 C59.5933862,-2.46265244 52.1658664,0.348212694 49,7.02656473 C46.5093394,12.2805793 43.2310921,11.6883638 43.2310921,21.2864088 C43.2310921,30.8844538 39.5540371,38.0763828 43.2310921,38.0763828 Z" id="Oval" />
                            <rect id="Rectangle" fill-opacity="0" x="0.186684949" y="18.7699638" width="81" height="9" />
                        </g>
                    </g>
                    <animateMotion dur="6s" begin="0.5s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                        <mpath xlink:href="#Line" />
                    </animateMotion>
                </g>

        </svg>
    </div>
    <svg class="fish" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMin slice" viewBox="0 0 743 645">

        <g id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="square">
            <path d="M177.367 337.568L182.709 357.739C198.517 417.421 249.748 460.995 311.193 467.019L421.508 477.834C478.237 483.396 532.831 454.649 560.346 404.729C607.09 319.923 557.549 214.182 462.47 195.822L375.079 178.946C368.369 177.651 361.766 175.854 355.324 173.572C251.651 136.837 149.205 231.245 177.367 337.568Z" class="line" id="Line" />

            <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Group" transform="translate(-103.000000, 3.000000)" fill="#F2AFAF">
                    <g id="l-1" transform="translate(118.396084, 37.985234) rotate(-89.000000) translate(-118.396084, -37.985234) translate(104.396084, 23.985234)">
                        <path d="M14.1723611,27.5145257 C19.9713509,27.5145257 24.6723611,22.8135155 24.6723611,17.0145257 C24.6723611,15.8258883 28.1981217,2.09701504 27.8341336,1.03166708 C26.4223375,-3.10048431 18.7827136,6.51452565 14.1723611,6.51452565 C9.87015746,6.51452565 1.67467528,-2.67194974 0.0523652038,1.03166708 C-0.512055182,2.32019808 3.67236107,15.5177394 3.67236107,17.0145257 C3.67236107,22.8135155 8.3733712,27.5145257 14.1723611,27.5145257 Z" />
                    </g>
                </g>
                <animateMotion dur="6s" begin="0s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                    <mpath xlink:href="#Line" />
                </animateMotion>
            </g>

            <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Group" transform="translate(-71.000000, 3.000000)" fill="#ff5050">
                    <g id="l-2" transform="translate(96.916890, 37.437926) rotate(-89.000000) translate(-96.916890, -37.437926) translate(60.416890, 13.437926)">
                        <ellipse id="Oval" cx="36.5" cy="24" rx="16.5" ry="24" />
                        <path d="M52.8409492,28.4193415 C56.9830848,28.4193415 67.8409492,23.8671728 67.8409492,17.7920406 C67.8409492,16.3338966 72.7364131,8.06546148 72.3851131,6.79204055 C71.2727759,2.75994931 63.4888957,6.79204055 60.3409492,6.79204055 C56.1988135,6.79204055 52.8409492,11.7169083 52.8409492,17.7920406 C52.8409492,23.8671728 48.6988135,28.4193415 52.8409492,28.4193415 Z" id="Oval" />
                        <path d="M1.84094917,28.4193415 C5.98308479,28.4193415 16.8409492,23.8671728 16.8409492,17.7920406 C16.8409492,16.3338966 21.7364131,8.06546148 21.3851131,6.79204055 C20.2727759,2.75994931 12.4888957,6.79204055 9.34094917,6.79204055 C5.19881354,6.79204055 1.84094917,11.7169083 1.84094917,17.7920406 C1.84094917,23.8671728 -2.30118646,28.4193415 1.84094917,28.4193415 Z" id="Oval" transform="translate(10.701577, 16.709671) scale(-1, 1) translate(-10.701577, -16.709671) " />
                    </g>
                </g>
                <animateMotion dur="6s" begin="0.1s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                    <mpath xlink:href="#Line" />
                </animateMotion>
            </g>

            <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Group" transform="translate(-58.000000, 3.000000)" fill="#D8D8D8">
                    <g id="l-3-" transform="translate(83.924588, 36.883456) rotate(-89.000000) translate(-83.924588, -36.883456) translate(69.424588, 12.883456)">
                        <path d="M14.1723611,48 C19.4148996,48 23.903645,41.8034457 25.7601702,33.016917 C26.3483828,30.2330353 28.8341336,32.1407168 28.8341336,28.9515567 C28.8341336,15.6967227 21.0759204,1.42108547e-14 14.1723611,1.42108547e-14 C7.2688017,1.42108547e-14 3.55271368e-15,13.745166 3.55271368e-15,27 C3.55271368e-15,29.9816317 1.95554677,29.8362716 2.47309478,32.4701788 C4.25630479,41.5452976 8.82173488,48 14.1723611,48 Z" id="l-3" />
                    </g>
                </g>
                <animateMotion dur="6s" begin="0.2s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                    <mpath xlink:href="#Line" />
                </animateMotion>
            </g>

            <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Group" transform="translate(-48.000000, 3.000000)" fill="#D8D8D8">
                    <g id="l-4-" transform="translate(73.917498, 37.202362) rotate(-89.000000) translate(-73.917498, -37.202362) translate(61.417498, 13.202362)">
                        <path d="M12.2878333,48 C16.8332608,48 20.7251285,41.8034457 22.3347878,33.016917 C22.8447845,30.2330353 25,32.1407168 25,28.9515567 C25,15.6967227 18.2734123,1.42108547e-14 12.2878333,1.42108547e-14 C6.30225431,1.42108547e-14 0,13.745166 0,27 C0,29.9816317 1.69551372,29.8362716 2.14424232,32.4701788 C3.69033525,41.5452976 7.64869079,48 12.2878333,48 Z" id="l-4" />
                    </g>
                </g>
                <animateMotion dur="6s" begin="0.3s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                    <mpath xlink:href="#Line" />
                </animateMotion>
            </g>

            <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Group" transform="translate(-32.000000, 3.000000)" fill="#D8D8D8">
                    <g id="l-5-" transform="translate(58.922677, 36.774735) rotate(-89.000000) translate(-58.922677, -36.774735) translate(49.422677, 12.774735)">
                        <path d="M9.33875331,48 C12.7932782,48 15.7510977,41.8034457 16.9744387,33.016917 C17.3620362,30.2330353 19,32.1407168 19,28.9515567 C19,15.6967227 13.8877933,1.42108547e-14 9.33875331,1.42108547e-14 C4.78971327,1.42108547e-14 0,13.745166 0,27 C0,29.9816317 1.28859043,29.8362716 1.62962417,32.4701788 C2.80465479,41.5452976 5.813005,48 9.33875331,48 Z" id="l-4" />
                    </g>
                </g>
                <animateMotion dur="6s" begin="0.4s" repeatCount="indefinite" rotate="auto" fill="freeze"        >
                    <mpath xlink:href="#Line" />
                </animateMotion>
            </g>

            <g xmlns="http://www.w3.org/2000/svg" id="Artboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Group" transform="translate(-1.000000, -2.000000)" fill="#D8D8D8">
                    <g id="l-6" transform="translate(23.080623, 42.228667) rotate(-89.000000) translate(-23.080623, -42.228667) translate(-17.919377, 20.728667)">
                        <path d="M41.7607406,42.4419194 C43.7607286,42.4419194 43.2381119,38.329711 43.2310921,38.0763828 C43.1415394,34.8446384 44.2788431,34.4419194 41.6451883,34.4419194 C39.0115336,34.4419194 39.7260112,34.2957436 39.8222402,37.76842 C39.82926,38.0217482 39.7195178,42.4419194 41.7607406,42.4419194 Z" id="Oval" />
                        <path d="M23.6342467,37.7478622 C27.3113017,37.7478622 36.9500408,30.5559331 36.9500408,20.9578882 C36.9500408,18.65418 41.2958401,5.59095627 40.9839843,3.57909038 C39.9965407,-2.7911731 32.569021,0.0196920288 29.4031545,6.69804407 C26.912494,11.9520586 23.6342467,11.3598432 23.6342467,20.9578882 C23.6342467,30.5559331 19.9571917,37.7478622 23.6342467,37.7478622 Z" id="Oval" transform="translate(31.500000, 18.873931) scale(-1, 1) translate(-31.500000, -18.873931) " />
                        <path d="M43.2310921,38.0763828 C46.9081472,38.0763828 56.5468863,30.8844538 56.5468863,21.2864088 C56.5468863,18.9827007 60.8926856,5.91947694 60.5808297,3.90761104 C59.5933862,-2.46265244 52.1658664,0.348212694 49,7.02656473 C46.5093394,12.2805793 43.2310921,11.6883638 43.2310921,21.2864088 C43.2310921,30.8844538 39.5540371,38.0763828 43.2310921,38.0763828 Z" id="Oval" />
                        <rect id="Rectangle" fill-opacity="0" x="0.186684949" y="18.7699638" width="81" height="9" />
                    </g>
                </g>
                <animateMotion dur="6s" begin="0.5s" repeatCount="indefinite" rotate="auto" fill="freeze">
                    <mpath xlink:href="#Line" />
                </animateMotion>
            </g>

    </svg>

</div>
<a class="link" href="https://codepen.io/Anna_Batura/" target="_blank"><svg class="rabbit" version="1.2" viewBox="0 0 600 600">
        <path d="m 335.94313,30.576451 c -9.79312,-0.146115 -17.39091,4.439466 -17.39091,13.789758 0,11.508038 -2.91019,60.415461 1.40532,76.238951 4.31553,15.82355 21.58583,38.97215 34.51834,54.67597 10.06946,12.22726 4.34772,41.69626 4.34772,56.0813 0,14.38499 -2.89751,25.9107 -8.65153,25.9107 -5.75402,0 -14.35971,5.75217 -20.11373,11.50612 -5.75395,5.75402 -11.51588,12.95631 -18.70841,7.20229 -7.19251,-5.75402 -20.15388,-11.49441 -43.16987,-15.80992 -23.01609,-4.31551 -61.84129,-0.0234 -86.29583,8.60763 -24.45458,8.63104 -76.25857,56.11061 -90.643535,77.6882 -14.385056,21.5775 -15.799189,87.73247 -14.36068,97.80193 1.438509,10.06953 -2.908267,17.28255 -10.100778,8.65153 -7.192459,-8.63104 -12.911438,-4.30381 -12.911438,-4.30381 0,0 -7.202292,14.37045 -7.202292,21.56298 0,7.19244 2.854564,14.36068 2.854564,14.36068 0,0 -11.506099,8.65056 -11.506099,14.40458 0,5.75397 11.515881,15.83044 18.708391,24.46146 7.192546,8.63097 31.651182,25.89997 41.720624,24.46148 10.069543,-1.43851 28.775063,-0.0121 35.967573,4.3038 7.19253,4.31551 24.44687,10.06761 46.02443,11.5061 21.57752,1.43851 81.97845,5.75307 97.80193,5.75307 15.82357,0 20.1675,-2.86435 27.35996,-10.05688 7.19253,-7.19245 -5.78527,-15.84115 -10.10079,-25.9107 -4.31551,-10.06946 14.40363,-7.16912 20.15765,-8.60763 5.75402,-1.43849 21.59424,-11.5061 31.66376,-11.5061 10.06953,0 8.6165,10.05589 21.56298,15.80993 12.94654,5.75393 31.63939,24.43902 46.02443,27.31602 14.38497,2.87695 47.47173,0.0121 58.97979,-4.30381 11.50797,-4.31551 10.06946,-14.37044 0,-21.56297 -10.06955,-7.19244 -34.50663,-20.16742 -38.82214,-27.35994 -4.31551,-7.19246 -5.74329,-15.81969 1.44924,-23.01224 7.19251,-7.19252 14.35876,-4.30292 25.86678,-10.05685 11.50806,-5.75402 15.80992,-23.04354 15.80992,-33.11301 0,-10.06953 1.36928,-21.01352 5.75307,-27.31602 3.67345,-5.28128 5.10015,-22.13212 5.30499,-33.64009 0.21874,-12.28864 -5.29329,-15.24871 -9.60881,-22.44122 -4.31543,-7.19246 4.30285,-17.25917 10.05687,-17.25917 5.75402,0 31.65108,-4.33602 41.72062,-8.65153 10.06946,-4.31546 20.16744,-23.03273 27.35995,-31.66377 7.19246,-8.63095 1.41799,-27.31512 -8.65154,-33.06907 -10.06954,-5.75402 -10.07746,-21.59431 -18.70841,-31.66377 -8.63103,-10.06953 -18.68507,-31.62961 -27.31604,-38.82213 -8.63101,-7.19253 -28.77502,-12.95535 -35.96755,-12.95535 -7.19253,0 -11.50612,9e-4 -11.50612,-5.75306 0,-5.75402 -1.44924,-12.9203 -1.44924,-25.86678 0,-12.94655 -16.24344,-68.464566 -37.3729,-102.149659 -4.40799,-7.027282 -11.5581,-5.405316 -20.15765,-2.898485 -5.69412,1.659863 -8.60761,4.35564 -8.60761,23.056136 0,18.700566 -11.50515,-0.03133 -17.25917,-10.100794 -5.75403,-10.069512 -15.86265,-21.58444 -28.80918,-24.461458 -2.42749,-0.539415 -4.76669,-0.800692 -7.02665,-0.834399 z" id="rabbit"></path>

        <defs>
            <filter id="goo">
                <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
                <feBlend in="SourceGraphic" in2="goo" />
            </filter>
        </defs>

    </svg></a> </div>




