<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="http://ghinda.net/css-toggle-switch/dist/toggle-switch.css" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>


<?php
$userAccessToken=NULL;

include("Helios-conf.php.dist");
include("Helios-conf.php");

$ninja_api_url = "https://api.ninja.is/rest/v0";

if($userAccessToken === NULL || $userAccessToken == "insert-token-here")
{
	die("Please enter a valid token");
}

if (isset($_POST["state"])){

if ($_POST["state"]=="ALL_ON")
{
	foreach($raspberries as $raspberry)
	{
		$url = $raspberry[1]."?lights=on";
		$handle = curl_init($url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_exec($handle);
		curl_close($handle);
	}



for ($x=0; $x<3; $x++) {
	$url = $ninja_api_url."/device/0112BB000635_0_0_11?user_access_token=".$userAccessToken;
	$handle = curl_init($url);
	$data = array('DA' => '000111111111111111111001');
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
	curl_exec($handle);
	sleep(1);
	$data = array('DA' => '000000000000000000000010');
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_exec($handle);
	sleep(1);
	$data = array('DA' => '110110101101101011011010');
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_exec($handle);
}
}

if ($_POST["state"]=="ALL_OFF")
{
	foreach($raspberries as $raspberry)
	{
		$url = $raspberry[1]."?lights=off";
		$handle = curl_init($url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_exec($handle);
		curl_close($handle);
	}


for ($x=0; $x<3; $x++) {
        $url = $ninja_api_url."/device/0112BB000635_0_0_11?user_access_token=".$userAccessToken;
        $handle = curl_init($url);
        $data = array('DA' => '000111111111111111111000');
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($handle);
sleep(1);
        $data = array('DA' => '000000000000000000000001');
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_exec($handle);
sleep(1);
        $data = array('DA' => '110110101101101011010010');
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_exec($handle);
}
}


$url = $ninja_api_url."/device/0112BB000635_0_0_11?user_access_token=".$userAccessToken;
$data = array('DA' => '000000000000000000000000');
if ($_POST["state"]=="WHITE"){
$data = array('DA' => '000111111111111111111111');
}
if ($_POST["state"]=="RED"){
$data = array('DA' => '000111111111111111111100');
}
if ($_POST["state"]=="GREEN"){
$data = array('DA' => '000111111111111111111010');
}
if ($_POST["state"]=="YELLOW"){
    $data = array('DA' => '000111111111111111111110');
}
if ($_POST["state"]=="BLUE"){
$data = array('DA' => '000111111111111111111001');
}
if ($_POST["state"]=="CYAN"){
    $data = array('DA' => '000111111111111111111011');
}
if ($_POST["state"]=="PURPLE"){
    $data = array('DA' => '000111111111111111111101');
}
if ($_POST["state"]=="BLACK"){
$data = array('DA' => '000111111111111111111000');
}
if ($_POST["state"]=="GLASS_OFF"){
$data = array('DA' => '000000000000000000000001');
}
if ($_POST["state"]=="GLASS_ON"){
$data = array('DA' => '000000000000000000000010');
}
if ($_POST["state"]=="GLASS_FLASH"){
$data = array('DA' => '000000000000000000000011');
}
if ($_POST["state"]=="GLASS_FADE"){
$data = array('DA' => '000000000000000000000100');
}
if ($_POST["state"]=="BLACKLIGHT_ON"){
$data = array('DA' => '110110101101101011011010');
}
if ($_POST["state"]=="BLACKLIGHT_OFF"){
$data = array('DA' => '110110101101101011010010');
}
$handle = curl_init($url);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
curl_exec($handle);
}
else if(isset($_POST["pi"]) && isset($_POST["status"]))
{
	$url = $raspberries[$_POST["pi"]][1]."?lights=".$_POST["status"];
	$handle = curl_init($url);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
	curl_exec($handle);
	curl_close($handle);
}
?>

<script>
function ledStrip(color){
	$.post("",{state:color});
}
function glassboard(color){
	$.post("",{state:color});
}
function blacklight(color){
	$.post("",{state:color});
}

function raspi(pi,status){
	$.post("",{"pi":pi, "status":status});
}

</script>
<div class="container" style="padding: 60px 15px 0;">
<div class="col-md-12">
	<h3>All</h3>
	<div class="col-md-6">

        <form method="post">
                <input type="hidden" name="state" value="ALL_ON"/>
                <button class="btn btn-success">All On      </button>
        </form>
	</div>
	<div class="col-md-6">
        <form method="post">
                <input type="hidden" name="state" value="ALL_OFF"/>
                <button class="btn btn-danger">All Off   </button>
        </form>
	</div>
</div>
<div class="col-md-4">
	<h3>LedStrip</h3>

<div class="col-md-12">
		<input type="hidden" name="state" value="RED"/>
		<button class="btn btn-danger" onclick="ledStrip('RED')">RED</button>

		<input type="hidden" name="state" value="GREEN"/>
		<button class="btn btn-success" onclick="ledStrip('GREEN')">GREEN	</button>

		<input type="hidden" name="state" value="BLUE"/>
		<button class="btn btn-primary" onclick="ledStrip('BLUE')">BLUE	</button>

		<br/>
		<br/>

        <input type="hidden" name="state" value="YELLOW"/>
        <button class="btn" onclick="ledStrip('YELLOW')">YELLOW	</button>

        <input type="hidden" name="state" value="CYAN"/>
        <button class="btn" onclick="ledStrip('CYAN')">CYAN	</button>

        <input type="hidden" name="state" value="PURPLE"/>
        <button class="btn" onclick="ledStrip('PURPLE')">PURPLE	</button>

        <br/>
        <br/>

		<input type="hidden" name="state" value="WHITE"/>
		<button class="btn" onclick="ledStrip('WHITE')">ON</button>

		<input type="hidden" name="state" value="BLACK"/>
		<button class="btn" onclick="ledStrip('BLACK')">OFF	</button>
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
		<button class="btn" onclick="glassboard('GLASS_OFF')">OFF	</button>

		<input type="hidden" name="state" value="GLASS_ON"/>
		<button class="btn btn-success" onclick="glassboard('GLASS_ON')">ON	</button>

		<input type="hidden" name="state" value="GLASS_FLASH"/>
		<button class="btn" onclick="glassboard('GLASS_FLASH')">FLASH	</button>

		<input type="hidden" name="state" value="GLASS_FADE"/>
		<button class="btn" onclick="glassboard('GLASS_FADE')">FADE	</button>

</div>
<div class="col-md-4">
	<h3>Blacklight</h3>

		<input type="hidden" name="state" value="BLACKLIGHT_OFF"/>
		<button class="btn" onclick="blacklight('BLACKLIGHT_OFF')">OFF	</button>

		<input type="hidden" name="state" value="BLACKLIGHT_ON"/>
		<button class="btn btn-success" onclick="blacklight('BLACKLIGHT_ON')">ON	</button>
</div>

<div class="col-md-12">
	<h1>Raspberry Pi Screens</h1>
</div>
<?php
	$iHateMyself = 0;
	foreach($raspberries as $raspberry)
	{
		echo "<div class='col-md-4'>";
		echo "<h3>".$raspberry[0]."</h3>";
		echo "<button class='btn' onclick=\"raspi('".$iHateMyself."', 'off')\">OFF</button>";
		echo "&nbsp;";
		echo "<button class='btn' onclick=\"raspi('".$iHateMyself++."', 'on')\">ON</button>";
		echo "</div>";
	}
?>

</div>
<script type="text/javascript">
function toggleColors(){
window.toggle=!window.toggle;
console.log("toggleColors:"+window.toggle);
if (window.toggle){
$("#loopledstrip").text("Disable Loop Colors");
}
else{
$("#loopledstrip").text("Enable Loop Colors");
}
}

$( document ).ready(function() {
window.color=0;
window.colors=["RED","GREEN","BLUE"];
setInterval(
	function(){
		if(window.toggle){
			window.color=(window.color+1)%3;
			console.log("Color["+window.color+"]:"+window.colors[window.color]);
			$.post("",{state:window.colors[window.color]})
		}
	}, 3000);
});

</script>
</body>
