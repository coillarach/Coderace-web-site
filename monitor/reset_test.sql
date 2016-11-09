set @a:=0;
update location set player_id = null, claimed = null, made_visible = null, visible = 0 where game_id = 1;
update location set visible = 1, made_visible = now() 
where id in (
	select id 
	from (
		select (@a:=@a+1) as rownum, id
		from (
			select id 
			from location
			where game_id = 1
			order by rand()
			) t
		) u
	where rownum < 5
);
update game set start = date_add(now(), interval 5 MINUTE), end = date_add(now(), interval 25 MINUTE) where  id = 1;
update player set device = null, last_activity = null, latitude = 90, longitude = 0 where team_id in 
(select id from team where game_id = 1);