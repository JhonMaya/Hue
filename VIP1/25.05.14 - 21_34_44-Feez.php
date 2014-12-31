<?php exit() ?>--by Feez 24.14.208.12
GetCurrentEnv().FILE_NAME = "DamageHud"
local DisplayTable = {
	['physical'] = {damage = 0, sprite = nil},
	['magical'] = {damage = 0, sprite = nil},
	['true'] = {damage = 0, sprite = nil},
	['coordinates'] = {x = 0, y = 0},
	['defaultcoordinates'] = {x = 0, y = 0},
	['totalDamage'] = 0
}
local displayBorder
local version = 1.0

function PrintText(text)
	print("<font color='#DB096B'>Damage Hud: </font><font color='#B5B5B5'>"..text.."</font>")
end

function OnLoad()
	UpdateWindow()

	DisplayTable['coordinates'].x = math.floor(.0105 * WINDOW_W)
	DisplayTable['coordinates'].y = math.floor(.1 * WINDOW_H)
	DisplayTable['defaultcoordinates'].x = math.floor(.0105 * WINDOW_W)
	DisplayTable['defaultcoordinates'].y = math.floor(.1 * WINDOW_H)

	Config = scriptConfig("Damage Hud", "dmghud")

	Config:addSubMenu("Display", "display")
	Config:addSubMenu("Coordinates", "coordinates")
	Config:addSubMenu("Damage", "damage")

	
	Config.damage:addParam("death", "Reset on death", SCRIPT_PARAM_ONOFF, false)

	-- myConfig:addParam(pVar, pText, SCRIPT_PARAM_SLICE, defaultValue, minValue, maxValue, decimalPlace)

	Config.coordinates:addParam("x", "X: ", SCRIPT_PARAM_SLICE, DisplayTable['coordinates'].x, 1, WINDOW_W, 0)
	Config.coordinates:addParam("y", "Y: ", SCRIPT_PARAM_SLICE, DisplayTable['coordinates'].y, 1, WINDOW_H, 0)
	Config.coordinates:addParam("reset", "Reset coordinates", SCRIPT_PARAM_ONOFF, false)

	Config.display:addParam("physical", "Physical", SCRIPT_PARAM_ONOFF, true)
	Config.display:addParam("magical", "Magical", SCRIPT_PARAM_ONOFF, true)
	Config.display:addParam("true", "True", SCRIPT_PARAM_ONOFF, true)





	DisplayTable['physical'].sprite = GetWebSprite("https://raw.githubusercontent.com/Feeez/BoL/master/Sprites/DamageHud/Physical.png")
	DisplayTable['magical'].sprite = GetWebSprite("https://raw.githubusercontent.com/Feeez/BoL/master/Sprites/DamageHud/Magical.png")
	DisplayTable['true'].sprite = GetWebSprite("https://raw.githubusercontent.com/Feeez/BoL/master/Sprites/DamageHud/True.png")
	displayBorder = GetWebSprite("https://raw.githubusercontent.com/Feeez/BoL/master/Sprites/DamageHud/BarBorder.png")
	


	


	Config:addParam("version", "Version:", SCRIPT_PARAM_INFO, tostring(version))

	PrintText("Loaded.")
end

function OnDraw()
	UpdateWindow()
	local i = 0

	--if OnScreen(barPos.x, barPos.y) then stunSprite:Draw(barPos.x+64, barPos.y-barPosOffset.y-37, 255) end

	if Config.display.physical and DisplayTable['physical'].sprite ~= nil then
		i = i + 1
		if DisplayTable['physical'].damage ~= 0 then
			local percent = DisplayTable['physical'].damage / DisplayTable['totalDamage']
			DisplayTable['physical'].sprite:SetScale(percent,1)
			DisplayTable['physical'].sprite:Draw(DisplayTable['coordinates'].x+3, DisplayTable['coordinates'].y+(i*46)+3, 255)
			displayBorder:Draw(DisplayTable['coordinates'].x, DisplayTable['coordinates'].y+(i*46), 255)
		else
			displayBorder:Draw(DisplayTable['coordinates'].x, DisplayTable['coordinates'].y+(i*46), 255)
		end
	end

	if Config.display.magical and DisplayTable['magical'].sprite ~= nil then
		i = i + 1
		if DisplayTable['magical'].damage ~= 0 then
			local percent = DisplayTable['magical'].damage / DisplayTable['totalDamage']
			DisplayTable['magical'].sprite:SetScale(percent,1)
			DisplayTable['magical'].sprite:Draw(DisplayTable['coordinates'].x+3, DisplayTable['coordinates'].y+(i*46)+3, 255)
			displayBorder:Draw(DisplayTable['coordinates'].x, DisplayTable['coordinates'].y+(i*46), 255)
		else
			displayBorder:Draw(DisplayTable['coordinates'].x, DisplayTable['coordinates'].y+(i*46), 255)
		end
	end

	if Config.display['true'] and DisplayTable['true'].sprite ~= nil then
		i = i + 1
		if DisplayTable['true'].damage ~= 0 then
			local percent = DisplayTable['true'].damage / DisplayTable['totalDamage']
			DisplayTable['true'].sprite:SetScale(percent,1)
			DisplayTable['true'].sprite:Draw(DisplayTable['coordinates'].x+3, DisplayTable['coordinates'].y+(i*46)+3, 255)
			displayBorder:Draw(DisplayTable['coordinates'].x, DisplayTable['coordinates'].y+(i*46), 255)
		else
			displayBorder:Draw(DisplayTable['coordinates'].x, DisplayTable['coordinates'].y+(i*46), 255)
		end
	end


end

function OnTick()
	if Config.coordinates.reset then Config.coordinates.x = DisplayTable['defaultcoordinates'].x; Config.coordinates.y = DisplayTable['defaultcoordinates'].y; Config.coordinates.reset = false end
	DisplayTable['coordinates'].x = math.floor(Config.coordinates.x)
	DisplayTable['coordinates'].y = math.floor(Config.coordinates.y)

	if myHero.dead and Config.damage.death then 
		DisplayTable['physical'].damage = 0
		DisplayTable['magical'].damage = 0
		DisplayTable['true'].damage = 0
		DisplayTable['totalDamage'] = 0
	end
end

-- 2 dodge, 3 crit, 4 phys, 5 miss, 6 phys?, 7 phys?, 8 invunerable, 9, 10 dodge, 11 crit

function ObjectFromNetworkID(id)
	for i = 1, objManager.maxObjects do
		local object = objManager:GetObject(i)
		if object ~= nil and object.networkID == id then return object end
	end
end

function OnRecvPacket(p)
	local pheader = string.format('0x%02X', p.header)
	if pheader == '0x64' then
		p.pos = 1
		local target = p:DecodeF()
		local Type = p:Decode1()
		local target_ = p:DecodeF()
		local source = p:DecodeF()
		local damage = p:DecodeF()
		if target == myHero.networkID then
			--print("Source: "..ObjectFromNetworkID(source).charName.." , Target: "..ObjectFromNetworkID(target).charName.." , Type: "..Type.." , DMG: "..tonumber(damage))
			if Type == 4 or Type == 12 or Type == 6 or Type == 7 or Type == 3 or Type == 11 then
				DisplayTable['physical'].damage = DisplayTable['physical'].damage + damage
				DisplayTable['totalDamage'] = DisplayTable['physical'].damage + DisplayTable['magical'].damage + DisplayTable['true'].damage
			elseif Type == 20 or Type == 19 then
				DisplayTable['magical'].damage = DisplayTable['magical'].damage + damage
				DisplayTable['totalDamage'] = DisplayTable['physical'].damage + DisplayTable['magical'].damage + DisplayTable['true'].damage
			elseif Type == 36 then
				DisplayTable['true'].damage = DisplayTable['true'].damage + damage
				DisplayTable['totalDamage'] = DisplayTable['physical'].damage + DisplayTable['magical'].damage + DisplayTable['true'].damage
			end
		end
	end
end