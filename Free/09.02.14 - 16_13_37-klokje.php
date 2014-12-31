<?php exit() ?>--by klokje 88.159.68.30
if GetUser() ~= "Without Silence" then return end
local version = 1.01
local spellT = { [0] = 'Q', [1] = 'W', [2] = 'E', [3] = 'R' }
local drawTable = { 'Ashe', 'Draven', 'Ezreal', 'Gangplank', 'Jinx', 'Karthus', 'Nocturne', 'Pantheon', 'Jinx', 'Shen', 'Soraka', 'Twisted Fate' }
local enemies = {}
local enemyID = {}

local creepTable = {}
local announceNext = {  [6] = { announce = false, name = 'Dragon', x = 9400, y = -61, z = 4130 } , [12] = { announce = false, name = 'Baron', x = 4620, y = -63, z = 10265   } }

local buttonTable = {}
local X = { i = math.round(WINDOW_W) - 50, min = 50, max = math.round(WINDOW_W) - 50 }
local Y = { i = 50, min = 50, max = math.round(WINDOW_H) - 50 }
local button = { 
	w = math.round(WINDOW_W/17.5), 
	h = math.round(WINDOW_H/35),
	dX = 0, -- 0 for vertical
	dY = math.round(WINDOW_H/15), -- 0 for horizontal
	offsetX = 5, -- button border offset
	offsetY = 2,
	width = 2,
}

function pSet(response)
	if response ~= tostring(version) then 
		PrintChat('<font color = "#FF0000">>>  A new version is available. Please contact Trees.</font>')
		OnRecvPacket = nil
		OnTick = nil
		Menu = nil
		return
	end
	PrintChat("<font color = '#00FFFF' >>> Notifeye by Trees v" .. tostring(version) .. "</font>")
end

	GetAsyncWebResult( Base64Decode('ZGwuZHJvcGJveHVzZXJjb250ZW50LmNvbQ=='), Base64Decode('L3UvNjc3OTEyNi9ub3RpZmV5ZWNoZWNrLnR4dA=='), pSet)
	
	Menu = scriptConfig('Notifeye', 'Notifeye')
	
	Menu:addSubMenu('RECV Ping Settings', 'Recv')
	Menu.Recv:addParam('Mute', 'Mute RECVed Pings', SCRIPT_PARAM_ONOFF, false)
	
	Menu:addSubMenu('Global Ult Settings', 'Global')
	
	Menu:addSubMenu('DC/RC Settings', 'DCRC')
	Menu.DCRC:addSubMenu('Disconnect Settings', 'DC')
	--Menu.DCRC:addSubMenu('Reconnect Settings', 'RC')
	Menu.DCRC.DC:addParam('DC', 'Enemy DC', SCRIPT_PARAM_ONOFF, true)
	Menu.DCRC.DC:addParam('Chat', 'PrintChat DC', SCRIPT_PARAM_ONOFF, true)
	Menu.DCRC.DC:addParam('Alert', 'PrintAlert DC', SCRIPT_PARAM_ONOFF, true)
	Menu.DCRC.DC:addParam('Ping', 'Ping DC', SCRIPT_PARAM_ONOFF, true)
	
	Menu.Global:addParam('Alert', 'Alert Global Ults', SCRIPT_PARAM_ONOFF, true)
	Menu.Global:addParam('Level', 'Alert Enemy Ult Levelup', SCRIPT_PARAM_ONOFF, true)
	--Menu.Global:addParam('self', 'Alert Own Team\'s Ults', SCRIPT_PARAM_ONOFF, true)
	Menu.Global:addSubMenu('Champion Configuration', 'Champ')
	
	Menu:addSubMenu('Last Know Position', 'LKP')
	Menu.LKP:addParam('X', 'Adjust X', SCRIPT_PARAM_SLICE, X.i, X.min, X.max, 0)
	Menu.LKP:addParam('Y', 'Adjust Y', SCRIPT_PARAM_SLICE, Y.i, Y.min, Y.max, 0)
	
	for i = 1, heroManager.iCount, 1 do
		local hero = heroManager:GetHero(i)
		if hero and hero.valid and hero.team ~= myHero.team  then
			for iv, heroName in ipairs(drawTable) do
				if hero.charName == heroName then Menu.Global.Champ:addParam(hero.charName, 'Alert to ' .. hero.charName .. ' Ult', SCRIPT_PARAM_ONOFF, true) break end
			end
			enemies[i] = {unit = hero, visible = false, seen = false, pinged = false, lastSeen = 0, color = ARGB(255, 0, 255, 0) }
			enemyID[hero.charName] = i
			local sprite = GetSprite("Characters/".. hero.charName .."_Square_40.dds")
			RegisterButton( Menu.LKP.X + button.dX * (i-1), Menu.LKP.Y + button.dY * (i-1), button.w, button.h, i, 'buttonTable', sprite)
		end
	end
	
	Menu:addSubMenu('Auto-MIA', 'MIA')
	Menu.MIA:addParam('send', 'Send MIA Ping', SCRIPT_PARAM_ONOFF, true)
	--Menu.MIA:addParam('dist', 'Send Distance', SCRIPT_PARAM_SLICE, 1000, 200, 3000, 0)
	Menu.MIA:addParam('time', 'MIA Time', SCRIPT_PARAM_SLICE, 5, 1, 15)
	Menu.MIA:addParam('Chat', 'PrintChat on MIA', SCRIPT_PARAM_ONOFF, true)
	Menu.MIA:addParam('Alert', 'PrintAlert on MIA', SCRIPT_PARAM_ONOFF, true)
	Menu.MIA:addParam('Ping', 'Recv MIA Ping (Clientside)', SCRIPT_PARAM_ONOFF, true)
	
	Menu:addSubMenu('Jungle Respawn Alerts', 'Respawn')
	Menu.Respawn:addParam('On', 'Announce Jungle Respawn', SCRIPT_PARAM_ONOFF, true)
	Menu.Respawn:addParam('Chat', 'PrintChat on Respawn', SCRIPT_PARAM_ONOFF, true)
	Menu.Respawn:addParam('Alert', 'PrintAlert on Respawn', SCRIPT_PARAM_ONOFF, true)
	Menu.Respawn:addParam('Ping', 'Ping on Respawn', SCRIPT_PARAM_ONOFF, true)
	
	Menu:addParam('LevelOne', 'Alert LVL1 LevelUp', SCRIPT_PARAM_ONOFF, true)
	Menu:addParam('AutoLevelUlt', 'Auto Level Ult', SCRIPT_PARAM_ONOFF, true)


function OnProcessSpell(unit, spellProc)
	-- this doesn't proc in fow
	if unit and unit.valid and unit.type == myHero.type and Menu.Global.Alert and Menu.Global.Champ[unit.charName] and spellProc.name == unit:GetSpellData(_R).name and ( unit.team ~= myHero.team or Menu.Global.self )  then
		-- if within a certain range (for tf/shen/) then alert
		-- print name of ulted (shen)
		PrintAlert(unit.charName .. " used ultimate!", 3, 50, 255, 50)
	end
end


function OnRecvPacket(p)
	if p.header == 0x3E then -- LevelUp
		p.pos = 1
		local unit = objManager:GetObjectByNetworkId(p:DecodeF())
		if unit and unit.valid and unit.isMe then
			local level = p:Decode1()
			local remainingLevelPoints = p:Decode1()
			if (level == 6 or level == 11 or level == 16) and p:Decode1() > 0 and Menu.AutoLevelUlt then Packet('PKT_NPC_UpgradeSpellReq', { spellId = _R}):send() end
		end
	end
	
	if p.header == 0x97 and Menu.DCRC.DC then -- DC
		p.pos = 5
		local unit = objManager:GetObjectByNetworkId(p:DecodeF())
		if unit and unit.valid and unit.team ~= myHero.team then
		--	PrintAlert(, 3, 255, 0, 0)
			local eventTable = { text = unit.charName .. ' has disconnected!' }
			if unit.visible then 
				eventTable.ping.x = unit.x 
				eventTable.ping.y = unit.z 
				eventTable.ping.targetNetworkId = unit.networkID
			end
			HandleEvent('DCRC', eventTable)
		end
	end
	
--[[	elseif p.header == 0x00 and Menu.DCRC.AlertRC then
		p.pos = 11
		local num = p:Decode1()
		PrintChat(tostring(num))
		local id = enemyID[p:Decode1()]
		if id == nil then return end
		PrintChat(tostring(enemies[id].unit.charName))]]
		
	if p.header == 0x15 then -- LevelUpSpell
		p.pos = 1
		local unit = objManager:GetObjectByNetworkId(p:DecodeF())
		local spellId = p:Decode1()
		if unit == nil or not unit.valid or unit.team == myHero.team then return end
		if Menu.Global.Level and Menu.Global.Champ[unit.charName] and spellId == 3 then PrintAlert(unit.charName .. ' has leveled ult!', 3, 255, 0, 0) end
		if Menu.LevelOne and unit.level == 1 then PrintAlert(unit.charName .. ' has leveled ' .. spellT[spellId] .. '!', 3, 255, 0, 0) end
	end
	
	if p.header == 0xE8 and Menu.Respawn.On then -- CreateObject
		p.pos = 128
		local networkId = p:DecodeF()
		p.pos = p.size - 3
		local campId = p:Decode1()
		if campId == 6 or campId == 12 then
			if not announceNext[creepId].announce then 
				announceNext[creepId].announce = true
				table.insert(creepTable, { networkID = networkId, campID = campId } )
			else
				local eventTable = { text = announceNext[creepId].name .. ' has respawned!', ping = {x = announceNext[creepId].x, y = announceNext[creepId].z } }
				HandleEvent('Respawn', eventTable)
				table.insert(creepTable, { networkID = networkId, campID = campId } )
			end
		end
	end
	
	if p.header == 0x22 and Menu.Respawn.On then -- Gold
		-- this packet isn't sent from FoW, means there was vision of death
		p.pos = 9
		MarkKilled(p:DecodeF(), false)
	end
	
	if p.header == 0xC2 and Menu.Respawn.On then -- DeleteNeutral
		local dp = Packet(p)
		local cID = dp:get('campId')
		local type = dp:get('emptyType')
		if cID ~= 6 and cID ~= 12 then return end
		
		if type == 3 then KillCamp(cID, true) -- camp found empty
		elseif type == 2 then KillCamp(cID, false) -- camp killed in vision
		end
	end
	
end

function OnShowUnit(unit)
	if unit and unit.valid and unit.type == myHero.type and unit.team ~= myHero.team  then
		local i = enemyID[unit.charName]
	--	PrintChat('onshowunit')
		enemies[i].seen = true
		enemies[i].visible = true
		enemies[i].lastSeen = os.clock()
		enemies[i].color = ARGB(255, 0, 255, 0) -- green
		enemies[i].pinged = false
	end
end

function OnLoseVision(unit)
	if unit and unit.valid and unit.type == myHero.type and unit.team ~= myHero.team then
		local i = enemyID[unit.charName]
		enemies[i].visible = false
		enemies[i].lastSeen = os.clock()
		enemies[i].color = ARGB(255, 255, 0, 0)
	end
end

function OnTick()
	for i, v in pairs(enemies) do
		if not v.seen or ( os.clock() - Menu.MIA.time ) < v.lastSeen or v.pinged then goto continue end
		enemies[i].pinged = true
		if Menu.MIA.send and GetDistanceSqr(v.unit, myHero) <= 1000000 then
			Packet('S_PING', { x = v.unit.x, y = v.unit.z, type = 3 }):send()
			goto continue
		end
		--('R_PING', { x = v.unit.x, y = v.unit.z, type = 3, playSound = Menu.Recv.Mute and false or true }):receive() end
		local eventTable = { text = v.unit.charName .. ' is MIA!', ping = { x = v.unit.x, y = v.unit.z, type = 3 } }
		HandleEvent('MIA', eventTable)
		::continue::
	end
end



function OnDraw()
	for i, v in ipairs(buttonTable) do v.sprite:Draw(v.x, v.y, 250) end
end

function OnWndMsg(msg, wParam)
	if msg == WM_LBUTTONUP then
		local pos = GetCursorPos()
		for i, v in ipairs(buttonTable) do
			if pos.x >= v.x1 and pos.x <= v.x2 and pos.y >= v.y1 and pos.y <= v.y2 then
				local unit = enemies[v.num].unit
				local pTable = { x = unit.x, y = unit.z, sourceNetworkId = myHero.networkID, targetNetworkId = unit.visible and unit.networkID or 0, playSound = Menu.Recv.Mute and false or true, type = 161}
				Packet('R_PING', pTable):receive()
				break
			end
		end
	end
end

-- }
function RegisterButton(x, y, w, h, i, tbl, sprite)
	local x1 = x - w
	local x2 = x + w
	local y1 = y - h
	local y2 = y + h
	local button = { x = x, x1 = x1, x2 = x2, y = y, y1 = y1, y2 = y2, w = w, h = h, sprite = sprite, num = i }
	table.insert(buttonTable, button)
end

function MarkKilled(nID, announce)
	for i, v in ipairs(creepTable) do
		if v.networkID == networkId then
			announceNext[v.campID].announce = announce
			creepTable[i] = nil
			break
		end
	end
end

function KillCamp(cID, announce)
	for i, v in ipairs(creepTable) do
		if v.campID == cID then
			announceNext[cID].announce = announce
			creepTable[i] = nil
			break
		end
	end
end

function HandleEvent(name, data)
	if Menu[name].Chat then PrintChat(data.text) end
	if Menu[name].Alert then PrintAlert(data.text, 3, 255, 0, 0) end
	if Menu[name].Ping then Packet('R_PING', { x = data.ping.x, y = data.ping.y, targetNetworkId = data.ping.targetNetworkId and data.ping.targetNetworkId or 0, type = data.ping.type and data.ping.type or 2, playSound = Menu.Recv.Mute and false or true }):receive() end
end