CREATE TABLE `UserClubs` (
  `id` int(10) UNSIGNED NOT NULL,
  `club_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `dadeo_club`;


INSERT INTO userclubs(id,club_id)
SELECT id,club_id
FROM users;


SELECT userclubs.id,userclubs.club_id,name,firstname,lastname from userclubs
INNER JOIN club
on userclubs.club_id = club.id
LEFT JOIN users
on users.id = userclubs.id;


CREATE TABLE `Invite` (
  `id` int(10) UNSIGNED NOT NULL,
  `club_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `dadeo_club`;


CREATE TABLE `user_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender` int(10) UNSIGNED NOT NULL,
  `message` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `message_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `dadeo_club`;

select club.name as InvitedFromClub,invite.id as InvitedClubID,invite.club_id,users.firstname, users.lastname from invite
INNER JOIN users on invite.id = users.id
LEFT JOIN club
on invite.club_id = club.id;


DROP TABLE club_application;
CREATE TABLE club_application(
	application_id int NOT NULL AUTO_INCREMENT,
	user_id int,
	club_id int,
	status varchar(255),
	primary key(application_id)
);

INSERT INTO club_application(user_id, club_id, status)
SELECT id, club_id, 'inClub' FROM USERCLUBS;



CREATE TABLE `banned_users` (
  `banned_id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL,
  `reason` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `ban_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `dadeo_club`;

ALTER TABLE club ADD COLUMN club_score int DEFAULT 0;

ALTER TABLE club ADD COLUMN season int DEFAULT 2018;

ALTER TABLE user_messages ADD COLUMN message_tag varchar(255) DEFAULT NULL;

