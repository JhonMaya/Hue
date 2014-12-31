<?php exit() ?>--by ikita 202.189.99.84
print("Recall Checker Loaded")
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
function OnRecvPacket(p)
	if p.header == 0xD7 then
		p.pos = 5
		local sourceNetworkId = p:DecodeF()
		p.pos = 112
		local type = p:Decode1()
		local unit = objManager:GetObjectByNetworkId(sourceNetworkId)
		if unit == nil or not unit.valid or unit.team == myHero.team then return end
		if RecallTable[sourceNetworkId] and type == 4 then RecallTable[sourceNetworkId] = nil
		elseif type == 6 then 
			RecallTable[sourceNetworkId] = {source = sourceNetworkId, time = GetTickCount(), finish = GetTickCount() + RecallTypes[GetGame().map.index], rTime = RecallTypes[GetGame().map.index]}
			if TimerRecDet == nil then
					TimerRecDet = os.time()
					print("<font color='#04B431'>Enemy </font><font color='#FF4000'>" .. unit.charName .. "</font> <font color='#04B431'>started Recall with </font><font color='#FF4000'>" .. math.round(unit.health,0) .. " HP. </font>")
			elseif  os.difftime(os.time(),TimerRecDet) >= 1 then
					print("<font color='#04B431'>Enemy </font><font color='#FF4000'>" .. unit.charName .. "</font> <font color='#04B431'>started Recall with </font><font color='#FF4000'>" .. math.round(unit.health,0) .. " HP. </font>")
			TimerRecDet = nil
			end
		end
	end
end