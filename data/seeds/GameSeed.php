<?php

use Phinx\Seed\AbstractSeed;

/**
 * Class GameSeed
 *
 * @codingStandardsIgnoreStart
 * @SuppressWarnings(PHPMD)
 */
class GameSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $currentDate    = new \DateTime();
        $applicationEnv = getenv('APP_ENV') === false ? 'production' : getenv('APP_ENV');
        $gamesToAdd     = [];
        $gamesToRemove  = [];
        $gamesToEdit    = [];
        $currentGames   = [];

        $gameList       = require __DIR__ . '/../../config/games/games.' . $applicationEnv . '.php';

        $gameList       = $gameList['games'][$applicationEnv];
        try {
            $existingStmt   = $this->query('SELECT * FROM games');
        } catch (\PDOException $exception) {
            if ($exception->getCode() != 23000) {
                $this->getOutput()->writeLn(
                    sprintf(
                        'Got Exception When trying to fetch game list: %s',
                        $exception->getMessage()
                    )
                );
            }
            throw $exception;
        }

        // Find all current games in the the DB
        foreach ($existingStmt as $key => $value) {
            $gameId = $value['game_id'];
            if (!isset($gameList[$gameId])) {
                $this->getOutput()->writeln(sprintf('The game "%s" is no longer in the list', $gameId));
                array_push($gamesToRemove, $gameId);
                continue;
            }

            $currentGames[$gameId] = $value;
        }

        // Check if the games have changed
        foreach ($currentGames as $gameId => $gameData) {
            $gameConfig = $gameList[$gameId];
            $editGame   = false;

            if ($gameData['coming_soon'] != $gameConfig['coming_soon']) {
                $this->getOutput()->writeln(sprintf('The game "%s" has changed coming soon', $gameId));
                $gameData['coming_soon'] = $gameConfig['coming_soon'];
                $editGame                = true;
            }

            if ($gameData['title'] !== $gameConfig['title']) {
                $this->getOutput()->writeln(sprintf('The game "%s" has title', $gameId));
                $gameData['title'] = $gameConfig['title'];
                $editGame          = true;
            }

            if ($gameData['description'] !== $gameConfig['description']) {
                $this->getOutput()->writeln(sprintf('The game "%s" has description', $gameId));
                $gameData['description'] = $gameConfig['description'];
                $editGame                = true;
            }

            if ($gameData['meta'] !== $gameConfig['meta']) {
                $this->getOutput()->writeln(sprintf('The game "%s" has meta', $gameId));
                $gameData['meta'] = $gameConfig['meta'];
                $editGame                = true;
            }

            if ($editGame) {
                $gameData['updated'] = $currentDate->format('Y-m-d H:i:s');
                $gamesToEdit[$gameId] = $gameData;
            }
        }

        // check for new games
        foreach ($gameList as $gameId => $gameData) {
            if (isset($currentGames[$gameId])) {
                // means we already have the game
                continue;
            }

            $this->getOutput()->writeln(sprintf('New game found "%s"', $gameId));
            $gameData['created'] = $currentDate->format('Y-m-d H:i:s');
            $gameData['updated'] = $currentDate->format('Y-m-d H:i:s');
            array_push($gamesToAdd, $gameData);
        }

        $this->getOutput()->writeln(sprintf('Env: %s', $applicationEnv));
        $this->getOutput()->writeln(sprintf('Total Games in config: %d', count($gameList)));
        $this->getOutput()->writeln(sprintf('Total Games to found: %d', count($currentGames)));
        $this->getOutput()->writeln(sprintf('Total Games to remove: %d', count($gamesToRemove)));
        $this->getOutput()->writeln(sprintf('Total Games to add: %d', count($gamesToAdd)));
        $this->getOutput()->writeln(sprintf('Total Games to edit: %d', count($gamesToEdit)));

        // remove games
        foreach ($gamesToRemove as $gameId) {
            try {
                $this->getOutput()->writeln(sprintf('Removing Game "%s"', $gameId));
                $this->query(sprintf(
                    "DELETE FROM games WHERE game_id='%s'",
                    $gameId
                ));
            } catch (\PDOException $exception) {
                if ($exception->getCode() != 23000) {
                    $this->getOutput()->writeLn(
                        sprintf(
                            'Got Exception When trying to remove game "%s": %s',
                            $gameId,
                            $exception->getMessage()
                        )
                    );
                }
            }
        }

        // edit games
        foreach ($gamesToEdit as $gameId => $gameData) {
            try {
                $this->getOutput()->writeln(sprintf('Editing Game "%s"', $gameId));
                $this->query(sprintf(
                    "UPDATE games SET title = \"%s\", description = \"%s\", updated = '%s', meta = '%s'  WHERE game_id='%s'",
                    $gameData['title'],
                    $gameData['description'],
                    $gameData['updated'],
                    $gameData['meta'],
                    $gameId
                ));
            } catch (\PDOException $exception) {
                if ($exception->getCode() != 23000) {
                    $this->getOutput()->writeLn(
                        sprintf(
                            'Got Exception When trying to edit game "%s": %s',
                            $gameId,
                            $exception->getMessage()
                        )
                    );
                }
            }
        }

        $table = $this->table('games');
        // add games
        foreach ($gamesToAdd as $gameData) {
            try {
                $this->getOutput()->writeln(sprintf('Adding Game "%s"', $gameData['game_id']));
                $table->insert($gameData)
                ->saveData();
                $table->setData([]);
            } catch (\PDOException $exception) {
                if ($exception->getCode() != 23000) {
                    $this->getOutput()->writeLn(
                        sprintf(
                            'Got Exception When trying to edit game "%s": %s',
                            $gameId,
                            $exception->getMessage()
                        )
                    );
                }
            }
        }
    }
}
