<?php

namespace Xorinzor\Shoutzor\App\Liquidsoap;

class LiquidsoapManager {

    //The directory where our liquidsoap files and scripts are located
    private $liquidsoapDirectory = __DIR__ . '/../../../../shoutzor-requirements/liquidsoap/';
    private $app;

    public function __construct() {
        $config = App::module('shoutzor')->config('liquidsoap');

        try {
            $wrapperLiquidsoap = new LiquidsoapCommunicator($config['socketPath'] . '/wrapper');
            $shoutzorLiquidsoap = new LiquidsoapCommunicator($config['socketPath'] . '/shoutzor');
        } catch(Exception $e) {

        }
    }

    public function __destruct() {
    }

    public function isUp($type) {

    }

    public function toggleStatus($type, $operation) {
        switch($operation):
            case "start":
                if(LiquidSoap::isRunning()) return array('result' => false);
                    exec("screen -dmS $type liquidsoap " . $this->liquidsoapDirectory . "$type.liq"); //Start the tracklist

                    sleep(5);

                    if(LiquidSoap::isRunning()):
                    //Queue the waiting request-list into the shoutzor script
                endif;
                break;
            case "stop":
                if(!LiquidSoap::isRunning()) return false;
                exec("screen -X -S $type quit");
                //exec("killall liquidsoap > /dev/null &");
                break;
            default:
                return false;
                break;
        endswitch;

    	return true;
    }

    public function generateConfigFile($values) {
        //Add a header to the config file
        $new_config  = "#\n";
        $new_config .= "# DO NOT MANUALLY EDIT THIS FILE - THIS FILE IS AUTOMATICALLY GENERATED \n";
        $new_config .= "# GENERATED AT: ".date("d-m-Y H:i:s")." (UTC) \n";
        $new_config .= "#\n\n";

        //Replace the fields in the template with their respective values
        $new_config .= str_replace(
                            array_keys($values),
                            array_values($values),
                            file_get_contents($this->liquidsoapDirectory . 'config.template'));

        //Save the config data
        file_put_contents($this->liquidsoapDirectory . 'config.liq', $new_config);
    }
}
