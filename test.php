<?php

require_once('nn.php');

$network = new NeuralNetwork(array(2, 4, 4, 1), 0.001);
$inputs = array(); // your training input
$output = array(); // your expected output
// start training
for ($i = 0; $i < 1000; $i++) {
    $network->FeedForward($inputs);
    $network->BackProp($output);
}

// use the network
$result = $network->FeedForward($inputs);
