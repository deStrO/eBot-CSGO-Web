<?php

function tablePagination($pager, $url, $name = "page", $add = "") {
    if (strpos($url, '?')) {
        $char = '&';
    } else {
        $char = '?';
    }

    if ($pager->haveToPaginate()) {
        echo '<ul>';
        echo "<li><a href=\"" . url_for($url . $char . "$name=" . $pager->getPreviousPage()) . "$add\">";
        echo "Prev";
        echo "</a></li>";


        foreach ($pager->getLinks(10) as $page) {
            if ($pager->getPage() == $page) {
                echo "<li><a href=\"" . url_for($url . $char . "$name=" . $page) . "$add\">";
                echo $page;
                echo "</a></li>";
            } else {
                echo "<li class=\"active\"><a href=\"" . url_for($url . $char . "$name=" . $page) . "$add\">";
                echo $page;
                echo "</a></li>";
            }
        }

        echo "<li><a href=\"" . url_for($url . $char . "$name=" . $pager->getNextPage()) . "$add\">";
        echo "Next";
        echo "</a></li>";
        echo '</ul>';
    }
}
?>

