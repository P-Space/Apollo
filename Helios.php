<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://ghinda.net/css-toggle-switch/dist/toggle-switch.css" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>


<?php

include("Helios-conf.php.dist");
include("Helios-conf.php");

/**
 * Sends a message to an mqtt topic.
 * @param $topic the MQTT topic the message should be posted to.
 * @param $message the message to publish.
 */
function sendMQTT($topic, $message)
{
    exec("mosquitto_pub -h 192.168.1.5 -t '" . $topic . "' -m '" . $message . "'");
}

if (isset($_POST["pi"]) && isset($_POST["status"])) {
    //used to switch the monitors of raspberry pis

    $url = $raspberries[$_POST["pi"]][1] . "?lights=" . $_POST["status"];
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    curl_exec($handle);
    curl_close($handle);

} else if (isset($_POST["state"])) {
    //used to switch other devices

    if ($_POST["state"] == "ALL_ON") {
        //used to switch on everything

        //turn on the raspberry monitors
        foreach ($raspberries as $raspberry) {
            $url = $raspberry[1] . "?lights=on";
            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
            curl_exec($handle);
            curl_close($handle);
        }

        //turn on the glassboard
        sendMQTT($topics["GLASS_FADE"], $commands["GLASS_FADE"]);

        //turn on the ledstrips
        for ($x = 0; $x < 3; $x++) {
            sendMQTT($topics["CYAN"], $commands["CYAN"]);
        }

    } else if ($_POST["state"] == "ALL_OFF") {
        //used to switch off everything

        //turn off the raspberry monitors
        foreach ($raspberries as $raspberry) {
            $url = $raspberry[1] . "?lights=off";
            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
            curl_exec($handle);
            curl_close($handle);
        }

        //turn off the glassboard
        sendMQTT($topics["GLASS_OFF"], $commands["GLASS_OFF"]);

        //turn off the ledstripts
        for ($x = 0; $x < 3; $x++) {
            sendMQTT($topics["BLACK"], $commands["BLACK"]);
        }

    } else {
        //used to switch a specific device

        $state = $_POST["state"];
        //check if the device is known
        if (array_key_exists($state, $topics) && array_key_exists($state, $commands)) {

            $topic = $topics[$state];
            $repeats = 1;
            if ($topic == 'rf') {
                //post multiple times for rf
                $repeats = 2;
            }
            for ($c = 0; $c < $repeats; $c++) {
                //post to mqtt
                sendMQTT($topic, $commands[$state]);
            }
        }
    }
}

?>

<script>
    function ledStrip(color) {
        $.post("", {state: color});
    }
    function glassboard(color) {
        $.post("", {state: color});
    }
    function blacklight(color) {
        $.post("", {state: color});
    }

    function raspi(pi, status) {
        $.post("", {"pi": pi, "status": status});
    }
    function plug(plug, status) {
        $.post("", {"plug": plug, "status": status});
    }

</script>
<div class="container" style="padding: 60px 15px 0;">
    <div class="col-md-12">
        <h3>All</h3>

        <div class="col-md-6">

            <form method="post">
                <input type="hidden" name="state" value="ALL_ON"/>
                <button class="btn btn-success">All On</button>
            </form>
        </div>
        <div class="col-md-6">
            <form method="post">
                <input type="hidden" name="state" value="ALL_OFF"/>
                <button class="btn btn-danger">All Off</button>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <h3>LedStrip</h3>

        <div class="col-md-12">
            <input type="hidden" name="state" value="RED"/>
            <button class="btn btn-danger" onclick="ledStrip('RED')">RED</button>

            <input type="hidden" name="state" value="GREEN"/>
            <button class="btn btn-success" onclick="ledStrip('GREEN')">GREEN</button>

            <input type="hidden" name="state" value="BLUE"/>
            <button class="btn btn-primary" onclick="ledStrip('BLUE')">BLUE</button>

            <br/>
            <br/>

            <input type="hidden" name="state" value="YELLOW"/>
            <button class="btn" onclick="ledStrip('YELLOW')">YELLOW</button>

            <input type="hidden" name="state" value="CYAN"/>
            <button class="btn" onclick="ledStrip('CYAN')">CYAN</button>

            <input type="hidden" name="state" value="PURPLE"/>
            <button class="btn" onclick="ledStrip('PURPLE')">PURPLE</button>

            <br/>
            <br/>

            <input type="hidden" name="state" value="WHITE"/>
            <button class="btn" onclick="ledStrip('WHITE')">ON</button>

            <input type="hidden" name="state" value="BLACK"/>
            <button class="btn" onclick="ledStrip('BLACK')">OFF</button>
        </div>

        <div class="col-md-12">

            <br/>

        </div>

        <div class="col-md-12">
            <button class="btn" id="loopledstrip" onclick="toggleColors();">
                Enable Loop Colors
            </button>
        </div>
    </div>
    <div class="col-md-4">
        <h3>GlassBoard</h3>

        <input type="hidden" name="state" value="GLASS_OFF"/>
        <button class="btn" onclick="glassboard('GLASS_OFF')">OFF</button>

        <input type="hidden" name="state" value="GLASS_ON"/>
        <button class="btn btn-success" onclick="glassboard('GLASS_ON')">ON</button>

        <input type="hidden" name="state" value="GLASS_FLASH"/>
        <button class="btn" onclick="glassboard('GLASS_FLASH')">FLASH</button>

        <input type="hidden" name="state" value="GLASS_FADE"/>
        <button class="btn" onclick="glassboard('GLASS_FADE')">FADE</button>

    </div>
    <div class="col-md-4">
        <h3>Blacklight</h3>
        <input type="hidden" name="state" value="BLACKLIGHT_OFF"/>
        <button class="btn" onclick="blacklight('BLACKLIGHT_OFF')">OFF</button>

        <input type="hidden" name="state" value="BLACKLIGHT_ON"/>
        <button class="btn btn-success" onclick="blacklight('BLACKLIGHT_ON')">ON</button>
        <h3>Lab Ledstrip</h3>
        <button class="btn" onclick="plug(1,0)">OFF</button>
        <button class="btn btn-success" onclick="plug(1,1)">ON</button>
    </div>

    <div class="col-md-12">
        <h1>Raspberry Pi Screens</h1>
    </div>
    <?php
    $iHateMyself = 0;
    foreach ($raspberries as $raspberry) {
        echo "<div class='col-md-4'>";
        echo "<h3>" . $raspberry[0] . "</h3>";
        echo "<button class='btn' onclick=\"raspi('" . $iHateMyself . "', 'off')\">OFF</button>";
        echo "&nbsp;";
        echo "<button class='btn' onclick=\"raspi('" . $iHateMyself++ . "', 'on')\">ON</button>";
        echo "</div>";
    }
    ?>

    <div class="col-md-12">
        <h1>Door Lamp (placeholder)</h1>
    </div>

</div>
<script type="text/javascript">
    function toggleColors() {
        window.toggle = !window.toggle;
        console.log("toggleColors:" + window.toggle);
        if (window.toggle) {
            $("#loopledstrip").text("Disable Loop Colors");
        }
        else {
            $("#loopledstrip").text("Enable Loop Colors");
        }
    }

    $(document).ready(function () {
        window.color = 0;
        window.colors = ["WHITE", "RED", "GREEN", "YELLOW", "BLUE", "CYAN", "PURPLE"];
        setInterval(
            function () {
                if (window.toggle) {
                    window.color = (window.color + 1) % window.colors.length;
                    console.log("Color[" + window.color + "]:" + window.colors[window.color]);
                    $.post("", {state: window.colors[window.color]})
                }
            }, 5000);
    });

</script>
</body>
