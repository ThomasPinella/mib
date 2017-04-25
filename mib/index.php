<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
    session_start();
    require_once('Database.php');
?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>mibNote</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        <script>
            $( document ).ready(function() {
                
                $('.tide_button').click(function() {
                    if($(this).css('width') != '105%')
                    {
                      //  $('.tide_button').css('width', '80%');
                        //$('.date').find('span').animate({'font-size':'20px'}, 'fast');
                        //$('.date').find('.food_table td table td').animate({'font-size':'12px'}, 'fast');

                    }
                    $(this).css('width', '105%');
                    //$(this).find('span').animate({'font-size':'30px'}, 'fast');
                    //$(this).find('.food_table td table td').animate({'font-size':'30px'}, 'fast');
                });

                $(function() {       
                    $('.tide_button').hover(function(){ //Open on hover 
                        $(this).css('width', '105%');
                    },    
                    function(){ //Close when not hovered
                        $('.tide_button').css('width', '80%');
                    });
                });

            });
        </script>
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
        <script>
            var intervalFunc;
            window.onload = function startGetLocation()
            {
                if (navigator.geolocation)
                {
                    intervalFunc = setInterval(getLocation, 5000);
                }
                else
                {
                    alert("FAILURE");
                }
            }
            
            function getLocation() 
            {
                navigator.geolocation.getCurrentPosition(ajaxFunc);
            }
            
            function reloadAfterSubmit(currentLoc)
            {
                var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp = new XMLHttpRequest(); //Code for IE7+, Firefox, Chrome, Opera, Safari
                }
                else
                {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); //Code for IE6, IE5
                }
                xmlhttp.open("GET", "ajax_find.php?q=" + currentLoc, true);
                xmlhttp.onreadystatechange = function()
                {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                    {
                        document.getElementById("note_data").innerHTML = xmlhttp.responseText;
                    }
                };
                xmlhttp.send();
            }
            
            function validateForm()
            {
                var note = document.forms["note_form"]["note"].value;
                var sig = document.forms["note_form"]["sig"].value;
                var lat = document.forms["note_form"]["lat"].value;
                var lng = document.forms["note_form"]["lng"].value;
                
                if (note == null || note == "")
                {
                    alert("Note must be filled out");
                    return false;
                }
                if (sig == null || sig == "")
                {
                    alert("Signature must be filled out");
                    return false;
                }
                if (lat == null || lat == "" || lng == null || lng == "")
                {
                    alert("GPS Coordinates not yet found...");
                    return false;
                }                
            }

            function ajaxFunc(position)
            {
                var currentLoc = position.coords.latitude + "," + position.coords.longitude;
                var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    xmlhttp = new XMLHttpRequest(); //Code for IE7+, Firefox, Chrome, Opera, Safari
                }
                else
                {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); //Code for IE6, IE5
                }
                xmlhttp.open("GET", "ajax_find.php?q=" + currentLoc, true);
                xmlhttp.onreadystatechange = function()
                {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                    {
                        document.getElementById("note_data").innerHTML = xmlhttp.responseText;
                    }
                };
                xmlhttp.send();
            }
            function stopGetLocation() 
            {
                alert("stopped");
                clearInterval(intervalFunc);
            }
            
            function showPositionTwo(position)
            {
                var y = document.getElementById("lat");
                var z = document.getElementById("lng");
                y.value = position.coords.latitude;
                z.value = position.coords.longitude;
                var load = document.getElementById("load");
                load.innerHTML = "Found location.";
            }
            
            function getLatLng()
            {
                if (navigator.geolocation)
                {
                    navigator.geolocation.getCurrentPosition(showPositionTwo);
                }
            }
            
            function writeNoteClicked()
            {
                getLatLng();
                document.getElementById('light').style.display='block';
                document.getElementById('fade').style.display='block';
            }
            
            function writeNoteClosed()
            {
                document.getElementById('light').style.display='none';
                document.getElementById('fade').style.display='none';
                document.getElementById('load').innerHTML = "Searching...";
            }
            
            function filterTides(tide_selected)
            {                    
                var note_divs = document.getElementById("note_data").getElementsByTagName("div").getElementsByTagName("p");
                alert(note_divs[0][0]);
                for (i = 0; i < note_divs.length; i++)
                {
                    if (note_divs[i].firstChild.value == tide_selected)
                    {
                        alert(tide_selected);
                        document.getElementById("a" + i).style.display = "block";
                    }
                    else
                    {
                        document.getElementById("a" + i).style.display = "none";
                    }
                }
            }
        </script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <?php
            $mib = new Database();
            $mib->db_connect();
            include("includes/header.php");
            include("includes/navigation.php");
         
            if (isset($_POST['submit']))
            {
                $lat = floatval($_REQUEST['lat']);
                $lng = floatval($_REQUEST['lng']);
                $note = $_REQUEST['note'];
                $sig = $_REQUEST['sig'];
                $tide = $_REQUEST['tide'];
                echo "<script>reloadAfterSubmit('$lat,$lng')</script>";
                
                $insert_query = "INSERT INTO notes VALUES (DEFAULT, NOW(), $lat, $lng, '$note', '$sig', $tide);";
                $result = $mib->do_query($insert_query);
            }
        ?>
        <div class="content">
        <div onclick="writeNoteClicked()" id="writenote" class="popupbutton">Write Note</div>
                
            <div id="light" class="white_content">
                <form method="POST" name="note_form" onsubmit="return validateForm()">
                    <input id="lat" type="hidden" name="lat">
                    <input id="lng" type="hidden" name="lng">
                    <p id="load">Searching...</p>
                    <table class="write_note">
                        <tr><td>
                            Tide:<br>
                            <select name="tide">
                                <?php
                                    $result = $mib->do_query('SELECT * FROM tides;');
                                    $i = 1;
                                    while ($row = mysqli_fetch_array($result))
                                    {
                                        echo '<option value="'.$i.'">'.$row['tide_name'].'</option>';
                                        $i++;
                                    }
                                ?>
                            </select>
                        </td></tr>
                        <tr><td>
                            Note:<br>
                            <textarea name="note" rows="8" cols="50"></textarea>
                        </td></tr>
                        <tr><td>
                            Signature:<br>
                            <input type="text" name="sig"> <br>
                            <!--<div class="g-recaptcha" data-sitekey="6Lcp1AQTAAAAAMZyPJ77EIyKyal2HA3Bjk8iyh6r"></div>-->
                            </td></tr>
                        <tr><td>
                            <br>
                            <input style="width:20%;" type="submit" name="submit" value="Submit">
                        </td></tr>
                    </table>
                </form>

                <a style="position:absolute;top:5%;right:5%;" href="javascript:void(0)" onclick = "writeNoteClosed()">Close</a>
            </div>
        
            
            <div id="fade" class="black_overlay"></div>

            <!--<button onclick="startGetLocation()">Start</button>
            <button onclick="stopGetLocation()">Stop</button>-->
            <div id="note_data">Loading...</div>
        </div>
    </body>
</html>
