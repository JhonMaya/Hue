<?php exit() ?>--by sniperbro 24.116.252.146
local heroes = {}

function OnLoad()
	for i = 1, heroManager.iCount do
		local hero = heroManager:getHero(i)
		heroesIndex = #heroes + 1
		heroes[heroesIndex] = hero
		heroes[heroesIndex].exp = 0
		heroes[heroesIndex].maxExp = hero.level * 100 + 180
	end
	script = scriptConfig("Minion Calculator", "minion.cfg") 
	script:addParam("enabled", "Enable?", SCRIPT_PARAM_ONOFF, true) 
	script:addParam("exp", "Show missing exp?", SCRIPT_PARAM_ONOFF, true) 
	script:addParam("waves", "#waves", SCRIPT_PARAM_ONOFF, false) 
	script:addParam("caster", "#caster minions", SCRIPT_PARAM_ONOFF, false) 
	script:addParam("melee", "#melee minions", SCRIPT_PARAM_ONOFF, false) 
	script:addParam("average", "#average minion", SCRIPT_PARAM_ONOFF, true) 
end

exp = 0
function OnRecvPacket(p)
	if p.header == 0x10 then --exp gained
		p.pos = 5
		local networkID = p:DecodeF()
		local amount = math.floor(p:DecodeF())
		for _,hero in ipairs(heroes) do
			if networkID == hero.networkID then
				if hero.exp then
					hero.exp = hero.exp + amount
				else
					hero.exp = amount
				end
			end
		end
	elseif p.header == 0x3E then --levelup
		p.pos = 1
		networkID = p:DecodeF()
		level = p:Decode1()
		for _,hero in ipairs(heroes) do
			if networkID == hero.networkID then
				hero.exp = 0
				hero.maxExp = level * 100 + 180
			end
		end
  end
end
function OnDraw()
	if script.enabled then
		for _,hero in ipairs(heroes) do
			if hero and hero.visible and not hero.dead and hero.team == myHero.team and hero.level ~= 18 then
				heroPos = WorldToScreen(hero.pos)
				expNeeded = math.floor(hero.maxExp - hero.exp)
				output = ""
				if script.exp then
					output = output .. expNeeded .. " "
					if script.waves or script.caster or script.melee or script.average then output = output .. "(" end
				end
				if script.waves then
					output = output .. "#Waves: " .. math.floor(expNeeded/261) + (expNeeded%261 and 1 or 0) .. " "
				end
				if script.caster then
					output = output .. "#Caster: " .. math.floor(expNeeded/29) + (expNeeded%29 and 1 or 0) .. " "
				end
				if script.melee then
					output = output .. "#Melee: " .. math.floor(expNeeded/58) + (expNeeded%58 and 1 or 0) .. " "
				end
				if script.average then
					output = output .. "#Average: " .. math.floor(expNeeded/43) + (expNeeded%43 and 1 or 0)
				end
				if script.exp and (script.waves or script.caster or script.melee or script.average) then output = output .. ")" end
				
				DrawText(output, 16, heroPos.x - (string.len(output)/2)*5, heroPos.y - 16, 0xFF80FF00)
			
			end
		end
	end
end