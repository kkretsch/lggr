<nav>
    <ul class="pagination">
<?php
$maxPages = ceil($state->getResultSize() / LggrState::PAGELEN); // maximum pages

if ($page > 9) {
    echo '<li><a class="" href="./do.php?a=paginate&page=' . ($page - 10) .
         '" aria-label="' . _('Ten left') .
         '"><span aria-hidden="true">&lArr;</span></a></li>';
} else {
    echo '<li class="disabled"><a href="#" aria-label="' . _('Ten left') .
         '"><span aria-hidden="true">&lArr;</span></a></li>';
} // if

if ($page > 0) {
    echo '<li><a class="pageleft" href="./do.php?a=paginate&page=' . ($page - 1) .
         '" aria-label="' . _('Previous') .
         '"><span aria-hidden="true">&laquo;</span></a></li>';
} else {
    echo '<li class="disabled"><a href="#" aria-label="' . _('Previous') .
         '"><span aria-hidden="true">&laquo;</span></a></li>';
} // if

if ($page - 4 > 1) {
    echo '<li><a href="./do.php?a=paginate&page=0">1</a></li>';
} // if

for ($i = $page - 4; $i < $page + 4; $i ++) {
    if ($page == $i) {
        $class = 'active';
    } else {
        $class = '';
    } // if
    if (($i >= 0) && ($i < $maxPages)) {
        echo '<li  class="' . $class . '"><a href="./do.php?a=paginate&page=' .
             $i . '">' . ($i + 1) . '</a></li>';
    } // if
} // for i

if ($page + 4 < $maxPages) {
    echo '<li><a href="./do.php?a=paginate&page=' . ($maxPages - 1) . '">' .
         $maxPages . '</a></li>';
} // if

if ($page + 1 >= $maxPages) {
    echo '<li class="disabled"><a href="./do.php?a=paginate&page=' . ($page + 1) .
         '" aria-label="' . _('Next') .
         '"><span aria-hidden="true">&raquo;</span></a></li>';
} else {
    echo '<li><a class="pageright" href="./do.php?a=paginate&page=' . ($page + 1) .
         '" aria-label="' . _('Next') .
         '"><span aria-hidden="true">&raquo;</span></a></li>';
} // if

if ($page + 10 >= $maxPages) {
    echo '<li class="disabled"><a href="./do.php?a=paginate&page=' . ($page + 10) .
         '" aria-label="' . _('Ten right') .
         '"><span aria-hidden="true">&rArr;</span></a></li>';
} else {
    echo '<li><a class="pageright" href="./do.php?a=paginate&page=' .
         ($page + 10) . '" aria-label="' . _('Ten right') .
         '"><span aria-hidden="true">&rArr;</span></a></li>';
} // if

?>
  </ul>
</nav>
