<?php
header('content-type: image/svg+xml');
$type = $_GET['type'];
$ext = $_GET['ext'];
$video_types = ['mp4', 'mkv', 'mov', 'avi', 'wmv', 'webm'];
$audio_types = ['mp3', 'wav', 'mid'];
$image_types = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'];
?>
<svg
   width="256"
   height="256"
   viewBox="0 0 67.733333 67.733333"
   version="1.1"
   id="svg5"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:svg="http://www.w3.org/2000/svg">
  <g
     id="folderbase"
     style="display:<?php echo (in_array($type, ['folder', 'folderfull']))?'inline':'none'; ?>">
    <path
       style="fill:none;stroke:none"
       d="M 16.933333,8.4666668 H 50.799999 V 59.266666 H 16.933333 V 8.4666668"
       id="path50" />
    <path
       style="fill:#decd87;stroke:none"
       d="M 16.933333,33.866666 V 8.4666666 H 33.866665 50.8 v 25.3999994 25.4 H 33.866665 16.933333 Z"
       id="path206" />
  </g>
  <g
     id="folderempty"
     style="display:<?php echo ($type == 'folder')?'inline':'none'; ?>">
    <path
       style="fill:#ffe680;stroke:none"
       d="M 16.933333,8.4666668 46.0375,11.641667 V 62.970833 L 16.933333,59.270812 Z"
       id="path412"/>
  </g>
  <g
     id="folderfull"
     style="display:<?php echo ($type == 'folderfull')?'inline':'none'; ?>">
    <g
       id="g3471-8"
       transform="matrix(0.96781379,0,0,0.96781379,0.54501981,1.9075698)">
      <path
         style="fill:#f2f2f2;stroke:none"
         d="m 16.933333,8.4666668 h 25.4 l 8.466666,8.3844852 V 59.266666 H 16.933333 Z"
         id="path3321-2" />
      <path
         style="fill:#ffffff;stroke:none"
         d="M 42.333333,8.466667 50.8,16.851152 l -8.466667,0.08218 z"
         id="path2753-6" />
    </g>
    <path
       style="fill:#ffe680;stroke:none"
       d="M 16.933333,8.4666668 40.216666,14.552083 V 65.881249 L 16.933333,59.270812 Z"
       id="path412-6"/>
  </g>
  <g
     id="file"
     style="display:<?php echo ($type == 'file')?'inline':'none'; ?>">
    <g
       id="g3471">
      <path
         style="fill:#f2f2f2;stroke:none"
         d="m 16.933333,8.4666668 h 25.4 l 8.466666,8.3844852 V 59.266666 H 16.933333 Z"
         id="path3321" />
      <path
         style="fill:#ffffff;stroke:none"
         d="M 42.333333,8.466667 50.8,16.851152 l -8.466667,0.08218 z"
         id="path2753" />
    </g>
  </g>
  <g
     id="iconvideo"
     style="display:<?php echo (in_array($ext, $video_types))?'inline':'none'; ?>">
    <g
       id="g5817">
      <g
         id="g5822">
        <path
           style="fill:#1a1a1a;stroke:#000000;stroke-width:1.05833;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
           d="m 26.999462,42.846972 c 0,0 0.293474,5.450204 5.198656,7.043339 4.905182,1.593136 11.696972,2.096231 13.66743,-2.222005 l 0.880416,3.102423 c 0,0 -3.940915,-3.437819 -10.229609,-0.251548 -6.755024,3.422544 -11.696973,-1.886609 -14.380149,-13.206259"
           id="path5417"/>
        <circle
           style="fill:#000000;stroke:none;stroke-width:0.264583"
           id="path4104"
           cx="33.866665"
           cy="33.866665"
           r="12.837373" />
      </g>
      <circle
         style="fill:#333333;stroke:none;stroke-width:0.264583"
         id="path4252"
         cx="33.866665"
         cy="33.866665"
         r="3.697803" />
      <path
         style="fill:#cccccc;stroke:none"
         d="M 43.937015,33.866666 C 43.853166,23.796321 33.866666,23.645492 33.866666,23.645492 l 10e-7,4.276313 c 0,0 5.710185,0.06698 5.877884,5.944861 z"
         id="path4367"/>
      <path
         style="fill:#cccccc;stroke:none"
         d="m 23.796317,33.866666 c 0.08385,10.070345 10.070349,10.221174 10.070349,10.221174 l -1e-6,-4.276313 c 0,0 -5.710185,-0.06698 -5.877884,-5.944861 z"
         id="path4367-9"/>
    </g>
  </g>
  <g
     id="iconaudio"
     style="display:<?php echo (in_array($ext, $audio_types))?'inline':'none'; ?>">
    <path
       id="path8517"
       style="display:inline;fill:#00aad4;stroke:#0088aa;stroke-width:1.05833;stroke-miterlimit:4;stroke-dasharray:none"
       d="m 27.69228,21.269017 -0.165573,17.610152 c -0.975432,-0.50294 -2.698612,-0.17544 -2.698612,-0.17544 -1.494207,-6e-6 -1.950948,1.964332 -1.951242,2.887803 -2.84e-4,0.923723 2.133613,1.924188 3.628227,1.924181 0.928696,-3.32e-4 3.377194,-0.235262 4.095367,-1.575564 l 0.153342,-16.196405 9.316505,2.241112 -4.65e-4,13.83469 c -0.975429,-0.377165 -1.31361,-0.469018 -2.36321,-0.343139 -1.787681,0.08384 -2.202497,1.712784 -2.202792,2.636255 -2.86e-4,0.923724 1.420894,2.049962 2.91551,2.049955 0.928694,-3.31e-4 3.964781,0.268486 4.682955,-1.07182 l 0.111418,-18.711884 0.0097,-1.513306 -9.591994,-2.19409 z"/>
  </g>
  <g
     id="iconimage"
     style="display:<?php echo (in_array($ext, $image_types))?'inline':'none'; ?>">
    <path
       style="fill:#37c837;stroke:#2ca02c;stroke-width:1.05833;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="m 21.131092,46.602242 h 25.47115 v -7.444637 l -4.76146,-5.290939 -7.974116,8.309512 c 0,0 -5.819087,-7.001412 -12.820501,0 z"
       id="path16881" />
    <circle
       style="fill:#ffd42a;fill-opacity:1;stroke:#ffcc00;stroke-width:1.05833;stroke-miterlimit:4;stroke-dasharray:none"
       id="path17021"
       cx="31.23385"
       cy="29.221468"
       r="6.2802596" />
  </g>
  <g
     id="iconother"
     style="display:<?php echo (strlen($ext)<=3)?'inline':'none'; ?>">
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:16.9333px;line-height:1;font-family:'Roboto Mono';-inkscape-font-specification:'Roboto Mono Bold';text-align:center;text-anchor:middle;fill:#cccccc;fill-opacity:1;stroke:none"
       x="33.94088"
       y="40.07151"
       id="text22925"><tspan
         id="tspan22923"
         style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:16.9333px;font-family:'Roboto Mono';-inkscape-font-specification:'Roboto Mono Bold';text-align:center;text-anchor:middle;fill:#cccccc;stroke-width:0.264583"
         x="33.94088"
         y="54"><?php echo $ext; ?></tspan></text>
  </g>
  <g
     id="g34795"
     style="display:<?php echo (strlen($ext)>3)?'inline':'none'; ?>">
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:8.46667px;line-height:1;font-family:'Roboto Mono';-inkscape-font-specification:'Roboto Mono Bold';text-align:center;text-anchor:middle;fill:#cccccc;fill-opacity:1;stroke:none"
       x="33.94088"
       y="36.984547"
       id="text34793"><tspan
         id="tspan34791"
         style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:8.46667px;font-family:'Roboto Mono';-inkscape-font-specification:'Roboto Mono Bold';text-align:center;text-anchor:middle;fill:#cccccc;stroke-width:0.264583"
         x="33.94088"
         y="54"><?php echo $ext; ?></tspan></text>
  </g>
</svg>
