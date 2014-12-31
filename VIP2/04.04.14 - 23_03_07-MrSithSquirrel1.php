<?php exit() ?>--by MrSithSquirrel1 121.98.53.201
-- Best script by Detective Squirrel

GameTime = 0
TimeToNextWrite = 30
TimeToNextLaugh = 1
LaughTime = 0
WroteTime = 0

function OnTick()
	GameTime = GetInGameTimer()
	
	if Menu.Snorting then
		Packet('R_WAYPOINTS', {wayPoints = {[myHero.networkID] = {{x=math.random(100, 12000), y=math.random(100, 16000)}}}}):receive()
		Packet("R_PING", {x=math.random(100, 12000), y=math.random(100, 16000), type = PING_FALLBACK}):receive()
		Packet("R_PING", {x=math.random(100, 12000), y=math.random(100, 16000), type = PING_DANGER}):receive()
		Packet("R_PING", {x=math.random(100, 12000), y=math.random(100, 16000), type = PING_ASSIST}):receive()
	end
	
	if Menu.pingMouse then
		--Packet('R_WAYPOINTS', {wayPoints = {[myHero.networkID] = {{x=math.random(100, 12000), y=math.random(100, 16000)}}}}):receive()
		--Packet("R_PING", {x=math.random(100, 12000), y=math.random(100, 16000), type = PING_FALLBACK}):receive()
		--Packet("R_PING", {x=math.random(100, 12000), y=math.random(100, 16000), type = PING_DANGER}):receive()
		Packet("S_PING", {x=mousePos.x, z=mousePos.z, type = PING_HELP}):send()
	end
	
	if Menu.yolo then
		if GameTime > LaughTime + TimeToNextLaugh then 
			SendChat("/l")
			LaughTime = GetInGameTimer()
		end
	end
end

function OnLoad()
	Menu = scriptConfig("Crack Cocaine Time?", "Crack Cocaine Time?")
	Menu:addParam("Snorting", "Here is your crack",  SCRIPT_PARAM_ONOFF, false)
	Menu:addParam("yolo", "I may be high",  SCRIPT_PARAM_ONOFF, false)
	Menu:addParam("pingMouse", "Ping Mouse",  SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))
end