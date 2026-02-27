<?php

$output = shell_exec("node ai.js 2>&1");

echo $output;
