<?php

function fMethodIs($type='get') {
	return $_SERVER['REQUEST_METHOD'] === strtoupper($type);
}