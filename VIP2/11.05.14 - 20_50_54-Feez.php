<?php exit() ?>--by Feez 24.14.208.12
if myHero.charName ~= "Annie" then return end


--[[
if string.lower(GetUser()) == "feez" or string.lower(GetUser()) == "paradoxel" or string.lower(GetUser()) == "q179339065" or string.lower(GetUser()) == "eunn" or string.lower(GetUser()) == "fiedler777" or string.lower(GetUser()) == "melody" then 
lse
print("<font color='#FFFFFF'>User: </font><font color='#F88017'>"..GetUser().."</font><font color='#FFFFFF'> not verified.</font>")
return 
end
]]


require 'VPrediction'
require 'SOW'

--[[
To Do (no order)
--------
-Support mode
]]
--[[
Bugs
---------
]]


local comboRange = 625
local flash = nil
local dfgslot = nil
local bftslot = nil
local fqcslot = nil
local hxtslot, hxtready, blgslot, blgready, odvslot, odvready = nil, nil, nil, nil, nil, nil
local qready, wready, eready, rready, dfgready, bftready, fqcready = false, false , false, false, false, false, false
local qmana, wmana, emana, rmana
local pyroStack = 0
local flashready = false
local finisher = false
local stunReady = false
local tibbersAlive = false
local ultAvailable = nil
local qdamage, wdamage, rdamage, comboDamage
local didCombo = nil
local doingCombo = false
local ctime = 0
local spawnTurret
local tryingAA, timeChanged = false
local aatime, aatime2 = 0,0
local tibLA, tibWind, tibAnimation = 0,0,0
local annieLA, annieWind, annieAnimation = 0,0,0
local annieReset
local tibbersObject, AAobject
local attackspeed, isRecalling
local ultTable, karthusUltTime, karthus, shieldTable
local checkVersion = true
local VPVersion
local QTime = 0
local QKillCasted = false
local SACloaded, MMAloaded
local version = "3.0"

function OnLoad()
	--Config
	AUConfig = scriptConfig("Annie the UnBEARable - "..version.."", "annieconfig")

	AUConfig:addSubMenu("Combo Settings", "combosettings")
	AUConfig:addSubMenu("Harass Settings", "harasssettings")
	AUConfig:addSubMenu("Orbwalker [SOW]", "orbwalker")
	AUConfig:addSubMenu("Stun Settings", "stunsettings")
	AUConfig:addSubMenu("Farm Settings", "farmsettings")
	AUConfig:addSubMenu("Auto Shield", "autoshield")
	AUConfig:addSubMenu("Draw Settings", "drawsettings")
	AUConfig:addSubMenu("Cast Settings", "castsettings")
	AUConfig:addSubMenu("Tibbers Settings", "tibbers")

	AUConfig.autoshield:addParam("info", "Turn off E stack for better results", SCRIPT_PARAM_INFO, "")
	AUConfig.autoshield:addParam("enabled", "Enabled", SCRIPT_PARAM_ONOFF, true)

	AUConfig.stunsettings:addSubMenu("Defensive Stun", "defensiveult")
	AUConfig.stunsettings:addParam("autoStunW", "Auto Stun enemies with W", SCRIPT_PARAM_ONOFF, true)
	AUConfig.stunsettings:addParam("autoStunR", "Auto Stun enemies with R (100% hit)", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings:addParam("autoStunWvalue", "least # of enemies to auto stun", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	AUConfig.stunsettings:addParam("autoStunCombo", "Combo after auto stun (if worked)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.stunsettings:addParam("div", "-------------------------------------------------", SCRIPT_PARAM_INFO, "")
	AUConfig.stunsettings:addParam("autoStunTower", "Auto Stun enemies focused by tower", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings.defensiveult:addParam("stunUlt", "Defensive Stun", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings.defensiveult:addParam("comboUlt", "Full combo if stunned", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings.defensiveult:addParam("div", "-------------------------------------------------", SCRIPT_PARAM_INFO, "")

	AUConfig.castsettings:addParam("div", "-------------------------------------------------", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("select1", "Cast Method (select one)", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("method1", "AoE (VPrediction too, best option)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.castsettings:addParam("method2", "No method (focus TS target)", SCRIPT_PARAM_ONOFF, false)
	AUConfig.castsettings:addParam("div", "-------------------------------------------------", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("spellexploit", "Use directional exploit", SCRIPT_PARAM_ONOFF, true)

	AUConfig.farmsettings:addParam("qfarm", "Auto Farm with Q", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("Z"))
	AUConfig.farmsettings:addParam("qfarmstop1", "Stop Q farm when stun ready", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("J"))
	AUConfig.farmsettings:permaShow("qfarm") --[[]]
	AUConfig.farmsettings:permaShow("qfarmstop1")--[[]]

	AUConfig.harasssettings:addParam("harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
	AUConfig.harasssettings:addParam("movetomouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassW", "Use W", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassStun", "Use Stun", SCRIPT_PARAM_ONOFF, false)
	AUConfig.harasssettings:addParam("screwAA", "Block AAs out of range [when spells ready]", SCRIPT_PARAM_ONOFF, true)

	AUConfig.drawsettings:addParam("lagfree", "Lag Free Draw", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("div", "-------------------------------------------------", SCRIPT_PARAM_INFO, "")
	AUConfig.drawsettings:addParam("drawaarange", "Draw AA range", SCRIPT_PARAM_ONOFF, false)
	AUConfig.drawsettings:addParam("aaColor", "Color:", SCRIPT_PARAM_COLOR, {255,0,255,17})
	AUConfig.drawsettings:addParam("drawharassrange", "Draw combo range", SCRIPT_PARAM_ONOFF, false)
	AUConfig.drawsettings:addParam("harassColor", "Color:", SCRIPT_PARAM_COLOR, {255,134,104,222})
	AUConfig.drawsettings:addParam("drawflashrange", "Draw flash + combo range", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("flashColor", "Color:", SCRIPT_PARAM_COLOR, {255,222,18,222})
	AUConfig.drawsettings:addParam("circleTarget", "Draw circle under TS target", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("targetColor", "Color:", SCRIPT_PARAM_COLOR, {255, 255, 0, 0})
	AUConfig.drawsettings:addParam("drawkillable", "Draw killable text on target", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("drawtibbers", "Draw timer of tibbers on yourself", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("fix", "Fix Draw Text", SCRIPT_PARAM_ONOFF, true)

	AUConfig.combosettings:addParam("comboActive", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	AUConfig.combosettings:addParam("movetomouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:addParam("useflash", "Use Flash when Killable", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:permaShow("comboActive")--[[]]

	AUConfig.tibbers:addParam("directTibbers", "Auto control & orbwalk tibbers", SCRIPT_PARAM_ONOFF, true)
	AUConfig.tibbers:addParam("followTibbers", "Tibbers follow toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("N"))
	AUConfig.tibbers:permaShow("followTibbers")--[[]]

	AUConfig:addParam("antimiss", "Anti-miss W", SCRIPT_PARAM_ONOFF, true)
	AUConfig:addParam("passiveStack", "Stack passive in spawn (w and e)", SCRIPT_PARAM_ONOFF, true)
	AUConfig:addParam("passiveStack2", "Stack passive everywhere (with e)", SCRIPT_PARAM_ONOFF, false)
	AUConfig:addParam("screwAA", "Block AAs out of range [when spells ready]", SCRIPT_PARAM_ONOFF, true)
	AUConfig:addParam("autolevel", "Auto level ult", SCRIPT_PARAM_ONOFF, true)
	AUConfig:addParam("debugprint", "Debug Printing in Chat (for dev)", SCRIPT_PARAM_ONOFF, false)

	
	--TS
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,1000,DAMAGE_MAGIC)
	ts.name = "Annie"
	ts.targetSelected = true
	AUConfig.combosettings:addTS(ts)
	AUConfig.combosettings.comboActive = false
	
	VP = VPrediction()

	SOWi = SOW(VP)
	SOWi:LoadToMenu(AUConfig.orbwalker)
	SOWi:EnableAttacks()
	
	DelayAction(function() print("<font color='#DB0004'>Annie the Un</font><font color='#543500'>BEAR</font><font color='#DB0004'>able</font>") end, 1)
	DelayAction(function() print("<font color='#FFFFFF'>User: </font><font color='#F88017'>"..GetUser().."</font>") end, 1)
	
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerFlash") then flash = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerFlash") then flash = SUMMONER_2 end
	
	for i=1, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil and object.type == "obj_AI_Turret" then
			if (myHero.team == TEAM_BLUE and object.name == "Turret_OrderTurretShrine_A") or (myHero.team == TEAM_RED and object.name == "Turret_ChaosTurretShrine_A") then
				spawnTurret = object
			end
		end
	end
	
	
	ultTable = {
	['FiddleSticks'] = {spell = 'Crowstorm'},
	['Katarina'] = {spell = 'KatarinaR'},
	['Caitlyn'] = {spell = 'CaitlynAceintheHole'},
	['Lucian'] = {spell = 'LucianR'},
	['Ezreal'] = {spell = 'EzrealTrueshotBarrage'},
	['MissFortune'] = {spell = 'MissFortuneBulletTime'},
	['Karthus'] = {spell = 'FallenOne'},
	['Warwick'] = {spell = 'InfiniteDuress'},
	['Nunu'] = {spell = 'AbsoluteZero'},
	['Malzahar'] = {spell = "AlZaharNetherGrasp"}
	}
	
	shieldTable = {
	['KatarinaR'] = 1,
	['CaitlynAceintheHole'] = 1,
	['FallenOne'] = 1,
	['InfiniteDuress'] = 1,
	['AlZaharNetherGrasp'] = 1,
	['BlindingDart'] = 1,
	['YasuoQW'] = 1,
	['FioraDance'] = 1,
	['SyndraR'] = 1,
	['VeigarPrimordialBurst'] = 1,
	['VeigarBalefulStrike'] = 1,
	['BusterShot'] = 1,
	['frostarrow'] = 1
	}
	
	for i=1, heroManager.iCount do
		local enemy = heroManager:getHero(i)
		if ultTable[enemy.charName] ~= nil and enemy.team == TEAM_ENEMY then
			AUConfig.stunsettings.defensiveult:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, true)
		end
	end
	
	enemyMinions = minionManager(MINION_ENEMY, 625, player.visionPos, MINION_SORT_HEALTH_ASC)

	DelayAction(function() SOWi.MyRange = function() return 765.5 end end, 1)
	--DelayAction(function() if _G.Activator and _G.Activator then _G.OffensiveItems = false; print("<font color='#DB0004'>Annie: Activator found.</font>") end end, 3)
end

--[[
Combo 1 - Q,W,R
Combo 2 - Q,W
Combo 3 - Q
Combo 4 - W
]]
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
	radius = radius or 300
	quality = math.max(8,math.floor(180/math.deg((math.asin((chordlength/(2*radius)))))))
	quality = 2 * math.pi / quality
	radius = radius*.92
	local points = {}
	for theta = 0, 2 * math.pi + quality, quality do
		local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
		points[#points + 1] = D3DXVECTOR2(c.x, c.y)
	end
	DrawLines2(points, width or 1, color or 4294967295)
end

function DrawCircle2(x, y, z, radius, color)
	local vPos1 = Vector(x, y, z)
	local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
	local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
	local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
	if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y })  then
		DrawCircleNextLvl(x, y, z, radius, 1, color, 75)	
	end
end

function OnDraw()
	if AUConfig.drawsettings.lagfree then
		if AUConfig.drawsettings.circleTarget and ts.target ~= nil then
			DrawCircle2(ts.target.x, ts.target.y, ts.target.z, 70, ARGB(AUConfig.drawsettings.targetColor[1], AUConfig.drawsettings.targetColor[2], AUConfig.drawsettings.targetColor[3], AUConfig.drawsettings.targetColor[4]))
		end
		if AUConfig.drawsettings.drawflashrange then
			DrawCircle2(myHero.x, myHero.y, myHero.z, 1000, ARGB(AUConfig.drawsettings.flashColor[1], AUConfig.drawsettings.flashColor[2], AUConfig.drawsettings.flashColor[3], AUConfig.drawsettings.flashColor[4]))
		end
		if AUConfig.drawsettings.drawharassrange then
			DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(AUConfig.drawsettings.harassColor[1], AUConfig.drawsettings.harassColor[2], AUConfig.drawsettings.harassColor[3], AUConfig.drawsettings.harassColor[4]))
		end
		if AUConfig.drawsettings.drawaarange then
			DrawCircle2(myHero.x, myHero.y, myHero.z, 765.5, ARGB(AUConfig.drawsettings.aaColor[1], AUConfig.drawsettings.aaColor[2], AUConfig.drawsettings.aaColor[3], AUConfig.drawsettings.aaColor[4]))
		end
	else
		if AUConfig.drawsettings.circleTarget and ts.target ~= nil then
			DrawCircle(ts.target.x, ts.target.y, ts.target.z, 70, ARGB(AUConfig.drawsettings.targetColor[1], AUConfig.drawsettings.targetColor[2], AUConfig.drawsettings.targetColor[3], AUConfig.drawsettings.targetColor[4]))
		end
		if AUConfig.drawsettings.drawflashrange then
			DrawCircle(myHero.x, myHero.y, myHero.z, 1000, ARGB(AUConfig.drawsettings.flashColor[1], AUConfig.drawsettings.flashColor[2], AUConfig.drawsettings.flashColor[3], AUConfig.drawsettings.flashColor[4]))
		end
		if AUConfig.drawsettings.drawharassrange then
			DrawCircle(myHero.x, myHero.y, myHero.z, 650, ARGB(AUConfig.drawsettings.harassColor[1], AUConfig.drawsettings.harassColor[2], AUConfig.drawsettings.harassColor[3], AUConfig.drawsettings.harassColor[4]))
		end
		if AUConfig.drawsettings.drawaarange then
			DrawCircle(myHero.x, myHero.y, myHero.z, 765.5, ARGB(AUConfig.drawsettings.aaColor[1], AUConfig.drawsettings.aaColor[2], AUConfig.drawsettings.aaColor[3], AUConfig.drawsettings.aaColor[4]))
		end
	end

	local function dtext(text, person)
		if person == nil then
			if ts.target ~= nil then 
				if AUConfig.drawsettings.fix then 
					DrawText3D(text, ts.target.x, ts.target.y+100, ts.target.z, 20, ARGB(255,255,255,255), true) 
				else
					PrintFloatText(ts.target, 0, text) 
				end
			end
		elseif person ~= nil then
			if AUConfig.drawsettings.fix then 
				DrawText3D(text, person.x, person.y+100, person.z, 20, ARGB(255,255,255,255), true) 
			else
				PrintFloatText(person, 0, text) 
			end
		end
	end

	--DrawText3D(text, myHero.x, myHero.y+100, myHero.z, 20, ARGB(255,255,255,255), true)

	if AUConfig.drawsettings.drawtibbers and tibbersAlive then dtext(""..math.floor(tibbersTimer - GetGameTimer()).."", myHero) end


	if ts.target ~= nil and canKill(ts.target, 6) and ValidTarget(ts.target, 625) and myHero.canMove and not myHero.isTaunted and not myHero.isFeared then dtext("Killable: AA")
		elseif ts.target ~= nil and canKill(ts.target, 3) and AUConfig.drawsettings.drawkillable and qready then dtext("Killable: Q")
		elseif ts.target ~= nil and canKill(ts.target, 4) and AUConfig.drawsettings.drawkillable and wready  then dtext("Killable: W")
		elseif ts.target ~= nil and canKill(ts.target, 2) and AUConfig.drawsettings.drawkillable and qready and wready then dtext("Killable: W-Q")
		elseif ts.target ~= nil and canKill(ts.target, 5) and not QKillCasted and AUConfig.drawsettings.drawkillable and rready and not ts.target.canMove and not wready and not qready and ultAvailable and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R")
		elseif canKill(ts.target, 7) and AUConfig.drawsettings.drawkillable and qready and not wready and ultAvailable and not ts.target.canMove and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R-Q")
		elseif canKill(ts.target, 8) and AUConfig.drawsettings.drawkillable and wready and not qready and ultAvailable and not ts.target.canMove and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R-W")
		elseif canKill(ts.target, 1.1) and AUConfig.drawsettings.drawkillable and qready and wready and ultAvailable and not ts.target.canMove and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R-W-Q")
		elseif ts.target ~= nil and canKill(ts.target, 1.1) and AUConfig.drawsettings.drawkillable and qready and wready and rready and not ((stunReady or (pyroStack == 3 and eready))) and ultAvailable then dtext("Killable: (No-Stun) R-W-Q")
		elseif ts.target ~= nil and canKill(ts.target, 1) and AUConfig.drawsettings.drawkillable and qready and wready and rready and (stunReady or (pyroStack == 3 and eready)) and ultAvailable then dtext("Killable: (Stun) R-W-Q") 
	end
end


function OnTick()
	ts:update()
	if ts.target ~= nil then SOWi:ForceTarget(ts.target) end
	qready = (myHero:CanUseSpell(_Q) == READY)
	if qready and AUConfig.farmsettings.qfarm then enemyMinions:update() end
	wready = (myHero:CanUseSpell(_W) == READY)
	eready = (myHero:CanUseSpell(_E) == READY)
	rready = (myHero:CanUseSpell(_R) == READY)
	qmana = myHero:GetSpellData(_Q).mana
	wmana = myHero:GetSpellData(_W).mana
	emana = myHero:GetSpellData(_E).mana
	rmana = myHero:GetSpellData(_R).mana
	dfgslot = GetInventorySlotItem(3128)
	dfgready = (dfgslot ~= nil and myHero:CanUseSpell(dfgslot) == READY)
	bftslot = GetInventorySlotItem(3188)
	bftready = (bftslot ~= nil and myHero:CanUseSpell(bftslot) == READY)
	fqcslot = GetInventorySlotItem(3092)
	fqcready = (fqcslot ~= nil and myHero:CanUseSpell(fqcslot) == READY)
	flashready = (flash ~= nil and myHero:CanUseSpell(flash) == READY)
	hxtslot = GetInventorySlotItem(3146)
	hxtready = (hxtslot ~= nil and myHero:CanUseSpell(hxtslot) == READY)
	blgslot = GetInventorySlotItem(3144)
	blgready = (blgslot ~= nil and myHero:CanUseSpell(blgslot) == READY)
	odvslot = GetInventorySlotItem(3023)
	odvready = (odvslot ~= nil and myHero:CanUseSpell(odvslot) == READY)


	MMAloaded = _G.MMA_Loaded
	SACloaded = _G.AutoCarry and _G.AutoCarry.MyHero
	_G.hasOrbwalker = (SACloaded or MMAloaded or AUConfig.orbwalker.Enabled)
	if MMAloaded and (_G.MMA_Target == nil or not AUConfig.combosettings.comboActive or not AUConfig.harasssettings.harass) then _G.MMA_Orbwalker = false end

	--if _G.Activator ~= nil then _G.OffensiveItems = false; print("<font color='#DB0004'>Annie: Activator found.</font>") end

	
	attackspeed = 0.579 * myHero.attackSpeed

	if ts.target ~= nil and ts.target.type == myHero.type and qTime ~= nil then QKillCasted = (canKill(ts.target, 3) == true) and (os.clock() <= qTime) end
	
	--Check VPrediction version
	if checkVersion then
		if VP.version ~= nil then
			VPVersion = VP.version
			VPVersion = string.gsub(VPVersion, "Version: ", "")
			VPVersion = tonumber(VPVersion)
			if VPVersion < 2.413 then
				print("<font color='#DB0004'>Annie the UnBEARable: You need to update VPrediction to the latest version!</font><")
				print("<font color='#DB0004'>Annie the UnBEARable: You need to update VPrediction to the latest version!</font><")
				print("<font color='#DB0004'>Annie the UnBEARable: You need to update VPrediction to the latest version!</font><")
			end
			checkVersion = false
		end
	end
	
	--Ult Available
	if not tibbersAlive and rready then 
		ultAvailable = true
	else
		ultAvailable = false
	end
	
	--Auto level
	if AUConfig.autolevel then
		local ultLevel = myHero:GetSpellData(_R).level
		local realLevel = GetHeroLeveled()
		
		if player.level > realLevel and (player.level >= 6 and ultLevel == 0) or (player.level >= 11 and ultLevel == 1) or (player.level >= 16 and ultLevel == 2) then
			LevelSpell(_R)
		end
	end
	
	--Damage calculations
	qdamage = math.floor(((((((myHero:GetSpellData(_Q).level-1) * 35) + 80) + .80 * myHero.ap))))
	wdamage = math.floor(((((((myHero:GetSpellData(_W).level-1) * 45) + 70) + .85 * myHero.ap))))
	if stunReady or (pyroStack == 3 and eready) then 
		rdamage = math.floor((((((myHero:GetSpellData(_R).level-1) * 125) + 175) + .80 * myHero.ap)))
	else
		rdamage = math.floor((((((myHero:GetSpellData(_R).level-1) * 125) + 210) + 1 * myHero.ap)))
	end
	AAdamage = myHero.damage
	
	--Random spawn stacker
	spawnStacker()
	
	
	--Update TS range if flash available
	if flashready and qready and wready and ultAvailable and ((stunReady or (pyroStack == 3 and eready))) then
		ts.range = 1000
		ts:update()
	elseif AUConfig.harasssettings.harass then
		ts.range = 1000
		ts:update()
	elseif not (qready or wready) then
		ts.range = 675.5
		ts:update()
	else
		ts.range = 620
		ts:update()
	end

	
	--Stuff
	Combo()
	AutoQFarm()
	Harass()
	CommandBear()
	AutoStun()

		
	
	--Karthus stuff
	if karthusUltTime ~= nil and karthus ~= nil then
		if os.clock() + GetLatency()/2000 > karthusUltTime + 2.2 and ValidTarget(karthus, 625) then
			if AUConfig.stunsettings.defensiveult.comboUlt and hasMana(7) and qready and wready and ultAvailable and stunReady then
				doCombo(7, karthus)
			elseif AUConfig.stunsettings.defensiveult.comboUlt and hasMana(1) and qready and wready and eready and ultAvailable and pyroStack == 3 then
				doCombo(1, karthus)
			elseif (qready or wready) and stunReady then
				if qready then CastExploit(_Q, karthus) elseif wready then CastW(target) end
			elseif qready and eready and pyroStack == 3 then
				CastSpell(_E)
				if qready then CastExploit(_Q, karthus) elseif wready then CastW(target) end
			end
			karthusUltTime = nil
			karthus = nil
		elseif os.clock() + GetLatency()/2000 > karthusUltTime + 2.2 and eready then
			CastSpell(_E)
		end
	end	
end

function DisableMMA()
	_G.MMA_Orbwalker = false
	_G.MMA_HybridMode = false
	_G.MMA_LaneClear = false
	_G.MMA_LastHit = false
	_G.MMA_Target = nil
end
function EnableMMA()
	--[[
	_G.MMA_Orbwalker = true
	_G.MMA_HybridMode = true
	_G.MMA_LaneClear = true
	_G.MMA_LastHit = true
	]]
end


function Combo()
	--Stop SAC/MMA/SOW from auto attacking when target is out of combo range and spells are ready
	if AUConfig.combosettings.comboActive then
		local shouldntAA = (qready or wready) and AUConfig.screwAA

		if not hasOrbwalker and AUConfig.combosettings.movetomouse then
			myHero:MoveTo(mousePos.x, mousePos.z)
		elseif hasOrbwalker and shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(false) end
			if MMAloaded then DisableMMA() end
			if AUConfig.orbwalker.Enabled then SOWi:DisableAttacks() end
			if AUConfig.combosettings.movetomouse then myHero:MoveTo(mousePos.x, mousePos.z) end
		elseif hasOrbwalker and not shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(true) end
			if MMAloaded then EnableMMA() end
			if AUConfig.orbwalker.Enabled then SOWi:EnableAttacks() end

			if SACloaded then
				_G.AutoCarry.MyHero:AttacksEnabled(true)
			elseif MMALoaded and _G.MMA_AbleToMove and AUConfig.combosettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			elseif AUConfig.orbwalker.Enabled and SOWi:CanMove() and AUConfig.combosettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			end
		end
	end


	if ts.target ~= nil and AUConfig.combosettings.comboActive then
		if string.find(ts.target.type, "obj_AI_Minion") and ts.target.team ~= myHero.team and (not string.find(ts.target.name, "Worm")) then
			if ValidTarget(ts.target, 625) then CastW(ts.target) end
			if ValidTarget(ts.target, 625) then CastExploit(_Q, ts.target)  end
		end
	end
	
	if ts.target ~= nil and not (string.find(ts.target.type, "obj_AI_Minion") and ts.target.team ~= myHero.team and (not string.find(ts.target.name, "Worm"))) then
		if AUConfig.combosettings.comboActive then
			if AUConfig.farmsettings.qfarm then
				AUConfig.farmsettings.qfarm = false
			end
			if canKill(ts.target, 3) and hasMana(3) and ValidTarget(ts.target, 620) and qready then doCombo(3, ts.target)
			elseif canKill(ts.target, 4) and hasMana(4) and wready and ValidTarget(ts.target, 620) then doCombo(4, ts.target) -- low range so W will hit
			elseif canKill(ts.target, 2) and hasMana(2) and qready and wready and ValidTarget(ts.target, 620) then doCombo(2, ts.target) --also low range so W will hit
			elseif canKill(ts.target, 7) and hasMana(8) and not canKill(ts.target, 3) and qready and not wready and ultAvailable and ValidTarget(ts.target, 620) then doCombo(8, ts.target)
			elseif canKill(ts.target, 8) and wreadyand and not canKill(ts.target, 4) and hasMana(11) and not qready and ultAvailable and ValidTarget(ts.target, 620) then doCombo(11, ts.target)
			elseif canKill(ts.target, 1.1) and hasMana(7) and qready and wready and ultAvailable and ValidTarget(ts.target, 620) then doCombo(7, ts.target)
			elseif canKill(ts.target, 1.1) and hasMana(7) and qready and wready and rready and not ((stunReady or (pyroStack == 3 and eready))) and ValidTarget(ts.target, 620) and ultAvailable then doCombo(7, ts.target)  --also low range so W will hit
			elseif canKill(ts.target, 1) and qready and wready and rready and ValidTarget(ts.target, 620) and ultAvailable and not canKill(ts.target, 2) then 
				if pyroStack == 3 and eready and hasMana(1) then
					doCombo(1, ts.target)
					elseif stunReady and hasMana(7) then
					doCombo(7, ts.target)
				end
			elseif canKill(ts.target, 1) and not canKill(ts.target, 2) and qready and wready and rready and (GetDistanceSqr(myHero.visionPos, ts.target.visionPos) > 562500 and GetDistanceSqr(myHero.visionPos, ts.target.visionPos) < 990025) and ValidTarget(ts.target, 995) and not tibbersAlive and AUConfig.combosettings.useflash and flashready then
				if pyroStack == 3 and eready and hasMana(5) then
					doCombo(5, ts.target)
				elseif stunReady and hasMana(6) then
					doCombo(6, ts.target)
				end
			elseif canKill(ts.target, 5) and ultAvailable and not wready and not qready and ValidTarget(ts.target, 620) and not ts.target.canMove then CastR(ts.target)
			end
			if ValidTarget(ts.target, 620) then
				--Check if enemy already stunned (if they are, then do full combo if ult available)

				--if not canKill target and q or w ready
				if hasMana(1) and qready and wready and rready and eready and not tibbersAlive and pyroStack == 3 and not didCombo then  --Combo at 3 stacks 
					doCombo(1, ts.target)
				elseif hasMana(7) and qready and wready and rready and stunReady and not tibbersAlive and not didCombo then
					doCombo(7, ts.target)
				end
				if ultAvailable and hasMana(12) and ((stunReady or (pyroStack == 3 and eready))) and not didCombo then---------------------------------------
					--if myHero:GetSpellData(_W).currentCd > 1.2 then doCombo(12, ts.target) end
					if not wready and qready and hasMana(12) then doCombo(12, ts.target) end
				end-----------------------------------------------------------------------------------------------------------------------------------------
				if not ts.target.canMove and ((qready and not canKill(ts.target, 3) or (wready and not canKill(ts.target, 4)))) and ultAvailable and not didCombo then
					if hasMana(12) and qready and not wready then doCombo(8, ts.target)
						elseif hasMana(11) and wready and not qready then doCombo(11, ts.target)
						elseif hasMana(7) and qready and wready then doCombo(7, ts.target)
					end
				end
				--No wasting of pyrostacks
				if pyroStack < 4 and not eready and ultAvailable and not didCombo then
					if hasMana(3) and qready and not wready then
						doCombo(3, ts.target)
					elseif hasMana(4) and wready and not qready then
						doCombo(4, ts.target)
					end
					elseif pyroStack < 3 and not eready and ultAvailable and not didCombo then
					if hasMana(3) and qready and not wready then
						doCombo(3, ts.target)
					elseif hasMana(4) and wready and not qready then
						doCombo(4, ts.target)
					end
				end

				if ultAvailable and ((stunReady or (pyroStack == 3 and eready))) then --so full combo isn't fucked over by a small combo
					didCombo = true
				end
				if hasMana(2) and qready and wready and not didCombo --[[and not ultAvailable]] then 
					doCombo(2, ts.target)
				elseif hasMana(3) and qready and not wready and not didCombo then
					doCombo(3, ts.target)
				elseif hasMana(4) and wready and not qready and not didCombo then -- reduced range for better accuracy/less flops
					doCombo(4, ts.target)
				end
				didCombo = false
			end
		end
	end
end


local enemiesStunned = 0

function AutoStun()
	if AUConfig.stunsettings.autoStunW and wready and not AUConfig.combosettings.comboActive and (stunReady or (pyroStack == 3 and eready)) and ts.target ~= nil then --avoid bugsplat so Combo does not conflict
		local wpos, hitChance, numberOfEnemiesInCone = VP:GetConeAOECastPosition(ts.target, .25, 24.76, 620, math.huge, myHero)
		if (wpos ~= nil and numberOfEnemiesInCone ~= nil) and (numberOfEnemiesInCone >= AUConfig.stunsettings.autoStunWvalue) and hitChance >= 3 then
			if hasMana({'E', 'W'}) and pyroStack == 3 and eready then
				CastSpell(_E)
				Packet("S_CAST", {spellId = _W, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			elseif hasMana({'W'}) and stunReady then
				Packet("S_CAST", {spellId = _W, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			end
		end
	end
	if AUConfig.stunsettings.autoStunR and ultAvailable and not AUConfig.combosettings.comboActive and (stunReady or (pyroStack == 3 and eready)) and ts.target ~= nil then
		local wpos, hitChance, numberOfEnemiesInCone = VP:GetCircularAOECastPosition(ts.target, .25, 250, 850, math.huge, myHero)
		if (wpos ~= nil and numblerOfEnemiesInCone ~= nil) and (numberOfEnemiesInCone >= AUConfig.stunsettings.autoStunWvalue) and hitChance >= 3 then
			if hasMana({'E', 'R'}) and pyroStack == 3 and eready then
				CastSpell(_E)
				Packet("S_CAST", {spellId = _R, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			elseif hasMana({'R'}) and stunReady then
				Packet("S_CAST", {spellId = _R, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			end
		end
	end
	if AUConfig.stunsettings.autoStunCombo and (not wready or not ultAvailable) and (AUConfig.stunsettings.autoStunR or AUConfig.stunsettings.autoStunW) and ts.target ~= nil then
		for i=1, heroManager.iCount do
			local enemy = heroManager:getHero(i)
			if enemy.team == TEAM_ENEMY and not enemy.canMove and qready and rready and not tibbersAlive and not didCombo and GetDistanceSqr(myHero.visionPos, enemy.visionPos) < 360000 then
				enemiesStunned = enemiesStunned + 1
			end
		end
		if enemiesStunned >= AUConfig.stunsettings.autoStunWvalue and hasMana(8) and qready and rready and not tibbersAlive and not didCombo then
			doCombo(8, ts.target)
			enemiesStunned = 0
			elseif enemiesStunned >= AUConfig.stunsettings.autoStunWvalue and not didCombo and not ultAvailable then
			if qready and wready and hasMana(2) then doCombo(2, ts.target)
				elseif not qready and wready and hasMana(4) then doCombo(4, ts.target)
				elseif qready and not wready and hasMana(3) then doCombo(3, ts.target)
			end
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
			elseif pyroStack == 3 and qready and eready and hasMana({'E', 'Q'}) and ValidTarget(unit, 625) and UnderTurret(unit, false) then
				CastSpell(_E)
				CastExploit(_Q, unit)
			elseif not qready and wready and pyroStack == 4 and GetDistanceSqr(myHero.visionPos, unit.visionPos) < 372100 then
				CastW(unit)
			elseif not qready and wready and eready and hasMana({'E', 'W'}) and pyroStack == 3 and GetDistanceSqr(myHero.visionPos, unit.visionPos) < 372100 then
				CastSpell(_E)
				CastW(unit)
			end
		end
	end
end


function Harass() -- or AUConfig.orbwalker.Enabled
	if AUConfig.harasssettings.harass then
		local shouldntAA = (qready or wready) and AUConfig.harasssettings.screwAA

		if not hasOrbwalker and AUConfig.harasssettings.movetomouse then
			myHero:MoveTo(mousePos.x, mousePos.z)
		elseif hasOrbwalker and shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(false) end
			if MMAloaded then DisableMMA() end
			if AUConfig.orbwalker.Enabled then SOWi:DisableAttacks() end
			if AUConfig.harasssettings.movetomouse then myHero:MoveTo(mousePos.x, mousePos.z) end
		elseif hasOrbwalker and not shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(true);_G.AutoCarry.Orbwalker:Orbwalk(ts.target) end
			if MMAloaded then EnableMMA();_G.MMA_Orbwalker = true end
			if AUConfig.orbwalker.Enabled then SOWi:EnableAttacks();SOWi:OrbWalk(ts.target) end

			if SACloaded then
				_G.AutoCarry.MyHero:AttacksEnabled(true)
			elseif MMAloaded and _G.MMA_AbleToMove and AUConfig.harasssettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			elseif AUConfig.orbwalker.Enabled and SOWi:CanMove() and AUConfig.harasssettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			end
		end

		--Use W (checks if allowed to stun)
		if AUConfig.harasssettings.harassW and AUConfig.harasssettings.harassStun and ValidTarget(ts.target, 625) and wready then CastW(ts.target)
		elseif AUConfig.harasssettings.harassW and not AUConfig.harasssettings.harassStun and not stunReady and wready and ValidTarget(ts.target, 625) then CastW(ts.target) end
		--Use Q (checks if allowed to stun)
		if AUConfig.harasssettings.harassQ and AUConfig.harasssettings.harassStun and ValidTarget(ts.target, 625) and qready then CastExploit(_Q, ts.target) 
		elseif AUConfig.harasssettings.harassQ and not AUConfig.harasssettings.harassStun and not stunReady and ValidTarget(ts.target, 625) and qready then CastExploit(_Q, ts.target) end	

		--if MMAloaded then DisableMMA() end
	end
end




function AutoQFarm()
	if AUConfig.farmsettings.qfarm and qready then
		if AUConfig.farmsettings.qfarmstop1 then
			if pyroStack ~= 4 then
				for index, minion in pairs(enemyMinions.objects) do
					if string.find(minion.type, "MechCannon") then
						MinionPredict(minion)
					end
				end
				for index, minion in pairs(enemyMinions.objects) do
					MinionPredict(minion)
				end
			end
		elseif not AUConfig.farmsettings.qfarmstop1 then
			for index, minion in pairs(enemyMinions.objects) do
				if string.find(minion.type, "MechCannon") then
					MinionPredict(minion)
				end
			end
			for index, minion in pairs(enemyMinions.objects) do
				MinionPredict(minion)
			end
		end
	end
end

function MinionPredict(minion)
	local time = .25 + GetDistance(minion.visionPos, myHero.visionPos) / 1400 - 0.07
	local PredictedHealth = VP:GetPredictedHealth(minion, time)
	if GetDistanceSqr(minion.visionPos, myHero.visionPos) <= 390625 and PredictedHealth <= myHero:CalcMagicDamage(minion, qdamage) and minion ~= nil then--shorter range to fix bug
		if ValidTarget(minion, 625) and qready then 
			CastExploit(_Q, minion) 
		end
	end
end

function CommandBear()
	if ts.target ~= nil and tibbersAlive and AUConfig.tibbers.directTibbers then
		local target = ts.target
		local hitboxSqr = VP:GetHitBox(target) + 80 -- 80 = tib hitbox
		hiboxSqr = hitboxSqr * hitboxSqr --since we are using GetDistanceSqr
		if GetDistanceSqr(tibbersObject.visionPos, target.visionPos) < 15625 + hitboxSqr then
			Packet("S_MOVE", {type = 5, dwArg1 = 1, dwArg2 = 0, sourceNetworkId = myHero.networkID, unitNetworkId = tibbersObject.networkID, targetNetworkId = target.networkID, x = ts.target.visionPos.x, y = ts.target.visionPos.z}):send()
		elseif GetDistanceSqr(tibbersObject.visionPos, target.visionPos) > 15625 + hitboxSqr then
			Packet("S_MOVE", {type = 6, dwArg1 = 1, dwArg2 = 0, sourceNetworkId = tibbersObject.networkID, unitNetworkId = tibbersObject.networkID, targetNetworkId = 0, waypoint = 1, x = ts.target.visionPos.x, y = ts.target.visionPos.z}):send()
		end
	elseif tibbersAlive and AUConfig.tibbers.followTibbers and not myHero.dead then
		if GetDistanceSqr(tibbersObject.visionPos, myHero.visionPos) > 15625 then Packet("S_MOVE", {type = 6, dwArg1 = 1, dwArg2 = 0, sourceNetworkId = tibbersObject.networkID, unitNetworkId = tibbersObject.networkID, targetNetworkId = 0, waypoint = 1, x = myHero.visionPos.x, y = myHero.visionPos.z}):send() end
	end
end


function CastExploit(spell, target)
	if AUConfig.castsettings.spellexploit and target ~= nil and (((ValidTarget(ts.target, 4000) and target.type == myHero.type)) or target.type ~= myHero.type) then 
		Packet("S_CAST", {spellId = spell, targetNetworkId = target.networkID}):send()
		QTime = os.clock() + (.25 + GetDistance(target.visionPos, myHero.visionPos) / 1400)
		--Packet("S_CAST", {spellId = spell, toX = target.x, toY = target.z, targetNetworkId = target.networkID}):send()
	elseif target ~= nil then
		CastSpell(spell, target)
	end
end
--function VPrediction:GetCircularAOECastPosition(unit, delay, radius, range, speed, from)
function CastR(target)
	if not QKillCasted then
		if AUConfig.castsettings.method1 and not AUConfig.castsettings.method2 then 
			spellPos = VP:GetCircularAOECastPosition(target, .25, 250, 600, math.huge, myHero)
		elseif AUConfig.castsettings.method2 and not AUConfig.castsettings.method1 then
			spellPos = nil
		else -- if neither are selected, then default
			spellPos = VP:GetCircularAOECastPosition(target, .25, 250, 600, math.huge, myHero)
		end
		

		if spellPos ~= nil then
			--CastSpell(_R, spellPos.x, spellPos.z)
			Packet("S_CAST", {spellId = _R, toX = spellPos.x, toY = spellPos.z, fromX = spellPos.x, fromY = spellPos.z}):send()
		else
			Packet("S_CAST", {spellId = _R, toX = target.visionPos.x, toY = target.visionPos.z, fromX = target.visionPos.x, fromY = target.visionPos.z}):send()
		end
	end
end


function CastW(target)
	if AUConfig.castsettings.method1 and not AUConfig.castsettings.method2 then 
		--VPrediction:GetCircularAOECastPosition(unit, delay, radius, range, speed, from)
		----------------------------------------------
		--VPrediction:GetConeAOECastPosition(unit, delay, angle, range, speed, from)
		spellPos = VP:GetConeAOECastPosition(target, .25, 24.76, 620, math.huge, myHero)
	elseif AUConfig.castsettings.method2 and not AUConfig.castsettings.method1 then
		spellPos = nil
	else -- if neither are selected, then default
		spellPos = VP:GetConeAOECastPosition(target, .25, 24.76, 620, math.huge, myHero)
	end

	
	if spellPos ~= nil and not AUConfig.antimiss then --308025
		--CastSpell(_R, spellPos.x, spellPos.z)
		Packet("S_CAST", {spellId = _W, toX = spellPos.x, toY = spellPos.z, fromX = spellPos.x, fromY = spellPos.z}):send()
	elseif AUConfig.antimiss and spellPos ~= nil and not (not isFacing(target, myHero, 400) and GetDistanceSqr(myHero.visionPos, target.visionPos) >= 360000 and target.canMove and not VP:isSlowed(ts.target, .25, 625, myHero)) then
		Packet("S_CAST", {spellId = _W, toX = spellPos.x, toY = spellPos.z, fromX = spellPos.x, fromY = spellPos.z}):send()
	elseif spellPos == nil then
		Packet("S_CAST", {spellId = _W, toX = target.visionPos.x, toY = target.visionPos.z, fromX = target.visionPos.x, fromY = target.visionPos.z}):send()
	end
end

function hasMana(combo)
	local totalMana = 0
	if type(combo) == 'number' then
		if combo == 1 then
			totalMana = emana + rmana + wmana + qmana
		end
		if combo == 2 then
			totalMana = wmana + qmana
		end
		if combo == 3 then
			totalMana = qmana
		end
		if combo == 4 then
			totalMana = wmana
		end
		if combo == 5 then
			totalMana = emana + rmana + wmana + qmana
		end
		if combo == 6 then
			totalMana = rmana + wmana + qmana
		end
		if combo == 7 then
			totalMana = rmana + wmana + qmana
		end
		if combo == 8 then
			totalMana = rmana + qmana
		end
		if combo == 9 then
			totalMana = emana + rmana + qmana
		end
		if combo == 10 then
			totalMana = rmana + wmana + qmana
		end
		if combo == 11 then
			totalMana = rmana + wmana
		end
		if combo == 12 then
			totalMana = rmana + qmana
		end
	elseif type(combo) == 'table' then
		for i=1, #combo do
			if combo[i] == 'Q' then totalMana = totalMana + qmana end
			if combo[i] == 'W' then totalMana = totalMana + wmana end
			if combo[i] == 'E' then totalMana = totalMana + emana end
			if combo[i] == 'R' then totalMana = totalMana + rmana end
		end
	end
	return totalMana <= myHero.mana
end
function doCombo(combo, target)

	local function ItemSpamCast(target)
		if fqcready then CastExploit(fqcslot, target) end
		if hxtready then CastExploit(hxtslot, target) end
		if blgready then CastExploit(blgslot, target) end
		if odvready then CastSpell(odvslot) end
	end

	if combo == 1 then --Full combo (no flash) at 3 stacks
		CastSpell(_E)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
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
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 2 then -- Q and W
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
		CastSpell(flash, target.visionPos.x, target.visionPos.z)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 6 then -- stun ready + flash + full combo
		CastSpell(flash, target.visionPos.x, target.visionPos.z)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 8 then -- after auto stun
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastExploit(_Q, target) 
		ItemSpamCast(target)
		didCombo = true
	end
	if combo == 9 then -- after auto stun, 3 stacks pyromania
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastSpell(_E)
		CastR(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		didCombo = true
	end
	if combo == 10 then --after defensive ult
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 11 then
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		ItemSpamCast(target)
		didCombo = true
	end
	if combo == 12 then
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		didCombo = true
	end
end

--the canKill combo # may not correspond with the doCombo combo #

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
		comboDamage = comboDamage -- without knowing if stun lands, i reduce the damage to be safe that combo can kill
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
	elseif combo == 7 and target ~= nil then
		comboDamage = qdamage + rdamage
		if myHero:CalcMagicDamage(target, comboDamage) > target.health then
			return true
		else
			return false
		end
	elseif combo == 8 and target ~= nil then
		comboDamage = wdamage + rdamage
		if myHero:CalcMagicDamage(target, comboDamage) > target.health then
			return true
		else
			return false
		end
	end
end


--Feez made this script and is sexy

function spawnStacker()
	if AUConfig.passiveStack then 
		if GetDistanceSqr(myHero.visionPos, spawnTurret.visionPos) < 1960000 and spawnTurret ~= nil and not isRecalling then
			if pyroStack < 4 and eready then 
				CastSpell(_E)
			end
			if pyroStack < 4 and wready and not isRecalling then
				CastSpell(_W, myHero.visionPos.x + 50, myHero.visionPos.z + 50)
			end
		end
	end
	if AUConfig.passiveStack2 and not isRecalling and pyroStack < 4 then
		if eready then
			CastSpell(_E)
		end
	end
end

function OnCreateObj(object)
	if object.team ~= TEAM_ENEMY and object.name == "Tibbers" then
		tibbersObject = object
	end
end

local notAA = {
	['shyvanadoubleattackdragon'] = true,
	['shyvanadoubleattack'] = true,
	['monkeykingdoubleattack'] = true,
}

function OnProcessSpell(unit, spellProc)
	--Defensive Stun--
	if AUConfig.stunsettings.defensiveult.stunUlt and ((stunReady or (pyroStack == 3 and eready))) and not isRecalling and myHero.canMove then
		if unit.team == TEAM_ENEMY and ultTable[unit.charName] ~= nil and ultTable[unit.charName].spell ~= nil and AUConfig.stunsettings.defensiveult[unit.charName] ~= nil and not spellProc.name == "FallenOne" then
			if spellProc.name == ultTable[unit.charName].spell and AUConfig.stunsettings.defensiveult[unit.charName] then
				if ((stunReady or (pyroStack == 3 and eready))) and ValidTarget(unit, 620) then 
					if AUConfig.stunsettings.defensiveult.comboUlt and qready and wready and ultAvailable and stunReady and hasMana(7) then
						doCombo(7, unit)
						elseif AUConfig.stunsettings.defensiveult.comboUlt and qready and wready and eready and ultAvailable and pyroStack == 3 and hasMana(1) then
						doCombo(1, unit)
						elseif (qready or wready) and stunReady then
						if qready then CastExploit(_Q, unit) elseif wready then CastW(target) end
						elseif qready and eready and pyroStack == 3 then
						CastSpell(_E)
						if qready then CastExploit(_Q, unit) elseif wready then CastW(target) end
					end
				end
			end
		elseif unit.team == TEAM_ENEMY and ultTable[unit.charName] ~= nil and ultTable[unit.charName].spell ~= nil and AUConfig.stunsettings.defensiveult[unit.charName] ~= nil and spellProc.name == "FallenOne" then
			if spellProc.name == ultTable[unit.charName].spell and AUConfig.stunsettings.defensiveult[unit.charName] then
				karthusUltTime = os.clock() - GetLatency()/2000 
				karthus = unit
			end
		end
	end
	--Auto Shield--
	if AUConfig.autoshield.enabled and unit.team ~= myHero.team and spellProc.target == myHero and eready and (string.find(string.lower(spellProc.name), "attack") and (not notAA[string.lower(spellProc.name)]) and not string.find(string.lower(spellProc.name), "minion") or (shieldTable[spellProc.name] ~= nil)) then
		CastSpell(_E)
	end
	
	--[[
	if (spellProc.name == "annietibbersBasicAttack") or (spellProc.name == "annietibbersBasicAttack2") or string.find(spellProc.name, "annietibbersBasic") and unit.team == myHero.team then
	tibLA = os.clock() - GetLatency()/2000
	tibWind = spellProc.windUpTime
	tibAnimation = spellProc.animationTime
	target = ts.target
	--print("ATACKEDDDDDDDDDDDDDDDDDDDDDDDDD")
	end
	]]
end

function OnAnimation(unit, animation)
	if unit.isMe and string.lower(animation) == "recall" then isRecalling = true end
	if unit.isMe and string.lower(animation) == "recall_winddown" then isRecalling = false end
	--if unit.isMe then print(animation) end
end
--Now uses advanced callbacks to check pyromania stacks and if tibbers is alive (VIP Only)

function OnGainBuff(unit, buff)
	if buff.name == "pyromania" and unit.isMe then
		pyroStack = buff.stack
	end
	if buff.name == "pyromania_particle" and unit.isMe then
		pyroStack = 4
		stunReady = true
	end
	if unit.isMe and buff.name == "infernalguardiantimer" then
		tibbersAlive = true
		tibbersTimer = buff.endT
	end
	if unit.isMe and (buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinCaptureChannel") then
		isRecalling = true
	end
	if unit.isMe and AUConfig.debugprint then print("Buff: "..buff.name) end
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
	end
	if unit.isMe and buff.name == "infernalguardiantimer" then
		tibbersAlive = false
		if AUConfig.debugprint then print("tibbers dead") end
	end
	if unit.isMe and (buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinCaptureChannel") then
		isRecalling = false
	end
end

--http://botoflegends.com/forum/topic/19669-for-devs-isfacing/
function isFacing(source, target, lineLength)
	if source.visionPos ~= nil then
		local sourceVector = Vector(source.visionPos.x, source.visionPos.z)
		local sourcePos = Vector(source.x, source.z)
		sourceVector = (sourceVector-sourcePos):normalized()
		sourceVector = sourcePos + (sourceVector*(GetDistance(target, source)))
		return GetDistanceSqr(target, {x = sourceVector.x, z = sourceVector.y}) <= (lineLength and lineLength^2 or 90000)
	end
end
