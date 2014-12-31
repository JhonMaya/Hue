<?php exit() ?>--by Kain 97.90.203.108
--[[
 
        Auto Carry Plugin - Varus Edition
		Author: Kain
		Some work previously by pqmailer and Vadash. Help from Dienofail.

		Version: See version variable below.
		Copyright 2014

		Dependency: Sida's Auto Carry
 
		How to install:
			Make sure you already have AutoCarry installed.
			Name the script EXACTLY "SidasAutoCarryPlugin - Varus.lua" without the quotes.
			Place the plugin in BoL/Scripts/Common folder.

		Features:
			Combo: SBTW Intelligent Q, W, E, R 
			Piercing Arrow Auto Shot: Not only does this aim with VIP prediction, but it automatically draws the arrow back the minimum correct amount to reach the target's current distance.
			AoE MEC Hail of Arrows Multi-shot: Hits as many targets as possible with Hail of Arrows. It finds the best position in the center of all enemies to aim.
			Auto Blight Detonation: Once max Blight stacks (three) or the configured "Minimum W Stacks" in the menu have been reached, fires Q or E to detonate for max damage.
			Blighted Quiver Counter: Enemies with Blight on them have a red circle around them. As the Blight stacks increase, so does this circle.
			Auto Chain of Corruption: Ultimate fires automatically under several conditions: 1) you can hit three targets with it, 2) you're close to death and need an escape, and 3) when at least two enemies are kill-able by the cast (one if killsteal is also enabled).
			Slow Closest Enemy: The best escape mechanism. Slows the closest enemy causing you danger with Hail of Arrows, unless it is on cooldown, then slows with Chain of Corruption (there are a few other checks like enemy counts and such, just in case you're wondering why it sometimes doesn't fire). This is cast-able with the key bind, or with the E button in Pro Mode.
			Smart Minion Farming: If at least two minions are kill-able with E in last hit or mixed mode, auto fires in the center to kill them all. If at least three are low in lane clear mode, fires in the center.
			Killsteal: Killsteal with Q, E, or R.
			Range Circles: Smart range circles turn on and off as their respective spells are available.
			Damage Combo Calulator: Shows messages on targets when kill-able by a combo.
			Auto Summoner's Spells: Barrier, Ignite, and Cleanse.
			Customization: Fully customizable Combo (Q, W, E, R), Harass (Q, W, E), and Draw
			Menus: Extensive configuration options in two menus.
			Computer Guided Manual Mode: Manually using your spell keys will still use VIP prediction to make you not suck. Pressing Q will fire at the nearby target which is either kill-able, has the most Blight stacks, or has the lowest health, in that order. Pressing W will allow you to cast Q in the direction of your mouse position, for those times you want to free cast into a bush or manually farm. E slows nearest enemy (see above). And R work as expected. Disable Pro Mode in the menu if you want the spell keys Q, W, E, and R to just act as normal.
			Reborn: Fully compatible.
			Misc: Mana Manger, Jungle Farming, Prioritize Q over E, and more.
		
		Download: https://bitbucket.org/KainBoL/bol/raw/master/Common/SidasAutoCarryPlugin%20-%20Varus.lua

		Version History:
			To Do:
			Version: 3.0:
				Rework Menu
				Jungle fix.
				New drawing features.
				Draw damage lib.
				Full reborn compatibility.
				Lag free circles.
			Version: 2.2:
				Added Q shot smart buffer, to hit enemies that are running away better and avoid a stuck Q.
				Fixed logic on auto Q cast to avoid stuck situations.
				Added toggle for Draw Arrow to Target. Set to off by default, due to BoL disabling of it.
				Possibly fixed rare CastSpell error?
			Version: 2.1g:
				Q doesn't miss fire due to orbwalking anymore.
				Added smart farming and last-hitting.
				Fixed aim on minion farming.
				Fixed jungle farming.
				Added stacks counter via circles that expand on target.
				Improved aim on E multi-hitting.
				Fixed damage calculator.
				Added manual keys for Q, W, E, R.
				Added "BoL Studio Script Updater" url and hash.

			Version: 2.0: https://bitbucket.org/KainBoL/bol/src/b498e572a876/Common/SidasAutoCarryPlugin%20-%20Varus.lua
		To Do:
			X Auto Ignite
			X Farm Skills
			Q, W manual mode weirdness.
--]]

if myHero.charName ~= "Varus" then return end

version = "3.0"

if not VIP_USER then
	print("Varus is a VIP only script, due to packets use.")
	return
end

require 'Prodiction'

--[[ Core]]--
function Vars()
	-- Reborn
	if AutoCarry.Skills then IsSACReborn = true else IsSACReborn = false end

	-- Disable SAC Reborn's skills. Ours are better.
	if IsSACReborn then
		AutoCarry.Skills:DisableAll()
	end

	KeySlowE = string.byte("E") -- slow nearest target
	KeyJungle = string.byte("J") -- jungle clearing

	KeyQ = string.byte("Q")
	KeyW = string.byte("W")
	KeyE = string.byte("E")
	KeyR = string.byte("R")

	levelSequence = { nil,0,2,1,1,4,1,3,1,3,4,3,3,2,2,4,2,2 } -- we level the spells that way, first point free choice; W or E
	
	--->>> Do not touch anything below here <<<---
	QRange = 1475
	ERange = 925
	RRange = 1075

	QSpeed = 1.85
	ESpeed = 1.5
	RSpeed = 1.95

	QDelay = 10
	EDelay = 242
	RDelay = 250

	QWidth = 60
	EWidth = 275
	RWidth = 80

	if IsSACReborn then
		SkillQ = AutoCarry.Skills:NewSkill(false, _Q, QRange, "Piercing Arrow", AutoCarry.SPELL_LINEAR, 0, false, false, QSpeed, QDelay, QWidth, false)
		-- SkillW = AutoCarry.Skills:NewSkill(false, _W, WRange, "Blighted Quiver", AutoCarry.SPELL_CIRCLE, 0, false, false, WSpeed, WDelay, WWidth, false)
		SkillE = AutoCarry.Skills:NewSkill(false, _E, ERange, "Hail of Arrows", AutoCarry.SPELL_CIRCLE, 0, false, false, ESpeed, EDelay, EWidth, false)
		SkillR = AutoCarry.Skills:NewSkill(false, _R, RRange, "Chain of Corruption", AutoCarry.SPELL_LINEAR, 0, false, false, RSpeed, RDelay, RWidth, false)
	else
		SkillQ = {spellKey = _Q, range = QRange, speed = QSpeed, delay = QDelay, width = QWidth, configName = "bouncingbomb", displayName = "Q (Piercing Arrow)", enabled = true, skillShot = true, minions = false, reset = false, reqTarget = true }
		-- SkillW = {spellKey = _W, range = WRange, speed = WSpeed, delay = WDelay, width = WWidth, configName = "satchelcharge", displayName = "W (Blighted Quiver)", enabled = true, skillShot = true, minions = false, reset = false, reqTarget = false }
		SkillE = {spellKey = _E, range = ERange, speed = ESpeed, delay = EDelay, width = EWidth, configName = "hexplosiveminefield", displayName = "E (Hail of Arrows)", enabled = true, skillShot = true, minions = false, reset = false, reqTarget = false }
		SkillR = {spellKey = _R, range = RRange, speed = RSpeed, delay = RDelay, width = RWidth, configName = "megainfernobomb", displayName = "R (Chain of Corruption)", enabled = true, skillShot = true, minions = false, reset = false, reqTarget = true }
	end

	QMinRange = 850
	RJumpRange = 550

	Prod = ProdictManager.GetInstance()
	ProdQ = Prod:AddProdictionObject(_Q, 1600, 1900, 0.250, 55)
	ProdQ2 = Prod:AddProdictionObject(_Q, 1600, 1900, 0.500, 55)
	ProdE = Prod:AddProdictionObject(_E, 925, 1500, 0.242, 275)
	Qlastcast = 0
	Qdifftime = 0 
	Qcasttime = 0
	Qstartcasttime = 0
	isPressedQ = false 
	CurrentRange = 0

	AdvancedCallback:bind('OnGainBuff', function(unit, buff) OnGainBuff(unit, buff) end)
	AdvancedCallback:bind('OnLoseBuff', function(unit, buff) OnLoseBuff(unit, buff) end)
	AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) OnUpdateBuff(unit, buff) end)

	floattext = {"Harass him","Fight him","Kill him","Murder him"} -- text assigned to enemys

	killable = {} -- our enemy array where stored if people are killable
	waittxt = {} -- prevents UI lags, all credits to Dekaron

	QReady, WReady, EReady, RReady, BWCReady, RUINEDKINGReady, QUICKSILVERReady, RANDUINSReady, IGNITEReady, BARRIERReady, CLEANSEReady = nil, nil, nil, nil, nil, nil, nil, nil, nil, nil, nil

	tick = nil

	Cast = false
	QTick = 0

	ProcStacks = {}

	for _, enemy in pairs(AutoCarry.EnemyTable) do
		ProcStacks[enemy.networkID] = 0
	end

	IGNITESlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
	BARRIERSlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerBarrier") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerBarrier") and SUMMONER_2) or nil)
	CLEANSESlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerCleanse") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerCleanse") and SUMMONER_2) or nil)

	for i=1, heroManager.iCount do waittxt[i] = i*3 end -- All credits to Dekaron

	if IsSACReborn then
		AutoCarry.Crosshair:SetSkillCrosshairRange(1600)
	else
		AutoCarry.SkillsCrosshair.range = 1600
	end

	-- qp = TargetPredictionVIP(QRange, QSpeed * 1000, QDelay / 1000, QWidth)

	debugMode = false
	debugErrorsMode = false

	-- Jungle
	JungleKill = nil

	JungleMobs = {}
	JungleFocusMobs = {}
	JungleBuffs = {}

	-- Twisted Treeline Jungle
	gameState = GetGame()
	TTMAP = false
	if gameState.map.shortName == "twistedTreeline" then
		TTMAP = true
	else
		TTMAP = false
	end

	wpm = WayPointManager()
 
	useDrawDamageLib = true

	-- if FileExist(SCRIPT_PATH..'Common/DrawDamageLib.lua') then
	-- 	require "DrawDamageLib"
	--	useDrawDamageLib = true
	-- else
	--	PrintChat("<font color='#CCCCCC'> >> Please install the DrawDamageLib for the best experience. <</font>")
	-- end

	QCastPosition = nil
	QTime = nil
	QHitChance  = nil

	Q2CastPosition = nil
	Q2Time = nil
	Q2HitChance = nil

	Target = nil
end

function Menu()
	Menu = AutoCarry.PluginMenu

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Info]", "ScriptInfo")
		Menu.ScriptInfo:addParam("sep", myHero.charName.." Auto Carry: Version "..version, SCRIPT_PARAM_INFO, "")
		Menu.ScriptInfo:addParam("sep1","Created By: Kain", SCRIPT_PARAM_INFO, "")

	-- Settings
	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Auto Carry]", "AutoCarry")
		Menu.AutoCarry:addParam("ComboQ", "Use Piercing Arrow", SCRIPT_PARAM_ONOFF, true)
		Menu.AutoCarry:addParam("ComboR", "Use Chain of Corruption", SCRIPT_PARAM_ONOFF, true) -- decide if ulti should be used in full combo
		Menu.AutoCarry:addParam("UseItems", "Use Items", SCRIPT_PARAM_ONOFF, true) -- decide if items should be used in full combo
		Menu.AutoCarry:addParam("SlowE", "Slow nearest enemy with E", SCRIPT_PARAM_ONKEYDOWN, false, KeySlowE) -- auto slow
		Menu.AutoCarry:addParam("SlowR", "Slow with R (if E on CD)", SCRIPT_PARAM_ONOFF, true) -- use ulti to escape

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Mixed Mode]", "MixedMode")
		Menu.MixedMode:addParam("HarassQ", "Harass with Q", SCRIPT_PARAM_ONOFF, true) -- Harass with Q
		Menu.MixedMode:addParam("HarassE", "Harass with E", SCRIPT_PARAM_ONOFF, true) -- Harass with E
		Menu.MixedMode:addParam("AutoQE", "Auto Q/E if W is stacked", SCRIPT_PARAM_ONOFF, true) -- Auto Q/E if W is stacked
		Menu.MixedMode:addParam("MaxQHarassDistance", "Max. Q Harass Range", SCRIPT_PARAM_SLICE, QRange, 0, QRange, 0) -- W stacks to Q

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Blight Stacks]", "BlightStacks")
		Menu.BlightStacks:addParam("PrioritizeQ", "Prioritize Q", SCRIPT_PARAM_ONOFF, true) -- q > e on prock
		Menu.BlightStacks:addParam("CarryMinWForQ", "Carry Mode: Min. to Q", SCRIPT_PARAM_SLICE, 3, 0, 3, 0) -- W stacks to Q
		Menu.BlightStacks:addParam("CarryMinWForE", "Carry Mode: Min. to E", SCRIPT_PARAM_SLICE, 2, 0, 3, 0) -- W stacks to E
		Menu.BlightStacks:addParam("MixedMinWForQE", "Mixed Mode: Min. to Q/E", SCRIPT_PARAM_SLICE, 1, 0, 3, 0) -- W stacks to Q/E

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Killsteal]", "Killsteal")
		Menu.Killsteal:addParam("KillstealQ", "Use Piercing Arrow", SCRIPT_PARAM_ONOFF, true) -- KS with all skills
		Menu.Killsteal:addParam("KillstealE", "Use Hail of Arrows", SCRIPT_PARAM_ONOFF, true) -- KS with all skills
		Menu.Killsteal:addParam("KillstealR", "Use Chain of Corruption", SCRIPT_PARAM_ONOFF, true) -- KS with all skills

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Farming]", "Farming")
		Menu.Farming:addParam("JungleClear", "Jungle clearing", SCRIPT_PARAM_ONKEYTOGGLE, true, KeyJungle) -- jungle clearing
		Menu.Farming:addParam("JungleSteal","Jungle Steal", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
		-- Menu.Farming:addParam("FarmSkills", "Use Skills with Lane Clear mode", SCRIPT_PARAM_ONOFF, true) -- spamming e on the minions while lane clearing
		Menu.Farming:addParam("LastHitE", "Smart Last hit with E", SCRIPT_PARAM_ONOFF, true) -- Last hit with E
		Menu.Farming:addParam("LastHitMinimumMinions", "Min minions for E last hit", SCRIPT_PARAM_SLICE, 2, 1, 10, 0) -- minion slider
		Menu.Farming:addParam("LaneClearE", "Lane Clear with E", SCRIPT_PARAM_ONOFF, true) -- Lane clearing with E.

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Misc]", "Misc")
		Menu.Misc:addParam("AutoLevelSkills", "Auto Level Skills (Requires Reload)", SCRIPT_PARAM_ONOFF, true) -- auto level skills
		Menu.Misc:addParam("ManaManager", "Mana Manager %", SCRIPT_PARAM_SLICE, 40, 0, 100, 2)
		Menu.Misc:addParam("ProMode", "Use Auto QWER Keys", SCRIPT_PARAM_ONOFF, true)
		Menu.Misc:addParam("WWaitDelay", "Delay before Q via W (ms)",SCRIPT_PARAM_SLICE, 250, 0, 2000, 2) -- the q delay

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Summoner Spells]", "SummonerSpells")
		Menu.SummonerSpells:addParam("AutoBarrier", "Use Barrier", SCRIPT_PARAM_ONOFF, true) -- barrier
		Menu.SummonerSpells:addParam("BarrierHealthRatio", "Barrier Health Ratio", SCRIPT_PARAM_SLICE, 0.15, 0, 1, 2) -- health ratio
		Menu.SummonerSpells:addParam("AutoCleanse", "Auto Cleanse", SCRIPT_PARAM_ONOFF, true) -- cleanse

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Draw]", "Draw")
		Menu.Draw:addParam("DrawStacks", "Draw Blighted Quiver Stacks", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawKillable", "Draw Killable Enemies", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawTargetArrow", "Draw Arrow to Target", SCRIPT_PARAM_ONOFF, false)
		Menu.Draw:addParam("DrawTargetLine","Draw Target Line", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawTargetWaypoints","Draw Target Waypoints", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawText", "Draw Text", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawPrediction", "Draw Prediction", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawMousePos", "Draw Mouse Position", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawDmg", "Draw Damage Ticks", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DisableDrawCircles", "Disable Draw", SCRIPT_PARAM_ONOFF, false)
		Menu.Draw:addParam("DrawFurthest", "Draw Furthest Spell Available", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawQ", "Draw Piercing Arrow", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawE", "Draw Hail of Arrows", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawR", "Draw Chain of Corruption", SCRIPT_PARAM_ONOFF, true)

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Advanced]", "Advanced")
		Menu.Advanced:addParam("QMaxBuffer", "Q maximum time buffer", SCRIPT_PARAM_SLICE, 1450, 300, 1450, 0) -- Max time to hold Q
		Menu.Advanced:addParam("QBuffer", "Q minimum time buffer", SCRIPT_PARAM_SLICE, 200, 0, 500, 0)
		Menu.Advanced:addParam("QHitChance", "Q Hit Chance Auto", SCRIPT_PARAM_SLICE, 0.10, 0, 1, 2) -- Max time to hold Q
		Menu.Advanced:addParam("QHitChanceManual", "Q Hit Chance Manual", SCRIPT_PARAM_SLICE, 0.40, 0, 1, 2) -- Max time to hold Qsss

--	Menu.AutoCarry:permaShow("SlowE")
--	Menu.AutoCarry:permaShow("Jungle")
end

function _G.PluginOnLoad()
	Vars()
	Menu()

	if Menu.Misc.AutoLevelSkills then -- setup the skill autolevel
		autoLevelSetSequence(levelSequence)
		autoLevelSetFunction(onChoiceFunction) -- add the callback to choose the first skill
	end

	LoadJungle()
	if useDrawDamageLib then
		SetDamageTicks()
	end

	PrintChat(" >> Varus: The Arrow of Retribution by Kain and pqmailer loaded!")
end

function _G.PluginOnTick()
		
	tick = GetTickCount()
	if (webTrialVersion ~= nil and scriptTrialVersion ~= webTrialVersion) or webTrialVersion == nil then
		if webTrialVersion ~= nil then
			print("<font color='#c22e13'>This version of the script has been disabled, go to forums for update.</font>")
		end

		return
	end

	-- Disable SAC Reborn's auto E. Ours is better.
	if AutoCarry.Skills then
		AutoCarry.Skills:GetSkill(_E).Enabled = false
	end

	Target = GetTarget()

	SpellCheck()

	UpdateQCasttime()
	CheckQCastTime()

	if not AutoCarry.MainMenu.AutoCarry and not AutoCarry.MainMenu.MixedMode and not AutoCarry.MainMenu.LastHit and not AutoCarry.MainMenu.LaneClear then CheckQ() end

	CurrentRange = ConvertQCastTime(Qcasttime)
	if ValidTarget(Target) and Target ~= nil then
		QCastPosition, QTime, QHitChance = ProdQ:GetPrediction(Target)
		Q2CastPosition, Q2Time, Q2HitChance = ProdQ2:GetPrediction(Target)		
		-- print(QCastPosition)
		-- print(Q2CastPosition)
	end

	if (TargetHaveBuff("SummonerDot", myHero) and TargetHaveBuff("SummonerExhaust", myHero))
		or (TargetHaveBuff("SummonerDot", myHero) and myHero.health/myHero.maxHealth <= 0.5)
		or (TargetHaveBuff("SummonerExhaust", myHero) and myHero.health/myHero.maxHealth <= 0.5)
		and CLEANSEReady and Menu.SummonerSpells.AutoCleanse then
		CastSpell(CLEANSESlot)
	end

	if myHero.health / myHero.maxHealth <= Menu.SummonerSpells.BarrierHealthRatio and BARRIERReady and Menu.SummonerSpells.AutoBarrier then
		CastSpell(BARRIERSlot)
	end

	Killsteal()

	if Menu.AutoCarry.SlowE then
		SlowClosestEnemy()
	end

	if AutoCarry.MainMenu.AutoCarry then
		Combo()
	end

	if AutoCarry.MainMenu.MixedMode then
		Harass()
		if Menu.MixedMode.AutoQE then
			CastEQAuto()
		end
	end

	if (AutoCarry.MainMenu.LastHit or Menu.Farming.JungleSteal) then
		JungleSteal()
	end

	if AutoCarry.MainMenu.LaneClear and Menu.Farming.JungleClear then
		JungleClear()
	end

	if (Menu.Farming.LastHitE or Menu.Farming.LaneClearE) and IsTickReady(40) and not IsMyManaLow() then
		if Menu.Farming.LastHitE and (AutoCarry.MainMenu.LastHit or AutoCarry.MainMenu.MixedMode) then
			SmartFarmWithE(false)
		elseif Menu.Farming.LaneClearE and AutoCarry.MainMenu.LaneClear then
			SmartFarmWithE()
		end
	end
end

function LoadJungle()
	if TTMAP then --
		FocusJungleNames = {
			["TT_NWraith1.1.1"] = true,
			["TT_NGolem2.1.1"] = true,
			["TT_NWolf3.1.1"] = true,
			["TT_NWraith4.1.1"] = true,
			["TT_NGolem5.1.1"] = true,
			["TT_NWolf6.1.1"] = true,
			["TT_Spiderboss8.1.1"] = true,
							}
		
		JungleMobNames = {
	        ["TT_NWraith21.1.2"] = true,
	        ["TT_NWraith21.1.3"] = true,
	        ["TT_NGolem22.1.2"] = true,
	        ["TT_NWolf23.1.2"] = true,
	        ["TT_NWolf23.1.3"] = true,
	        ["TT_NWraith24.1.2"] = true,
	        ["TT_NWraith24.1.3"] = true,
	        ["TT_NGolem25.1.1"] = true,
	        ["TT_NWolf26.1.2"] = true,
	        ["TT_NWolf26.1.3"] = true,
						}

		JungleBuffNames = {
			["TT_Spiderboss7.1.1"] = true
						  }
	else 
		JungleMobNames = { 
	        ["Wolf8.1.2"] = true,
	        ["Wolf8.1.3"] = true,
	        ["YoungLizard7.1.2"] = true,
	        ["YoungLizard7.1.3"] = true,
	        ["LesserWraith9.1.3"] = true,
	        ["LesserWraith9.1.2"] = true,
	        ["LesserWraith9.1.4"] = true,
	        ["YoungLizard10.1.2"] = true,
	        ["YoungLizard10.1.3"] = true,
	        ["SmallGolem11.1.1"] = true,
	        ["Wolf2.1.2"] = true,
	        ["Wolf2.1.3"] = true,
	        ["YoungLizard1.1.2"] = true,
	        ["YoungLizard1.1.3"] = true,
	        ["LesserWraith3.1.3"] = true,
	        ["LesserWraith3.1.2"] = true,
	        ["LesserWraith3.1.4"] = true,
	        ["YoungLizard4.1.2"] = true,
	        ["YoungLizard4.1.3"] = true,
	        ["SmallGolem5.1.1"] = true,
						}
		FocusJungleNames = {
	        ["Dragon6.1.1"] = true,
	        ["Worm12.1.1"] = true,
	        ["GiantWolf8.1.1"] = true,
	        ["AncientGolem7.1.1"] = true,
	        ["Wraith9.1.1"] = true,
	        ["LizardElder10.1.1"] = true,
	        ["Golem11.1.2"] = true,
	        ["GiantWolf2.1.1"] = true,
	        ["AncientGolem1.1.1"] = true,
	        ["Wraith3.1.1"] = true,
	        ["LizardElder4.1.1"] = true,
	        ["Golem5.1.2"] = true,
			["GreatWraith13.1.1"] = true,
			["GreatWraith14.1.1"] = true,
					}

		JungleBuffNames = {
			["Worm12.1.1"] = true,
			["Dragon6.1.1"] = true,
			["AncientGolem1.1.1"] = true,
			["AncientGolem7.1.1"] = true,
			["LizardElder4.1.1"] = true,
			["LizardElder10.1.1"] = true,
						  }
	end

	for i = 0, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil then
			if JungleBuffNames[object.name] then
				table.insert(JungleBuffs, object)
			end
			if FocusJungleNames[object.name] then
				table.insert(JungleFocusMobs, object)
			elseif JungleMobNames[object.name] then
				table.insert(JungleMobs, object)
			end
		end
	end
end

function GetTarget()
	if IsSACReborn then
		return AutoCarry.Crosshair:GetTarget()
	else
		return AutoCarry.GetAttackTarget()
	end
end

function SetDamageTicks()
	setOrder("Q", 4)
	setOrder("W", 3)
	setOrder("E", 2)
	setOrder("R", 1)
	setFlipped(true)
end

function _G.PluginOnDraw()
	-- DrawText3D("Current isPressedQ status is " .. tostring(isPressedQ) .. ' ' ..tostring(Qcasttime) .. ' ' .. tostring(CurrentRange), myHero.x, myHero.y, myHero.z, 25,  ARGB(255,255,0,0), true)

	if Target ~= nil and not Target.dead and Menu.Draw.DrawTargetArrow and (AutoCarry.MainMenu.AutoCarry or AutoCarry.MainMenu.MixedMode) then
		if Menu.Draw.DrawTargetArrow then
			DrawArrowsToPos(myHero, Target)
		end

		if Menu.Draw.DrawTargetLine then
			local x1, y1, OnScreen1 = get2DFrom3D(myHero.x, myHero.y, myHero.z)
			local x2, y2, OnScreen2 = get2DFrom3D(Target.x, Target.y, Target.z)
			DrawLine(x1, y1, x2, y2, 3, 0xFFFF0000)
		end

		if Menu.Draw.DrawTargetWaypoints then
			wpm:DrawWayPoints(Target, ARGB(255, 255, 0, 0))
		end
	end

	if Target ~= nil and not Target.dead and QCastPosition ~= nil then
		DrawCircle(QCastPosition.x, QCastPosition.y, QCastPosition.z, 100, 0xFF0000)
	end

	-- if Target ~= nil and not Target.dead and Q2CastPosition ~= nil then
	-- 	DrawCircle(Q2CastPosition.x, Q2CastPosition.y, Q2CastPosition.z, 100, 0xFF0000)
	-- end

	if Menu.Draw.DrawStacks then
		for i, enemy in pairs(AutoCarry.EnemyTable) do
			if ValidTarget(enemy, ERange) then
				if ProcStacks[enemy.networkID] > 0 then
					-- DrawCircle(enemy.x, enemy.y, enemy.z, (60+(20 * ProcStacks[enemy.networkID])), 0xFF0000)
					for j=0, 10 * ProcStacks[enemy.networkID] do
						DrawCircle(enemy.x, enemy.y, enemy.z, 80 + j*1.5, 0xFF0000) -- Red
					end
				end
			end
		end
	end

	if IsTickReady(75) then DMGCalculation() end
	DrawKillable()

	if Menu.Draw.DrawDmg and useDrawDamageLib then
		drawDamage()
	end

	if Menu.Draw.DrawMousePos then
		local trueRange = getTrueRange()
		if GetDistance(mousePos) < trueRange then
			local myPosV = Vector(myHero.x, myHero.z)
			local mousePosV = Vector(mousePos.x, mousePos.z)
			local finalV = myPosV + (mousePosV - myPosV):normalized() * (trueRange - 35)
			DrawCircle(finalV.x, myHero.y, finalV.y, 40, 0x00FF00)
			DrawCircle(finalV.x, myHero.y, finalV.y, 50, 0xFFFF00) 
		end
	end

	DrawRanges()
end

function getTrueRange()
    return myHero.range + GetDistance(myHero.minBBox)
end

function DrawKillable()
	if Target ~= nil and Menu.Draw.DrawPrediction then
		if VIP_USER then
			pred = TargetPredictionVIP(QRange, QSpeed * 1000, QDelay / 1000, QWidth)
		elseif not VIP_USER then
			pred = TargetPrediction(QRange, QSpeed, QDelay, QWidth)
		end
		
		if pred then
			predPos = pred:GetPrediction(Target)
			if predPos then
				DrawCircle(predPos.x, Target.y, predPos.z, 200, 0x0000FF)
			end
		end
	end

	if Menu.Draw.DrawKillable and not myHero.dead then
		for i=1, heroManager.iCount do
			local Unit = heroManager:GetHero(i)
			if ValidTarget(Unit) then -- we draw our circles
				 if killable[i] == 1 then
				 	DrawCircle(Unit.x, Unit.y, Unit.z, 100, 0xFFFFFF00)
				 end

				 if killable[i] == 2 then
				 	DrawCircle(Unit.x, Unit.y, Unit.z, 100, 0xFFFFFF00)
				 end

				 if killable[i] == 3 then
				 	for j=0, 10 do
				 		DrawCircle(Unit.x, Unit.y, Unit.z, 100+j*0.8, 0x099B2299)
				 	end
				 end

				 if killable[i] == 4 then
				 	for j=0, 10 do
				 		DrawCircle(Unit.x, Unit.y, Unit.z, 100+j*0.8, 0x099B2299)
				 	end
				 end

				 if Menu.Draw.DrawText and waittxt[i] == 1 and killable[i] ~= nil and killable[i] ~= 0 and killable[i] ~= 1 then
				 	PrintFloatText(Unit,0,floattext[killable[i]])
				 end
			end

			if waittxt[i] == 1 then
				waittxt[i] = 30
			else
				waittxt[i] = waittxt[i]-1
			end
		end
	end
end

function DrawRanges()
	if not Menu.Draw.DisableDrawCircles and not myHero.dead then
		local farSpell = FindFurthestReadySpell()

		-- DrawCircle(myHero.x, myHero.y, myHero.z, getTrueRange(), 0x808080) -- Gray

		if Menu.Draw.DrawQ and QReady and ((Menu.Draw.DrawFurthest and farSpell and farSpell == QRange) or not Menu.Draw.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, QRange, 0x0099CC) -- Blue
		end

		if Menu.Draw.DrawE and EReady and ((Menu.Draw.DrawFurthest and farSpell and farSpell == ERange) or not Menu.Draw.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, ERange, 0xFFFF00) -- Yellow
		end

		if Menu.Draw.DrawR and RReady and ((Menu.Draw.DrawFurthest and farSpell and farSpell == RRange) or not Menu.Draw.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, RRange, 0xFF0000) -- Red
		end

		Target = GetTarget()
		if Target ~= nil then
			for j=0, 10 do
				DrawCircle(Target.x, Target.y, Target.z, 40 + j*1.5, 0x00FF00) -- Green
			end
		end
	end
end

function FindFurthestReadySpell()
	local farSpell = nil

	if Menu.Draw.DrawQ and QReady then farSpell = QRange end
	if Menu.Draw.DrawE and EReady and (not farSpell or ERange > farSpell) then farSpell = ERange end
	if Menu.Draw.DrawR and RReady and (not farSpell or RRange > farSpell) then farSpell = RRange end

	return farSpell
end

function DrawArrowsToPos(pos1, pos2)
	if pos1 and pos2 then
		startVector = D3DXVECTOR3(pos1.x, pos1.y, pos1.z)
		endVector = D3DXVECTOR3(pos2.x, pos2.y, pos2.z)
		DrawArrows(startVector, endVector, 60, 0xE97FA5, 100)
	end
end

function DrawWayPoints()
	local points = wpm:GetWayPoints(myHero)

	if points and #points >= 2 then
		local endPoint = {x = points[#points].x, y = myHero.y, z = points[#points].y}
		
		if not currentForm and GetDistance(endPoint) > RushMinDistance then
			wpm:DrawWayPoints(myHero, ARGB(255, 0, 255, 0))
		end
	end
end

--Added by Dienofail--

function CheckQCastTime()
	if GetTickCount() - Qstartcasttime > 1450 then
		Qcasttime = 1450
	end
	if GetTickCount()- Qstartcasttime > 5000 then
		Qcasttime = 0
		isPressedQ = false
		AutoCarry.CanAttack = true
		AutoCarry.CanMove = true
	end
end

function ConvertQCastTime()
	local range = 0

	if isPressedQ then 
		--PrintChat("Q is being updated!")
		range = 850
		if Qcasttime >= 1450 then
			range = 1600
		end
		if Qcasttime > 0 and Qcasttime < 1450 then
			--PrintChat("Middle calculation being done!")
			range = (Qcasttime / 2000)*1150 + 850
			--PrintChat("middle calculation result " .. tostring(range))
		end
		return range
	else 
		return 0
	end
end

function UpdateQCasttime()
	if isPressedQ then
		Qcasttime = GetTickCount() - Qstartcasttime
	end
	if not isPressedQ then
		Qcasttime = 0
	end
end

function _G.OnGainBuff(unit, buff)
	if unit.isMe and (buff.name == 'varusqlaunch'or buff.name == 'VarusQ' or buff.name == 'varusqlaunchsound')  then
		--PrintChat("Gained")
		isPressedQ = true
		Qstartcasttime = GetTickCount()
	end

	if buff.name == 'varuswdebuff' and unit.team ~= myHero.team then
		ProcStacks[unit.networkID] = 1
	end
end

function _G.OnUpdateBuff(unit, buff)
	if buff.name == 'varuswdebuff' then
		ProcStacks[unit.networkID] = buff.stack
	end
end

function _G.OnLoseBuff(unit, buff)
	if unit.isMe and buff.name == 'VarusQ' then
		--PrintChat("Lost")
		isPressedQ  = false
		Qcasttime = 0
		QTick = GetTickCount()
		AutoCarry.CanAttack = true
		AutoCarry.CanMove = true
	end

	if buff.name == 'varuswdebuff' and unit.team ~= myHero.team then
		ProcStacks[unit.networkID] = 0
	end
end

function CheckQ()
	if not IsKeyDown(KeyQ) and isPressedQ then
		local closeTarget = Target and GetDistance(Target) < QRange

 		if closeTarget and QCastPosition then
			Send2ndQPacket(QCastPosition.x, QCastPosition.z)
 		elseif not closeTarget then
 			Send2ndQPacket(mousePos.x, mousePos.z)
 		end
	end
end

function Send2ndQPacket(xpos, zpos)
	--PrintChat("Packet Called!")
	if isPressedQ then
		packet = CLoLPacket(0xE5)
		packet:EncodeF(myHero.networkID)
		packet:Encode1(128)
		packet:EncodeF(xpos)
		packet:EncodeF(myHero.y)
		packet:EncodeF(zpos)
		packet.dwArg1 = 1
		packet.dwArg2 = 0
		SendPacket(packet)
	end
	--PrintChat("Packet Sent!")
--nID, spell, x, y, z
end

function Cast1stQTarget(Target)
	--print('Cast1stQTarget called! with ' .. tostring(Q2HitChance) .. ' ' .. tostring(Q2CastPosition.x) .. ' ' .. tostring(Q2CastPosition.z))
	if QReady and ValidTarget(Target) and not isPressedQ and Q2HitChance ~= nil and Q2CastPosition ~= nil then
		if Q2HitChance > Menu.Advanced.QHitChance and GetDistance(Q2CastPosition, myHero) < 1700 then
			Packet("S_CAST", {spellId = _Q, x = Q2CastPosition.x, y = Q2CastPosition.z}):send()
			isPressedQ = true
			AutoCarry.CanAttack = false
		end
	end
end


function Cast1stQTargetManual(Target)
	--print('Cast1stQTargetManual called!')
	if QReady and ValidTarget(Target) and not isPressedQ and Q2HitChance ~= nil and Q2CastPosition ~= nil then
		if Q2HitChance > Menu.Advanced.QHitChanceManual and GetDistance(Q2CastPosition, myHero) < 1700 then
			Packet("S_CAST", {spellId = _Q, x = Q2CastPosition.x, y = Q2CastPosition.z}):send()
			isPressedQ = true
			AutoCarry.CanAttack = false
		end
	end
end

function Cast2ndQTargetManual(Target)
	-- print('Cast2ndQTargetManual called!')
	if QReady and ValidTarget(Target) and QCastPosition and GetDistance(myHero, Target) < 1700 and isPressedQ then
		--print(to_move_position)
		if QHitChance > Menu.Advanced.QHitChanceManual and GetDistance(QCastPosition, myHero) < CurrentRange then -- QQQ, QHitChance doesn't exist. Need to rework prodiction here somehow.
			QMovePos(Target)
			--print('Move 1 to called at ' .. tostring(to_move_position))
			Send2ndQPacket(QCastPosition.x, QCastPosition.z)
			AutoCarry.CanAttack = true
			--print('Move 3 to called at ' .. tostring(to_move_position))
		end
	end
end

function Cast2ndQTarget(Target)
	if QCastPosition ~= nil and QHitChance ~= nil then
		-- print('Cast2ndQTarget called! with ' .. tostring(QHitChance) .. ' ' .. tostring(QCastPosition.x) .. ' ' .. tostring(QCastPosition.z) .. ' ' ..tostring(AutoCarry.CanAttack))
		if QReady and ValidTarget(Target) and GetDistance(myHero, Target) < 1700 and isPressedQ and Qcasttime > Menu.Advanced.QBuffer then
			--to_move_position = myHero + (Vector(QCastPosition) - myHero):normalized()*10 
			--print(to_move_position)
			if QHitChance > 0.45 and GetDistance(QCastPosition, myHero) < CurrentRange then
				QMovePos(Target)
				--print('Move 1 to called at ' .. tostring(to_move_position))
				Send2ndQPacket(QCastPosition.x, QCastPosition.z)
				AutoCarry.CanAttack = true
			elseif Qcasttime > Menu.Advanced.QMaxBuffer / 2 and QHitChance > 0.20 and GetDistance(QCastPosition, myHero) < CurrentRange then
				QMovePos(Target)
				Send2ndQPacket(QCastPosition.x, QCastPosition.z)
				AutoCarry.CanAttack = true
				--print('Move 2 to called at ' .. tostring(to_move_position))
			elseif Qcasttime > Menu.Advanced.QMaxBuffer and GetDistance(QCastPosition, myHero) < CurrentRange then
				QMovePos(Target)
				Send2ndQPacket(QCastPosition.x, QCastPosition.z)
				AutoCarry.CanAttack = true
				--print('Move 3 to called at ' .. tostring(to_move_position))
			end
		end
	end
end


--End additions-


function _G.OnSendPacket(packet)
	-- Old handler for SAC: Revamped
	PluginOnSendPacket(packet)
end

--[[
Old code from previous version packets:
function _G.PluginOnSendPacket(packet)
	-- New handler for SAC: Reborn
	local p = Packet(packet)

	-- if p:get("spellId") == _E and not (AutoCarry.MainMenu.AutoCarry or AutoCarry.MainMenu.LaneClear or AutoCarry.MainMenu.MixedMode or Menu.AutoCarry.SlowE) then
	--	p:block()
	-- end
    if packet.header == 0xE6 then --and Cast then -- 2nd cast of channel spells packet2
		packet.pos = 5
        spelltype = packet:Decode1()
        if spelltype == 0x80 then -- 0x80 == Q
            packet.pos = 1
            packet:Block()
        end
    end
end
]]--

function _G.PluginOnSendPacket(packet)
	-- New handler for SAC: Reborn
	local p = Packet(packet)
	--if p:get("spellId") == _E and not (AutoCarry.MainMenu.AutoCarry or AutoCarry.MainMenu.LaneClear or AutoCarry.MainMenu.MixedMode or Menu.AutoCarry.SlowE) then
		--p:block()
	--end
    if packet.header == 0xE5 and isPressedQ then --and Cast then -- 2nd cast of channel spells packet2

		packet.pos = 5
        spelltype = packet:Decode1()
        if spelltype == 0x80 then -- 0x80 == Q
            packet.pos = 1
            packet:Block()
            PrintChat("(Debug) Packet blocked")
        end
    end
    if packet.header == 0x99 and isPressedQ then --and Cast then -- 2nd cast of channel spells packet2
        packet:Block()
		PrintChat("(Debug) Packet blocked")
	end
end

function Combo()
	local target = GetTarget()

	if isPressedQ and ValidTarget(target) then
		Cast2ndQTarget(target)
	end

	local calcenemy = 1
	local EnemysInRange = CountEnemyHeroInRange()
	local TrueRange = GetTrueRange()

	if not target or not ValidTarget(target) then return true end

	for i=1, heroManager.iCount do
    	local Unit = heroManager:GetHero(i)
    	if Unit.charName == target.charName then
    		calcenemy = i
    	end
   	end

   	if IGNITEReady and killable[calcenemy] == 3 then CastSpell(IGNITESlot, target) end

   	if Menu.AutoCarry.UseItems then
   		if BWCReady and (killable[calcenemy] == 2 or killable[calcenemy] == 3) then CastSpell(BWCSlot, target) end
   		if RUINEDKINGReady and (killable[calcenemy] == 2 or killable[calcenemy] == 3) then CastSpell(RUINEDKINGSlot, target) end
   		if RANDUINSReady then CastSpell(RANDUINSSlot) end
   	end

	if RReady and Menu.AutoCarry.ComboR and (EnemyCount(target, RJumpRange) >= 3 or (myHero.health / myHero.maxHealth <= 0.4) or killable[calcenemy] == 2 or killable[calcenemy] == 3) then
		CastR()
	end

	CastEQAuto()
end

function Harass()
	local target = GetTarget()

	if isPressedQ and ValidTarget(target) then
		Cast2ndQTargetManual(target)
	end

	local TrueRange = GetTrueRange()

	if ValidTarget(target) then
		local targetDistance = GetDistance(target)

		if not IsMyManaLow() then
			if Menu.MixedMode.HarassE and EReady and targetDistance <= ERange then CastE(target) end
			if Menu.MixedMode.HarassQ and QReady and targetDistance <= QRange and targetDistance > TrueRange
				and targetDistance <= Menu.MixedMode.MaxQHarassDistance then -- and GetTickCount() > QTick + (GetQDelay(target)) then
				CastQ(target, false)
			end
		end
	end
end

--[[
function LaneClear()
	if not EReady then return true end

	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		if ValidTarget(minion, ERange) and getDmg("E", minion, myHero) >= minion.health then
			if IsSACReborn then
				SkillE:Cast(minion)
			else
				AutoCarry.CastSkillshot(SkillE, minion)
			end
		end
	end

	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		if ValidTarget(minion, Qrange) and getDmg("Q", minion, myHero) >= minion.health then CastQ(minion, false) end
	end

	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		if ValidTarget(minion, ERange) then
			if IsSACReborn then
				SkillE:Cast(minion)
			else
				AutoCarry.CastSkillshot(SkillE, minion)
			end
		end
	end

	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		if ValidTarget(minion, QRange) then CastQ(minion, false) end
	end
end
--]]

--[[
function LastHitE()
	if not EReady then return true end

	local killableMinions = 0
	local Minions = {}

	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		if ValidTarget(minion, ERange) and getDmg("E", minion, myHero) >= minion.health then
			killableMinions = killableMinions + 1
			table.insert(Minions, minion)
		end
	end

	if killableMinions >= Menu.Farming.LastHitMinimumMinions then
		for _, minion in pairs(Minions) do
			if ValidTarget(minion, ERange) and EReady then
				if IsSACReborn then
					SkillE:Cast(minion)
				else
					AutoCarry.CastSkillshot(SkillE, minion)
				end
			end
			return
		end
	end
	return
end
--]]

function GetDamage(enemy, spell)
	if spell == _E then
		return myHero:CalcDamage(enemy, ((35*(myHero:GetSpellData(_E).level-1) + 65) + (.60 * myHero.addDamage)))
	end
end

function SmartFarmWithE(laneClear)
	if not EReady then return true end

	local minions = {}
	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		if ValidTarget(minion, ERange) and EReady then
			local spellDmg = GetDamage(minion, _E)
			if (not laneClear and minion.health < spellDmg) or (laneClear and minion.health < spellDmg * 3) then 
				table.insert(minions, minion)
			end
		end
	end

	local minionClusters = {}

	local closeMinion = EWidth
	for _, minion in pairs(minions) do
		local foundCluster = false
		for i, mc in ipairs(minionClusters) do
			if GetDistance(mc, minion) < closeMinion then
				mc.x = ((mc.x * mc.count) + minion.x) / (mc.count + 1)
				mc.z = ((mc.z * mc.count) + minion.z) / (mc.count + 1)
				mc.count = mc.count + 1
				foundCluster = true
				break
			end
		end
 
		if not foundCluster then
			local mc = {x=0, z=0, count=0}
			mc.x = minion.x
			mc.z = minion.z
			mc.count = 1
			table.insert(minionClusters, mc)
		end
	end

	if #minionClusters < 1 then return end

	local largestCluster = 0
	local largestClusterSize = 0
	for i, mc in ipairs(minionClusters) do
		if mc.count > largestClusterSize then
			largestCluster = i
			largestClusterSize = mc.count
		end
	end

	if debugMode and largestClusterSize >= Menu.Farming.LastHitMinimumMinions then
		PrintChat("totalClusters: "..#minionClusters..", largestCluster: "..largestCluster..", largestClusterSize: "..largestClusterSize)
	end

	if largestClusterSize >= Menu.Farming.LastHitMinimumMinions then
		minionCluster = minionClusters[largestCluster]
		
		-- Needs to be in OnDraw to function.
		-- local minionClusterPoint = {x=minionCluster.x, y=myHero.y, z=minionCluster.z}
		-- DrawArrowsToPos(myHero, minionClusterPoint)

		if minionCluster then
			CastSpell(_E, minionCluster.x, minionCluster.z)
		end
	end

	minions = nil
	minionClusters = nil
end

function CastEQAuto()
	TrueRange = GetTrueRange()
	if QReady and (Menu.BlightStacks.PrioritizeQ or not EReady) then
		local mostStacksEnemy = FindEnemyWithMostStacks(QRange)

		if isPressedQ and ValidTarget(mostStacksEnemy) and (AutoCarry.MainMenu.AutoCarry or AutoCarry.MainMenu.MixedMode) then
			Cast2ndQTarget(mostStacksEnemy)
		end

		if mostStacksEnemy and not mostStacksEnemy.dead
		and (((AutoCarry.MainMenu.MixedMode or AutoCarry.MainMenu.LaneClear) and ProcStacks[mostStacksEnemy.networkID] >= Menu.BlightStacks.MixedMinWForQE)
			or (AutoCarry.MainMenu.AutoCarry and ProcStacks[mostStacksEnemy.networkID] >= Menu.BlightStacks.CarryMinWForQ))
		and ValidTarget(mostStacksEnemy, QRange) and GetTickCount() - QTick > Menu.Misc.WWaitDelay then
			if Menu.AutoCarry.ComboQ then CastQ(mostStacksEnemy, false) end
		end
	elseif EReady and ((not Menu.BlightStacks.PrioritizeQ) or (Menu.BlightStacks.PrioritizeQ and not QReady)) then
		local mostStacksEnemy = FindEnemyWithMostStacks(ERange)

		if mostStacksEnemy and not mostStacksEnemy.dead and (ProcStacks[mostStacksEnemy.networkID] == 3
			or ((((AutoCarry.MainMenu.MixedMode or AutoCarry.MainMenu.LaneClear) and ProcStacks[mostStacksEnemy.networkID] >= Menu.BlightStacks.MixedMinWForQE)
				or (AutoCarry.MainMenu.AutoCarry and ProcStacks[mostStacksEnemy.networkID] >= Menu.BlightStacks.CarryMinWForE))
			and GetDistance(mostStacksEnemy) > TrueRange))
			and ValidTarget(mostStacksEnemy, ERange) then -- and GetTickCount() > QTick + (GetQDelay(mostStacksEnemy)) then
			CastE(mostStacksEnemy)
		end
	end
end

function CastE(enemy)
	if not myHero.dead and not enemy and Target then enemy = Target end

	if EReady and enemy and not enemy.dead and ValidTarget(enemy, ERange) then
		if EnemyCount(enemy, EWidth) > 1 then
			local spellPos = GetAoESpellPosition((EWidth / 2), enemy, EDelay, ESpeed)

			if spellPos and GetDistance(spellPos) <= ERange then
				CastSpell(_E, spellPos.x, spellPos.z)
				return true
			end
		else
			if IsSACReborn then
				SkillE:Cast(enemy)
			else
				AutoCarry.CastSkillshot(SkillE, enemy)
			end

			return true
		end
	end

	return false
end

--[[
function GetQDelay(enemy)
	local distanceOverMin = GetDistance(enemy) - QMinRange
	local delay = 125

	if distanceOverMin > 0 then
		delay = (GetDistance(enemy) - QMinRange) * (2000 / (QRange - QMinRange))
	end

	-- Add in a bit of buffer to hit enemies running away.
	delay = delay + 50
	if enemy.charName then -- Only predict for an enemy, not a position.
		QPred = qp:GetPrediction(enemy)
		if QPred then
			local predDistance = GetDistance(QPred)
			local diffDistance = predDistance - GetDistance(enemy)
			if diffDistance > 0 then
				delay = delay + diffDistance
			end
		end
	end

	if delay > 2000 then delay = 2000 end

	return delay
end

function CastManualQ(mouse) -- cast Q to lowhp enemy or mousepos
	if mouse then
		CastQ(nil, mouse)
		return true
	else
		local lowHealthEnemy = FindLowestHealthEnemy(QRange)

		-- Is there a killsteal?
		if lowHealthEnemy and ValidTarget(lowHealthEnemy, QRange) and getDmg("Q", lowHealthEnemy, myHero) >= lowHealthEnemy.health then
			CastQ(lowHealthEnemy, false)
			return true
		else
			local mostStacksEnemy = FindEnemyWithMostStacks(QRange)
			-- How about proc stacks minimum requirement met?
			if mostStacksEnemy and ProcStacks[mostStacksEnemy.networkID] >= Menu.BlightStacks.CarryMinWForQ and ValidTarget(mostStacksEnemy, QRange) then
				CastQ(mostStacksEnemy, false)
				return true
			else
				-- Everything else failed, so just hit the enemy with the lowest health.
				CastQ(lowHealthEnemy, false)
				return true
			end
		end
	end

	return false
end
--]]

function QMovePos(target)
	AutoCarry.CanMove = false
	local moveDistance = 100
	local targetDistance = GetDistance(target)
	movetoPos = { x = myHero.x + ((target.x - myHero.x) * (moveDistance) / targetDistance), z = myHero.z + ((target.z - myHero.z) * (moveDistance) / targetDistance)}
	myHero:MoveTo(movetoPos.x, movetoPos.z)
end

function _G.PluginOnDeleteObj(object)
	if object.valid and object.name == "VarusQChannel2.troy" then
		isPressedQ  = false
		Qcasttime = 0
		QTick = GetTickCount()
		AutoCarry.CanAttack = true
		AutoCarry.CanMove = true
	end

end

function CastQ(Target, mouse)
	if mouse == false then
		Cast1stQTarget(Target)
		Cast2ndQTarget(Target)
	elseif mouse == true then
		local closestchamp = nil
		--print('Cast Q harass called')
        for _, champ in pairs(AutoCarry.EnemyTable) do
                if closestchamp and closestchamp.valid and champ and champ.valid then
                        if GetDistance(champ, mousePos) < GetDistance(closestchamp, mousePos) then
                            closestchamp = champ
                        end
                else
                    closestchamp = champ
                end
        end
        if ValidTarget(closestchamp) and GetDistance(closestchamp, myHero) < 1600 and QReady then
        	--print(closestchamp.name)
        	Cast1stQTargetManual(closestchamp)
			Cast2ndQTargetManual(closestchamp)
		end
	end	
end

--[[
function CastQ(Unit, mouse)
	if myHero.dead then return false end

	-- Lost target due to range or death. Try to get another.
	if (not Unit or Unit.dead) and not mouse then
		Unit = GetTarget()
	end

	-- We couldn't get a suitable target.
	if (not Unit or Unit.dead) and not mouse then return false end

	if Unit then
		QPred = qp:GetPrediction(Unit)
	end

	if (QPred or mouse) and not Cast and ((mouse == 1 and GetTickCount() - QTick > Menu.Misc.WWaitDelay) or (QPred and GetTickCount() - QTick >= GetQDelay(QPred))) then
		if mouse then
			CastSpell(_Q, mousePos.x, mousePos.z)
		else
			CastSpell(_Q, QPred.x, QPred.z)
		end
		QTick = GetTickCount()
		Cast = true
	end

	if (QPred or mouse) and Cast and ((mouse == 2 and GetTickCount() - QTick > Menu.Misc.WWaitDelay) or (QPred and GetTickCount() - QTick >= GetQDelay(QPred))) then
		PQ2 = CLoLPacket(0xE6)
		PQ2:EncodeF(myHero.networkID)
		PQ2:Encode1(128)

		local movePos = nil

		if mouse then
			PQ2:EncodeF(mousePos.x)
			PQ2:EncodeF(myHero.y)
			PQ2:EncodeF(mousePos.z)

			movePos = QMovePos(mousePos)
		else
			PQ2:EncodeF(QPred.x)
			PQ2:EncodeF(QPred.y)
			PQ2:EncodeF(QPred.z)

			movePos = QMovePos(QPred)
		end

		PQ2.dwArg1 = 1
		PQ2.dwArg2 = 0

		-- AutoCarry.CanMove = false
		if movePos then myHero:MoveTo(movePos.x, movePos.z) end
		SendPacket(PQ2)
		-- AutoCarry.CanMove = true

		QTick = GetTickCount()
		Cast = false
		return true
	end

	return false
end
--]]
--[[
function Old2CastQ(Unit, mouse)
	if not Unit and not mouse then return end

	if Unit then
		QPred = qp:GetPrediction(Unit)
	end

	if (QPred or mouse) and not Cast and ((mouse == 1 and GetTickCount() - QTick > Menu.Misc.WWaitDelay) or (QPred and GetTickCount() - QTick >= GetQDelay(QPred))) then
		if mouse then
			CastSpell(_Q, mousePos.x, mousePos.z)
		else
			CastSpell(_Q, QPred.x, QPred.z)
		end
		QTick = GetTickCount()
		Cast = true
	end

	if (QPred or mouse) and Cast and ((mouse == 2 and GetTickCount() - QTick > Menu.Misc.WWaitDelay) or (QPred and GetTickCount() - QTick >= GetQDelay(QPred))) then
		PQ2 = CLoLPacket(0xE6)
		PQ2:EncodeF(myHero.networkID)
		PQ2:Encode1(128)
		
		if mouse then
			PQ2:EncodeF(mousePos.x)
			PQ2:EncodeF(myHero.y)
			PQ2:EncodeF(mousePos.z)
		else
			PQ2:EncodeF(QPred.x)
			PQ2:EncodeF(QPred.y)
			PQ2:EncodeF(QPred.z)
		end

		PQ2.dwArg1 = 1
		PQ2.dwArg2 = 0
		SendPacket(PQ2)
		QTick = GetTickCount()
		Cast = false
		return true
	end

	return false
end
--]]

--[[
function OldCastQ(Unit)
	if not Unit then return end

	QPred = qp:GetPrediction(Unit)

	if QPred and not Cast and GetTickCount() - QTick >= GetQDelay(QPred) then
		CastSpell(_Q, QPred.x, QPred.z)
		QTick = GetTickCount()
		Cast = true
	end

	if QPred and Cast and GetTickCount() - QTick >= GetQDelay(QPred) then
		PQ2 = CLoLPacket(0xE6)
		PQ2:EncodeF(myHero.networkID)
		PQ2:Encode1(128)
		PQ2:EncodeF(QPred.x)
		PQ2:EncodeF(QPred.y)
		PQ2:EncodeF(QPred.z)
		PQ2.dwArg1 = 1
		PQ2.dwArg2 = 0
		SendPacket(PQ2)
		QTick = GetTickCount()
		Cast = false	
	end
end
--]]

function CastR(enemy)
	if not enemy and Target then enemy = Target end

	if RReady and enemy and not enemy.dead and ValidTarget(enemy, RRange) then -- and not AutoCarry.GetCollision(SkillR, myHero, enemy) then
		if IsSACReborn then
			SkillR:Cast(enemy)
		else
			AutoCarry.CastSkillshot(SkillR, enemy)
		end

		return true
	end

	return false
end

function JungleClear()
	local MonsterTarget = nil
	local TrueRange = GetTrueRange()

	if Target then return false end

	for i=1, #JungleFocusMobs do
		local monster = JungleBuffs[i]
		if monster and monster.valid and monster.visible and not monster.dead and GetDistance(monster) <= QRange then
			MonsterTarget = monster
			break
		end
	end

	if not MonsterTarget then
		for i=1, #JungleMobs do
			local monster = JungleBuffs[i]
			if monster and monster.valid and monster.visible and not monster.dead and GetDistance(monster) <= QRange then
				MonsterTarget = monster
				break
			end
		end
	end

	if not Target and MonsterTarget then
		if myHero:GetDistance(MonsterTarget) <= TrueRange then CustomAttackEnemy(MonsterTarget) end

		if not MonsterTarget.dead and EReady and MonsterTarget.health >= getDmg("E", MonsterTarget, myHero) and myHero:GetDistance(MonsterTarget) <= ERange then
			if IsSACReborn then
				SkillE:Cast(MonsterTarget)
			else
				AutoCarry.CastSkillshot(SkillE, MonsterTarget)
			end

			return true
		end

		if not MonsterTarget.dead and QReady and GetDistance(MonsterTarget) < QRange and MonsterTarget.health >= getDmg("Q", MonsterTarget, myHero) then
			CastQ(MonsterTarget, false)
			return true
		end
	end
end

function JungleSteal()
	for i=1, #JungleBuffs do
		local monster = JungleBuffs[i]

		DmgOnObject = 0
		if QReady then DmgOnObject = DmgOnObject + getDmg("Q", monster, myHero) end
		if RReady then DmgOnObject = DmgOnObject + getDmg("R", monster, myHero) end

		if monster and monster.valid and monster.visible and not monster.dead and GetDistance(monster) <= QRange then
			if ValidTarget(monster, TrueRange) and getDmg("AD", monster, myHero) >= monster.health then
				CustomAttackEnemy(monster)
				return true
			elseif ValidTarget(monster, ERange) and EReady and getDmg("E", monster, myHero) >= monster.health then
				if IsSACReborn then
					SkillE:Cast(monster)
				else
					AutoCarry.CastSkillshot(SkillE, monster)
				end
				return true
			elseif QReady and GetDistance(monster) < QRange and monster.health  + 25 < getDmg("Q", monster, myHero) then
				CastQ(monster, false)
				return true
			elseif RReady and GetDistance(monster) < RRange and monster.health + 50 < getDmg("R", monster, myHero) then
				CastR(monster)
				return true
			end
		end
	end

	return false
end

function Killsteal()
	local TrueRange = GetTrueRange()
	for _, enemy in pairs(AutoCarry.EnemyTable) do
		if ValidTarget(enemy, 500) and BWCReady and getDmg("BWC", enemy, myHero) >= enemy.health then
			CastSpell(BWCSlot, enemy)
			return true
		elseif ValidTarget(enemy, 500) and RUINEDKINGReady and getDmg("RUINEDKING", enemy, myHero) >= enemy.health then
			CastSpell(RUINEDKINGSlot, enemy)
			return true
		elseif ValidTarget(enemy, TrueRange) and getDmg("AD", enemy, myHero) >= enemy.health then
			CustomAttackEnemy(enemy)
			return true
		elseif Menu.Killsteal.KillstealE and ValidTarget(enemy, ERange) and getDmg("E", enemy, myHero) >= enemy.health then
			CastE(enemy)
			return true
		elseif Menu.Killsteal.KillstealQ and ValidTarget(enemy, QRange) and getDmg("Q", enemy, myHero) >= enemy.health then
			CastQ(enemy, false)
			return true
 		elseif Menu.Killsteal.KillstealR and RReady and ValidTarget(enemy, RRange) and getDmg("R", enemy, myHero) >= enemy.health then
			CastR(enemy)
			return true
		end
	end

	return false
end

function SlowClosestEnemy()
	local closestEnemy = FindClosestEnemy()
	if not closestEnemy then return false end

	if RANDUINSReady and GetDistance(closestEnemy) <= 200 then CastSpell(RANDUINSSlot) end

	if EReady and ValidTarget(closestEnemy, ERange) then
		if CastE(closestEnemy) then return true end
	end

	if RReady and Menu.AutoCarry.SlowR and EnemyCount(closestEnemy, RRange) >= 3 and ValidTarget(closestEnemy, RRange) then
		CastR(closestEnemy)
		return true
	end

	return false
end

function FindEnemyWithMostStacks(range)
	local mostStacksEnemy = nil

	for _, enemy in pairs(AutoCarry.EnemyTable) do
		if enemy and enemy.valid and not enemy.dead then
			if (not mostStacksEnemy and ProcStacks[enemy.networkID] > 0) or (mostStacksEnemy and ProcStacks[enemy.networkID] > ProcStacks[mostStacksEnemy.networkID] and GetDistance(enemy) <= range) then
				mostStacksEnemy = enemy
			end
		end
	end

	return mostStacksEnemy
end

function FindClosestEnemy()
	local closestEnemy = nil

	for _, enemy in pairs(AutoCarry.EnemyTable) do
		if enemy and enemy.valid and not enemy.dead then
			if not closestEnemy or GetDistance(enemy) < GetDistance(closestEnemy) then
				closestEnemy = enemy
			end
		end
	end

	return closestEnemy
end

function FindLowestHealthEnemy(range)
	local lowHealthEnemy = nil

	for _, enemy in pairs(AutoCarry.EnemyTable) do
		if enemy and enemy.valid and not enemy.dead then
			if not lowHealthEnemy or (GetDistance(enemy) <= range and enemy.health < lowHealthEnemy.health) then
				lowHealthEnemy = enemy
			end
		end
	end

	return closestEnemy
end

function EnemyCount(point, range)
	local count = 0

	for _, enemy in pairs(GetEnemyHeroes()) do
		if enemy and not enemy.dead and GetDistance(point, enemy) <= range then
			count = count + 1
		end
	end            

	return count
end

function IsMyManaLow()
	if myHero.mana < (myHero.maxMana * ( Menu.Misc.ManaManager / 100)) then
		return true
	else
		return false
	end
end

function onChoiceFunction() -- our callback function for the ability leveling
	if myHero:GetSpellData(_E).level < myHero:GetSpellData(_Q).level then
		return 3
	else
		return 1
	end
end

function GetTrueRange()
	return myHero.range + GetDistance(myHero.minBBox)
end

function IsTickReady(tickFrequency)
	-- Improves FPS
	if tick ~= nil and math.fmod(tick, tickFrequency) == 0 then
		return true
	else
		return false
	end
end

function CustomAttackEnemy(enemy)
	myHero:Attack(enemy)
	AutoCarry.shotFired = true
end

function SpellCheck()
	RUINEDKINGSlot, QUICKSILVERSlot, RANDUINSSlot, BWCSlot = GetInventorySlotItem(3153), GetInventorySlotItem(3140), GetInventorySlotItem(3143), GetInventorySlotItem(3144)
	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)
	RUINEDKINGReady = (RUINEDKINGSlot ~= nil and myHero:CanUseSpell(RUINEDKINGSlot) == READY)
	QUICKSILVERReady = (QUICKSILVERSlot ~= nil and myHero:CanUseSpell(QUICKSILVERSlot) == READY)
	RANDUINSReady = (RANDUINSSlot ~= nil and myHero:CanUseSpell(RANDUINSSlot) == READY)
	IGNITEReady = (IGNITESlot ~= nil and myHero:CanUseSpell(IGNITESlot) == READY)
	BARRIERReady = (BARRIERSlot ~= nil and myHero:CanUseSpell(BARRIERSlot) == READY)
	CLEANSEReady = (CLEANSESlot ~= nil and myHero:CanUseSpell(CLEANSESlot) == READY)
end

function DMGCalculation()
	for i=1, heroManager.iCount do
        local Unit = heroManager:GetHero(i)
        if ValidTarget(Unit) then
        	local RUINEDKINGDamage, IGNITEDamage, BWCDamage = 0, 0, 0
        	local QDamage = getDmg("Q",Unit,myHero)
			local WDamage = getDmg("W",Unit,myHero)
			local EDamage = getDmg("E",Unit,myHero)
			local RDamage = getDmg("R", Unit, myHero)
			local HITDamage = getDmg("AD",Unit,myHero)
			local IGNITEDamage = (IGNITESlot and getDmg("IGNITE",Unit,myHero) or 0)
			local BWCDamage = (BWCSlot and getDmg("BWC",Unit,myHero) or 0)
			local RUINEDKINGDamage = (RUINEDKINGSlot and getDmg("RUINEDKING",Unit,myHero) or 0)
			local combo1 = HITDamage
			local combo2 = HITDamage
			local combo3 = HITDamage
			local mana = 0

			if QReady then
				combo1 = combo1 + QDamage
				combo2 = combo2 + QDamage
				combo3 = combo3 + QDamage
				mana = mana + myHero:GetSpellData(_Q).mana
			end

			if WReady then
				combo1 = combo1 + WDamage
				combo2 = combo2 + WDamage
				combo3 = combo3 + WDamage
				mana = mana + myHero:GetSpellData(_W).mana
			end

			if EReady then
				combo1 = combo1 + EDamage
				combo2 = combo2 + EDamage
				combo3 = combo3 + EDamage
				mana = mana + myHero:GetSpellData(_E).mana
			end

			if RReady then
				combo2 = combo2 + RDamage
				combo3 = combo3 + RDamage
				mana = mana + myHero:GetSpellData(_R).mana
			end

			if BWCReady then
				combo2 = combo2 + BWCDamage
				combo3 = combo3 + BWCDamage
			end

			if RUINEDKINGReady then
				combo2 = combo2 + RUINEDKINGDamage
				combo3 = combo3 + RUINEDKINGDamage
			end

			if IGNITEReady then
				combo3 = combo3 + IGNITEDamage
			end

			killable[i] = 1 -- the default value = harass

			if combo3 >= Unit.health and myHero.mana >= mana then -- all cooldowns needed
				killable[i] = 2
			end

			if combo2 >= Unit.health and myHero.mana >= mana then -- only spells + ulti and items needed
				killable[i] = 3
			end

			if combo1 >= Unit.health and myHero.mana >= mana then -- only spells but no ulti needed
				killable[i] = 4
			end
		end
	end
end

function _G.PluginOnWndMsg(msg,key)
	Target = GetTarget()

	if Menu.Misc.ProMode then
		-- if msg == KEY_DOWN and key == KeyQ then CastManualQ(false) end
		if msg == KEY_DOWN and key == KeyQ then CastQ(nil, true) end

		--[[
		if key == KeyW then
			if msg == KEY_DOWN then
				CastManualQ(1)
			elseif msg == KEY_UP then
				CastManualQ(2)
			end
		end
		--]]
		if msg == KEY_DOWN and key == KeyE then SlowClosestEnemy() end
		if msg == KEY_DOWN and key == KeyR then CastR(Target) end
	end
end

webTrialVersion = nil
scriptTrialVersion = "g105"
GetAsyncWebResult("bolscripts.com","scriptauth/script_date_check.php?s=varus&v="..version, function(result) webTrialVersion = result end)

-- End of Varus script

--[[ 
    AoE_Skillshot_Position 2.0 by monogato
    Modified by Dienofail and Kain for VPrediction support.
    
    GetAoESpellPosition(radius, main_target, delay, speed) returns best position in order to catch as many enemies as possible with your AoE skillshot, making sure you get the main target.
    Note: You can optionally add delay in ms for prediction (VIP if avaliable, normal else).
]]

useProdiction = false
useVPrediction = false
aoeProd = nil

if FileExist(SCRIPT_PATH..'Common/Prodiction.lua') then
	require 'Prodiction'
	aoeProd = ProdE
	useProdiction = true
elseif FileExist(SCRIPT_PATH..'Common/VPrediction.lua') then
	require 'VPrediction'
	aoeProd = VPrediction()
	useVPrediction = true
end

function GetCenter(points)
    local sum_x = 0
    local sum_z = 0
   
    for i = 1, #points do
            sum_x = sum_x + points[i].x
            sum_z = sum_z + points[i].z
    end
   
    local center = {x = sum_x / #points, y = 0, z = sum_z / #points}
   
    return center
end

function ContainsThemAll(circle, points)
    local radius_sqr = circle.radius*circle.radius
    local contains_them_all = true
    local i = 1
   
    while contains_them_all and i <= #points do
            contains_them_all = GetDistanceSqr(points[i], circle.center) <= radius_sqr
            i = i + 1
    end
   
    return contains_them_all
end

-- The first element (which is gonna be main_target) is untouchable.
function FarthestFromPositionIndex(points, position)
    local index = 2
    local actual_dist_sqr
    local max_dist_sqr = GetDistanceSqr(points[index], position)
   
    for i = 3, #points do
        actual_dist_sqr = GetDistanceSqr(points[i], position)
        if actual_dist_sqr > max_dist_sqr then
            index = i
            max_dist_sqr = actual_dist_sqr
        end
    end
   
    return index
end

function RemoveWorst(targets, position)
    local worst_target = FarthestFromPositionIndex(targets, position)
   
    table.remove(targets, worst_target)
   
    return targets
end

function GetInitialTargets(radius, main_target)
    local targets = {main_target}
    local diameter_sqr = 4 * radius * radius
   
    for i=1, heroManager.iCount do
            target = heroManager:GetHero(i)
            if target.networkID ~= main_target.networkID and ValidTarget(target) and GetDistanceSqr(main_target, target) < diameter_sqr then table.insert(targets, target) end
    end
   
    return targets
end

function GetPredictedInitialTargets(radius, main_target, delay, speed)
    local predicted_main_target = nil
    local time = nil
    local hitchance = nil

    if useProdiction and aoeProd then
    	predicted_main_target, time, hitchance = aoeProd:GetPrediction(main_target)
    elseif useVPrediction and aoeProd then
        predicted_main_target = aoeProd:GetPredictedPos(main_target, delay, speed, myHero)
    elseif not useProdiction and not useVPrediction then
        if VIP_USER and not vip_target_predictor then vip_target_predictor = TargetPredictionVIP(nil, nil, delay/1000) end
        predicted_main_target = VIP_USER and vip_target_predictor:GetPrediction(main_target) or GetPredictionPos(main_target, delay)
    end

    local predicted_targets = {predicted_main_target}
    local diameter_sqr = 4 * radius * radius
   
    for i=1, heroManager.iCount do
            target = heroManager:GetHero(i)
            if ValidTarget(target) then
                    if useProdiction and aoeProd then
                    	predicted_target, time, hitchance = aoeProd:GetPrediction(target)
                    elseif useVPrediction and aoeProd then
                        predicted_target = aoeProd:GetPredictedPos(target, delay, speed, myHero)
                    elseif not useProdiction and not useVPrediction then
                        predicted_target = VIP_USER and vip_target_predictor:GetPrediction(target) or GetPredictionPos(target, delay)
                    end

                    if predicted_target and target.networkID ~= main_target.networkID and GetDistanceSqr(predicted_main_target, predicted_target) < diameter_sqr then table.insert(predicted_targets, predicted_target) end
            end
    end
   
    return predicted_targets
end

-- I don´t need range since main_target is gonna be close enough. You can add it if you do.
function GetAoESpellPosition(radius, main_target, delay, speed)
    local targets = delay and GetPredictedInitialTargets(radius, main_target, delay, speed) or GetInitialTargets(radius, main_target)
    local position = GetCenter(targets)
    local best_pos_found = true
    local circle = Circle(position, radius)
    circle.center = position
   
    if #targets > 2 then best_pos_found = ContainsThemAll(circle, targets) end
   
    while not best_pos_found do
            targets = RemoveWorst(targets, position)
            position = GetCenter(targets)
            circle.center = position
            best_pos_found = ContainsThemAll(circle, targets)
    end
   
    return position
end

function GetAoEBounces(radius, main_target, delay, speed)
	local targets = GetPredictedInitialTargets(radius, main_target, delay, speed)
	return #targets
end
--- End AOE skill shot position


--[[

DrawDamageLib
by Draesia

-- NOTE --

If you use this libary or any of my code in your script, please give me credits, thankyou.

-- Functions --

drawDamage() -- Call this every drawTick
setOrder(key, value) -- Use this to change the order in which the values will draw, right to left, higher value will favour right.
addDrawDamage(key, [isMagic]) -- Use this to add keys to draw, by default, all keys are added, isMagic is optional
removeDrawDamage(key) --Use this to remove keys to draw, by default, all keys are added.

-- Keys --

P
Q
W
E
R
AD
IGNITE
DFG
HXG
BWC
SHEEN
TRINITY
LICHBANE

--]]

local flipped = true
local ignite = nil     
local width, offsetX, offsetY, characterSpacing = 104, 74, 31, 6
local magicTable = {}
local damageTable = {["DFG"] = 0, ["P"] = 0, ["Q"] = 0, ["W"] = 0, ["E"] = 0, ["R"] = 0, ["AD"] = 0, ["IGNITE"] = 0, ["HXG"] = 0, ["BWC"] = 0, ["SHEEN"] = 0, ["TRINITY"] = 0, ["LICHBANE"] = 0} 
local order = {["DFG"] = 0, ["P"] = 0, ["Q"] = 0, ["W"] = 0, ["E"] = 0, ["R"] = 0, ["AD"] = 0, ["IGNITE"] = 0, ["HXG"] = 0, ["BWC"] = 0, ["SHEEN"] = 0, ["TRINITY"] = 0, ["LICHBANE"] = 0}

UpdateWindow()

local function getDamage(damageSource, enemy)
  if not enemy then return 0 end
  if damageSource == "P" then return getDmg("P", enemy, myHero)
  elseif damageSource == "Q" and myHero:CanUseSpell(_Q) == READY then return getDmg("Q", enemy, myHero)
  elseif damageSource == "W" and myHero:CanUseSpell(_W) == READY then return getDmg("W", enemy, myHero)
  elseif damageSource == "E" and myHero:CanUseSpell(_E) == READY then return getDmg("E", enemy, myHero)
  elseif damageSource == "R" and myHero:CanUseSpell(_R) == READY then return getDmg("R", enemy, myHero)
  elseif damageSource == "AD" and enemy.canAttack then return getDmg("AD", enemy, myHero)
  elseif damageSource == "SHEEN" and enemy.canAttack and GetInventorySlotItem(3057) then return getDmg("SHEEN", enemy, myHero) 
  elseif damageSource == "TRINITY" and enemy.canAttack and GetInventorySlotItem(3078) then return getDmg("TRINITY", enemy, myHero) 
  elseif damageSource == "LICHBANE" and enemy.canAttack and GetInventorySlotItem(3100) then return getDmg("LICHBANE", enemy, myHero) 
  elseif damageSource == "IGNITE" and ignite and myHero:CanUseSpell(ignite) == READY then return getDmg("IGNITE", enemy, myHero)
  elseif damageSource == "DFG" and GetInventorySlotItem(3128) and myHero:CanUseSpell(GetInventorySlotItem(3128)) == READY then return getDmg("DFG", enemy, myHero) 
  elseif damageSource == "HXG" and GetInventorySlotItem(3146) and myHero:CanUseSpell(GetInventorySlotItem(3146)) == READY then return getDmg("HXG", enemy, myHero)  
  elseif damageSource == "BWC" and GetInventorySlotItem(3144) and myHero:CanUseSpell(GetInventorySlotItem(3144)) == READY then return getDmg("BWC", enemy, myHero)  
  end
  return 0
end

local function spairs(t, order)
  local keys = {}
  for k in pairs(t) do keys[#keys+1] = k end
  if order then
    table.sort(keys, function(a,b) return order(t, a, b) end)
  else
    table.sort(keys)
  end
  local i = 0
  return function()
    i = i + 1
    if keys[i] then
      return keys[i], t[keys[i]]
    end
  end
end

local function doDraw()
  for i, enemy in pairs(GetEnemyHeroes()) do
    if(enemy ~= myHero) then offsetX = 49 else offsetX = 73 end
    if enemy.visible and not enemy.dead then
      local dfg = (damageTable["DFG"] ~= -1 and GetInventorySlotItem(3128) and myHero:CanUseSpell(GetInventorySlotItem(3128)) == READY)
      for damageSource, shouldDisplay in pairs(damageTable) do 
        if shouldDisplay ~= -1 then
          damageTable[damageSource] = 0
          local damage = getDamage(damageSource, enemy)
          if magicTable.damageSource and dfg then damage = damage * 1.2 end
          damageTable[damageSource] = damage
        end
      end
      local dx = ((enemy.maxHealth - enemy.health)/enemy.maxHealth) * width
      local pos = 0
      local counter = 0
      for damageSource, value in spairs(damageTable, (function(t,a,b) if order[a] ~= order[b] then return order[a] > order[b] else return t[b] < t[a] end end)) do 
        if value > 0 then
          local dw = (value/enemy.maxHealth) * width
          local pos1 = GetUnitHPBarPos(enemy)
          local x = pos1.x-dx-pos+offsetX
          local ny = GetUnitHPBarOffset(enemy).y * 50 - 5
          local y = pos1.y+ny+(flipped and 18 or 0)
          if x > 0 and x < WINDOW_W+width and y > 0 and y < WINDOW_H then
            if pos + dw > (enemy.health/enemy.maxHealth) * width then
              dw = (enemy.health/enemy.maxHealth) * width - pos
              if pos == 0 then
                DrawLine(x, y, x, y-10, 1, 0xFF00FF00)
                DrawLine(x-dw, y, x-dw, y-10, 1, 0xFF00FF00)
              else
                DrawLine(x-dw, y, x-dw, y-10, 1, 0xFF00FF00)
              end
              if dw > (characterSpacing*damageSource:len()) then DrawText(damageSource, 8, x-(dw*0.5)-2, y-15+(flipped and 18 or 0), 0xFF00FF00) end
              break
            end
            if dw > (characterSpacing*damageSource:len()) then DrawText(damageSource, 8, x-(dw*0.5)-2, y-15+(flipped and 15 or 0), 0xFF00FF00) end
            if pos == 0 then
              DrawLine(x, y, x, y-10, 1, 0xFF00FF00)
              DrawLine(x-dw, y, x-dw, y-10, 1, 0xFF00FF00)
            else
              DrawLine(x-dw, y, x-dw, y-10, 1, 0xFF00FF00)
            end
            counter = counter + 1
            pos = pos + dw
          end
        end
      end
    end
  end
end

function drawDamage()
  doDraw()
end

function setOrder(key, value)
  if damageTable[key] then
    damageTable[key] = value
  end
end

function addDrawDamage(damageSource, isMagic)
  if damageSource ~= "IGNITE" then
    if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then ignite = SUMMONER_1
    elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then ignite = SUMMONER_2 
    else return
    end
  end
  if isMagic and damageSource ~= "DFG" then magicTable.insert(damageSource) end
  damageTable[damageSource] = 0
end

function removeDrawDamage(damageSource)
  damageTable[damageSource] = -1
  magicTable[damageSource] = nil
end

function setFlipped(flip)
  flipped = flip
end

--UPDATEURL=https://bitbucket.org/KainBoL/bol/raw/master/Common/SidasAutoCarryPlugin%20-%20Varus.lua
--HASH=CE49C4EA474FB615AB46755B8CF4F097ssssssss