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
        $ammo = 200,
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

    public function canShoot() {
        return $this->ammo >= 10;
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
    public function setName($name) {
        $this->name = $name;
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
    public function setLocation($location) {
        $this->location = $location;
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

        $this->ammo = 200;
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

    public function giveheal($target = null) {
        if ($target === null || $this->HitpointsInTank < $this->extraheal) return false;
        $target->hitPoints += $this->extraheal;
        $this->HitpointsInTank -= $this->extraheal;
        return true;
    }
}
class Carriership extends Spaceship {
    protected int $FuelInTank = 400;
    public function getFuelInTank() {
        return $this->FuelInTank;
    }
    public function giveFuel($target = null) {
        if ($target === null || $this->FuelInTank < $this->extrafuel) return false;
        $target->fuel += $this->extrafuel;
        $this->FuelInTank -= $this->extrafuel;
        return true;
    }
}

class Battle {
    protected string $win = "";
    protected string $battleNotes = "";
    protected string $Move;
    protected $teamRed;
    protected $teamBlue;
    protected array $lastTurnNotes = [];
    protected int $turn = 0;
    protected int $maxTurns = 1000;

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
    public function getTurn() {
        return $this->turn;
    }
    public function getLastTurnNotes() {
        return $this->lastTurnNotes;
    }
    public function setBattleNotes($notes) {
        $this->battleNotes = $this->battleNotes . "</br>" . $notes;
        $this->lastTurnNotes[] = $notes;
    }
    public function battle1() {
        if ($this->win !== "") return $this->win . " won";
        if ($this->allLivingShipsOutOfAmmo()) {
            $this->win = "draw";
            return "draw";
        }
        if ($this->turn >= $this->maxTurns) {
            $this->win = "draw";
            return "draw";
        }
        $this->turn++;
        $this->lastTurnNotes = [];
        $total = array_merge($this->teamBlue, $this->teamRed);
        foreach ($total as $ship) {
            if (!$ship->getAlive()) {
                continue;
            }
            $moveset = array("shoot", "move");
            if ($ship instanceof Fightership) $moveset[] = "boost";
            if ($ship instanceof Healer) $moveset[] = "giveheal";
            if ($ship instanceof Carriership) $moveset[] = "giveFuel";
            if (!$ship->canShoot()) $moveset = array_values(array_diff($moveset, array("shoot")));
            $action = $moveset[array_rand($moveset)];
            $validTargets = array_values(array_filter($total, fn($target) => $target !== $ship && $target->getAlive() && $target->get_team() === $ship->get_team()));
            switch ($action) {
                case "move":
                    $location = $ship->getLocation();
                    $ship->move(max(0, min(29, $location[0] + rand(-1, 1))), max(0, min(29, $location[1] + rand(-1, 1))));
                    $this->setBattleNotes($ship->getName() . " of team " . $ship->get_team() . " moved to " . $ship->getLocation()[0] . " " . $ship->getLocation()[1]);
                    break;
                case "shoot":
                    $enemyTargets = [];
                    foreach ($total as $target) {
                        if ($target->getAlive() && strcmp($target->get_team(), $ship->get_team()) !== 0) {
                            $enemyTargets[] = $target;
                        }
                    }
                    if (count($enemyTargets) > 0) {
                        $target = $enemyTargets[array_rand($enemyTargets)];
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
                    if (count($validTargets) > 0) {
                        $target = $validTargets[array_rand($validTargets)];
                        $ship->giveheal($target);
                        $this->setBattleNotes($ship->getName() . " healed " . $target->getName() . ".");
                    }
                    break;
                case "giveFuel":
                    if (count($validTargets) > 0) {
                        $target = $validTargets[array_rand($validTargets)];
                        $ship->giveFuel($target);
                        $this->setBattleNotes($ship->getName() . " refueled " . $target->getName() . ".");
                    }
            }
        }
        $this->updateWinner();
        if ($this->win === "" && $this->allLivingShipsOutOfAmmo()) $this->win = "draw";
        return $this->win ? $this->win . " won" : "turn complete";
    }
    protected function allLivingShipsOutOfAmmo() {
        $livingShips = array_filter(array_merge($this->teamBlue, $this->teamRed), fn($ship) => $ship->getAlive());
        return count($livingShips) > 0 && count(array_filter($livingShips, fn($ship) => $ship->getAmmo() >= 10)) === 0;
    }
    protected function updateWinner() {
        $blueAlive = count(array_filter($this->teamBlue, fn($ship) => $ship->getAlive()));
        $redAlive = count(array_filter($this->teamRed, fn($ship) => $ship->getAlive()));
        if ($blueAlive === 0) $this->win = "red";
        if ($redAlive === 0) $this->win = "blue";
    }
}
