<div class="app">
  <div class="leftBar">
    <!-- HEADER -->
    <div class="header">
      <button id="closeMenuBtn"></button>
    </div>

    <div class="content">
      <!-- ITEM LIST -->
      <ul class="itemList">
        <li class="item selected">

          <a href="#" class="itemClick">
            <div class="itemIcon lb-icon-home"></div>
            <label class="itemLabel">Arborescence</label>
          </a>
          <!-- .\ SUB ITEMS -->
        </li>

        <li class="item">
          <a href="#" class="itemClick">
            <div class="itemIcon lb-icon-city"></div>
            <label class="itemLabel">Créer ville</label>
          </a>
        </li>
        <li class="item">
          <a href="#" class="itemClick">
            <div class="itemIcon lb-icon-road"></div>
            <label class="itemLabel">Créer parcours</label>
          </a>
        </li>
        <li class="item">
          <a href="#" class="itemClick">
            <div class="itemIcon lb-icon-roadMap"></div>
            <label class="itemLabel">Créer points d'intérêts</label>
          </a>
        </li>
        <li class="item">
          <a href="/logout.html" class="itemClick">
            <div class="itemIcon lb-icon-logout"></div>
            <label class="itemLabel">
              Déconnexion
            </label>
          </a>
        </li>
        <li class="item itemBySTS">
          <label class="itemLabel itemLogo">SCIENCE TROTTERS</label>
        </li>
        <li class="item itemByTNG">
          <label class="itemLabel itemLogo">TNG</label>
        </li>
      </ul>

    </div>

  </div>

  <div class="content">

    <!-- NAV BAR -->
    <div class="navBar">
      <div class="itemsNavBar">

        <div class="item searchBar">
          <label for="inputSearchBar" class="labelSearchBar"></label>
          <input type="text" id="inputSearchBar" placeholder="Ville, parcours, point d'intérêt..." />
        </div>
        <a href="#" class="item itemClick">
          <div class="itemIcon iconMessage"></div>
        </a>
        <a href="#" class="item itemClick">
          <div class="itemIcon iconInfo"></div>
        </a>
      </div>
    </div>
    <!-- .\ NAV BAR -->

    <div class="mainContent">

      <div class="filDAriane">
        <ul class="pathList">
          <li class="pathName"><a class="faLink" href="#">Arborescence</a></li>
          <li class="pathName"><a class="faLink" href="#">Arborescence</a></li>
          <li class="pathName"><a class="faLink" href="#">Arborescence</a></li>
        </ul>
      </div>

      <div class="contentView">

<div class="arbo">

  <!-- ROW -->
  <div class="arboItem">
    <!-- NAME -->
    <div class="columnName">
      <label>villes</label> 
    </div>

    <!-- DATA -->
    <div class="columnData">

      <ul class="itemList">

        <!-- ITEM -->
        <li class="item">
          <div class="itemAction">
            <a href="#">
              <i class="icon-pre icon-list-remove"></i>
            </a>
            <a href="#">
              <i class="icon-pre icon-list-edit"></i>
            </a>
            <a href="#">
              <i class="icon-pre icon-list-preview"></i>
            </a>
          </div>
          <label class="itemLabel">label</label>
        </li>
        <!-- .\ ITEM -->
        <!-- ITEM ACTIVE-->
        <li class="item selected">
          <div class="itemAction">
            <a href="#">
              <i class="icon-pre icon-list-remove"></i>
            </a>
            <a href="#">
              <i class="icon-pre icon-list-edit"></i>
            </a>
            <a href="#">
              <i class="icon-pre icon-list-preview"></i>
            </a>
          </div>
          <label class="itemLabel">label</label>
        </li>
        <!-- .\ ITEM -->
      </ul>

    </div>

    <!-- BTN LINK -->
    <div class="columnBtnLink">
      <a href="#">
        <i class="icon icon-create-city"></i>
        Créer une ville
      </a>
    </div>

  </div>
  <!-- .\ ROW -->

  <!-- ROW -->
  <div class="arboItem">
    <!-- NAME -->
    <div class="columnName">
      <label>parcours</label>
    </div>

    <!-- DATA -->
    <div class="columnData">
    </div>

    <!-- BTN LINK -->
    <div class="columnBtnLink">
      <a href="#">
        <i class="icon icon-create-road"></i>
        Créer un parcours
      </a>
    </div>

  </div>
  <!-- .\ ROW -->

  <!-- ROW -->
  <div class="arboItem">
    <!-- NAME -->
    <div class="columnName">
      <label>point d'intérêt</label>
    </div>

    <!-- DATA -->
    <div class="columnData">
    </div>

    <!-- BTN LINK -->
    <div class="columnBtnLink">
      <a href="#">
        <i class="icon icon-create-roadMap"></i>
        Créer un point d'intérêt
      </a>
    </div>

  </div>
  <!-- .\ ROW -->


</div>





      </div>
    </div>
  </div>
</div>