<?php 

header("Content-type: image/svg+xml"); 

echo'
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 33.367 39.207"> <defs> <style> .cls-1 { fill: #'.$_GET['color'].'; stroke: #fff; fill-rule: evenodd; }  .cls-2 { filter: url(#Tracé_992); } </style> <filter id="Tracé_992" x="0" y="0" width="33.367" height="39.207" filterUnits="userSpaceOnUse"> <feOffset dy="1" input="SourceAlpha"/> <feGaussianBlur stdDeviation="1" result="blur"/> <feFlood flood-color="#4a4961" flood-opacity="0.6"/> <feComposite operator="in" in2="blur"/> <feComposite in="SourceGraphic"/> </filter> </defs> <g class="cls-2" transform="matrix(1, 0, 0, 1, 0, 0)"> <path id="Tracé_992-2" data-name="Tracé 992" class="cls-1" d="M3.763,3.838A13.252,13.252,0,0,1,22.5,22.579L13.134,31.95,3.763,22.579A13.614,13.614,0,0,1,3.763,3.838Z" transform="translate(3.47 2.55)"/> </g> </svg>
 ';exit();