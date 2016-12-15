<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/11/16
 * Time: 5:27 PM
 */
session_start();
?>

<?php
require '../php/db_query.php';
$username = $_SESSION['username'];
$connection = connectDb();
$eid = cleanInput($_GET['eid'], 10, $connection);
$eventInfo = getEventInfoByEid($eid);
$gid = $eventInfo[0]['gid'];
$isMember = isMember($gid, $username);
$isRSVP = isRSVP($eid, $username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CookZilla</title>
    <link rel="stylesheet" href="../css/common.css" type="text/css"/>
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="../css/home.css" type="text/css"/>
    <script src="../js/jquery-3.1.1.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#tags").hide();
        })
    </script>
</head>
<body>
    <div class="body">
        <?php
        $username = $_SESSION['username'];
        if($username == null) {
            echo "<script>window.location.href = './login.php';</script>";
        }
        ?>
        <div class="header">
            <div id="goHome">
                <a href="home.php" style="text-decoration: none;">CookZilla</a>
            </div>
            <form method="post" action="search.php" enctype="multipart/form-data">
                <div class="search">
                    <div>
                        <input id="searchText" name="search" type="text" class="form-control" placeholder="Find a recipe" style="color: grey;">
                    </div>
                    <div id="tag" style="cursor:pointer;">
                        Tag<span class="caret"></span>
                    </div>
                    <div style="margin-top: 3px;">
                        <button class="btn btn-default" style="color: yellowgreen">Search</button>
                    </div>
                </div>
                <div id="username">
                    <a href="profile.php?id=self" style="text-decoration: none;" ><?php echo $username;?></a>
                    <a href="logout.php"  style="margin-left: 10px;text-decoration: none;">Sign Out</a>
                </div>
                <script type="text/javascript">
                    $("#tag").click(function(){
                        $("#tags").toggle();
                    });
                </script>
                <div id="tags" class="tags">
                    <?php
                    $count = 1;
                    foreach (getTags() as $element) {
                        echo "<div id='tagOption".$count."' style='width: 30%'><input type='checkbox' name=selectedTag[] value=".$element['tname']." style='width: 20px; float:left;'/><label>".$element['tname']."</label></div>";
                        $count = $count + 1;
                    }
                    ?>
                    <div id="addTag" class="btn btn-primary" style="float: right; margin-right: 3px;">OK</div>
                    <script>
                        $("#addTag").click(function () {
                            $("#tags").hide();
                        });
                    </script>
                </div>
            </form>
        </div>
    </div>

    <div id="event_details" class="form-group" style="display: block; margin-left: 20%; margin-right: 20%;">
        <h3 style="margin-left: -5%">Event Details</h3>
        <hr style="margin-left: -5%">
        <h4>Event Title</h4>
        <p><?= $eventInfo[0]['etitle']?></p>
        <h4>Event Description</h4>
        <p><?= str_replace("\\r\\n", "</br>", $eventInfo[0]['edesc'])?></p>
        <h4>Event Location</h4>
        <p><?= $eventInfo[0]['elocation']?></p>
        <h4>Event Date and Time</h4>
        <p><?= $eventInfo[0]['etime']?></p>
        <h4>RSVP Users</h4>
            <?php
            $RSVPed = getRSVPedUser($eid);
                if (count($RSVPed) == 0) {
                    echo "<p>No RSVPed users yet!</p>";
                } else {
                    for ($i = 0; $i < count($RSVPed); $i++) {
                        echo "<p>" . $RSVPed[$i]['uname'] . "</p>";
                    }
                }
            ?>
        <hr>
    </div>

    <div id="event_report" class="form-group" style="margin-left: 20%; margin-right: 20%; display: <?php if($isMember) {echo 'block';} else {echo 'none';} ?>">
        <h3 style="margin-left: -5%">Event Report</h3>
        <?php
            $reports = getReportInfoByEid($eid);
            if(empty($reports)){
                echo "<hr>";
            }
            for ($i = 0; $i < count($reports); $i++) {
                echo "<h4>User: " . $reports[$i]['uname'] . "</h4>";
                echo "<h4>Report Time: " . $reports[$i]['ertime'] . "</h4>";
                echo "<h4>Report Text: " . $reports[$i]['ertext'] . "</h4>";
                if ($reports[$i]['erimage'] == null) {
                    echo "<img src='../img/default.jpg' style='width:400px; height=400px'>";
                }
                $imgs = explode(";", $reports[$i]['erimage']);
                foreach ($imgs as $img) {
                    if ($img == null) continue;
                    echo "<img src='" . $img . "' style='width:400px; height=400px'>";
                }
                echo "<hr>";
            }
        ?>
    </div>

    <div id="write_report" class="form-group" style="display: <?php if($isRSVP) {echo 'block';} else {echo 'none';} ?>">
        <h3 style="margin-left: 20%">Write a report about this event!</h3>
        <form id="create_report" action="../php/checkreport.php" method="post" style="width: 60%; margin-left: 20%" enctype="multipart/form-data">
            <input type="hidden" name="eid" value="<?= $eid ?>">
            <br>
            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Report text:</p>
                <textarea class="form-control" name="ertext" rows="10" placeholder="Enter your text here."></textarea>
                <small class="form-text text-muted">You must enter your report text.</small>
            </div>
            <br><br><br>
            <p style="text-align: center; font-size: medium; font-weight: bold;">Images:</p>
            <div id="reportImg" style="margin-left: 20%;"></div>
            <div align="center">
                <br>
                <input type="button" value="Add More Images!" class="btn" onclick="addImg()">
            </div>
            <br><br><br>
            <div align="center">
                <button type="submit" class="btn btn-primary">Submit your report!</button>
            </div>
        </form>
        <script>
            function addImg() {
                var obj = document.getElementById("reportImg");
                var newDiv = document.createElement("div");
                newDiv.innerHTML = "<input type='file' name='image[]'><br>";
                obj.appendChild(newDiv);
            }
        </script>
    </div>

    <div class="footer">
        <div>Copyright &copy; CookZilla TM. All Right Reserved.</div>
        <div>ADDRESS: 5 MetroTech, Brooklyn, 11201</div>
        <div>EMAIL: CookZilla@gmail.com</div>
    </div>

</body>
</html>
