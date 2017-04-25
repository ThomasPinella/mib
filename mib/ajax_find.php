<?php
    require_once('Database.php');

    $loc = $_GET['q'];
    $mib = new Database();
    $mib->db_connect();
    $array = explode(',', $loc);
    $lat = floatval($array[0]);
    $lng = floatval($array[1]);
    
    $radius = 0.000300;
    
    $lat_lower = $lat - $radius;
    $lat_upper = $lat + $radius;
    
    $lng_lower = $lng - $radius;
    $lng_upper = $lng + $radius;
    
    $query = "SELECT note_text, signature, tide FROM notes WHERE (lat >= $lat_lower AND lat <= $lat_upper) AND (lng >= $lng_lower AND lng <= $lng_upper);";
    $result = $mib->do_query($query);
    echo '<div id="contains_notes">';
    $i = 0;
    while ($row = mysqli_fetch_array($result))
    {
        echo '<div id="a'.i.'" class="note">';
        echo '<p class="tide_num" style="display:none;">'.$row['tide'].'</p>';
        echo $row['note_text'];
        echo '<br><br>';
        echo '<span style="padding-left:10px;font-style:italic;">-'.$row['signature'].'</span>';
        echo '</div>';
        $i++;
    }
    echo '</div>';
?>