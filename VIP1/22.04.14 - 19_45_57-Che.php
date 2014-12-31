<?php exit() ?>--by Che 91.8.137.34
if (myHero.charName ~= "Ashe" and myHero.charName ~= "Ezreal" and myHero.charName ~= "Draven" and myHero.charName ~= "Jinx") then return end

local enemies = {}
local RecallTable = {}
local RecallTypes = {}
local Spawn
local SpellData = {}
local BlockedMovement = false
local LastDestination = nil
local TimerCollision = nil
local TimerRecDet = nil
_G.SpawnKill = nil
_G.Check = Base64Encode(_G.GetUser())

if Check ~= "b2xvcmlu" then 
	print("auth failed, gtfo faggot")
return end


function OnLoad()
	Spawn = GetEnemySpawnPos()
	RecallTypes = {[1] = 8000, [8] = 4500, [10] = 8000}

	if myHero.charName == "Ashe" then
		SpellData = {Speed = 1600, Delay = 0.125, Width = 130}
	elseif myHero.charName == "Ezreal" then
		SpellData = {Speed = 2000, Delay = 1.0, Width = 160}
	elseif myHero.charName == "Draven" then
		SpellData = {Speed = 2000, Delay = 0.4, Width = 160}
	elseif myHero.charName == "Jinx" then
		SpellData = {Delay = 0.5, Width = 150 }
	end
	Menu = scriptConfig('SpawnKiller', 'SK')
	Menu:addParam('All', 'Ult All', SCRIPT_PARAM_ONOFF, true)
	enemies = GetEnemyHeroes()
	for i,v in ipairs(enemies) do
		Menu:addParam('ult' .. v.charName, 'Ult ' .. v.charName, SCRIPT_PARAM_ONOFF, true)
	end
	print("<font color='#FF4000'>SpawnKill inc</font>")
	PrintAlert('Loaded SpawnKiller', 2, 0, 0, 204)
end

function OnTick()
	if _G.SpawnKill ~= nil and GetTickCount() > _G.SpawnKill + SpellData.Delay*1000 + GetLatency() then
		_G.SpawnKill = nil
	end
	for _, Enemy in pairs(RecallTable) do
		Enemy.rTime = Enemy.rTime - (GetTickCount() - Enemy.time)
		Enemy.time = GetTickCount()
		local Unit = objManager:GetObjectByNetworkId(Enemy.source)
		local TravelTime = GetTravelTime()
		--local Col = Collision(math.huge, GetSpeed(), SpellData.Delay, SpellData.Width)
				
		if myHero:CanUseSpell(_R) == READY and TravelTime >= Enemy.rTime and TravelTime < Enemy.rTime + 50 and getDmg("R", Unit, myHero) >= Unit.health + 20 and (Menu['ult' .. Unit.charName] or Menu.All) then
			--if not Col:GetHeroCollision(myHero, Spawn, HERO_ENEMY) then
				--TimerCollision = nil
				_G.SpawnKill = GetTickCount()
				PrintAlert('Ulting ' .. Unit.charName .. '!', 2, 204, 0, 0)
				--CastSpell(_R, Spawn.x, Spawn.z)
				Packet('S_CAST', { spellId = _R, fromX = Spawn.x, fromY = Spawn.z, toX = Spawn.X, toY = Spawn.Z}):send()
			--[[else
				if TimerCollision == nil then
					TimerCollision = os.time()	
					print("Can't SpawnKill " .. Unit.charName .. " Collision detected .")
				elseif os.difftime(os.time(),TimerCollision) >= 1 then
					TimerCollision = nil
				end	
			end]]
		end
	end
	RestoreMovement()
end

function OnRecvPacket(p)
	if p.header == 0xD7 then
		p.pos = 5
		local __source = p:DecodeF()
		p.pos = 112
		local __type = p:Decode1()
		local Unit = objManager:GetObjectByNetworkId(__source)
		if Unit == nil or not Unit.valid or Unit.team == myHero.team or Unit.team ~= TEAM_ENEMY then return end
		if RecallTable[__source] and __type == 4 then
			RecallTable[__source] = nil
		elseif RecallTable[__source] == nil and __type == 6 then
		if ( getDmg("R", Unit, myHero) >= Unit.health ) then
		Action_taken = "ULTING"
		else
		Action_taken = "Not enough DMG"
		end
			RecallTable[__source] = {source = __source, time = GetTickCount(), finish = GetTickCount() + RecallTypes[GetGame().map.index], rTime = RecallTypes[GetGame().map.index]}
			if TimerRecDet == nil then
					TimerRecDet = os.time()
					print("<font color='#04B431'>Enemy </font><font color='#FF4000'>" .. Unit.charName .. "</font> <font color='#04B431'>started Recall with </font><font color='#FF4000'>" .. math.round(Unit.health,0) .. " HP </font> " .. Action_taken .. "  ")
			elseif  os.difftime(os.time(),TimerRecDet) >= 1 then
					print("<font color='#04B431'>Enemy </font><font color='#FF4000'>" .. Unit.charName .. "</font> <font color='#04B431'>started Recall with </font><font color='#FF4000'>" .. math.round(Unit.health,0) .. " HP  </font>" .. Action_taken .. "  ")
			TimerRecDet = nil
			end
		end
	end
end

function OnSendPacket(p)
	local packet = Packet(p)

	if _G.SpawnKill ~= nil and packet:get('name') == 'S_MOVE' then
		if packet:get('sourceNetworkId') == myHero.networkID then
			lastDestination = Point(packet:get('x'), packet:get('y'))
			packet:block()
		end
	end
end

function RestoreMovement()
	if _G.SpawnKill == nil and LastDestination ~= nil then
		myHero:MoveTo(LastDestination.x, LastDestination.y)
		LastDestination = nil
	end
end

function GetTravelTime()
	return GetDistance(Spawn) / (GetSpeed()/1000) + SpellData.Delay*1000 + GetLatency()
end

function GetSpeed()
	if myHero.charName == "Jinx" then
		local Distance = GetDistance(Spawn)
		return (Distance > 1350 and (1350*1700+((Distance-1350)*2200))/Distance or 1700)
	else
		return SpellData.Speed
	end
end