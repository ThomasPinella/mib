<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div class="navigation">
            <h2>tides</h2>
            <?php 
                $result = $mib->do_query('SELECT * FROM tides;');
                $i = 1;
                
                while ($row = mysqli_fetch_array($result))
                {
                    echo '<div onclick="filterTides(\''.$i.'\')" class="tide_button">'.$row['tide_name'].'</div>';
                    $i++;
                }
            ?>
        </div>
    </body>
</html>
