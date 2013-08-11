<?php
    $menu_str = '<ul class="nav nav-tabs nav-stacked">';
    foreach (CMenuManager::getMenu("main_menu")->getMenuPublishedItemsInHierarchy()->getItems() as $item) {
        if ($item->getChilds()->getCount() > 0) {
            $menu_str .= '<li class="dropdown-submenu">';
            $menu_str .= '<a class="dropdown-toggle" data-toggle="dropdown" href="#">';
            $menu_str .= $item->getName();
            $menu_str .= '</a>';
            $menu_str .= '<ul class="dropdown-menu">';
            foreach ($item->getChilds()->getItems() as $child) {
                $menu_str .= '<li><a tabindex="-1" title="'.htmlspecialchars($child->getName()).'" href="'.htmlspecialchars($child->getLink()).'">'.$child->getName().'</a></li>';
            }
            $menu_str .= '</ul>';
            $menu_str .= '</li>';
        } else {
            $menu_str .= '<li><a tabindex="-1" title="'.htmlspecialchars($item->getName()).'" href="'.htmlspecialchars($item->getLink()).'">'.$item->getName().'</a></li>';
        }
    }
    $menu_str .= '</ul>';