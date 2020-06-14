<?php

declare(strict_types=1);

/**
 * @param $arr
 * @return float|int|mixed
 */
function calculate_median($arr) {
    $count = count($arr); //total numbers in array
    $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
    if($count % 2) { // odd number, middle is the median
        $median = $arr[$middleval];
    } else { // even number, calculate avg of 2 medians
        $low = @$arr[$middleval];
        $high = @$arr[$middleval+1];
        $median = (($low+$high)/2);
    }
    return $median;
}

$csn = ['adesaportland', 'aamontana', 'adesaseattle', 'daanw', 'daaseattle'];

$file = 'edgepipeline_postsale_adesaportland-all.csv';
$file1 = 'edgepipeline_postsale_aamontana-all.csv';
$file2 = 'edgepipeline_postsale_adesaseattle-all.csv';
$file3 = 'edgepipeline_postsale_daanw (1).csv';
$file4 = 'edgepipeline_postsale_daaseattle-all (1).csv';
$csv = array_map('str_getcsv', file($file));
array_walk($csv, function(&$a) use ($csv) {
    $a = array_combine($csv[0], $a);
});
array_shift($csv);


$csv1 = array_map('str_getcsv', file($file));
array_walk($csv1, function(&$a) use ($csv1) {
    $a = array_combine($csv1[0], $a);
});
array_shift($csv1);
$csv2 = array_map('str_getcsv', file($file1));
array_walk($csv2, function(&$a) use ($csv2) {
    $a = array_combine($csv2[0], $a);
});
array_shift($csv2);
$csv3 = array_map('str_getcsv', file($file2));
array_walk($csv3, function(&$a) use ($csv3) {
    $a = array_combine($csv3[0], $a);
});
array_shift($csv3);
$csv4 = array_map('str_getcsv', file($file3));
array_walk($csv4, function(&$a) use ($csv4) {
    $a = array_combine($csv4[0], $a);
});
array_shift($csv4);
$csv5 = array_map('str_getcsv', file($file4));
array_walk($csv5, function(&$a) use ($csv5) {
    $a = array_combine($csv5[0], $a);
});
array_shift($csv5);

$cs = [$csv1, $csv2, $csv3, $csv4, $csv5];

$years = [];
$makes = [];
foreach ($csv as $csv_item):
    $years[] = $csv_item['Year'];
    $makes[] = $csv_item['Make'];
endforeach;
$years = array_unique($years);
$makes = array_unique($makes);

//print_r($csv);

if($_GET) {
    if(isset($_GET['year']) && isset($_GET['make'])) {
        $year = $_GET['year'];
        $make = $_GET['make'];
        $medians = [];
        foreach ($cs as $k => $css) {
            $median_array = [];
            foreach ($css as $csv_item):
                if($csv_item['Year'] == $year & $csv_item['Make'] == $make){
                    $median_array[] = $csv_item['Price'];
                }
            endforeach;
            $medians[$k] = calculate_median($median_array);
        }

    }
}


?>
<html lang="">
<body>
<form>
    <select name="year">
        <?php foreach ($years as $yr): ?>
        <option value="<?=$yr?>"><?=$yr?></option>
        <?php endforeach; ?>
    </select>
    <label>
        <select name="make">
            <?php foreach ($makes as $mk): ?>
                <option value="<?=$mk?>"><?=$mk?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <input type="submit">
</form>
<?php if($medians) foreach($medians as $k => $med) echo $csn[$k].' $'.$med.'<br/>'; ?>
</body>
</html>
