<?php
require_once 'Spaceship.php';
session_start();
const BATTLE_VERSION = 4;

function createRandomShip(string $type, string $name, array $location): Spaceship
{
    if ($type === 'fighter') {
        return new Fightership($name, 200, 120, 123, $location);
    }
    if ($type === 'healer') {
        $ship = new Healer();
        $ship->setName($name);
        $ship->setLocation($location);
        return $ship;
    }
    if ($type === 'carrier') {
        return new Carriership($name, 200, 210, 121, $location);
    }
    return new Spaceship($name, 200, 1000, 100, $location);
}

function createBattle(): Battle
{
    $types = ['scout', 'fighter', 'healer', 'carrier'];
    $blueTypes = [$types[array_rand($types)], $types[array_rand($types)]];
    $redTypes = [$types[array_rand($types)], $types[array_rand($types)]];
    return new Battle('',
        [
            createRandomShip($redTypes[0], ucfirst($redTypes[0]) . ' Red 1', [25, 5]),
            createRandomShip($redTypes[1], ucfirst($redTypes[1]) . ' Red 2', [25, 25]),
        ],
        [
            createRandomShip($blueTypes[0], ucfirst($blueTypes[0]) . ' Blue 1', [5, 5]),
            createRandomShip($blueTypes[1], ucfirst($blueTypes[1]) . ' Blue 2', [8, 8]),
        ]
    );
}

if (isset($_POST['reset']) || !isset($_SESSION['battle']) || ($_SESSION['battle_version'] ?? null) !== BATTLE_VERSION) {
    $_SESSION['battle'] = createBattle();
    $_SESSION['battle_version'] = BATTLE_VERSION;
}
if (isset($_POST['next_turn']) && $_SESSION['battle']->get_win() === '') {
    $_SESSION['battle']->battle1();
}

$battle = $_SESSION['battle'];
$ships = array_merge($battle->getTeamBlue(), $battle->getTeamRed());
$gridSize = 30;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spaceboot</title>
    <style>
        body { margin: 0; background: #10151f; color: #e8eef8; font: 16px system-ui, sans-serif; }
        main { max-width: 860px; margin: auto; padding: 28px 16px; }
        header { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; }
        h1 { color: #77d8ff; } .turn { color: #aab8ca; }
        .latest { max-width: 440px; padding: 12px 14px; border: 1px solid #30445d; border-radius: 6px; background: #182334; }
        .latest-title { margin: 0 0 6px; color: #77d8ff; font-size: .85rem; text-transform: uppercase; letter-spacing: .08em; }
        .latest-events { margin: 0; color: #d5dfec; line-height: 1.45; }
        .battle-layout { display: grid; grid-template-columns: 250px 1fr; gap: 24px; align-items: start; }
        .fleet-panel { display: grid; gap: 10px; }
        .fleet-title { margin: 0 0 2px; color: #77d8ff; font-size: 1rem; }
        .ship-card { padding: 12px; border: 1px solid #30445d; border-left: 4px solid #8091a8; border-radius: 6px; background: #182334; }
        .ship-card.blue { border-left-color: #42b9f5; } .ship-card.red { border-left-color: #f06d76; }
        .ship-name { display: flex; justify-content: space-between; gap: 8px; margin: 0 0 8px; font-weight: 700; }
        .ship-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 10px; margin: 0; color: #b8c6d8; font-size: .85rem; }
        .ship-stats dt { color: #8091a8; } .ship-stats dd { margin: 0; text-align: right; color: #e8eef8; }
        .arena { min-width: 0; }
        .board { display: grid; grid-template-columns: repeat(30, minmax(8px, 1fr)); gap: 2px; max-width: 650px; }
        .cell { aspect-ratio: 1; display: grid; place-items: center; background: #1d2a3b; border: 1px solid #30445d; font-size: clamp(7px, 2vw, 15px); }
        .blue { background: #164d70; } .red { background: #713339; }
        .legend, .notes { color: #b8c6d8; } .notes { line-height: 1.6; white-space: pre-line; }
        .actions { display: flex; gap: 10px; margin: 18px 0; }
        button { border: 0; border-radius: 4px; padding: 10px 16px; font-weight: 700; cursor: pointer; }
        button[name="next_turn"] { background: #77d8ff; color: #0d1924; } button[name="reset"] { background: #354457; color: #fff; }
        @media (max-width: 760px) { .battle-layout { grid-template-columns: 1fr; } .fleet-panel { grid-template-columns: repeat(2, minmax(0, 1fr)); } .fleet-title { grid-column: 1 / -1; } }
        @media (max-width: 480px) { header { flex-direction: column; } .latest { max-width: none; } .fleet-panel { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<main>
    <header>
        <div><h1>Spaceboot</h1><div class="turn">Turn <?= $battle->getTurn() ?><?= $battle->get_win() ? ' · ' . ucfirst($battle->get_win()) . ' wins' : '' ?></div></div>
        <aside class="latest">
            <p class="latest-title">Latest turn</p>
            <p class="latest-events"><?= $battle->getLastTurnNotes() ? implode('<br>', $battle->getLastTurnNotes()) : 'No turns played yet.' ?></p>
        </aside>
    </header>
    <div class="battle-layout">
        <aside class="fleet-panel">
            <h2 class="fleet-title">Fleet status</h2>
            <?php foreach ($ships as $ship): ?>
                <section class="ship-card <?= $ship->get_team() ?>">
                    <p class="ship-name"><span><?= htmlspecialchars($ship->getName()) ?></span><span><?= $ship->getAlive() ? 'ALIVE' : 'DESTROYED' ?></span></p>
                    <dl class="ship-stats">
                        <dt>Team</dt><dd><?= ucfirst($ship->get_team()) ?></dd>
                        <dt>HP</dt><dd><?= $ship->getHitPoints() ?></dd>
                        <dt>Ammo</dt><dd><?= $ship->getAmmo() ?></dd>
                        <dt>Fuel</dt><dd><?= (int) $ship->getfuel() ?></dd>
                        <dt>Position</dt><dd><?= implode(', ', $ship->getLocation()) ?></dd>
                        <?php if ($ship instanceof Fightership): ?><dt>Boost</dt><dd><?= $ship->getboost() ?></dd><?php endif; ?>
                        <?php if ($ship instanceof Healer): ?><dt>Heal tank</dt><dd><?= $ship->getHitpointsInTank() ?></dd><?php endif; ?>
                        <?php if ($ship instanceof Carriership): ?><dt>Fuel tank</dt><dd><?= $ship->getFuelInTank() ?></dd><?php endif; ?>
                    </dl>
                </section>
            <?php endforeach; ?>
        </aside>
        <section class="arena">
            <p class="legend">Blue fleet: B · Red fleet: R. Each ship chooses one action per turn.</p>
            <div class="board" aria-label="30 by 30 battle grid">
                <?php for ($y = 0; $y < $gridSize; $y++): ?>
                    <?php for ($x = 0; $x < $gridSize; $x++): ?>
                        <?php $occupants = array_filter($ships, fn($ship) => $ship->getAlive() && $ship->getLocation() === [$x, $y]); ?>
                        <div class="cell <?= $occupants ? $occupants[array_key_first($occupants)]->get_team() : '' ?>">
                            <?= $occupants ? count($occupants) : '' ?>
                        </div>
                    <?php endfor; ?>
                <?php endfor; ?>
            </div>
            <form class="actions" method="post">
                <?php if (!$battle->get_win()): ?><button name="next_turn" type="submit">Next turn</button><?php endif; ?>
                <button name="reset" type="submit">New battle</button>
            </form>
            <div class="notes"><?= $battle->getBattleNotes() ?: 'Choose Next turn to begin.' ?></div>
        </section>
    </div>
</main>
</body>
</html>
