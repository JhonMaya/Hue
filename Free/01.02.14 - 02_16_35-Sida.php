<?php exit() ?>--by Sida 81.170.95.141
function OnLoad()
	Menu = scriptConfig('AutoLantern', 'Lantern')
	Menu:addParam('Lantern', 'Use Lantern', SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu:addParam('Auto', 'Auto-Lantern at Low HP', SCRIPT_PARAM_ONOFF, true)
	Menu:addParam('Health', 'Auto-Lantern Health %', SCRIPT_PARAM_SLICE, 20, 0, 100, 2)
	print("<font color = '#00FFFF' >Trees AutoLantern Loaded</font>")
end

function Lantern(id)
	p = CLoLPacket(0x39)
	p.dwArg1 = 1
	p.dwArg2 = 0
	p:EncodeF(myHero.networkID)
	p:EncodeF(id)
	SendPacket(p)
end

function OnCreateObj(obj)
	if obj and obj.valid and obj.name == 'ThreshLantern' and obj.team ~= TEAM_ENEMY then lant = obj end
end

function OnDeleteObj(obj)
	if obj and obj.valid and obj.name == 'ThreshLantern' and obj.team ~= TEAM_ENEMY then lant = nil end
end

function checkHP()
	if Menu.Auto and myHero.health < (myHero.maxHealth * ( Menu.Health)/100) then return true end
	return false
end

function OnTick()
	if lant and GetDistanceSqr(lant, myHero) <= 90000 and (Menu.Lantern or checkHP()) then Lantern(lant.networkID) end
end