<nav>
  <ul class="pagination">
<?php
if($page>0) {
        echo '<li><a href="./do.php?a=paginate&page=' . ($page-1) . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
} else {
        echo '<li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
} // if

for($i=0; $i<9; $i++) {
        if($page == $i) {
                echo '<li class="active"><a href="./do.php?a=paginate&page=' . $i . '">' . ($i+1) . '</a></li>';
        } else {
                echo '<li><a href="./do.php?a=paginate&page=' . $i . '">' . ($i+1) . '</a></li>';
        } // if
} // for i

echo '<li><a href="./do.php?a=paginate&page=' . ($page+1) . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
?>
  </ul>
</nav>