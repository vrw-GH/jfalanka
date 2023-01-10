<?php

/**
 * @link: http://www.Awcore.com/dev
 */
include_once('models/dbConfig.php');

function pagination($query, $per_page = 10, $pagepagi = 1, $url) {
    $myCon = new dbConfig();
    $myCon->connect();
    $query = "SELECT COUNT(*) as `num` FROM {$query}";
    $row = mysqli_fetch_array($myCon->query($query));
    $total = $row['num'];
    $adjacents = "2";

    $pagepagi = ($pagepagi == 0 ? 1 : $pagepagi);
    $start = ($pagepagi - 1) * $per_page;

    $prev = $pagepagi - 1;
    $next = $pagepagi + 1;
    $lastpage = ceil($total / $per_page);
    $lpm1 = $lastpage - 1;

    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<ul class='pagination'>";
        $pagination .= "<li class='details'>Page $pagepagi of $lastpage</li>";
        if ($lastpage < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $pagepagi)
                    $pagination.= "<li><a class='current'>$counter</a></li>";
                else
                    $pagination.= "<li><a href='{$url}&pagepagi=$counter'>$counter</a></li>";
            }
        }
        elseif ($lastpage > 5 + ($adjacents * 2)) {
            if ($pagepagi < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $pagepagi)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}&pagepagi=$counter'>$counter</a></li>";
                }
                $pagination.= "<li class='dot'>...</li>";
                $pagination.= "<li><a href='{$url}&pagepagi=$lpm1'>$lpm1</a></li>";
                $pagination.= "<li><a href='{$url}&pagepagi=$lastpage'>$lastpage</a></li>";
            }
            elseif ($lastpage - ($adjacents * 2) > $pagepagi && $pagepagi > ($adjacents * 2)) {
                $pagination.= "<li><a href='{$url}&pagepagi=1'>1</a></li>";
                $pagination.= "<li><a href='{$url}&pagepagi=2'>2</a></li>";
                $pagination.= "<li class='dot'>...</li>";
                for ($counter = $pagepagi - $adjacents; $counter <= $pagepagi + $adjacents; $counter++) {
                    if ($counter == $pagepagi)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}&pagepagi=$counter'>$counter</a></li>";
                }
                $pagination.= "<li class='dot'>..</li>";
                $pagination.= "<li><a href='{$url}&pagepagi=$lpm1'>$lpm1</a></li>";
                $pagination.= "<li><a href='{$url}&pagepagi=$lastpage'>$lastpage</a></li>";
            }
            else {
                $pagination.= "<li><a href='{$url}&pagepagi=1'>1</a></li>";
                $pagination.= "<li><a href='{$url}&pagepagi=2'>2</a></li>";
                $pagination.= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $pagepagi)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}&pagepagi=$counter'>$counter</a></li>";
                }
            }
        }

        if ($pagepagi < $counter - 1) {
            $pagination.= "<li><a href='{$url}&pagepagi=$next'>Next</a></li>";
            $pagination.= "<li><a href='{$url}&pagepagi=$lastpage'>Last</a></li>";
        } else {
            $pagination.= "<li><a class='current'>Next</a></li>";
            $pagination.= "<li><a class='current'>Last</a></li>";
        }
        $pagination.= "</ul>\n";
    }


    return $pagination;
}

?>