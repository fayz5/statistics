<nav class='navbar navbar-default navbar-fixed-top'>
    <div class='container'>
      <div class='navbar-header'>
        <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#mainNavbar'>
          <span class='icon-bar'></span>
          <span class='icon-bar'></span>
          <span class='icon-bar'></span>
        </button>
        <a class='navbar-brand' href='index.php'><img src="images/iiv.png" style = "height:70px;">
          <!-- УМКШ &nbsp; <span class='glyphicon glyphicon-home'></span> -->
        </a>
      </div>
      <div class='collapse navbar-collapse' id='mainNavbar'>
        <ul class='nav navbar-nav'>
          <li><a href="#" title="Kiritish va Tahrirlash" >Ввод и редактирование</a></li>
          <li class=""><a href="#" title="Passport-Forma 1" >Паспорт Форма-1</a></li>
          <li class='dropdown'>
            <a class='dropdown-toggle' data-toggle='dropdown' href='#'>Отчеты и таблицы
            <span class='caret'></span></a>
            <ul class='dropdown-menu'>
              <li><a href='#'>Информация</a></li>
              <li><a href='#'>Таблицы</a></li>
              <li><a href='#'>Список</a></li>
            </ul>
          </li>

          <?php 
          if($_SESSION['username'] == 'admin' AND $_SESSION['version'] == 7){ ?>
            <li><a href='#'>Государственная граница</a></li>
            <li><a href='#'>Проблемы</a></li>
            <li><a href='#' title='Murojatlar' >Обращения</a></li>
          <?php } else {?>
            <li><a href='#'>Проблемы</a></li>
            <li><a href='#'>Обратится к администратору</a></li>

            <?php 
              if($_SESSION['province_id'] == 1618){
                echo "<li><a href='#' title='Taxrirlash' >Taxrirlash</a></li>";
              }
            } ?>
        </ul>
        <ul class='nav navbar-nav navbar-right'>
          <!-- <li><a href='#'>O'ZB</a></li>
		  -->
          <?php 
            if($_SESSION['username'] == 'admin' AND $_SESSION['version'] == 7){ ?>
              <li><a href='#'>&nbsp;<span class='glyphicon glyphicon-cog'></span>&nbsp;</a></li>
          <?php 
            }
          ?>
              <li><a href='#'><span class='glyphicon glyphicon-log-out'></span> Выход</a></li>
        </ul>
      </div>
    </div>
  </nav>