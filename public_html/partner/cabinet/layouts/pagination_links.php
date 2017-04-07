<div class="row text-center" style="padding: 0 25px;">
    <div class="col-sm-12">
        <ul class="pagination pagination-sm">
<?php

$range = 4;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo "<li><a href=\"{$_SERVER['PHP_SELF']}?pg=1\">First</a></li>";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo "<li><a href='{$_SERVER['PHP_SELF']}?pg=$prevpage'><</a><li>";
}

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo "<li class=\"active\"><a href=''>$x</a></li>";
      // if not current page...
      } else {
         // make it a link
         echo "<li><a href='{$_SERVER['PHP_SELF']}?pg=$x'>$x</a></li>";
      } // end else
   } // end if
} // end for

// if not on last page, show forward and last page links
if ($currentpage != $totalpages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page
   echo "<li><a href='{$_SERVER['PHP_SELF']}?pg=$nextpage'>></a></li>";
   // echo forward link for lastpage
   echo "<li><a href='{$_SERVER['PHP_SELF']}?pg=$totalpages'>Last</a></li>";
} // end if
/****** end build pagination links ******/
?>
        </ul>
    </div>
</div>