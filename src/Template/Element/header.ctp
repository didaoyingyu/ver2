<!DOCTYPE html>
<html lang="en">
  <head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FlashCard Game</title>
     <?= $this->Html->meta('icon') ?>

    <!-- Bootstrap core CSS -->
    <?= $this->Html->css('bootstrap.min.css') ?>

    <!-- Custom styles for this template -->
    <?= $this->Html->css('custom.css') ?>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="header-background">
      <div class="container">
        <div class="row">
          <div class="col-md-12 header-title-con">
            <h3><?php echo $headerTitle; ?></h3>
          </div>
        </div>
      </div>
    </div>
    <nav class="navbar navbar-inverse" id="customNavBar">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div id="navbar" class="collapse navbar-collapse">

            <ul class="nav navbar-nav">

              <li><?= $this->Html->link('Home','/',['class'=>'active']) ?></li>
              <li><?= $this->Html->link('Register','/register') ?></li>
            </ul> 
          </div><!--/.nav-collapse -->
        </div>
        </nav>