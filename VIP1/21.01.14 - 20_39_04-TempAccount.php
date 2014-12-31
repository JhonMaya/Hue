<?php exit() ?>--by TempAccount 62.153.15.103
if myHero.charName ~= "Darius" then return end

-- combo ignite + ult + passive damage berechnen
-- items zum ks evtl mitrechnen
-- shielder berechnen
-- barrier up berechnen
-- zur auswahl machen: ult zac,anivia,aatrox trotz passive
-- ult + ignite + passive dmg zum direkten killen auf der lane wenn man low lvl ist
-- kein auto ignite wenn schon durch passive tot
-- mma target

local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,800,DAMAGE_PHYSICAL,true)

local lastAttack = GetTickCount()
local lastWindUpTime = 0
local lastAttackCD = 0
local lastAnimation = ""

function Var()
	QREADY, WREADY, EREADY, RREADY = false, false, false, false
	qRange, eRange, rRange = 410, 540, 460
	passivedmg = nil
end

function Menu()
	DariusMenu = scriptConfig("Darius - The Hand of Noxus", "Darius")
	
	DariusMenu:addSubMenu("[Combo Settings]", "combo")
	DariusMenu.combo:addParam("fullcombo", "Full Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	DariusMenu.combo:addParam("orbwalking", "Enable Orbwalking (uses MMA)", SCRIPT_PARAM_ONOFF, true)
	DariusMenu.combo:addParam("noult", "DON'T ULT", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte('T'))
	DariusMenu.combo:addParam("w", "Use W as AA Animation Cancel", SCRIPT_PARAM_ONOFF, true)
	DariusMenu.combo:addParam("th", "Use Items as AA Animation Cancel", SCRIPT_PARAM_ONOFF, true)
	DariusMenu.combo:addParam("e", "Use E Grab", SCRIPT_PARAM_ONOFF, true)
	DariusMenu.combo:addParam("adjustultdamage", "Adjust Ult Damage", SCRIPT_PARAM_SLICE, 10, -200, 100, 0)
	DariusMenu.combo:permaShow("fullcombo")
	DariusMenu.combo:permaShow("noult")
	
	DariusMenu:addSubMenu("[Harass Settings]", "harass")
	DariusMenu.harass:addParam("harass", "Q Harass", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte('G'))
	DariusMenu.harass:permaShow("harass")
	
	DariusMenu:addSubMenu("[KS Settings]", "ks")
	DariusMenu.ks:addParam("ignite", "Auto Ignite", SCRIPT_PARAM_ONOFF, true)
	DariusMenu.ks:addParam("ksq", "Auto Q (under turret)", SCRIPT_PARAM_ONOFF, true)
	DariusMenu.ks:addParam("ksult", "Auto Ult", SCRIPT_PARAM_ONOFF, true)
	DariusMenu.ks:permaShow("ignite")
	
	DariusMenu:addSubMenu("[Draw Settings]", "draw")
	DariusMenu.draw:addParam("draw0", "Disable drawings", SCRIPT_PARAM_ONOFF, false)
	DariusMenu.draw:addParam("drawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
	DariusMenu.draw:addParam("drawE", "Draw E Range", SCRIPT_PARAM_ONOFF, false)
	DariusMenu.draw:addParam("drawR", "Draw R Range", SCRIPT_PARAM_ONOFF, false)
	DariusMenu.draw:addParam("drawDmg", "Draw Damage for Ult", SCRIPT_PARAM_ONOFF, true)
	DariusMenu.draw:addParam("drawpassive", "Draw Passive Kill", SCRIPT_PARAM_ONOFF, true)
	DariusMenu.draw:addParam("drawFocus", "Draw Focus", SCRIPT_PARAM_ONOFF, false)
	DariusMenu.draw:addParam("drawNoUlt", "Draw Ult Warning", SCRIPT_PARAM_ONOFF, true)
	
	DariusMenu:addTS(ts)
end

function Prios()
		if #GetEnemyHeroes() <= 1 then
        	PrintChat("No enemies, can't arrange priority's!")
   		else
        	arrangePrioritys(#GetEnemyHeroes())
        	PrintChat(" >> Arranged priority's!")
   		end
	end

function OnLoad()
	Menu()
	Var()
	Prios()
	IgnSlot()
	ts.name = "Darius"
	
	for i=0, heroManager.iCount, 1 do
        local playerObj = heroManager:GetHero(i)
        if playerObj and playerObj.team ~= player.team then
            playerObj.hemo = { object = playerObj, tick = 0, count = 0, }
            table.insert(enemyTable,playerObj)
        end
    end
    
	PrintChat("<font color='#01DF01'>>> Darius - The Dunkmaster <<")
end

function IgnSlot()
			if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then ign = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then ign = SUMMONER_2
			else ign = nil
		end
	end
	
function AutoIgnite()
	local iDmg = 0
	if ign ~= nil and IREADY and not myHero.dead then
		for i = 1, heroManager.iCount, 1 do
			local target = heroManager:getHero(i)
			if ValidTarget(target) then
				iDmg = 50 + 20 * myHero.level
				if target ~= nil and target.team ~= myHero.team and not target.dead and target.visible and GetDistance(target) < 600 and target.health+10 < iDmg then
					if DariusMenu.ks.ignite and not TargetHaveBuff("SummonerDot", target) then
						CastSpell(ign, target)
					end
				end
			end
		end
	end
end

function OnDraw()
	if not DariusMenu.draw.draw0 and not myHero.dead then
		if QREADY and DariusMenu.draw.drawQ then
			DrawCircle(myHero.x, myHero.y, myHero.z, qRange, 0x20B2AA)
		end
		if EREADY and DariusMenu.draw.drawE then
			DrawCircle(myHero.x, myHero.y, myHero.z, eRange, 0x800080)
		end
		if RREADY and DariusMenu.draw.drawR then
			DrawCircle(myHero.x, myHero.y, myHero.z, rRange, 0x800080)
		end
		if DariusMenu.draw.drawFocus and ValidTarget(ts.target) and myHero.dead == false and ts.target.dead == false then
			DrawCircle(ts.target.x, ts.target.y, ts.target.z, 100, 0x00FF00)
		end
		if DariusMenu.combo.noult and DariusMenu.draw.drawNoUlt then
			DrawText3D("Ult deactivated", myHero.x, myHero.y , myHero.z, 15, 0xFFFFE303, true)
		end
		if DariusMenu.draw.drawDmg and RREADY then
			for _,enemy in pairs(enemyTable) do
				local object = enemy.hemo.object
				if object ~= nil and ValidTarget(object) and object.visible == true then
					local rBaseDmg = getDmg("R", object, myHero)
					local stacks = enemy.hemo.count
					local rDmg = nil
					rDmg = rBaseDmg + ((rBaseDmg * 0.2) * stacks)
					if DariusMenu.combo.adjustultdamage >= 0 then rDmg = rDmg + DariusMenu.combo.adjustultdamage else rDmg = rDmg - DariusMenu.combo.adjustultdamage end
					local missingdmg = object.health - rDmg
					missingdmg = math.round(missingdmg, 0)
					DrawText3D(tostring(missingdmg), object.pos.x, object.pos.y , object.pos.z, 18, 0xFFFFE303, true)
				end
			end
		end
		if DariusMenu.draw.drawpassive then
			for _,enemy in pairs(enemyTable) do
				local object = enemy.hemo.object
				if object ~= nil and ValidTarget(object) and object.visible == true then
					if enemy.hemo.dead == true then
						DrawText3D("DEAD", object.pos.x, object.pos.y , object.pos.z-30, 18, 0xFFFFE303, true)
					end
				end
			end
		end
	end
end

--shields = {}

--function OnShield(unit, shield)
--	if shield.type == "physical" then
--		table.insert(shields, {charName = unit.charName, amount = shield.amount})
--	end
--	PrintChat(tostring(shield.id))
--	PrintChat(tostring(shield.amount))
--end

local BlockedUlts = {
	{spellName="SivirE"},
	{spellName="NocturneShroudofDarkness"},
	{spellName="JudicatorIntervention"},
	{spellName="UndyingRage"},
	{spellName="ChronoShift"},
}

buffs = {}

function OnGainBuff(unit, buff)
	for _,curbuff in pairs(BlockedUlts) do
		if buff.name == curbuff.spellName then
			table.insert(buffs, {name = unit.charName, spellName = curbuff.spellName})
		end
	end
end

function OnLoseBuff(unit, buff)
	for i,thebuff in pairs(buffs) do
		table.remove(buffs, i)
	end
end

hemoTable = {
    [1] = "darius_hemo_counter_01.troy",
    [2] = "darius_hemo_counter_02.troy",
    [3] = "darius_hemo_counter_03.troy",
    [4] = "darius_hemo_counter_04.troy",
    [5] = "darius_hemo_counter_05.troy",
}

enemyTable = {}

function OnCreateObj(object)
	if object and string.find(string.lower(object.name),"darius_hemo_counter") then
		for i, enemy in pairs(enemyTable) do
			if enemy and not enemy.dead and enemy.visible and GetDistance(enemy,object) <= 80 then
				for k, hemo in pairs(hemoTable) do
					if object.name == hemo then enemy.hemo.tick = GetTickCount() enemy.hemo.count = k end
				end
			end
		end
	end
end

--function GetMMATarget()
--    if _G.MMA_Target ~= nil and _G.MMA_Target.type:lower() == "obj_ai_hero" then
--        return _G.MMA_Target
--    else
--        ts:update()
--        return ts.target
--    end
--end

function GetHitBoxRadius(Unit)
	return GetDistance(Unit, Unit.minBBox)
end

function OnTick()
	ts:update()
	
	--ts.target = GetMMATarget()
	
	--if _G.MMA_Loaded then PrintChat("Darius dunk with mma") else PrintChat("darius dunk without mma") end
	
	IREADY = (ign ~= nil and myHero:CanUseSpell(ign) == READY)
	if DariusMenu.ks.ignite then AutoIgnite() end
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	WREADY = (myHero:CanUseSpell(_W) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)
	
	if DariusMenu.combo.noult == false then
		if DariusMenu.ks.ksult or DariusMenu.combo.fullcombo then UltToKill() end
	end
	if DariusMenu.ks.ksq then KSQ() end
	
	if DariusMenu.combo.fullcombo then Q() end
	
	if DariusMenu.harass.harass then Q() end
	
	if DariusMenu.combo.orbwalking then
		if not _G.MMA_Loaded then
			if DariusMenu.combo.fullcombo and ts.target ~= nil and GetDistance(ts.target) < 125 + GetHitBoxRadius(ts.target) then
				if timeToShoot() then
					local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
					Packet("S_MOVE", {type = 7, x = moveToPos.x, y = moveToPos.z}):send()
				elseif heroCanMove() then
					moveToCursor()
				end
			else
				if DariusMenu.combo.fullcombo then moveToCursor() end
			end    
		end
	end
	
	if DariusMenu.combo.fullcombo and DariusMenu.combo.e then
		if EREADY and ts.target ~= nil and ValidTarget(ts.target) and ts.target.visible == true and myHero.dead == false then
			if GetDistance(ts.target) < eRange and GetDistance(ts.target) > 200 then
				CastSpell(_E, ts.target)
				myHero:Attack(ts.target)
			end
		end
	end
	
--	if DariusMenu.combo.fullcombo and ts.target ~= nil and ValidTarget(ts.target) and ts.target.dead == false and ts.target.visible == true then
--		if GetDistance(ts.target) < 300 then myHero:Attack(ts.target) end
--	end
	
	if DariusMenu.draw.drawpassive and ts.target ~= nil and ValidTarget(ts.target) and ts.target.visible == true then
		getPassiveDmg(ts.target)
	end
	
	if ts.target ~= nil and ValidTarget(ts.target) and ts.target.dead == false and ts.target.visible == true then
		if GetDistance(ts.target) > eRange then
			blockE = true
		else
			blockE = false
		end
	else
		blockE = true
	end
end

function OnSendPacket(p)
        local packet = Packet(p)
        if packet:get('name') == 'S_CAST' then
                if packet:get('spellId') == _E then
                        if blockE == true then
                                packet:block()
            end
        end
    end
end

function getPassiveDmg()
	local basedmg = nil
	local passivelvl = myHero.level
	if passivelvl % 2 == 0 then
		passivelvl = myHero.level-1
		if myHero.level == 18 then passivelevel = 18 end
	end
	if passivelvl == 1 then basedmg = 12 end
	if passivelvl == 3 then basedmg = 15 end
	if passivelvl == 5 then basedmg = 18 end
	if passivelvl == 7 then basedmg = 21 end
	if passivelvl == 9 then basedmg = 24 end
	if passivelvl == 11 then basedmg = 27 end
	if passivelvl == 13 then basedmg = 30 end
	if passivelvl == 15 then basedmg = 33 end
	if passivelvl == 18 then basedmg = 36 end
	
	if basedmg ~= nil then
		for _,enemy in pairs(enemyTable) do
			if enemy.hemo.object ~= nil and ValidTarget(enemy.hemo.object) and enemy.hemo.object.visible == true and enemy.hemo.object.dead == false then
				passivedmg = player:CalcMagicDamage(enemy.hemo.object, (basedmg + (0.3*myHero.addDamage)) * enemy.hemo.count)
				if passivedmg - 25 > enemy.hemo.object.health then enemy.hemo.dead = true else enemy.hemo.dead = false end
			else
				if enemy.hemo.object ~= nil then
					enemy.hemo.dead = false
				end
			end
		end
	end
end

function OnProcessSpell(unit, spell)
	if myHero.dead then return end
	
	local spellIsAA = spell.name:lower():find("attack")
	if unit.isMe then
		if spellIsAA then
			lastAttack = GetTickCount() - GetLatency()/2
			lastWindUpTime = spell.windUpTime*1000
			lastAttackCD = spell.animationTime*1000
		elseif spell.name == "DariusNoxianTacticsONHAttack" then
			lastAttack = GetTickCount() - GetLatency()/2 - lastAttackCD
		end
	end
        
	local somethingused = false
	if WREADY and DariusMenu.combo.w and DariusMenu.combo.fullcombo and ts.target ~= nil and unit.isMe and spell.name:lower():find("attack") then
		if ValidTarget(ts.target) and ts.target.dead == false and tostring(spell.target) == tostring(ts.target.name) then
			DelayAction(function()
				CastSpell(_W)
				somethingused = true
			end, spell.windUpTime-GetLatency()/1000)
		end
	end
	if DariusMenu.combo.th then
		if GetInventorySlotItem(3074) ~= nil and not somethingused and DariusMenu.combo.fullcombo and ts.target ~= nil and unit.isMe and spell.name:lower():find("attack") then
			if ValidTarget(ts.target) and ts.target.dead == false and tostring(spell.target) == tostring(ts.target.name) then
				DelayAction(function()
					CastSpell(GetInventorySlotItem(3074))
					somethingused = true
				end, spell.windUpTime-GetLatency()/1000)
			end
		end

		if GetInventorySlotItem(3077) ~= nil and not somethingused and DariusMenu.combo.fullcombo and ts.target ~= nil and unit.isMe and spell.name:lower():find("attack") then
			if ValidTarget(ts.target) and ts.target.dead == false and tostring(spell.target) == tostring(ts.target.name) then
				DelayAction(function()
					CastSpell(GetInventorySlotItem(3077))
					somethingused = true
				end, spell.windUpTime-GetLatency()/1000)
			end
		end
	end
end

function OnAnimation(unit,animationName)
	if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

function heroCanMove()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end
 
function timeToShoot()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end
 
function moveToCursor()
	if GetDistance(mousePos) > 50 or lastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
	end
end

function KSQ()
	if QREADY then
		for _,enemy in pairs(enemyTable) do
			local target = enemy.hemo.object
			
			if target ~= nil and ValidTarget(target) and target.visible == true and getDmg("Q", target, myHero) > target.health then
				if GetDistance(target) < qRange then
					CastSpell(_Q)
				end
			end
		end
	end
end

function Q()
	if QREADY then
		if ts.target ~= nil and ValidTarget(ts.target) and ts.target.visible == true and QREADY then
			if UnderTurret(myHero.pos, true) == false then
				if GetDistance(ts.target) < qRange then
					CastSpell(_Q)
				end
			end
		end
	end
end

function UltToKill()
	if RREADY then
		for _,enemy in pairs(enemyTable) do
			if (GetTickCount() - enemy.hemo.tick > 5000) or (enemy and enemy.dead) then enemy.hemo.count = 0 end
			
			local object = enemy.hemo.object
			if object ~= nil and ValidTarget(object) and object.visible == true then
				local rBaseDmg = getDmg("R", object, myHero)
				local stacks = enemy.hemo.count
				local rDmg = nil
				rDmg = rBaseDmg + ((rBaseDmg * 0.2) * stacks)
				if DariusMenu.combo.adjustultdamage >= 0 then rDmg = rDmg + DariusMenu.combo.adjustultdamage else rDmg = rDmg - DariusMenu.combo.adjustultdamage end
				
				local dontult = false
				if RREADY and GetDistance(object) < rRange and rDmg > object.health then
					for i = 1, object.buffCount do
						thatbuff = ts.target:getBuff(i)
						if thatbuff ~= nil and thatbuff.name == "willrevive" and thatbuff.valid then -- ga check buff
							dontult = true
						end
					end
					for _,thebuff in pairs(buffs) do
						if thebuff.name == object.charName then -- some ult blocker check
							dontult = true
						end
					end
					if dontult == false then
						CastSpell(_R, object)
					end
				end
			end
		end
	end
end


	-- by Manciuszz
 
local priorityTable = {
 
    AP = {
        "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
        "Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
        "Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra", "MasterYi",
    },
    Support = {
        "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Sona", "Soraka", "Thresh", "Zilean",
    },
 
    Tank = {
        "Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Shen", "Singed", "Skarner", "Volibear",
        "Warwick", "Yorick", "Zac", "Nunu", "Taric", "Alistar",
    },
 
    AD_Carry = {
        "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "KogMaw", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
        "Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Zed", "Jinx"
 
    },
 
    Bruiser = {
        "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",
        "Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao", "Aatrox"
    },
 
}
 
function SetPriority(table, hero, priority)
    for i=1, #table, 1 do
        if hero.charName:find(table[i]) ~= nil then
            TS_SetHeroPriority(priority, hero.charName)
        end
    end
end
 
function arrangePrioritys(enemies)
    local priorityOrder = {
        [2] = {1,1,2,2,2},
        [3] = {1,1,2,3,3},
        [4] = {1,2,3,4,4},
        [5] = {1,2,3,4,5},
    }
    for i, enemy in ipairs(GetEnemyHeroes()) do
        SetPriority(priorityTable.AD_Carry, enemy, priorityOrder[enemies][1])
        SetPriority(priorityTable.AP,       enemy, priorityOrder[enemies][2])
        SetPriority(priorityTable.Support,  enemy, priorityOrder[enemies][3])
        SetPriority(priorityTable.Bruiser,  enemy, priorityOrder[enemies][4])
        SetPriority(priorityTable.Tank,     enemy, priorityOrder[enemies][5])
    end
end