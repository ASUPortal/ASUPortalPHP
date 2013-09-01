<?php
    function menuItemsAsListWithCount(array $data, $level) {
        if ($level == 0) {
            echo '<ul class="nav">';
        } else {
            echo '<ul class="dropdown-menu">';
        }
        foreach ($data as $entry) {
            if ($entry->getChilds()->getCount() > 0) {
                echo '<li class="dropdown">';
                echo '<a class="dropdown-toggle" data-toggle="dropdown" href="'.htmlspecialchars($entry->getLink()).'">'.htmlspecialchars($entry->getName()).'</a>';
                menuItemsAsListWithCount($entry->getChilds()->getItems(), ($level + 1));
                echo '</li>';
            } else {
                echo '<li>';
                if ($entry->getName() == "<hr />") {
                    echo '<a class="divider"></a>';
                } else {
                    echo '<a href="'.htmlspecialchars($entry->getLink()).'">'.htmlspecialchars($entry->getName()).'</a>';
                }
                echo '</li>';
            }
        }
        echo '</ul>';
    }

    //не показывать в анкете
    if (isset($_SESSION['id']) && (strstr($_SERVER["SCRIPT_NAME"],"anketa")=='' || 1>0) ) {
        ?>
        <div class="navbar">
            <div class="navbar-inner">
                <ul class="nav">
                    <li>
                        <p class="navbar-text">
                            <a href="#" id="asu_menu_hider" class="icon-th-list"></a>
                        </p>
                    </li>
                </ul>
                <?php menuItemsAsListWithCount(CMenuManager::getMenu("admin_menu")->getMenuPublishedItemsInHierarchy()->getItems(), 0); ?>
            </div>
        </div>
        <?php
    }