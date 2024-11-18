<?php

class Spaceship
{
    // Properties
    protected string $_name;
    protected bool $isAlive;
    protected float $_fuel;
    protected int $_hitPoints;
    protected int $_ammo;
    protected array $_location;
    protected int $_extrafuel;
    protected int $_extraheal;
    protected bool $_barrier;


    public function __construct(
        $_name = 'spaceship',
        $_ammo = 100,
        $_fuel = 1000,
        $_hitPoints = 100,
        $_location = array(0,0),
        $_extrafuel = 100,
        $_extraheal = 100,
        $_barrier = false,
    ) {
        $this->name = $_name;
        $this->ammo = $_ammo;
        $this->fuel = $_fuel;
        $this->hitPoints = $_hitPoints;
        $this->location = $_location;
        $this->extrafuel = $_extrafuel;
        $this->extraheal = $_extraheal;
        $this->barrier = $_barrier;



        $this->isAlive = true;
    }


    public function shoot($hitship): int
    {

        $shot = 10;
        $damage = 2;
        if ($this->ammo - $shot >= 0) {
            $this->ammo -= $shot;
            return $hitship->hit($shot * $damage);
        } else {
            return 0;
        }
    }

    public function hit($damage)
    {
        if($this->barrier === true){
            return $this->barrier = false;
        }
        elseif($this->hitPoints - $damage > 0) {
            $this->hitPoints -= $damage;
        } else {
            $this->isAlive = false;
        }
    }

    public function move($x, $y)

    {

        $distance = (sqrt(pow($this->location[0] - $x, 2) + pow($this->location[1] - $y, 2)));

        if($distance > 0){

            $_fuelUsage = 2 * $distance;

            if ($this->fuel - $_fuelUsage > 0) {

                $this->fuel -= $_fuelUsage;

            } else {

                $this->fuel = 0;

            }
            $location =array($x,$y);

        return $this->location = $location;

            }
        }
    public function getName(){
        return $this->name;
    }
    public function getAmmo(){
        return $this->ammo;
    }
    public function getHitPoints(){
        return $this->hitPoints;
    }
    public function getlong(){
        return $this->long;
    }
    public function getlat(){
        return $this->lat;
    }
    public function getfuel(){
        return $this->fuel;
    }
    public function getextraFuel(){
        return $this->fuel += $this->extrafuel;
    }
    public function getextrahitpoints(){
        return $this->hitPoints += $this->extraheal;
    }
    public function getAlive(){
        return $this->isAlive;
    }
    
}
class Fightership extends Spaceship{
    protected int $boost = 99;
    protected int $boostused = 10;

    public function getboost()
        {
           return $this->boost;
           
        }
        public function boost()
        {
            if($this->boost - $this->boostused > 0) {
                return $this->boost -= $this->boostused;
            }
    }
}
class Healer extends Spaceship{
    protected int $HitpointsInTank = 400;
    
    function __construct(){
        parent::__construct();
        $this->name = "Healer";
        $this->hitPoints = 10;
        $this->barrier = true;

        $this->ammo = 31;
        $this->fuel = 454;
        $this->location = array(52,5);
        $this->extrafuel = 100;
        $this->extraheal = 100;
    }
    public function getbarrier()
    {
        return (boolval($this->barrier) ? 'true' : 'false');
    }
    
    public function getHitpointsInTank()
        {
           return $this->HitpointsInTank;
        }
    public function giveheal()
        {
           return $this->HitpointsInTank -= $this->extraheal;
        }
}
class Carriership extends Spaceship{
    protected int $FuelInTank = 400;
    public function getFuelInTank()
        {
           return $this->FuelInTank;
        }
    public function giveFuel()
        {
           return $this->FuelInTank -= $this->extrafuel;
        }
}

class Battle{
    protected bool $_win;
    protected string $_Move;

    public function __construct(
        $_Move = null
    ) {
        $this->move = $_Move;
    }
    public function battle1($teamRed, $teamblue)
    {
        foreach ($teamblue as $ships) {
            $blueAlive[] = $ships->getAlive();
        }
        $filtered = array_filter($blueAlive, function($k) {
            return $k == true;
        });

        if (count($filtered) === count($blueAlive)) {
        return "red won";
        }

        foreach ($teamRed as $ships) {
            $RedAlive[] = $ships->getAlive();
        }
        $filtered = array_filter($RedAlive, function($k) {
            return $k == true;
        });

        if (count($filtered) === count($RedAlive)) {
        return "blue won";
        }

        $default=array("shoot","move");
        $fighter=array("boost");
        $healer=array("giveheal");
        $Carriership=array("giveFuel");
        $functions = null;
        $total = [];

        for ($i = 0; $i < count($teamblue); $i++) {
            $total[] = $teamblue[$i];
            if (isset($teamRed[$i])) {
                $total[] = $teamRed[$i];
            }
        }

        $i = 0;
        
        foreach ($total as $ship){
            if($ship->getName() == "Carriership"){
                    $randomIndex = rand(0, count($CariershipMoveset=$Carriership + $default) - 1);
                    $selectedValue = $CariershipMoveset[$randomIndex];
                    $functions = $selectedValue;
            }
            elseif($ship->getName() == "Healer"){
                    $randomIndex = rand(0, count($HealerMoveset=$healer + $default) - 1);
                    $selectedValue = $HealerMoveset[$randomIndex];
                    $functions = $selectedValue;
            }
            elseif($ship->getName() == "fightership"){
                    $randomIndex = rand(0, count($FighterMoveset=$fighter + $default) - 1);
                    $selectedValue = $FighterMoveset[$randomIndex];
                    $functions = $selectedValue;
            }
            else{
                    $randomIndex = rand(0, count($default) - 1);
                    $selectedValue = $default[$randomIndex];
                    $functions = $selectedValue;
            }

            switch ($functions) {
                case "move":
                    $ship->move(rand(10 ,25))
                    break;
                case "shoot":
                    while (empty($total[$i + $more])) {
                        $more += 2;
                      }                      
                    $ship->shoot($total[$i+$more]);
                    break;
                case "boost":
                    $ship->boost();
                    break;
                case "giveheal":
                    $ship->giveheal();
                    break;
                case "giveFeul":
                    $ship->giveFuel();
            }
            $i++;
        }
    }
}
