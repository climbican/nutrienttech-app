<?php
/**
 * NOTES:
 *
 * apple current 457 active installs
 *
 *
 */
namespace App\Http\Controllers;

/**
 * Class StatsController
 * @package App\Http\Controllers
 */

class StatsController extends Controller {
    var $base_path = '';

    public function index(){
        $base_path = storage_path();
        $file = $base_path . '/app/stats/android-all-countries-install-base-102420-052421.csv';
        $apple_file = $base_path . '/app/stats/apple-time-series-app-units-20201026-20210419.csv';
        $androidStats = $this->csvtojson($file, true);

        $appleStats = $this->csvtojson($apple_file, true);

        return view('pages.stats', compact('androidStats', 'appleStats'));
    }

    /**
     * @param $file
     * @param $delimiter
     * @return false|string
     *
     * NOTES:
     * $jsonresult = csvtojson("./doc.csv", ",");
     *
     */
    function csvtojson($file, $remove_array_key=false) {
        // open csv file
        if (!($fp = fopen($file, 'r'))) {
            die("Can't open file...");
        }

        //read csv headers
        $key = fgetcsv($fp,"1024",",");

        // parse csv rows into array
        $json = array();
        $i = 0;
        while ($row = fgetcsv($fp,"1024",",")) {

            for($j=0; $j<count($row); $j++) {
                if (preg_match('/\d,\d/', $row[$j])){
                    $row[$j] = preg_replace('/,/', '', $row[$j]);
                }
            }

            if ($remove_array_key ) {
                $json[$i] = $row;
                $keys = array();
                foreach($key as $k){
                    array_push($keys, $k);
                }
            }
            else {
                $json[] = array_combine($row, $key);
            }
            $i++;
        }

        // release file handle
        fclose($fp);
        if ($remove_array_key) {
            array_unshift($json, $keys);
            /* THIS WORKS FOR THE CHARTS
             * echo "\n". "---------------------------". "\n";
            echo "\n". 'as array... '. $json[3][1];
            echo "\n". "---------------------------". "\n";
            echo "\n". ' data >>> '; print_r(json_encode($json));
            echo "\n". "---------------------------". "\n";*/
        }

        // encode array to json
        return json_encode($json, JSON_NUMERIC_CHECK);
    }
}
