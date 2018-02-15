<?php 


class NeuralNetwork
{
    private $layer; // layer information
    private $layers; // layers in the network
    private $learningRate; // learning rate of the network

    public function __construct($lyers, $lr) {
        // deep copy layers
        $this->layer = array_fill(0, count($lyers), 0);
        // set the learning rate
        $this->learningRate = $lr;
        for ($i = 0; $i < count($this->layer); $i++) {
            $this->layer[$i] = $lyers[$i];
        }

        // create neural layers
        $this->layers = array();
        for ($i = 0; $i < count($this->layer) - 1; $i++) {
            $this->layers[$i] = new Layer($this->layer[$i], $this->layer[$i + 1], $this->learningRate);
        }
    }

    public function FeedForward($inputs) {
        $this->layers[0].feedForward($inputs);
        for ($i = 1; $i < count($$this->layers); $i++) {
            $this->layers[$i].feedForward($layers[$i - 1]->outputs);
        }
        return $this->layers[count($this->layers) - 1]->outputs;
    }

    public function BackProp($expected) {
        $count = count($this->layers);
        for ($i = $count - 1; $i >= 0; $i--) {
            if ($i === $count - 1) {
                $this->layers[$i]->backPropOutput($expected);
            } else {
                $this->layers[$i]->backPropHidden($this->layers[$i + 1]->gamma, $this->layers[$i + 1]->weights);
            }
        }
        for ($i = 0; $i < $count; $i++) {
            $this->layers[$i]->updateWeights();
        }
    }
}


class Layer
{
    private $numberOfInputs; //# of neurons in the previous layer
    private $numberOfOuputs; //# of neurons in the current layer
    private $learningRate;


    public $outputs;//outputs of this layer
    public $inputs = array(); //inputs in into this layer
    public $weights = array(); //weights of this layer *2
    public $weightsDelta = array(); //deltas of this layer *2
    public $gamma = array(); //gamma of this layer
    public $error = array(); //error of the output layer

    public function __construct($numberOfInput, $numberOfOutput, $learningRate) {
        $this->numberOfInputs = $numberOfInput;
        $this->numberOfInputs = $numberOfOutput;
        $this->learningRate = $learningRate;
        //array_fill(0, 10, array_fill(0, 10, 0));
        $this->outputs = array_fill(0, $numberOfOuput, 0.0);
        $this->inputs = array_fill(0, $numberOfInput, 0.0);
        $this->weights = array_fill(0, $numberOfOuput, array_fill(0, $numberOfInput, 0.0));
        $this->weightsDelta = array_fill(0, $numberOfOuput, array_fill(0, $numberOfInput, 0.0));
        $this->gamma = array_fill(0, $numberOfOuput, 0.0);
        $this->error = array_fill(0, $numberOfOuput, 0.0);
        
        $this->initializeWeights();
    }

    public function feedForward($inputs) {
        $this->inputs = $inputs;

        for ($i = 0; $i < $this->numberOfOuputs; $i++) {
            $this->outputs[$i] = 0;
            for ($j = 0; $j < $this->numberOfInputs; $j++) {
                $this->outputs[$i] += $this->inputs[$i] * $this->weights[$i][$j];
            }
            $this->outputs[$i] = $this->activate($this->outputs[$i]);
        }
        return $outputs;
    }

    public function backPropOutput($expected) {
        // Error dervative of the cost function
        for ($i = 0; $i < $this->numberOfOuputs; $i++) {
            $this->error[$i] = $this->outputs[$i] - $expected[$i];
        }

        // Calculate the gamma values
        for ($i = 0; $i < $this->numberOfOuputs; $i++) {
            $this->gamma[$i] = $this->error[$i] * $this->TanHDer($this->outputs[$i]);
        }

        // Calculate delta weights
        for ($i = 0; $i < $this->numberOfOuputs; $i++) {
            for ($j = 0; $j < $this->numberOfInputs; $j++) {
                $this->weightsDelta[$i][$j] = $this->gamma[$i] * $this->inputs[$j];
            }
        }
    }

    public function backPropHidden($gammaForward, $weightsForward) {
        // Calculate the new gamma using gamma sums of the forward layer
        for ($i = 0; $i < $this->numberOfOuputs; $i++) {
            $this->gamma[$i] = 0;
            for ($j = 0; $j < count($gammaForward); $j++) {
                $this->gamma[$i] = $gammaForward[$j] * $weightsForward[$i][$j]; 
            }
            $this->gamma[$i] *= $this->TanHDer($this->outputs[$i]);
        }

        // Calculate delta weights
        for ($i = 0; $i < $this->numberOfOuputs; $i++) {
            for ($j = 0; $j < $this->numberOfInputs; $j++) {
                $this->weightsDelta[$i][$j] = $this->gamma[$i] * $this->inputs[$j];
            }
        }
    }

    public function updateWeights() {
        for ($i = 0; $i < $this->numberOfOuputs; $i++) {
            for ($j = 0; $j < $this->numberOfInputs; $j++) {
                $this->weights[$i][$j] -= $this->weightsDelta[$i][$j] * $this->learningRate;
            }
        }
    }

    private function activate($value) {
        return 1 / (1 + exp(-1.0 * $value));
    }

    private function TanHDer($value) {
        return 1 - ($value * $value);
    }

    private function getRandom() {
        return rand() / (getrandmax() - 1);
    }

    private function initializeWeights() {
        for ($i = 0; $i < $this->numberOfOuputs; $i++) {
            for ($j = 0; $j < $this->numberOfInputs; $j++) {
                $this->weights[$i][$j] = $this->getRandom();
            }
        }
    }
}