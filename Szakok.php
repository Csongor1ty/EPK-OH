<?php

class Szakok
{
    public $institutionName;
    public $departmentName;
    public $majorName;
    public $primaryRequired;
    public $primaryAdvanced;
    public $optionalRequired;

    function set_institutionName($institutionName){
        $this->institutionName = $institutionName;
    }
    function get_institutionName($institutionName){
        return $this->institutionName;
    }

    function set_departmentName($departmentName){
        $this->departmentName = $departmentName;
    }
    function get_departmentName($departmentName){
        return $this->departmentName;
    }

    function set_majorName($majorName){
        $this->majorName = $majorName;
    }
    function get_majorName($majorName){
        return $this->majorName;
    }

    function set_primaryRequired($primaryRequired){
        $this->primaryRequired = $primaryRequired;
    }
    function get_primaryRequired($primaryRequired){
        return $this->primaryRequired;
    }

    function set_primaryAdvanced($primaryAdvanced){
        $this->primaryAdvanced = $primaryAdvanced;
    }
    function get_primaryAdvanced($primaryAdvanced){
        return $this->primaryAdvanced;
    }

    function set_optionalRequired($optionalRequired){
        $this->optionalRequired = $optionalRequired;
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
}