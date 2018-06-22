  <div class="leftBar">
    <!-- HEADER -->
    <div class="header">
      <button id="closeMenuBtn"></button>
    </div>

    <div class="content">
      <!-- ITEM LIST -->
      <ul class="itemList">
        <li class="item {if $sPage === 'index'}selected{/if}">

          <a href="/" class="itemClick">
            <div class="itemIcon lb-icon-home"></div>
            <label class="itemLabel">Arborescence</label>
          </a>
          <!-- .\ SUB ITEMS -->
        </li>

        <li class="item {if $sPage === 'edit/city'}selected{/if}">
          <a href="/edit/city.html" class="itemClick">
            <div class="itemIcon lb-icon-city"></div>
            <label class="itemLabel">Créer ville</label>
          </a>
        </li>
        <li class="item {if $sPage === 'edit/parcours'}selected{/if}">
          <a href="/edit/parcours.html" class="itemClick">
            <div class="itemIcon lb-icon-road"></div>
            <label class="itemLabel">Créer parcours</label>
          </a>
        </li>
        <li class="item {if $sPage === 'edit/interest'}selected{/if}">
          <a href="/edit/interest.html" class="itemClick">
            <div class="itemIcon lb-icon-roadMap"></div>
            <label class="itemLabel">Créer points d'intérêts</label>
          </a>
        </li>
        <li class="item {if $sPage === 'credit'}selected{/if}">
          <a href="/credit.html" class="itemClick">
            <div class="itemIcon lb-icon-iconInfo"></div>
            <label class="itemLabel">Crédit</label>
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
        {*
        <li class="item itemBySTS">
          <label class="itemLabel itemLogo">SCIENCE TROTTERS</label>
        </li>
        *}
        <li class="item itemByTNG">
          <label class="itemLabel itemLogo">TNG</label>
        </li>
      </ul>

    </div>

  </div>
