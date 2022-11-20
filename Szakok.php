<?php

class Szakok
{
    private $institutionName;
    private $departmentName;
    private $majorName;
    private $primaryRequired;
    private $primaryAdvanced;
    private $optionalRequired;


    function get_institutionName($institutionName){
        return $this->institutionName;
    }

    function get_departmentName($departmentName){
        return $this->departmentName;
    }

    function get_majorName($majorName){
        return $this->majorName;
    }

    function get_primaryRequired($primaryRequired){
        return $this->primaryRequired;
    }

    function get_primaryAdvanced($primaryAdvanced){
        return $this->primaryAdvanced;
    }

    function get_optionalRequired($optionalRequired){
        return $this->optionalRequired;
    }

    function __construct($institutionName, $departmentName, $majorName, $primaryRequired, $primaryAdvanced, $optionalRequired){
        $this->institutionName = $institutionName;
        $this->departmentName = $departmentName;
        $this->majorName = $majorName;
        $this->primaryRequired = $primaryRequired;
        $this->primaryAdvanced = $primaryAdvanced;
        $this->optionalRequired = $optionalRequired;
    }

    function __destruct(){}
}