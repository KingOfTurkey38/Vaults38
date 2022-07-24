-- #!sqlite
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
REPLACE INTO vaults(username, number, data) VALUES(:username, :number, :data);
-- #  }

-- #}