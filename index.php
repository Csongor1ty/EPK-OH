<?php
if(file_exists('homework_input.php')){
require_once 'homework_input.php';
require_once 'Szakok.php';
require_once 'Calculator.php';

// -------------------------------

$calc = new Calculator();
$pti = new Szakok('ELTE','IK', 'Programtervező informatikus',
    'matematika',0, ['biológia', 'fizika', 'informatika', 'kémia']);
$angl = new Szakok('PPKE', 'BTK', 'Anglisztika',
    'angol nyelv', 1, ['francia', 'német', 'olasz', 'orosz', 'spanyol', 'történelem']);
$mustAbsolve = ['magyar nyelv és irodalom', 'történelem', 'matematika'];

//----------manual tests----------
//$calc->findPrimarySubject($exampleData, $pti->get_primaryRequired());
//$calc->findBestOptionalRequired($exampleData, $pti->get_optionalRequired());
//$calc->calculateExtraPoints($exampleData);
//$calc->requirementsMet($exampleData, $mustAbsolve);
//$calc->whichMajor($exampleData);
//$calc->calculateFinalPoints(90, 70, 50);

function getVariableName($var) {
    foreach($GLOBALS as $varName => $value) {
        if ($value === $var) {
            return $varName;
        }
    }
    return null;
}

/* ITT LEHET MEGVÁLTOZTATNI AZ ÉLES PÉLDÁT! */

// global scope nélkül undefined az átvett input változó, de működőképes

global $exampleData3;
$examinedStudent = $exampleData3;

/* -----------------------------------------*/

    // input ellenőrzése - jelentkezés alapján szak példány kiválasztása
if ($calc->requirementsMet($examinedStudent, $mustAbsolve) == 0){
    $chosenMajor = $calc->whichMajor($examinedStudent);
    if($chosenMajor[0] == $pti->get_institutionName($pti) &&
        $chosenMajor[1] == $pti->get_departmentName($pti) &&
        $chosenMajor[2] == $pti->get_majorName($pti)){
        if(($calc->findBestOptionalRequired($examinedStudent, $pti->get_optionalRequired($pti))) <= 0){return 1;}
        $calc->calculateFinalPoints($calc->findPrimarySubject($examinedStudent, $pti->get_primaryRequired($pti),
            $pti->get_primaryAdvanced($pti)), $calc->findBestOptionalRequired($examinedStudent, $pti->get_optionalRequired($pti)),
            $calc->calculateExtraPoints($examinedStudent));
    }
    if($chosenMajor[0] == $angl->get_institutionName($angl) &&
        $chosenMajor[1] == $angl->get_departmentName($angl) &&
        $chosenMajor[2] == $angl->get_majorName($angl)){
        if(($calc->findBestOptionalRequired($examinedStudent, $angl->get_optionalRequired($angl))) <= 0){return 1;}
        $calc->calculateFinalPoints($calc->findPrimarySubject($examinedStudent, $angl->get_primaryRequired($angl),
            $angl->get_primaryAdvanced($angl)), $calc->findBestOptionalRequired($examinedStudent, $angl->get_optionalRequired($angl)),
            $calc->calculateExtraPoints($examinedStudent));
    }
}
}else{
    echo 'Az input fájl "homework_input.php" nem található.';
    return 1;
}