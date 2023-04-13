CREATE TABLE floppy_state.Accounts (
account_id int,
username varchar(255),
passwordkey varchar(255),
PRIMARY KEY (account_id)
);

CREATE TABLE floppy_state.Leaderboard (
position_id int,
account_id int,
username varchar(255),
score int,
PRIMARY KEY (position_id)
FOREIGN KEY (account_id) REFERENCES Accounts(account_id),
FOREIGN KEY (username) REFERENCES Accounts(username)
);