<?php exit() ?>--by CLG Aphromoo 70.193.140.81



if debug.getinfo(GetUser).linedefined ~= nil then return end

if string.lower(GetUser()) == "feez" or string.lower(GetUser()) == "paradoxel" or string.lower(GetUser()) == "sehcure" or string.lower(GetUser()) == "empty1991" or string.lower(GetUser()) == "q179339065" or string.lower(GetUser()) == "Eunn" or string.lower(GetUser()) == "melody" then 
else
	print("<font color='#FFFFFF'>User: </font><font color='#F88017'>"..GetUser().."</font><font color='#FFFFFF'> not verified.</font>")
	return 
end


if myHero.charName ~= "Annie" then return end

require "AoESkillshotPosition"
require "ConeHelper"

--[[
To Do (no order)
--------
--Auto stun against channeling ults
--Option to lower auto W range
--improve auto farm (target minions who aren't too low health)
--Change stun under tower to (OnTowerFocus) advanced callback
--Improve AA orbwalking (don't studder as much)
--Add orbwalking to tibbers
--Have tibbers block skillshots 
]]


local comboRange = 625
local flash = nil
local ignite = nil
local dfgslot = nil
local bftslot = nil
local fqcslot = nil
local qready, wready, eready, rready, dfgready, bftready, fqcready = false, false, false, false, false, false, false
local pyroStack = 0
local igniteready, flashready = false, false
local finisher = false
local stunReady = false
local tibbersAlive = nil
local ultAvailable = nil
local qdamage, wdamage, rdamage, ignitedamage, comboDamage
local didCombo = nil
local ctime = 0
local spawnTurret
local tryingAA, timeChanged = false
local aatime, aatime2 = 0,0
local tibbersTimer, attackspeed
local didFirstAA = false
local version = "2.0"

function OnLoad()
	AUConfig = scriptConfig("Annie the UnBEARable - "..version, "annieconfig")
	AUConfig:addSubMenu("Combo Settings", "combosettings")
	AUConfig:addSubMenu("Harass Settings", "harasssettings")
	AUConfig:addSubMenu("Stun Settings", "stunsettings")
	AUConfig:addSubMenu("Farm Settings", "farmsettings")
	AUConfig:addSubMenu("Draw Settings", "drawsettings")
	AUConfig:addSubMenu("Cast Settings", "castsettings")
	AUConfig.castsettings:addParam("select1", "Cast Method (select one)", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("method1", "AoE (tries to get most targets hit - best)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.castsettings:addParam("method2", "No method (focus TS target)", SCRIPT_PARAM_ONOFF, false)
	AUConfig.castsettings:addParam("div", "-------------------------------------------------", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("spellexploit", "Use directional exploit", SCRIPT_PARAM_ONOFF, true)
	AUConfig.farmsettings:addParam("qfarm", "Auto Farm with Q", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("Z"))
	AUConfig.farmsettings:addParam("aafarm", "Auto Farm with AA (if no Q)", SCRIPT_PARAM_ONOFF, false)
	AUConfig.harasssettings:addParam("harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
	AUConfig.harasssettings:addParam("movetomouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassW", "Use W", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassStun", "Use Stun", SCRIPT_PARAM_ONOFF, false)
	AUConfig.farmsettings:addParam("qfarmstop1", "Stop Q farm when stun ready", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("J"))
	AUConfig.drawsettings:addParam("drawrange", "Draw combo range", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("drawharassrange", "Draw harass range (Q-W)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("drawflashrange", "Draw flash + combo range", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("circleTarget", "Draw circle under TS target", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("drawfountain", "Draw fountain turret range", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("drawkillable", "Draw killable text on target", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("drawtibbers", "Draw timer of tibbers on yourself", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:addParam("comboActive", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	AUConfig:addParam("directTibbers", "Auto control tibbers to attack TS target", SCRIPT_PARAM_ONOFF, true)
	AUConfig:addParam("passiveStack", "Stack passive in spawn (w and e)", SCRIPT_PARAM_ONOFF, true)
	AUConfig:addParam("passiveStack2", "Stack passive everywhere (with e)", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings:addParam("autoStunW", "Auto Stun enemies with W", SCRIPT_PARAM_ONOFF, true)
	AUConfig.stunsettings:addParam("autoStunWvalue", "least # of enemies to auto stun", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	AUConfig.stunsettings:addParam("autoStunCombo", "Combo after auto stun (if worked)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.stunsettings:addParam("div", "-------------------------------------------------", SCRIPT_PARAM_INFO, "")
	AUConfig.stunsettings:addParam("autoStunTower", "Auto Stun enemies focused by tower", SCRIPT_PARAM_ONOFF, false)
	AUConfig:addParam("debugprint", "Debug Printing in Chat (for dev)", SCRIPT_PARAM_ONOFF, false)
	AUConfig.combosettings:addParam("movetomouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:addParam("useflash", "Use Flash when Killable", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:permaShow("comboActive")
	AUConfig.harasssettings:permaShow("harass")
	AUConfig.farmsettings:permaShow("qfarm")
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,1000,DAMAGE_MAGIC)
	ts.name = "Annie"
	AUConfig.combosettings:addTS(ts)

	PrintChat("<font color='#DB0004'>Annie the Un</font><font color='#543500'>BEAR</font><font color='#DB0004'>able</font>")
	print("<font color='#FFFFFF'>Beta Test User: </font><font color='#F88017'>"..GetUser().."</font>")

	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then ignite = SUMMONER_2 end
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerFlash") then flash = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerFlash") then flash = SUMMONER_2 end

	for i = 1, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil and object.type == "obj_AI_Turret" then
			if (myHero.team == TEAM_BLUE and object.name == "Turret_OrderTurretShrine_A") or (myHero.team == TEAM_RED and object.name == "Turret_ChaosTurretShrine_A") then
				spawnTurret = object
			end
		end
	end


	enemyMinions = minionManager(MINION_ENEMY, 625, player, MINION_SORT_HEALTH_ASC)


end

--[[
Combo 1 - Q,W,R
Combo 2 - Q,W
Combo 3 - Q
Combo 4 - W
]]



function OnDraw()
	if AUConfig.drawsettings.drawrange then
		DrawCircle(myHero.x, myHero.y, myHero.z, 600, ARGB(255,36,0,255))
	end
	if AUConfig.drawsettings.circleTarget and ts.target ~= nil then
		DrawCircle(ts.target.x, ts.target.y, ts.target.z, 70, ARGB(255, 255, 0, 0))
	end
	if AUConfig.drawsettings.drawflashrange then
		DrawCircle(myHero.x, myHero.y, myHero.z, 1000, ARGB(255,222,18,222))
	end
	if AUConfig.drawsettings.drawharassrange then
		DrawCircle(myHero.x, myHero.y, myHero.z, 650, ARGB(255,134,104,222))
	end
	if AUConfig.drawsettings.drawtibbers and tibbersAlive then 
    	PrintFloatText(myHero, 0, ""..math.floor(tibbersTimer - GetGameTimer()).."")
	end

	if ts.target ~= nil and canKill(ts.target, 6) and ValidTarget(ts.target, 625) and myHero.canMove and not myHero.isTaunted and not myHero.isFeared then PrintFloatText(ts.target, 0, "Killable: AA (Manual)")
	elseif ts.target ~= nil and canKill(ts.target, 3) and AUConfig.drawsettings.drawkillable and qready then PrintFloatText(ts.target, 0, "Killable: Q")
	elseif ts.target ~= nil and canKill(ts.target, 4) and AUConfig.drawsettings.drawkillable and wready  then PrintFloatText(ts.target, 0, "Killable: W")
	elseif ts.target ~= nil and canKill(ts.target, 2) and AUConfig.drawsettings.drawkillable and qready and wready then PrintFloatText(ts.target, 0, "Killable: Q-W")
	elseif ts.target ~= nil and canKill(ts.target, 1.1) and AUConfig.drawsettings.drawkillable and qready and wready and rready and not ((stunReady or (pyroStack == 3 and eready))) and ultAvailable then PrintFloatText(ts.target, 0, "Killable: (No-Stun) Q-W-R")
	elseif ts.target ~= nil and canKill(ts.target, 1) and AUConfig.drawsettings.drawkillable and qready and wready and rready and (stunReady or (pyroStack == 3 and eready)) and ultAvailable then PrintFloatText(ts.target, 0, "Killable: (Stun) Q-W-R") 
	elseif ts.target ~= nil and canKill(ts.target, 5) and AUConfig.drawsettings.drawkillable and rready and not ts.target.canMove and not wready and not qready and ultAvailable and ValidTarget(ts.target, 600) then PrintFloatText(ts.target, 0, "Killable: R") end
end

function OnTick()
	ts:update()
	enemyMinions:update()
	qready = (myHero:CanUseSpell(_Q) == READY)
	wready = (myHero:CanUseSpell(_W) == READY)
	eready = (myHero:CanUseSpell(_E) == READY)
	rready = (myHero:CanUseSpell(_R) == READY)
	dfgslot = GetInventorySlotItem(3128)
	dfgready = (dfgslot ~= nil and myHero:CanUseSpell(dfgslot) == READY)
	bftslot = GetInventorySlotItem(3188)
	bftready = (bftslot ~= nil and myHero:CanUseSpell(bftslot) == READY)
	fqcslot = GetInventorySlotItem(3092)
	fqcready = (fqcslot ~= nil and myHero:CanUseSpell(fqcslot) == READY)
	igniteready = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
	flashready = (flash ~= nil and myHero:CanUseSpell(flash) == READY)

	--attackspeed = 0.579 * myHero.attackSpeed


	qdamage = math.floor(((((((myHero:GetSpellData(_Q).level-1) * 40) + 85) + .70 * myHero.ap))))
	wdamage = math.floor(((((((myHero:GetSpellData(_W).level-1) * 50) + 80) + .75 * myHero.ap))))
	rdamage = math.floor((((((myHero:GetSpellData(_R).level-1) * 125) + 200) + .70 * myHero.ap)))
	ignitedamage = 50 + (20 * myHero.level)
	AAdamage = myHero.damage

	spawnStacker()


    if flashready and qready and wready and ultAvailable and stunReady then
    	ts.range = 1000
    	ts:update()
    else
    	ts.range = 625
    	ts:update()
    end



    if GetGameTimer() > aatime and tryingAA and didFirstAA then
    	aatime2 = GetGameTimer()
		tryingAA = false
	end

	Combo()
	AutoQFarm()
	Harass()
	CommandBear()
	AutoStun()




	if not tibbersAlive and (myHero:CanUseSpell(_Q) == READY) then 
		ultAvailable = true
	else
		ultAvailable = false
	end

	
 end



local enemiesStunned = 0

function AutoStun()
	if AUConfig.stunsettings.autoStunW and wready and not AUConfig.combosettings.comboActive and (stunReady or (pyroStack == 3 and eready)) then --avoid bugsplat so Combo does not conflict
		local wpos, numberOfEnemiesInCone = GetCone(625, 39)
		if (wpos ~= nil and numberOfEnemiesInCone ~= nil) and (numberOfEnemiesInCone >= AUConfig.stunsettings.autoStunWvalue) then
			if pyroStack == 3 and eready then
				CastSpell(_E)
				CastW(target)
			elseif stunReady then
				CastW(target)
			end
		end
	end
	if AUConfig.stunsettings.autoStunCombo then
		for i=1, heroManager.iCount do
			local enemy = heroManager:getHero(i)
			if enemy.team == TEAM_ENEMY and not enemy.canMove and qready and rready and not tibbersAlive and not didCombo then
				enemiesStunned = enemiesStunned + 1
			end
		end
		if enemiesStunned >= AUConfig.stunsettings.autoStunWvalue and qready and rready and not tibbersAlive and not didCombo then
			doCombo(8, ts.target)
			enemiesStunned = 0
		end
		enemiesStunned = 0
	end
end


--Auto stun under tower
function OnTowerFocus(tower, unit)
	if unit ~= nil then
		if AUConfig.stunsettings.autoStunTower and (qready or wready) and (stunReady or (pyroStack == 3 and eready)) and unit.team == TEAM_ENEMY and string.lower(unit.type) == "obj_ai_hero" then
			if pyroStack == 4 and qready and ValidTarget(unit, 625) and UnderTurret(unit, false) then
				CastExploit(_Q, unit)
			elseif pyroStack == 3 and qready and ValidTarget(unit, 625) and UnderTurret(unit, false) then
				CastSpell(_E)
				CastExploit(_Q, unit)
			elseif not qready and wready and pyroStack == 4 and GetDistance(myHero, unit) < 610 then
				CastW(unit)
			elseif not qready and wready and pyroStack == 3 and GetDistance(myHero, unit) < 610 then
				CastSpell(_E)
				CastW(unit)
			end
		end
	end
end

function OnTowerIdle(tower, unit)
	if unit ~= nil then
		if AUConfig.stunsettings.autoStunTower and (qready or wready) and (stunReady or (pyroStack == 3 and eready)) and unit.team == TEAM_ENEMY and string.lower(unit.type) == "obj_ai_hero" then
			if pyroStack == 4 and qready and ValidTarget(unit, 625) and UnderTurret(unit, false) then
				CastExploit(_Q, unit)
			elseif pyroStack == 3 and qready and ValidTarget(unit, 625) and UnderTurret(unit, false) then
				CastSpell(_E)
				CastExploit(_Q, unit)
			elseif not qready and wready and pyroStack == 4 and GetDistance(myHero, unit) < 610 then
				CastW(unit)
			elseif not qready and wready and pyroStack == 3 and GetDistance(myHero, unit) < 610 then
				CastSpell(_E)
				CastW(unit)
			end
		end
	end
end



function Harass()
	if AUConfig.harasssettings.harass then
		if AUConfig.harasssettings.movetomouse then
			myHero:MoveTo(mousePos.x, mousePos.z)
			--Use Q (checks if allowed to stun)
			if AUConfig.harasssettings.harassQ and AUConfig.harasssettings.harassStun and ValidTarget(ts.target, 625) then CastExploit(_Q, ts.target) 
			elseif AUConfig.harasssettings.harassQ and not AUConfig.harasssettings.harassStun and not stunReady and ValidTarget(ts.target, 625) then CastExploit(_Q, ts.target) end
			--Use W (checks if allowed to stun)
			if AUConfig.harasssettings.harassW and AUConfig.harasssettings.harassStun and ValidTarget(ts.target, 625) then CastW(ts.target)
			elseif AUConfig.harasssettings.harassW and not AUConfig.harasssettings.harassStun and not stunReady and ValidTarget(ts.target, 625) then CastW(ts.target) end
		end
	end
end



function AutoQFarm()
	if AUConfig.farmsettings.qfarm then
		if AUConfig.farmsettings.qfarmstop1 then
			if pyroStack ~= 4 then
				for index, minion in pairs(enemyMinions.objects) do
					if myHero:GetDistance(minion) <= 625 and minion.health <= myHero:CalcMagicDamage(minion, qdamage) and minion ~= nil then	--shorter range to fix bug
						if ValidTarget(minion, 625) and qready then 
							CastExploit(_Q, minion) 
						elseif ValidTarget(minion, 625) and not qready and myHero:CalcDamage(minion, myHero.damage) > minion.health and AUConfig.farmsettings.aafarm then
							myHero:Attack(minion)
						end
					end
				end
			end
		elseif not AUConfig.farmsettings.qfarmstop1 then
				for index, minion in pairs(enemyMinions.objects) do
					if myHero:GetDistance(minion) <= 625 and minion.health <= myHero:CalcMagicDamage(minion, qdamage) and minion ~= nil then	--shorter range to fix bug
						if ValidTarget(minion, 625) and qready then 
							CastExploit(_Q, minion) 
						elseif ValidTarget(minion, 625) and not qready and myHero:CalcDamage(minion, myHero.damage) > minion.health and AUConfig.farmsettings.aafarm then
							myHero:Attack(minion)
						end
					end
				end
			end
		end
	end




function CommandBear()
	if tibbersAlive and rready and ts.target ~= nil and AUConfig.directTibbers then
		ctime = ctime + 1
		if ctime == 50 then
			CastSpell(_R, ts.target)
			ctime = 0
		end
	end
end

--[[
method 1 = group center near target
method 2 = group center from nearest enemies
method 3 = default, focus target
]]

function CastExploit(spell, target)
	if AUConfig.castsettings.spellexploit and target ~= nil then 
		Packet("S_CAST", {spellId = spell, targetNetworkId = target.networkID}):send()
	else
		CastExploit(spell, target)
	end


end

function CastR(target)
	if AUConfig.castsettings.method1 and not AUConfig.castsettings.method2 then 
        spellPos = GetAoESpellPosition(230, target, 250)
    elseif AUConfig.castsettings.method2 and not AUConfig.castsettings.method1 then
    	spellPos = nil
    else -- if neither are selected, then default
    	spellPos = GetAoESpellPosition(230, target, 250)
    end
                
    if spellPos ~= nil then
        CastSpell(_R, spellPos.x, spellPos.z)
        CastSpell(_R, target)
    else
        CastSpell(_R, target.x, target.z)
        CastSpell(_R, target)
    end
end


function CastW(target)
	local wpos = GetCone(625, 39)
	if wpos ~= nil then 
		CastSpell(_W, wpos.x, wpos.z)
		if AUConfig.debugprint then print("W position not nil") end
		else
		CastSpell(_W, target.x, target.z)
		if AUConfig.debugprint then print("W position was nil") end
	end
end

local doingCombo = false

function doCombo(combo, target)
	if combo == 1 then --Full combo (no flash) at 3 stacks
		CastSpell(_E)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		--CastR(target)
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		if fqcready then CastExploit(fqcslot, target) end
		--if igniteready then CastExploit(ignite, target) end
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 7 then --Full combo (no flash) with stun ready
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		if fqcready then CastExploit(fqcslot, target) end
		--if igniteready then CastExploit(ignite, target) end
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 2 then -- W and Q
		CastW(target)
		CastExploit(_Q, target)
		didCombo = true
	end
	if combo == 3 then -- Q
		CastExploit(_Q, target)
		didCombo = true
	end
	if combo == 4 then -- W
		CastW(target)
		didCombo = true
	end
	if combo == 5 then -- 3 stacks + flash + full combo
		CastSpell(_E)
		CastSpell(flash, target.x, target.z)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		if fqcready then CastExploit(fqcslot, target) end
		--if igniteready then CastExploit(ignite, target) end
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 6 then -- stun ready + flash + full combo
		CastSpell(flash, target.x, target.z)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		if fqcready then CastExploit(fqcslot, target) end
		--if igniteready then CastExploit(ignite, target) end
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 8 then -- after auto stun
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
	 	CastExploit(_Q, target)
	 	if fqcready then CastExploit(fqcslot, target) end
	 	--if igniteready then CastExploit(ignite, target) end
	 	didCombo = true
	end
	if combo == 9 then -- after auto stun, 3 stacks pyromania
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastSpell(_E)
		CastR(target)
	 	CastExploit(_Q, target)
	 	if fqcready then CastExploit(fqcslot, target) end
	 	--if igniteready then CastExploit(ignite, target) end
	 	didCombo = true
	end
	if combo == 10 and not tryingAA then
		tryingAA = true
		myHero:Attack(target)
	end
end


--the canKill combo # may not correspond with the doCombo combo #

function Combo()
	if math.floor(GetGameTimer()) >= math.floor(aatime2 + .3) then
	else
		tryingAA = false
	end
	if qready or wready then
		tryingAA = false
	end
	if AUConfig.combosettings.comboActive and not tryingAA then
		if AUConfig.combosettings.movetomouse then
			myHero:MoveTo(mousePos.x, mousePos.z)
			if ts.target ~= nil then--
				if canKill(ts.target, 3) and ValidTarget(ts.target, 625) and qready then doCombo(3, ts.target)
				elseif canKill(ts.target, 4) and wready and ValidTarget(ts.target, 600) then doCombo(4, ts.target) -- low range so W will hit
				elseif canKill(ts.target, 2) and qready and wready and ValidTarget(ts.target, 600) then doCombo(2, ts.target) --also low range so W will hit
				elseif canKill(ts.target, 1.1) and qready and wready and rready and not ((stunReady or (pyroStack == 3 and eready))) and ValidTarget(ts.target, 625) and ultAvailable then doCombo(7, ts.target)  --also low range so W will hit
				elseif canKill(ts.target, 1) and qready and wready and rready and ValidTarget(ts.target, 625) and ultAvailable then 
					if pyroStack == 3 then
						doCombo(1, ts.target)
					elseif stunReady then
						doCombo(7, ts.target)
					end
				elseif canKill(ts.target, 1) and qready and wready and rready and (GetDistance(myHero, ts.target) > 750 and GetDistance(myHero, ts.target) < 995) and ValidTarget(ts.target, 995) and not tibbersAlive and AUConfig.combosettings.useflash and flashready then
					if pyroStack == 3 then
						doCombo(5, ts.target)
					elseif stunReady then
						doCombo(6, ts.target)
					end
				elseif canKill(ts.target, 5) and ultAvailable and not wready and not qready and ValidTarget(ts.target, 600) and not ts.target.canMove then
					CastR(ts.target)
				end
				--if qready and wready and (myHero:CanUseSpell(_Q) == READY) and eready then PrintChat("Skills good") end
				if not qready and not wready and ts.target ~= nil and ValidTarget(ts.target, 625) and not tryingAA then
					if aatime2 ~= 0 and aatime2 ~= nil and math.floor(GetGameTimer()) >= math.floor(aatime2 + .3) then
						doCombo(10, ts.target)
					elseif aatime2 == 0 or aatime2 == nil then
						doCombo(10, ts.target)
					end
				end
				if qready and wready and rready and eready and ValidTarget(ts.target, 625) and not tibbersAlive and pyroStack == 3 then  --Combo at 3 stacks 
					doCombo(1, ts.target)
				elseif qready and wready and rready and stunReady and ValidTarget(ts.target, 625) and not tibbersAlive and not didCombo then
					doCombo(7, ts.target)
				elseif qready and wready and ValidTarget(ts.target, 625) and not didCombo then --and not ultAvailable
					doCombo(2, ts.target)
				elseif qready and ValidTarget(ts.target, 625) and not wready and not didCombo then
					doCombo(3, ts.target)
				elseif wready and GetDistance(myHero, ts.target) < 610 and not qready and not didCombo then -- reduced range for better accuracy/less flops
					doCombo(4, ts.target)
				end
					if qready and wready and rready and (GetDistance(myHero, ts.target) > 750 and GetDistance(myHero, ts.target) < 995) and ValidTarget(ts.target, 995) and not tibbersAlive and AUConfig.combosettings.useflash and flashready and canKill(ts.target, 1) == true and pyroStack == 3 then  --Full combo kill at 3 stacks
					doCombo(5, ts.target)
				
				elseif qready and wready and rready and (GetDistance(myHero, ts.target) > 750 and GetDistance(myHero, ts.target) < 995) and ValidTarget(ts.target, 995) and not tibbersAlive and AUConfig.combosettings.useflash and flashready and canKill(ts.target, 1) == true then
					doCombo(6, ts.target)
				end
				didCombo = false
			end--
		end
	end
end


function canKill(target, combo)
	if combo == 1 and target ~= nil then
		comboDamage = qdamage + wdamage + rdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*target.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*target.health)) + (.20*comboDamage)
		end
		if myHero:CalcMagicDamage(target, comboDamage) > target.health then
			return true
		else
			return false
		end
	elseif combo == 1.1 and target ~= nil then
		comboDamage = qdamage + wdamage + rdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*target.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*target.health)) + (.20*comboDamage)
		end
		comboDamage = comboDamage - 100 -- without knowing if stun lands, i reduce the damage to be safe that combo can kill
		if myHero:CalcMagicDamage(target, comboDamage) > target.health then
			return true
		else
			return false
		end
	elseif combo == 2 and target ~= nil then
		comboDamage = qdamage + wdamage
		if myHero:CalcMagicDamage(target, comboDamage) > target.health then
			return true
		else
			return false
		end
	elseif combo == 3 and target ~= nil then
		comboDamage = qdamage
		if myHero:CalcMagicDamage(target, comboDamage) > target.health then
			return true
		else
			return false
		end
	elseif combo == 4 and target ~= nil then
		comboDamage = wdamage 
		if myHero:CalcMagicDamage(target, comboDamage) > target.health then
			return true
		else
			return false
		end
	elseif combo == 5 and target ~= nil then
		comboDamage = rdamage 
		if myHero:CalcMagicDamage(target, comboDamage) > target.health then
			return true
		else
			return false
		end
	elseif combo == 6 and target ~= nil then
		comboDamage = myHero.damage
		if myHero:CalcDamage(target, comboDamage) > target.health then
			return true
		else
			return false
		end
	end
end


--Feez made this script and is sexy

function spawnStacker()
	if AUConfig.passiveStack then 
		if GetDistance(myHero, spawnTurret) < 1400 and spawnTurret ~= nil then
			if pyroStack < 4 and eready then 
			CastSpell(_E)
			end
			if pyroStack < 4 and wready then
				CastSpell(_W, myHero.x + 50, myHero.z + 50)
			end
		end
	end
	if AUConfig.passiveStack2 then
		if eready then
			CastSpell(_E)
		end
	end
end



function OnProcessSpell(object, spellProc)
	if (tostring(object.name) == tostring(myHero.name)) and (spellProc.name == "AnnieBasicAttack" or spellProc.name == "AnnieBasicAttack2") then
		tryingAA = true
		aatime = spellProc.windUpTime + GetGameTimer() + .1
		didFirstAA = true
		--print(spellProc.windUpTime)
	end
	if (tostring(object.name) == tostring(myHero.name)) then
		if AUConfig.debugprint then print(spellProc.name) end
	end
end

function OnCreateObj(object)

end


--Now uses advanced callbacks to check pyromania stacks and if tibbers is alive (VIP Only)

function OnGainBuff(unit, buff)
	if buff.name == "pyromania" and unit.isMe then
		pyroStack = buff.stack
		if AUConfig.debugprint then print("stacks :"..pyroStack) end
	end
	if buff.name == "pyromania_particle" and unit.isMe then
		pyroStack = 4
		stunReady = true
		if AUConfig.debugprint then print("stacks :"..pyroStack) end
	end
	if unit.isMe and buff.name == "infernalguardiantimer" then
		tibbersAlive = true
		tibbersTimer = buff.endT
		if AUConfig.debugprint then print("tibbers alive") end
	end
end

function OnUpdateBuff(unit, buff)
    if buff.name == "pyromania" and unit.isMe then
		pyroStack = buff.stack
		if AUConfig.debugprint then print("stacks :"..pyroStack) end
	end   
end


function OnLoseBuff(unit, buff)
    if buff.name == "pyromania_particle" and unit.isMe then
		pyroStack = 0
		stunReady = false
		if AUConfig.debugprint then print("stacks :"..pyroStack) end
	end
	if unit.isMe and buff.name == "infernalguardiantimer" then
		tibbersAlive = false
		if AUConfig.debugprint then print("tibbers dead") end
	end
end