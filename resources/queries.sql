-- #! mysql //directly copied from https://github.com/Muqsit/PlayerVaults/blob/842ff5c68db0da3711146b5359c2111597da4683/resources/psfs/mysql.sql
-- #{ vaults38

-- #  { init
CREATE TABLE IF NOT EXISTS vaults(
    username VARCHAR(25) NOT NULL,
    number TINYINT UNSIGNED NOT NULL,
    data BLOB NOT NULL,
    PRIMARY KEY(username, number)
);
-- #  }

-- #  { load
-- #    :username string
-- #    :number int
SELECT data FROM vaults WHERE username=:username AND number=:number;
-- #  }

-- #  { save
-- #    :username string
-- #    :number int
-- #    :data string
INSERT INTO vaults(username, number, data) VALUES(:username, :number, :data)
ON DUPLICATE KEY UPDATE data=VALUES(data);
-- #  }

-- #}