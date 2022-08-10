<?
  if ($_POST[email]) {
    // validate email address
    if (filter_var($_POST[email], FILTER_VALIDATE_EMAIL)) {
      // open connection to the DB
      $conn = mysqli_connect("localhost", "ulisse", "", "u_newsletter");
      // check if it already exists
      if (mysqli_num_rows(mysqli_query($conn,"
        SELECT
          *
        FROM
          alpha_news
        WHERE
          email = '".$_POST[email]."'
      ")) > 0)
        $added = 2;
      else {
        // insert in the DB
        mysqli_query($conn,"
          INSERT INTO
            alpha_news
          VALUES (
            '".$_POST[email]."'
          )
        ");
        $added = 1;
      }
    }
    else $added = 3;
  }
?>
<!doctype html>
<html>
  <head>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
    <title>Ulisse School</title>
		<style>
      @font-face {
        font-family: metro_font;
        src: url('font/weblysleekuil.ttf');
      }
      @font-face {
        font-family: metro_font_bold;
        src: url('font/weblysleekuisb.ttf');
      }
			html, body {
				height: 10%;
				padding: 0;
				margin: 0;
				border: 0;
				box-sizing: border-box;
				-webkit-box-sizing: border-box;
				-moz-box-sizing: border-box;
			}
			body {
        min-width: 900px;
				background: #eee;
        font-family: metro_font;
			}
      b {
        font-family: metro_font_bold;
      }
      a {
        text-decoration: none;
      }
			header {
				width: 100%;
				height: 62px;
				background: white;
				border-bottom: 2px solid #067082;
			}
			header #logo {
    		position: relative;
    		left: 0;
    		width: 220px;
    		height: 100%;
    		float: left;
    		background: url('img/logo_school.png');
    		background-size: auto 30px;
    		background-position: top 15px left 20px;
    		background-repeat: no-repeat;

    		border: none;
    		margin: 0 auto;
			}
      .right {
        float: right;
        text-align: right;
      }
			.btn, input[type=submit] {
				width: auto;
				background: #067082;

				padding: 12px 20px;
				border: none;
				border-radius: 4px;
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
				margin-top: 13px;
				margin-right: 15px;

				color: white;
				font-size: 12px;
				text-align: center;

				cursor: pointer;
				clear: left;
				text-transform: uppercase;
        font-family: metro_font;
        letter-spacing: 2px;
        display: inline-block;
      }
			.btn:hover, input[type=submit]:hover {
					background: #005968;
      }
      .btn.grey {
        background: #eee;
        color: #666;
      }
      .btn.grey:hover {
        background: #ccc;
      }
      article {
        text-align: center;
        padding: 30px 0 0 0;
      }
      article p {
        color: #333;
      }
      input[type=email] {
        width: 250px;
        padding: 13px;
        border: none;
				border-radius: 4px;
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
        font-size: 14px;
        margin-right: 15px;
      }
      #showcase {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 400px;
        background-image: url('img/browser.png');
        background-size: 800px auto;
        background-position: top center;
        background-repeat: no-repeat;
        text-align: center;
        overflow-y: hidden;
      }
      #showcase img {
        float: center;
        width: 800px;
        margin: 25px auto;
      }
		</style>
  </head>

  <body>
		<header>
      <div class="right">
        <a href="mailto:info@ulisse.school">
          <div class="btn grey">
            <span>CONTATTI</span>
          </div>
        </a>
        <a href="app/login">
          <div class="btn">
            <span>ACCEDI</span>
          </div>
        </a>
      </div>
			<div id="logo"></div>
		</header>
		<main>
	    <article>
				<p><b>Attualmente questo registro elettronico è in versione Alpha riservata esclusivamente ad alcuni utenti selezionati</b></p>
				<p>Immetti il tuo indirizzo email se desideri esere contattato al momento in cui saremo pronti ad accogliere richieste di adesione</p>

        <? if ($added == 3) { ?>
          <p><b><u>Indirizzo email non valido!</u></b></p>
        <? } elseif ($added == 2) { ?>
          <p><b><u>Indirizzo email già presente nella lista di attesa!</u></b></p>
        <? } elseif ($added == 1) { ?>
          <p><b><u>Sei stato aggiunto con successo alla lista di attesa!</u></b></p>
        <? } else { ?>
          <form method="post">
  					<input type="email" name="email" placeholder="Indirizzo email..." />
  					<input type="submit" value="Resta aggiornato" />
  				</form>
        <? } ?>
			</article>
			<div id="showcase">
        <img src="img/screen.png"/>
			</div>
		</main>
  </body>
</html>
