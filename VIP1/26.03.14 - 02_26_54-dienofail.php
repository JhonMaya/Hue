<?php exit() ?>--by dienofail 68.48.159.9
local Saved = GetSave("Camps2")
local LastPing = 0
local LastChat = 0
local ID = myHero.networkID..myHero.charName
	for i, enemy in ipairs(GetEnemyHeroes()) do
			ID =  ID..enemy.networkID
	end

 if not tostring(Saved["gameid"]):find(ID) then
		
		Saved["gameid"] = ID
		Saved["camps"] = 
		{	-- Camp name 			Camp side  				Map Position 									UI position 									ID 				Rate(?)				NextRespawn			 FirstSpawn
			--BLUE ["side"]
			{["cname"] = "Wraiths", ["side"] = TEAM_BLUE, 	["position"] = {["x"] = 6423, ["z"] =  5208}, 	["sposition"] = {["x"] = 500, ["z"] =  925},	["Id"] = 3, 	["Rate"] = 50, 		["NextRespawn"] = 0, ["FirstSpawn"] = 125},
			{["cname"] = "Wolves", 	["side"] = TEAM_BLUE, 	["position"] = {["x"] = 3317, ["z"] =  6215}, 	["sposition"] = {["x"] = 500, ["z"] =  950},	["Id"] = 2, 	["Rate"] = 50, 		["NextRespawn"] = 0, ["FirstSpawn"] = 125},
			{["cname"] = "Golems", 	["side"] = TEAM_BLUE, 	["position"] = {["x"] = 8024, ["z"] =   2433},	["sposition"] = {["x"] = 500, ["z"] =  975}, 	["Id"] = 5, 	["Rate"] = 50, 		["NextRespawn"] = 0, ["FirstSpawn"] = 125},
			{["cname"] = "Wight", 	["side"] = TEAM_BLUE, 	["position"] = {["x"] = 1688, ["z"] =   8248},	["sposition"] = {["x"] = 500, ["z"] =  1000}, 	["Id"] = 13, 	["Rate"] = 50, 		["NextRespawn"] = 0, ["FirstSpawn"] = 125},
			
			{["cname"] = "Red", 	["side"] = TEAM_BLUE, 	["position"] = {["x"] = 7420, ["z"] =   3733},	["sposition"] = {["x"] = 500, ["z"] =  1025}, 	["Id"] = 4, 	["Rate"] = 5*60, 	["NextRespawn"] = 0, ["FirstSpawn"] = 115},
			{["cname"] = "Blue", 	["side"] = TEAM_BLUE,	["position"] = {["x"] = 3647, ["z"] =  7572}, 	["sposition"] = {["x"] = 500, ["z"] =  1050},	["Id"] = 1, 	["Rate"] = 5*60, 	["NextRespawn"] = 0, ["FirstSpawn"] = 115},
			
			--RED ["side"]
			{["cname"] = "Wraiths", ["side"] = TEAM_RED, 	["position"] = {["x"] = 7491,["z"] =  9264},  	["sposition"] = {["x"] = 1380, ["z"] =  925},	["Id"] = 9, 	["Rate"] = 50, 		["NextRespawn"] = 0, ["FirstSpawn"] = 125},
			{["cname"] = "Wolves", 	["side"] = TEAM_RED, 	["position"] = {["x"] = 10517,["z"] =  8119}, 	["sposition"] = {["x"] = 1380, ["z"] =  950},	["Id"] = 8, 	["Rate"] = 50, 		["NextRespawn"] = 0, ["FirstSpawn"] = 125},
			{["cname"] = "Golems", 	["side"] = TEAM_RED, 	["position"] = {["x"] = 5974,["z"] =  12012}, 	["sposition"] = {["x"] = 1380, ["z"] =  975},	["Id"] = 11, 	["Rate"] = 50, 		["NextRespawn"] = 0, ["FirstSpawn"] = 125},
			{["cname"] = "Wight", 	["side"] = TEAM_RED, 	["position"] = {["x"] = 12266,["z"] =  6215}, 	["sposition"] = {["x"] = 1380, ["z"] =  1000},	["Id"] = 14, 	["Rate"] = 50, 		["NextRespawn"] = 0, ["FirstSpawn"] = 125},
			
			{["cname"] = "Red", 	["side"] = TEAM_RED, 	["position"] = {["x"] = 6436,["z"] =  10524}, 	["sposition"] = {["x"] = 1380, ["z"] =  1025},	["Id"] = 10, 	["Rate"] = 5*60, 	["NextRespawn"] = 0, ["FirstSpawn"] = 115},
			{["cname"] = "Blue", 	["side"] = TEAM_RED, 	["position"] = {["x"] = 10480,["z"] =  6860}, 	["sposition"] = {["x"] = 1380, ["z"] =  1050},	["Id"] = 7, 	["Rate"] = 5*60, 	["NextRespawn"] = 0, ["FirstSpawn"] = 115},

			--BARON & DRAGON
			{["cname"] = "Dragon", 	["side"] = TEAM_NEUTRAL, ["position"] = {["x"] = 9455,["z"] =  4272}, 	["sposition"] = {["x"] = 930, ["z"] =  5},		["Id"] = 6, 	["Rate"] = 6*60, 	["NextRespawn"] = 0, ["FirstSpawn"] = 2*60+30},
			{["cname"] = "Baron", 	["side"] = TEAM_NEUTRAL, ["position"] = {["x"] = 4490,["z"] =  10153},	["sposition"] = {["x"] = 970, ["z"] =  5}, 		["Id"] = 12, 	["Rate"] = 7*60, 	["NextRespawn"] = 0, ["FirstSpawn"] = 15*60},
		
		}
		NewGame = true
	end
dCoords = { x = 0, y = 0 }

function OnLoad()
	PrintChat("<font color=\"#00FF00\">".."ExLoaded")
	bg = GetWebSprite("http://i.imgur.com/1OCkyNL.png")
	local Camps = Saved["camps"]
	Menu = scriptConfig("JungleTimers", "JungleTimers")
	Menu:addSubMenu("Camps(Bugged)", "Camps")
	for i, camp in ipairs(Camps) do
		Menu.Camps:addParam(camp["side"]..camp["cname"], (camp["side"] == TEAM_RED and "RED" or camp["side"] == TEAM_BLUE and "BLUE" or "NEUTRAL").." - "..camp["cname"], SCRIPT_PARAM_ONOFF, true)
	end
	Menu:addSubMenu("Ping", "Ping")
		Menu.Ping:addParam("Time", "Time(Not working, 60 secs default)", SCRIPT_PARAM_SLICE, 60, 0, 60)
		Menu.Ping:addParam("Baron", "Baron", SCRIPT_PARAM_ONOFF, true)
		Menu.Ping:addParam("Dragon", "Dragon", SCRIPT_PARAM_ONOFF, true)
		Menu.Ping:addParam("Blue", "Blue buff", SCRIPT_PARAM_ONOFF, true)
		Menu.Ping:addParam("Red", "Red buff", SCRIPT_PARAM_ONOFF, true)
		Menu.Ping:addParam("Enabled", "Ping before respawn", SCRIPT_PARAM_ONOFF, true)
			Menu.Ping:addSubMenu("Type", "S")
				Menu.Ping.S:addParam("N", "Number of pings", SCRIPT_PARAM_SLICE, 3, 0, 10)
				Menu.Ping.S:addParam("I", "Time between pings", SCRIPT_PARAM_SLICE, 1000, 0, 1000)
				Menu.Ping.S:addParam("T", "Time between pings", SCRIPT_PARAM_LIST, 1, {"Normal", "Danger" })
			

	
	Menu:addSubMenu("Chat", "Chat")
		Menu.Chat:addParam("Time", "Time", SCRIPT_PARAM_SLICE, 60, 0, 60)
		Menu.Chat:addParam("Baron", "Baron", SCRIPT_PARAM_ONOFF, true)
		Menu.Chat:addParam("Dragon", "Dragon", SCRIPT_PARAM_ONOFF, true)
		Menu.Chat:addParam("Blue", "Blue buff", SCRIPT_PARAM_ONOFF, true)
		Menu.Chat:addParam("Red", "Red buff", SCRIPT_PARAM_ONOFF, true)
		Menu.Chat:addParam("Enabled", "Chat before respawn", SCRIPT_PARAM_ONOFF, true)
		
	Menu:addSubMenu("Appearance", "Appearance")
		Menu.Appearance:addParam("Background", "Background", SCRIPT_PARAM_ONOFF, true)
		Menu.Appearance:addParam("size", "Font size", SCRIPT_PARAM_SLICE, 18, 10, 20)
		Menu.Appearance:addParam("Ucolor", "UP color", SCRIPT_PARAM_COLOR, {255,0,255,0})
		Menu.Appearance:addParam("Dcolor", "Timer(Down) color", SCRIPT_PARAM_COLOR, {255,255,0,0})
		Menu.Appearance:addParam("Tcolor", "Text color", SCRIPT_PARAM_COLOR, {255,255,255,0})
		
	Menu:addSubMenu("BuffExploit", "BuffExploit")
		Menu.BuffExploit:addParam("Ping", "Ping", SCRIPT_PARAM_ONOFF, true)
end

function RecPing(X, Y)
	local type = 0
	if Menu.Ping.S.T == 1 then
		type = PING_NORMAL
	else
		type = PING_FALLBACK
	end
	Packet("R_PING", {x = X, y = Y, type = type}):receive()
end

function HasBuff(unit, buffname)
	for i = 1, unit.buffCount, 1 do	
			local buff = unit:getBuff(i)
            if buff.startT<=GetGameTimer() and buff.endT>=GetGameTimer() then
				if buff.name == buffname then
					return buff.startT
				end
			end
	end
	return 0
end

function OnTick()
	local Camps = Saved["camps"]

	for i, camp in ipairs(Camps) do
		if math.floor(camp["NextRespawn"] - os.clock()) ==  60 then
			if Menu.Chat.Enabled and Menu.Ping[camp["cname"]] and Menu.Ping.Enabled and (os.clock() - LastChat) > 2 then
				PrintChat("<font color=\"#FF0000\">"..camp["cname"].." will respawn in 60 seconds")
			end
			LastChat = os.clock()
		end
	
		if math.floor(camp["NextRespawn"] - os.clock()) ==  60 then
			if Menu.Ping[camp["cname"]] and Menu.Ping.Enabled and (os.clock() - LastPing) > 2 then
				for i = 1, Menu.Ping.S.N do
					DelayAction(RecPing, Menu.Ping.S.I * i/1000, {camp["position"]["x"], camp["position"]["z"]})
				end
				LastPing = os.clock()
			end
		end
	end
end
	
Packet.headers.PKT_S2C_Neutral_Camp_Empty = 194
function OnRecvPacket(p)
	local Camps = Saved["camps"]
	if p.header == Packet.headers.PKT_S2C_Neutral_Camp_Empty then
		local packet = Packet(p)
		--print(packet)
		if packet:get("emptyType") ~= 3 then--3 found empty
			for i, camp in ipairs(Camps) do
				if camp["Id"] == packet:get("campId") then
					Camps[i]["NextRespawn"] = os.clock() + camp["Rate"]
				end
			end
		end
	end
	
	if p.header == 0x17 then
		p.pos = 1
		local networkID = p:DecodeF()
		local object1 = objManager:GetObjectByNetworkId(networkID)
		p.pos = 7
		if object1 and object1.valid and (not object1.visible ) then
			local name = ""
			for i=7, p.size-9, 1 do
				name = name .. string.char(p:Decode1())
			end
			
			if ("BlessingOfTheLizardElderLines"):find(name) then
				local Dist = math.huge
				local id = 0
				for i, camp in ipairs(Camps) do
					if camp["cname"] == "Red" and camp["NextRespawn"] <= os.clock() and (GetDistance(object1, Vector(camp["position"]["x"],0,camp["position"]["z"])) < Dist) then
						 id = i
						 Dist = GetDistance(object1, Vector(camp["position"]["x"],0,camp["position"]["z"]))
					end
				end
				if id ~= 0 then
					Camps[id]["NextRespawn"] = os.clock() + 10 + Camps[id]["Rate"]
					if Menu.BuffExploit.Ping then
						RecPing(Camps[id]["position"]["x"], Camps[id]["position"]["z"])
					end
				end
			end
			
			if ("CrestOfTheAncientGolemLines"):find(name) then
				local Dist = math.huge
				local id = 0
				for i, camp in ipairs(Camps) do
					if camp["cname"] == "Blue" and camp["NextRespawn"] <= os.clock() and (GetDistance(object1, Vector(camp["position"]["x"],0,camp["position"]["z"])) < Dist) then
						 id = i
						 Dist = GetDistance(object1, Vector(camp["position"]["x"],0,camp["position"]["z"]))
					end
				end
				if id ~= 0 then
					Camps[id]["NextRespawn"] = os.clock() + 10 + Camps[id]["Rate"]
					if Menu.BuffExploit.Ping then
						RecPing(Camps[id]["position"]["x"], Camps[id]["position"]["z"])
					end
				end
			end
			
			if ("WormAttackInitial"):find(name) then
				for i, camp in ipairs(Camps) do
					if camp["cname"] == "Baron" and camp["NextRespawn"] <= os.clock() then
						Camps[i]["NextRespawn"] = os.clock() + camp["Rate"]
						if Menu.BuffExploit.Ping then
							RecPing(camp["position"]["x"], camp["position"]["z"])
						end
					end
				end
			end
		end
	elseif p.header == 0xE8 then 
	
		p.pos = 5
		local x = p:DecodeF()
		local y = p:DecodeF()
		local z = p:DecodeF()
		--dprint('x: ' .. tostring(x) .. ' y: ' .. tostring(y) .. 'z: ' .. tostring(z))
		--local objectNetworkId = p:DecodeF()
		p.pos = p.size - 3
		--local pos = p.pos
		local num = p:Decode1()
		--print(pos, ': campId: ', num)
		
		for i, camp in ipairs(Camps) do
			if camp["Id"] == num then
				Camps[i]["NextRespawn"] = os.clock() - 1
			end
		end
				
	end
end

function OnReady()
	if NewGame then
		local Camps = Saved["camps"]
		for i, camp in ipairs(Camps) do
			Camps[i]["NextRespawn"] = os.clock() + Camps[i]["FirstSpawn"]
		end
	end
end

function TimeToText(t)
	local m = 0
	while t > 60 do
		m = m + 1
		t = t - 60
	end
	if t < 10 then
		t = "0"..t
	end
	return m..":"..t
end

function OnDraw()
	if Menu.Appearance.Background then
		bg:Draw(dCoords.x,dCoords.y,255)
	end
	--Blue Team Monsters
	DrawText("Wraiths:", 	Menu.Appearance.size, 425, 925, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Wolves:",		Menu.Appearance.size, 425, 950, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Golems:",		Menu.Appearance.size, 425, 975, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Wight:", 		Menu.Appearance.size, 425, 1000, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Red:", 		Menu.Appearance.size, 425, 1025, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Blue:", 		Menu.Appearance.size, 425, 1050, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	--Red Team Monsters
	DrawText("Wraiths:",	Menu.Appearance.size, 1305, 925, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Wolves:", 	Menu.Appearance.size, 1305, 950, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Golems:", 	Menu.Appearance.size, 1305, 975, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Wight:", 		Menu.Appearance.size, 1305, 1000, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Red:", 		Menu.Appearance.size, 1305, 1025, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Blue:", 		Menu.Appearance.size, 1305, 1050, 	ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	--Neutral Monsters
	DrawText("Dragon", 		Menu.Appearance.size, 870, 4, 		ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]))
	DrawText("Baron", 		Menu.Appearance.size, 1000, 4, 		ARGB(Menu.Appearance.Tcolor[1],Menu.Appearance.Tcolor[2],Menu.Appearance.Tcolor[3],Menu.Appearance.Tcolor[4]));

	local Camps = Saved["camps"]
	for i, camp in ipairs(Camps) do
		if os.clock() - (camp["NextRespawn"]) < 0 then --and Menu.Camps(camp["side"]..camp["cname"]) then
			local t = TimeToText(- math.floor(os.clock() - camp["NextRespawn"]))
			local Pointx = camp["sposition"].x
			local Pointy = camp["sposition"].z
			DrawText(tostring(t), Menu.Appearance.size, Pointx, Pointy, ARGB(Menu.Appearance.Dcolor[1],Menu.Appearance.Dcolor[2],Menu.Appearance.Dcolor[3],Menu.Appearance.Dcolor[4]))
		elseif os.clock() - (camp["NextRespawn"]) > 0 then
			local Pointx = camp["sposition"].x
			local Pointy = camp["sposition"].z
			DrawText("UP",  Menu.Appearance.size, Pointx, Pointy, ARGB(Menu.Appearance.Ucolor[1],Menu.Appearance.Ucolor[2],Menu.Appearance.Ucolor[3],Menu.Appearance.Ucolor[4]))
			--if Menu.Appearance.Border then
				--DrawText(tostring(t), 13, 100, 100, 0xFFFFFF00)
			--end
		end
	end
end
