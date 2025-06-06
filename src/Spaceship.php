<?php

class Spaceship {
    // Properties
    protected string $name;
    protected bool $isAlive;
    protected float $fuel;
    protected int $hitPoints;
    protected int $ammo;
    protected array $location;
    protected int $extrafuel;
    protected int $extraheal;
    protected bool $barrier;
    protected string $team = "none";


    public function __construct(
        $name = 'spaceship',
        $ammo = 100,
        $fuel = 1000,
        $hitPoints = 100,
        $location = array(0, 0),
        $extrafuel = 100,
        $extraheal = 100,
        $barrier = false,
    ) {
        $this->name = $name;
        $this->ammo = $ammo;
        $this->fuel = $fuel;
        $this->hitPoints = $hitPoints;
        $this->location = $location;
        $this->extrafuel = $extrafuel;
        $this->extraheal = $extraheal;
        $this->barrier = $barrier;

        $this->isAlive = true;
    }

    public function get_team() {
        return $this->team;
    }

    public function set_team($team) {
        $this->team = $team;
    }

    public function shoot($hitship) {
        $shot = 10;
        $damage = 2;
        if ($this->ammo - $shot >= 0) {
            $this->ammo -= $shot;
            $hitship->hit($shot * $damage);
            return $shot * $damage;
        } else {
            return 0;
        }
    }

    public function hit($damage) {
        if ($this->barrier === true) {
            return $this->barrier = false;
        } elseif ($this->hitPoints - $damage > 0) {
            $this->hitPoints -= $damage;
        } else {
            $this->hitPoints = 0;
            $this->isAlive = false;
        }
    }

    public function move($x, $y) {
        $distance = (sqrt(pow($this->location[0] - $x, 2) + pow($this->location[1] - $y, 2)));
        if ($distance > 0) {
            $fuelUsage = 2 * $distance;
            if ($this->fuel - $fuelUsage > 0) {
                $this->fuel -= $fuelUsage;
            } else {
                $this->fuel = 0;
            }
            $location = array($x, $y);
            return $this->location = $location;
        }
    }
    public function getName() {
        return $this->name;
    }
    public function getAmmo() {
        return $this->ammo;
    }
    public function getHitPoints() {
        return $this->hitPoints;
    }
    public function getLocation() {
        return $this->location;
    }
    public function getfuel() {
        return $this->fuel;
    }
    public function getextraFuel() {
        return $this->fuel += $this->extrafuel;
    }
    public function getextrahitpoints() {
        return $this->hitPoints += $this->extraheal;
    }
    public function getAlive() {
        return $this->isAlive;
    }
}

class Fightership extends Spaceship {
    protected int $boost = 99;
    protected int $boostused = 10;

    public function getboost() {
        return $this->boost;
    }
    public function boost() {
        if ($this->boost - $this->boostused > 0) {
            return $this->boost -= $this->boostused;
        }
    }
}
class Healer extends Spaceship {
    protected int $HitpointsInTank = 400;
    function __construct() {
        parent::__construct();
        $this->name = "Healer";
        $this->hitPoints = 10;
        $this->barrier = true;

        $this->ammo = 31;
        $this->fuel = 454;
        $this->location = array(52, 5);
        $this->extrafuel = 100;
        $this->extraheal = 100;
    }

    public function getbarrier() {
        return (boolval($this->barrier) ? 'true' : 'false');
    }

    public function getHitpointsInTank() {
        return $this->HitpointsInTank;
    }

    public function giveheal() {
        if ($this->HitpointsInTank - $this->extraheal > 0) {
            $this->hitPoints += $this->extraheal;
            return $this->HitpointsInTank -= $this->extraheal;
        } else {
            return $this->HitpointsInTank = 0;
        }
    }
}
class Carriership extends Spaceship {
    protected int $FuelInTank = 400;
    public function getFuelInTank() {
        return $this->FuelInTank;
    }
    public function giveFuel() {
        return $this->FuelInTank -= $this->extrafuel;
        $this->fuel += $this->extrafuel;
    }
}

class Battle {
    protected string $win = "";
    protected string $battleNotes = "";
    protected string $Move;
    protected $teamRed;
    protected $teamBlue;

    public function __construct($_Move = null, $teamRed = null, $teamBlue = null) {
        $this->Move = $_Move;
        foreach ($teamRed as $ship) {
            $ship->set_team("red");
        }
        $this->teamRed = $teamRed;
        foreach ($teamBlue as $ship) {
            $ship->set_team("blue");
        }
        $this->teamBlue = $teamBlue;
    }
    public function get_win() {
        return $this->win;
    }
    public function getTeamRed() {
        return $this->teamRed;
    }
    public function getTeamBlue() {
        return $this->teamBlue;
    }
    public function getBattleNotes() {
        return $this->battleNotes;
    }
    public function setBattleNotes($notes) {
        $this->battleNotes = $this->battleNotes . "</br>" . $notes;
    }
    public function battle1() {
        foreach ($this->teamBlue as $ships) {
            $blueAlive[] = $ships->getAlive();
        }
        $filtered = array_filter($blueAlive, function ($k) {
            return $k == true;
        });

        if (count($filtered) === 0) {
            $this->win = "red";
            return "red won";
        }

        foreach ($this->teamRed as $ships) {
            $RedAlive[] = $ships->getAlive();
        }
        $filtered = array_filter($RedAlive, function ($k) {
            return $k == true;
        });

        if (count($filtered) === 0) {
            $this->win = "blue";
            return "blue won";
        }

        $default = array("shoot", "move");
        $fighter = array("boost");
        $healer = array("giveheal");
        $Carriership = array("giveFuel");
        $functions = null;
        $total = [];

        for ($i = 0; $i < count($this->teamBlue); $i++) {
            $total[] = $this->teamBlue[$i];
            if (isset($this->teamRed[$i])) {
                $total[] = $this->teamRed[$i];
            }
        }

        $i = 0;
        foreach ($total as $ship) {
            if (!$ship->getAlive()) {
                continue; // Skip dead ships
            }
            if ($ship->getName() == "Carriership") {
                $CarriershipMoveset = array_merge($Carriership, $default);
                $randomIndex = rand(0, count($CarriershipMoveset) - 1);
                $selectedValue = $CarriershipMoveset[$randomIndex];
                $functions = $selectedValue;
            } elseif ($ship->getName() == "Healer") {
                $HealerMoveset = array_merge($healer, $default);
                $randomIndex = rand(0, count($HealerMoveset) - 1);
                $selectedValue = $HealerMoveset[$randomIndex];
                $functions = $selectedValue;
            } elseif ($ship->getName() == "fightership") {
                $FighterMoveset = array_merge($fighter, $default);
                $randomIndex = rand(0, count($FighterMoveset) - 1);
                $selectedValue = $FighterMoveset[$randomIndex];
                $functions = $selectedValue;
            } else {
                $randomIndex = rand(0, count($default) - 1);
                $selectedValue = $default[$randomIndex];
                $functions = $selectedValue;
            }
            $validTargets = [];
            switch ($functions) {
                case "move":
                    $ship->move(rand(10, 25), rand(10, 25));
                    $this->setBattleNotes($ship->getName() . " of team " . $ship->get_team() . " moved to " . $ship->getLocation()[0] . " " . $ship->getLocation()[1]);
                    break;
                case "shoot":
                    foreach ($total as $target) {
                        if ($target->getAlive() && strcmp($target->get_team(), $ship->get_team()) !== 0) {
                            $validTargets[] = $target;
                        }
                    }
                    $randTarget = rand(1, count($validTargets)-1);
                    if (isset($validTargets[$randTarget])) {
                        $target = $validTargets[$randTarget];
                        $damage = $ship->shoot($target);
                        $this->setBattleNotes($ship->getName() . " of team " . $ship->get_team() . " shot at " . $target->getName() . " of team " . $target->get_team() . " and did " . $damage . " damage. enemy has now " . $target->getHitPoints() . " hitpoints left.");
                        if (!$target->getAlive()) {
                            $this->setBattleNotes($target->getName() . " of team " . $target->get_team() . " has been destroyed.");
                        }
                    }
                    break;
                case "boost":
                    $ship->boost();
                    $this->setBattleNotes($ship->getName() . " of team " . $ship->get_team() . " used boost and has now " . $ship->getboost() . " boost left.");
                    break;
                case "giveheal":
                    $ship->giveheal();
                    $this->setBattleNotes($ship->getName() . " of team " . $ship->get_team() . " gave heal and has now " . $ship->getHitpointsInTank() . " hitpoints in tank left.");
                    break;
                case "giveFuel":
                    $ship->giveFuel();
                    $this->setBattleNotes($ship->getName() . " of team " . $ship->get_team() . " gave fuel and has now " . $ship->getFuelInTank() . " fuel in tank left.");
            }
            $i++;
            // if ($i >= count($total)) {
            //     var_dump($this->getBattleNotes());
            //     die();
            // }
        }
    }
}
