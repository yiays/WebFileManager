<?php
// Generates a SVG image for any file/folder/drive

header('content-type: image/svg+xml');
$type = array_key_exists('type', $_GET)?strtolower($_GET['type']):null;
$ext = array_key_exists('ext', $_GET)?strtolower($_GET['ext']):null;

include('types.php');

if($type=='file') {
   $filetype = find_type($ext);
}

?>
<svg width="256" height="256" version="1.1" viewBox="0 0 67.733 67.733" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
 <defs>
  <clipPath id="clipPath3030">
   <use xlink:href="#g3026"/>
  </clipPath>
  <clipPath id="clipPath3030-0">
   <use width="100%" height="100%" xlink:href="#g3026-7"/>
  </clipPath>
 </defs><?php
   if($type == 'drive') {
 ?>
 <g id="drive">
  <path d="m8.4667 46.302 15.412 12.965 35.388-14.354-17.066-11.046z" fill="#e6e6e6"/>
  <path d="m8.4667 38.365 15.412 12.965 35.388-14.354-17.066-11.046z" fill="#e6e6e6"/>
  <path d="m59.267 36.976v7.9375l-35.388 14.354v-7.9375z" fill="#ccc"/>
  <path d="m23.879 51.329v7.9375l-15.412-12.965v-7.9375z" fill="#1a1a1a"/>
  <path d="m21.894 52.421 0.89297 0.8599v1.2898l-0.89297-0.89297z" fill="#0f0"/>
 </g><?php
   } elseif(in_array($type, ['folder', 'folderfull'])) {
  ?>
 <g id="folderbase">
  <path d="m16.933 8.4667h33.867v50.8h-33.867v-50.8" fill="none" stroke="#000" stroke-width=".26458px"/>
  <path d="m16.933 33.867v-25.4h33.867v50.8h-33.867z" fill="#decd87"/>
 </g><?php
   }
   if($type == 'folder') {
  ?>
 <g id="folderempty">
  <path d="m16.933 8.4667 29.104 3.175v51.329l-29.104-3.7z" fill="#ffe680"/>
 </g><?php
   } elseif($type == 'folderfull') {
  ?>
 <g id="folderfull">
  <g transform="matrix(.96781 0 0 .96781 .54502 1.9076)">
   <path d="m16.933 8.4667h25.4l8.4667 8.3845v42.416h-33.867z" fill="#f2f2f2"/>
   <path d="m42.333 8.4667 8.4667 8.3845-8.4667 0.08218z" fill="#fff"/>
  </g>
  <path d="m16.933 8.4667 23.283 6.0854v51.329l-23.283-6.6104z" fill="#ffe680"/>
 </g><?php
   } elseif($type == 'file') {
  ?>
 <g id="file">
  <path d="m16.933 8.4667h25.4l8.4667 8.3845v42.416h-33.867z" fill="#f2f2f2"/>
  <path d="m42.333 8.4667 8.4667 8.3845-8.4667 0.08218z" fill="#fff"/>
 </g><?php
   }
   if($filetype == 'video') {
  ?>
 <g id="iconvideo">
  <path d="m21.141 33.867v18.027l25.461 1.93e-4v-18.027z" color="#000000" stroke-width=".26458px"/>
  <g transform="translate(0 1.0583)" fill="none" stroke="#fff" stroke-width=".5305">
   <path d="m24.077 39.953h19.579"/>
   <path d="m24.077 44.186h19.579"/>
   <path d="m24.077 48.419h19.579"/>
  </g>
  <g clip-path="url(#clipPath3030)">
   <g id="g3026">
    <rect transform="rotate(-6.0056)" x="17.482" y="31.095" width="25.491" height="4.7978"/>
    <path d="m20.639 29.095 7.3744 5.5653" fill="none" stroke="#fff" stroke-width="2.6458"/>
    <path d="m26.492 27.388 8.1696 6.4786" fill="none" stroke="#fff" stroke-width="2.6458"/>
    <path d="m33.867 26.663 7.9825 6.0798" fill="none" stroke="#fff" stroke-width="2.6458"/>
    <path d="m40.658 25.47 7.3744 5.5653" fill="none" stroke="#fff" stroke-width="2.6458"/>
   </g>
  </g>
  <g transform="rotate(5.9576 -25.164 36.278)" clip-path="url(#clipPath3030-0)">
   <g id="g3026-7">
    <rect transform="rotate(-6.0056)" x="17.482" y="31.095" width="25.491" height="4.7978"/>
    <path d="m20.639 29.095 7.3744 5.5653" fill="none" stroke="#fff" stroke-width="2.6458"/>
    <path d="m26.492 27.388 8.1696 6.4786" fill="none" stroke="#fff" stroke-width="2.6458"/>
    <path d="m33.867 26.663 7.9825 6.0798" fill="none" stroke="#fff" stroke-width="2.6458"/>
    <path d="m40.658 25.47 7.3744 5.5653" fill="none" stroke="#fff" stroke-width="2.6458"/>
   </g>
  </g>
  <path d="m0 0" fill="none" stroke="#fff" stroke-width=".70004"/>
 </g><?php
   } elseif($filetype == 'audio') {
  ?>
 <g id="iconaudio">
  <path d="m27.692 26.561-0.16557 17.61c-0.97543-0.50294-2.6986-0.17544-2.6986-0.17544-1.4942-6e-6 -1.9509 1.9643-1.9512 2.8878-2.84e-4 0.92372 2.1336 1.9242 3.6282 1.9242 0.9287-3.32e-4 3.3772-0.23526 4.0954-1.5756l0.15334-16.196 9.3165 2.2411-4.65e-4 13.835c-0.97543-0.37716-1.3136-0.46902-2.3632-0.34314-1.7877 0.08384-2.2025 1.7128-2.2028 2.6363-2.86e-4 0.92372 1.4209 2.05 2.9155 2.05 0.92869-3.31e-4 3.9648 0.26849 4.683-1.0718l0.11142-18.712 0.0097-1.5133-9.592-2.1941z" fill="#00aad4" stroke="#08a" stroke-width="1.0583"/>
 </g><?php
   } elseif($filetype == 'image') {
  ?>
 <g id="iconimage">
  <g transform="translate(0 5.2917)" stroke-width="1.0583">
   <path d="m21.131 46.602h25.471v-7.4446l-4.7615-5.2909-7.9741 8.3095s-5.8191-7.0014-12.821 0z" fill="#37c837" stroke="#2ca02c"/>
   <circle cx="31.234" cy="29.221" r="6.2803" fill="#ffd42a" stroke="#fc0"/>
  </g>
 </g><?php
   } elseif($filetype == 'application') {
  ?>
 <g id="iconexe">
  <rect x="21.094" y="29.607" width="25.538" height="19.457" ry="1.7306" fill="#fff" stroke="#b3b3b3" stroke-width=".52917"/>
  <path d="m21.047 32.741h25.444" fill="none" stroke="#b3b3b3" stroke-width=".52917"/>
  <path d="m30.162 33.023v15.792l-7.3422-0.01654s-1.1906 0.03307-1.5048-1.3725v-14.42z" fill="#e6e6e6"/>
  <g fill="none" stroke="#b3b3b3" stroke-width=".27865">
   <path d="m22.104 36.116h7.0555"/>
   <path d="m22.104 37.174h7.0555"/>
   <path d="m22.104 38.232h7.0555"/>
   <path d="m31.166 35.123h7.0555"/>
   <path d="m31.166 36.182h7.0555"/>
   <path d="m31.166 37.24h7.0555"/>
   <path d="m31.166 38.298h7.0555"/>
   <path d="m31.166 39.357h7.0555"/>
   <path d="m31.166 40.415h7.0555"/>
  </g>
  <rect x="39.721" y="33.867" width="5.9862" height="11.212" fill="#0cf"/>
 </g><?php
   } elseif($filetype == 'disc') {
  ?>
 <g id="icondisc">
  <path transform="scale(.26458)" d="m128 99.865a48.134 48.134 0 0 0-48.135 48.135 48.134 48.134 0 0 0 48.135 48.135 48.134 48.134 0 0 0 48.135-48.135 48.134 48.134 0 0 0-48.135-48.135zm-0.1875 40.51a8.0625 8.0625 0 0 1 8.0625 8.0625 8.0625 8.0625 0 0 1-8.0625 8.0625 8.0625 8.0625 0 0 1-8.0625-8.0625 8.0625 8.0625 0 0 1 8.0625-8.0625z" fill="#afdde9" stroke="#b3b3b3" stroke-width="2"/>
  <path d="m33.867 31.485s7.9706-0.16536 7.9375 6.6807" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="2.6458"/>
 </g><?php
   } elseif($filetype == 'document') {
  ?>
 <g id="icondocument">
  <rect x="33.867" y="26.423" width="12.736" height="11.81" fill="#80e5ff"/>
  <path d="m33.867 38.232 5.7547-6.8461 5.9862 6.813z" fill="#87de87"/>
  <circle cx="43.64" cy="29.253" r="1.8686" fill="#ffd42a"/>
  <g transform="translate(0 .52917)" fill="none" stroke="#000" stroke-width=".52917">
   <path d="m21.861 32.874 2.6128-5.6224 2.1167 5.6555"/>
   <path d="m23.184 30.162h2.4143"/>
  </g>
  <g fill="none" stroke="#4d4d4d">
   <g stroke-width=".52917">
    <path d="m27.517 27.814h5.5232"/>
    <path d="m27.517 28.873h5.5232"/>
    <path d="m27.517 29.931h4.8286"/>
    <path d="m27.517 30.989h5.5232"/>
    <path d="m27.517 32.048h4.3656"/>
    <path d="m27.517 33.106h5.5232"/>
    <path d="m27.517 34.164h4.994"/>
   </g>
   <g stroke-width=".6032">
    <path d="m21.894 35.223h11.146"/>
    <path d="m21.894 36.281h8.8635"/>
    <path d="m21.894 37.339h10.286"/>
    <path d="m21.894 38.398h6.9784"/>
    <path d="m21.894 39.456h23.912"/>
    <path d="m21.894 40.514h21.564"/>
    <path d="m21.894 41.573h23.912"/>
    <path d="m21.894 42.631h22.853"/>
    <path d="m21.894 43.689h20.538"/>
    <path d="m21.894 44.748h22.953"/>
    <path d="m21.894 45.806h23.482"/>
    <path d="m21.894 46.864h22.787"/>
    <path d="m21.894 47.923h23.912"/>
    <path d="m21.894 48.981h23.25"/>
    <path d="m21.894 50.039h22.126"/>
    <path d="m21.894 51.098h4.6633"/>
   </g>
  </g>
 </g><?php
   }
   if($ext) {
  ?>
 <g id="exttext">
  <rect x="26.847" y="16.851" width="23.953" height="6.3478" fill="<?php echo $type_colors[$filetype]; ?>"/>
  <text x="59.839821" y="21.857258" fill="#ffffff" font-family="'Roboto Mono'" font-size="5.3889px" font-weight="bold" stroke-width=".1684" text-align="end" text-anchor="end" style="line-height:1" xml:space="preserve">
   <tspan><?php echo strtoupper($ext); ?></tspan>
  </text>
 </g><?php
   }
  ?>
</svg>
