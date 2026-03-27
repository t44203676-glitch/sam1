<script>
var media_center_side_navigation = true ;
</script>
<?php
$current_file = basename($_SERVER['PHP_SELF']);
?>
<div id="navleft_sections" class="navleft_sections">
    <table dir="RTL">
        <tr><td><div class="heading_sections">&nbsp;الأقسام</div></td></tr>
        <tr>
            <td>
                <table id='psss_NavigatorTab1' class="navleft">
                    <tr>
                        <td>
                            <a href='media_photos.php'>
                                <font class='navleft1_link <?php echo ($current_file == "media_photos.php") ? "navleft1_linkactive" : ""; ?>'>&nbsp;الصور</font></a> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table id='psss_NavigatorTab2' class="navleft">
                    <tr>
                        <td>
                            <span class="navleft1_link <?php echo ($current_file == "media_news.php" || $current_file == "news_details.php" || $current_file == "index.php") ? "navleft1_linkactive" : ""; ?>"><a href='media_news.php' >&nbsp;الأخبار</a></span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table id='psss_NavigatorTab3' class="navleft">
                    <tr>
                        <td>
                            <span class="navleft1_link <?php echo ($current_file == "media_videos.php") ? "navleft1_linkactive" : ""; ?>"><a href='media_videos.php' >&nbsp;الفيديو</a></span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table id='psss_NavigatorTab4' class="navleft">
                    <tr>
                        <td>
                            <span class="navleft1_link <?php echo ($current_file == "statements.php") ? "navleft1_linkactive" : ""; ?>"><a href='statements.php' >&nbsp;البيانات الصحفية</a></span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<BR />
