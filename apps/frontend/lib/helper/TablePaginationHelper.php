<?php

function tablePagination($pager, $url, $name = "page", $add= "") {
    if (strpos($url, '?')) {
        $char = '&';
    } else {
        $char = '?';
    }
    if ($pager->haveToPaginate()) {
        echo "<a href=\"" . url_for($url . $char."$name=" . $pager->getPreviousPage()) . "$add\">";
        echo "\t&lt; Précédent";
        echo "</a>";


        foreach ($pager->getLinks(10) as $page) {
            if ($page == $pager->getPage()) {
                echo "&nbsp;<b>" . $page . "</b>";
            } else {
                echo "<a href=\"" . url_for($url . $char."$name=" . $page) . "$add\">";
                echo "&nbsp;" . $page;
                echo "</a>";
            }
        }

        echo "<a href=\"" . url_for($url . $char."$name=" . $pager->getNextPage()) . "$add\">";
        echo "\tSuivant &gt;";
        echo "</a>";
    }
}
?>

