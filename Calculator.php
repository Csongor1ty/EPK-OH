<?php

class Calculator
{
    // minimum pontok vizsgálata, kötelező érettségi tárgyak meglétének vizsgálata
    final function requirementsMet($exampleData, $mustAbsolvedSubjects){
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

    // választott szak információinak kinyerése egyszerűbb vizsgálathoz az index.php-n
    final function whichMajor($exampleData){
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

    // kötelező tantárgy kiválasztása, ha sikertelen, hibával térünk vissza, egyébként pontszámmal
    final function findPrimarySubject($exampleData, $primarySubject, $isAdvanced){
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
                            return $num;
                        }
                    }
                }

                echo '<br/>';
            }
        }
        return -1;
    }

    // legjobban sikerült kötelezően választható tárgy kiválasztása, ha hiányzik, hibával térünk vissza, egyébként pontszámmal
    final function findBestOptionalRequired($exampleData, $optionalRequired){
        $arrlength = count($optionalRequired);
        $bestScore = 0;
        $requiredOptionalsCount = 0;
        foreach($exampleData as $element => $element_value){
            if($element == 'erettsegi-eredmenyek'){
                foreach($element_value as $subjects){
                    foreach ($optionalRequired as $opt){
                        if($opt == $subjects['nev']){
                            $requiredOptionalsCount += 1;
                        }
                    }
                    for($x = 0; $x < $arrlength; $x++){
                        (int)$currentScore = str_replace('%','',$subjects['eredmeny']);
                        if($subjects['nev'] == $optionalRequired[$x] && $currentScore > $bestScore){
                            (int)$bestScore = str_replace('%','',$subjects['eredmeny']);
                        }
                    }
                }
            }
        }
        if($requiredOptionalsCount <= 0){
            echo 'hiba, nem lehetséges a pontszámítás a kötelezően választható tárgy(ak) hiánya miatt <br/>';
            return -2;
        }
        return $bestScore;
    }

    // többletpontok kiszámítása - ha van emelt érettségi és középfokú nyelvvizsga ugyan abból a tárgyból, az előbbit számolja
    final function calculateExtraPoints($exampleData){
        $advancedLanguageName = '';
        $needle = 'nyelv';
        $extraPoints = 0;
        foreach($exampleData as $element => $element_value){
            if($element == 'erettsegi-eredmenyek'){
                foreach($element_value as $advancedLevel){
                    if($advancedLevel['tipus'] == 'emelt'){
                        if((strpos($advancedLevel['nev'], $needle) !== false) && $advancedLevel['nev'] !== 'magyar nyelv és irodalom'){
                            $advancedLanguageName = str_replace(" nyelv", "", $advancedLevel['nev']);
                        }
                        $extraPoints += 50;
                    }
                }
            }
            if($element == 'tobbletpontok'){
                $proficientLanguages = [];
                $intermediateLanguages = [];
                foreach($element_value as $languagesKnown){
                    if($languagesKnown['tipus'] == 'C1'){
                        array_push($proficientLanguages, $languagesKnown['nyelv']);
                        $extraPoints += 40;
                    }
                    if($languagesKnown['tipus'] == 'B2' && $advancedLanguageName !== $languagesKnown['nyelv']){
                        array_push($intermediateLanguages, $languagesKnown['nyelv']);
                        $extraPoints += 28;
                    }
                }
                foreach ($intermediateLanguages as $interLang){
                    foreach ($proficientLanguages as $profLang){
                        if ($interLang == $profLang){
                            $extraPoints -= 28;
                        }
                    }
                }
            }
        }
        if ($extraPoints > 100) {
            $extraPoints = 100;
        }
        return $extraPoints;
    }

    // összpontszám kiszámítása - többletpontok újraellenőrzése
    final function calculateFinalPoints($pointValuePrimary, $pointValueBestOptional, $extraPoints){
        if($pointValuePrimary == -1){
            return null;
        }
        if($extraPoints > 100){
            $extraPoints = 100;
        }
        $baseSum = (($pointValuePrimary + $pointValueBestOptional) * 2);
        echo ($baseSum + $extraPoints).' ('.$baseSum.' alappont + '.$extraPoints.' többletpont)';
        return ($baseSum + $extraPoints);
    }
}