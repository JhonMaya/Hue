<?php exit() ?>--by pqmailer 217.82.58.58
if (myHero.charName ~= "Ashe" and myHero.charName ~= "Ezreal" and myHero.charName ~= "Draven" and myHero.charName ~= "Jinx") or not VIP_USER then return end
require 'Collision'

local RecallTable = {}
local RecallTypes = {}
local Spawn
local SpellData = {}
local LastDestination = nil
_G.SpawnKill = nil

function OnLoad()
	Spawn = GetEnemySpawnPos()
	RecallTypes = {Recall = 8000, RecallImproved = 7000, OdinRecall = 4500, OdinRecallImproved = 4000}

	if myHero.charName == "Ashe" then
		SpellData = {Speed = 1600, Delay = 0.125, Width = 130}
	elseif myHero.charName == "Ezreal" then
		SpellData = {Speed = 2000, Delay = 1.0, Width = 160}
	elseif myHero.charName == "Draven" then
		SpellData = {Speed = 2000, Delay = 0.4, Width = 160}
	elseif myHero.charName == "Jinx" then
		SpellData = {Delay = 0.5, Width = 150}
	end

	PrintChat("SpawnKiller loaded")
end

function OnTick()
	if _G.SpawnKill ~= nil and GetTickCount() > _G.SpawnKill + SpellData.Delay*1000 + GetLatency() then
		_G.SpawnKill = nil
	end
	for _, Enemy in pairs(RecallTable) do
		Enemy.rTime = Enemy.rTime - (GetTickCount() - Enemy.time)
		Enemy.time = GetTickCount()
		local Unit = Enemy.object
		local TravelTime = GetTravelTime()
		local Col = Collision(math.huge, GetSpeed(), SpellData.Delay, SpellData.Width)

		if myHero:CanUseSpell(_R) == READY and TravelTime >= Enemy.rTime and TravelTime < Enemy.rTime + 20 then
			if getDmg("R", Unit, myHero) >= Unit.health then
				_G.SpawnKill = GetTickCount()
				CastSpell(_R, Spawn.x, Spawn.z)
			end
		end
	end
	RestoreMovement()
end

function OnRecvPacket(p)
	if p.header == 0xD7 then
		p.pos = 5
		local __source = p:DecodeF()
		local __hero = objManager:GetObjectByNetworkId(__source)
		p.pos = 112
		local __type = p:Decode1()

		if IsAlly(__source) or myHero.networkID == __source then return end

		if RecallTable[__source] and __type == 4 then
			RecallTable[__source] = nil
		elseif RecallTable[__source] == nil and __type == 6 and __hero.visible then
			RecallTable[__source] = {object = __hero, time = GetTickCount(), finish = GetTickCount() + RecallTypes[__hero:GetSpellData(RECALL).name], rTime = RecallTypes[__hero:GetSpellData(RECALL).name]}
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

function IsAlly(nId)
	for _, Ally in pairs(GetAllyHeroes()) do
		if Ally.networkID == nId then
			return true
		end
	end
	return false
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