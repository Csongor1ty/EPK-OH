<?php
require_once 'homework_input.php';
require_once 'Szakok.php';

$pti = new Szakok('ELTE','IK', 'Programtervező informatikus', 'matematika',0, ['biológia', 'fizika', 'informatika', 'kémia']);
$angl = new Szakok('PPKE', 'BTK', 'Anglisztika', 'angol nyelv', 1, ['francia', 'német', 'olasz', 'orosz', 'spanyol', 'történelem']);
$mustAbsolve = ['magyar nyelv és irodalom', 'történelem', 'matematika'];

function requirementsMet($exampleData, $mustAbsolvedSubjects){
    $arrlength = count($mustAbsolvedSubjects);
    $requiredNum = 0;
    foreach($exampleData as $element => $element_value){
        if($element == 'erettsegi-eredmenyek'){
            foreach($element_value as $subjects){
                (int)$minPoints = (str_replace('%','',$subjects['eredmeny']));
                if($minPoints <= 20){
                    echo 'hiba, nem lehetséges a pontszámítás a '.$subjects['nev'].' tárgyból elért 20% alatti eredmény miatt <br>';
                    return 1;
                }
                for($x = 0; $x < $arrlength; $x++){
                    if($subjects['nev'] == $mustAbsolvedSubjects[$x]){
                        $requiredNum += 1;
                    }
                }
            }
        }
    }
    if($requiredNum == 3){
        return 0;
    }
    echo 'hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt <br>';
    return 2;
}

function whichMajor($exampleData){
    $simplified = [];
    foreach($exampleData as $element => $element_value){
        if($element == 'valasztott-szak'){
            foreach($element_value as $input_variables){
                array_push($simplified, $input_variables);
            }
        }
    }
    return $simplified;
}

function findPrimarySubject($exampleData, $primarySubject, $isAdvanced){
    foreach($exampleData as $element => $element_value){
        if($element == 'erettsegi-eredmenyek'){
            foreach($element_value as $subjects){
                if($subjects['nev'] == $primarySubject){
                    if(($subjects['tipus'] == 'közép') && $isAdvanced == 1){
                        echo 'hiba, nem lehetséges a pontszámítás, mert '.$subjects['nev'].' tantárgyból emelt érettségi szükséges a választott szakra <br>';
                        return -1;
                    }
                    if((str_replace('%','',$subjects['eredmeny'])) > 20){
                        (int)$num = str_replace('%','',$subjects['eredmeny']);
                        echo 'Kötelező tárgy pontszám: '.$num.'<br>';
                        return $num;
                    }
                }
            }

            echo '<br/>';
        }
    }
    return -1;
}

function findBestOptionalRequired($exampleData, $optionalRequired){
    $arrlength = count($optionalRequired);
    $bestScore = 0;
    foreach($exampleData as $element => $element_value){
        if($element == 'erettsegi-eredmenyek'){
            foreach($element_value as $subjects){
                for($x = 0; $x < $arrlength; $x++){
                    (int)$currentScore = str_replace('%','',$subjects['eredmeny']);
                    if($subjects['nev'] == $optionalRequired[$x] && $currentScore > $bestScore){
                        (int)$bestScore = str_replace('%','',$subjects['eredmeny']);
                    }
                }
            }
        }
    }
    echo 'legjobb kötvál pontszám: '.$bestScore.'<br>';
    return $bestScore;
}

function calculateExtraPoints($exampleData){
    $extraPoints = 0;
    foreach($exampleData as $element => $element_value){
        if($element == 'erettsegi-eredmenyek'){
            foreach($element_value as $advancedLevel){
                if($advancedLevel['tipus'] == 'emelt'){
                    $extraPoints += 50;
                }
            }
        }
        if($element == 'tobbletpontok'){
            $alreadyGotLower = [];
            foreach($element_value as $languagesKnown){
                if($languagesKnown['tipus'] == 'B2'){
                    array_push($alreadyGotLower, $languagesKnown['nyelv']);
                    $extraPoints += 28;
                }
                if($languagesKnown['tipus'] == 'C1'){
                    foreach ($alreadyGotLower as $acquiredB2){
                        if($acquiredB2 == $languagesKnown['nyelv']){
                            $extraPoints -= 28;
                        }
                        $extraPoints += 40;
                    }
                }
            }
        }
    }
    if ($extraPoints > 100) {
        $extraPoints = 100;
    }
    echo 'extra pontok: '.$extraPoints.'<br>';
    return $extraPoints;
}

function calculateFinalPoints($pointValuePrimary, $pointValueBestOptional, $extraPoints){
    if($pointValuePrimary == -1){
        return null;
    }
    if($extraPoints > 100){
        $extraPoints = 100;
    }
    echo (($pointValuePrimary + $pointValueBestOptional) * 2) + $extraPoints;
    return (($pointValuePrimary + $pointValueBestOptional) * 2) + $extraPoints;
}

// ---------------------------------------------------------------------------------------------------------------------

echo '<br>';

//----------manual tests----------
//findPrimarySubject($exampleData, $pti->primaryRequired);
//findBestOptionalRequired($exampleData, $pti->optionalRequired);
//calculateExtraPoints($exampleData);

function getVariableName($var) {
    foreach($GLOBALS as $varName => $value) {
        if ($value === $var) {
            return $varName;
        }
    }
    return null;
}
/* ITT LEHET MEGVÁLTOZTATNI A PÉLDÁT! */
$examinedStudent = $exampleData4;

echo '<br>';
echo 'final test: on test subject: '.getVariableName($examinedStudent).'<br>';
echo '<br>';
echo 'A tanuló mehet? -> ';
if (requirementsMet($examinedStudent, $mustAbsolve) == 0){
    echo '<br>';
    $chosenMajor = whichMajor($examinedStudent);
    echo 'A tanuló által választott szak: '.$chosenMajor[2]."<br/>";
    echo '<br>';

    if($chosenMajor[2] == $pti->majorName){
        //ELTE IK PTI szerinti pontszámítás
        echo 'A hallgató az ELTE IK PTI-re jelentkezett <br><br>';
        calculateFinalPoints(findPrimarySubject($examinedStudent, $pti->primaryRequired, $angl->primaryAdvanced), findBestOptionalRequired($examinedStudent, $pti->optionalRequired), calculateExtraPoints($examinedStudent));
    }
    if($chosenMajor[2] == $angl->majorName){
        //PPKE BTK Anglisztika szerinti pontszámítás
        echo 'A hallgató az PPKE BTK Anglisztikára jelentkezett <br><br>';
        calculateFinalPoints(findPrimarySubject($examinedStudent, $angl->primaryRequired, $angl->primaryAdvanced), findBestOptionalRequired($examinedStudent, $angl->optionalRequired), calculateExtraPoints($examinedStudent));
    }
}
