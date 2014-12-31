<?php exit() ?>--by Kain 97.90.203.108
--[[
 
        Auto Carry Plugin - Ziggs Edition - The Hexplosives Expert
		Author: Kain
		Version: See version variable below.
		Copyright 2014

		Dependency: Sida's Auto Carry: Revamped
 
		How to install:
			Make sure you already have AutoCarry installed.
			Name the script EXACTLY "SidasAutoCarryPlugin - Ziggs.lua" without the quotes.
			Place the plugin in BoL/Scripts/Common folder.

		To Do:
			Detect short fuse and prioritize harassing enemies.

		Download: https://bitbucket.org/KainBoL/bol/raw/master/Common/SidasAutoCarryPlugin%20-%20Ziggs.lua

		Version History:
			Version: 2.0:
				Fixed Ultimate Killsteal on Reborn.
				Use Satchel Charge to interrupt enemy spells.
				Add Draw Damage library for showing damage ticks on enemies.
				Fixed a couple of bugs that make spells occasionally not fire.
				Change Satchel Charge and Hexplosive Minefield default firing to shoot just behind the enemy.
				Add toggle expert mode to aim Satchel Charge and Hexplosive Minefield based on mouse position relative to enemy.
				Reworked Menu.
				Jungle and Baron Nashor steal. (Including Twisted Treeline Jungle)
				Added showing target waypoints and line.
				Show mouse position.
				Added Satchel Charge Killsteal.
				Prodiction to v.3 for private release.
			Version: 1.6:
				Updated for 3.13 patch. Increased W radius.
			Version: 1.52:
				Added auto ignite.
			Version: 1.5:
				Added PROdiction 2.0.
			Version: 1.4:
				Added new PROdiction.
			Version: 1.3b: http://pastebin.com/r7be1V6j
				Improved FPS.
				Added dynamic ranges.
				Fixed AFK Killsteal disabling.
				Split menu into two menus.
				Fixed harass firing combo.
				Fixed draw prediction bug.
				Added "BoL Studio Script Updater" url and hash.
			Version: 1.2d: http://pastebin.com/2J8F8XFt
				Added Wall check for Q.
				Added E toggle for Satchel Jump and Harass.
				Added line draw to currently selected target.
				Added auto disable for Killsteal when AFK.
				Cleaned up plugin menu.
			Version: 1.1d: http://pastebin.com/T3rUd14Y
			Version: 1.1c: http://pastebin.com/exXzdFD8
				Improved Satchel Jump to fire more accurately and also in the direction of the mouse position.
				Added low mana handling with the Mana Manager.
				Added Smart Q farming. Uses Q when it can kill multiple minions and mana is higher than the Mana Manager low limit.
				Improved prediction range logic for Q and R.
				Fixed Q to not hit minions as much.
				Added enemy low health logic.
			Version: 1.08: http://pastebin.com/ebB89AnC
			Version: 1.07: http://pastebin.com/Mj7i5k9X
				Added text on screen when Killsteal occurs to make it more noticeable.
				Added slider variable to set Killsteal hitchance/sensitivity.
			Version: 1.06: http://pastebin.com/5j9W654G (7/23/2013)
				Fixed Karthus / Kog'maw, etc. bug where script would try to kill their 'ghost' after they were dead, but still present nearby.
				Added toggle for auto harass.
				Change range color indicators.
			Version: 1.05: http://pastebin.com/CcgW9n27
				Added Satchel Jump.
				Improved "Ultimate Mega Killsteal" calculation. Now operates on a hitchance curve. The further away the target is, the higher the hitchance required to throw the Bomb. Should reduce the number of cross-map misses. Will experiment with the settings after more feedback.
				Removed Satchel from normal combo.
				Added a secondary Full Combo option.
			Version: 1.04: http://pastebin.com/z4nTWmkr
				Fixed Bouncing Bomb for full range prediction. Requires SAC 4.9 or later.
			Version: 1.03 Beta: http://pastebin.com/kZED9bqV
			Version: 1.02 Beta: http://pastebin.com/5CzSRtdL
			Version: 1.01 Beta: http://pastebin.com/hx2RYDaQ
			Version: 1.0 Beta: http://pastebin.com/bGa23bFR
--]]

if myHero.charName ~= "Ziggs" then return end

version = "2.0"

-- Reborn
if AutoCarry.Skills then IsSACReborn = true else IsSACReborn = false end

-- Disable SAC Reborn's skills. Ours are better.
if IsSACReborn then
	AutoCarry.Skills:DisableAll()
end

local Target

-- Prediction
local QRange = 850
local QMaxRange = 1400
local WRange = 1000
local ERange = 900
local RRange = 5300

local QSpeed = 1.722 -- Old: 1.2
local WSpeed = 1.727 -- Old: 1.5
local ESpeed = 2.694 -- Old: 1.45
local RSpeed = 1.5 -- Old: 1.856 -- Old: 1.5

local QDelay = 218
local WDelay = 249
local EDelay = 125
local RDelay = 1014

local QWidth = 150
local WWidth = 325 -- Was 225
local EWidth = 250
local RWidth = 550

if IsSACReborn then
	SkillQ = AutoCarry.Skills:NewSkill(false, _Q, QMaxRange, "Bouncing Bomb", AutoCarry.SPELL_LINEAR_COL, 0, false, false, QSpeed, QDelay, QWidth, true)
	SkillW = AutoCarry.Skills:NewSkill(false, _W, WRange, "Satchel Charge", AutoCarry.SPELL_CIRCLE, 0, false, false, WSpeed, WDelay, WWidth, false)
	SkillE = AutoCarry.Skills:NewSkill(false, _E, ERange, "Hexplosive Minefield", AutoCarry.SPELL_CIRCLE, 0, false, false, ESpeed, EDelay, EWidth, false)
	SkillR = AutoCarry.Skills:NewSkill(false, _R, RRange, "Mega Inferno Bomb", AutoCarry.SPELL_CIRCLE, 0, false, false, RSpeed, RDelay, RWidth, false)
else
	local SkillQ = {spellKey = _Q, range = QMaxRange, speed = QSpeed, delay = QDelay, width = QWidth, configName = "bouncingbomb", displayName = "Q (Bouncing Bomb)", enabled = true, skillShot = true, minions = true, reset = false, reqTarget = true }
	local SkillW = {spellKey = _W, range = WRange, speed = WSpeed, delay = WDelay, width = WWidth, configName = "satchelcharge", displayName = "W (Satchel Charge)", enabled = true, skillShot = true, minions = false, reset = false, reqTarget = false }
	local SkillE = {spellKey = _E, range = ERange, speed = ESpeed, delay = EDelay, width = EWidth, configName = "hexplosiveminefield", displayName = "E (Hexplosive Minefield)", enabled = true, skillShot = true, minions = false, reset = false, reqTarget = false }
	local SkillR = {spellKey = _R, range = RRange, speed = RSpeed, delay = RDelay, width = RWidth, configName = "megainfernobomb", displayName = "R (Mega Inferno Bomb)", enabled = true, skillShot = true, minions = false, reset = false, reqTarget = true }

	tpQ = nil
end

local KeyQ = string.byte("Q")
local KeyW = string.byte("W")
local KeyE = string.byte("E")
local KeyR = string.byte("R")

local tick = nil
local doUlt = false

-- Draw
local waittxt = {}
local calculationenemy = 1
local floattext = {"Skills not available", "Able to fight", "Killable", "Murder him!"}
local killable = {}

-- Items
local ignite = nil
local DFGSlot, HXGSlot, BWCSlot, SheenSlot, TrinitySlot, LichBaneSlot = nil, nil, nil, nil, nil, nil
local QReady, WReady, EReady, RReady, DFGReady, HXGReady, BWCReady, IReady = false, false, false, false, false, false, false, false

local IgniteRange = 600

-- Satchel Jump
local satchelChargeExists = false
local pendingSatchelChargeActivation = nil

-- AFK Vars
local afkTick = nil
local lastAFKStatus = false
local myHeroLastPos = nil

-- Jungle
local JungleKill = nil

local wpm = WayPointManager()

-- Debug
local debugMode = false

if VIP_USER then
	if FileExist(SCRIPT_PATH..'Common/Prodiction.lua') then
		function _G.InitPROdiction()
			wp = ProdictManager.GetInstance()
		end

		function _G.SetupPROdiction(castSpell, range, speed, delay, width, source, callbackFunction)
			if AutoCarry.Skills then -- Reborn
				return AutoCarry.Plugins:GetProdiction(castSpell, range, speed, delay, width, source, callbackFunction)
			else -- Revamped
				return wp:AddProdictionObject(castSpell, range, speed, delay, width, source, callbackFunction)
			end
		end

		if not IsSACReborn then
			require "Prodiction"
			InitPROdiction()
		end

		tpQ = SetupPROdiction(_Q, QMaxRange, QSpeed, QDelay, QWidth, myHero,
			function(unit, pos, castSpell)
				if myHero:CanUseSpell(_Q) == READY and GetDistance(unit) < QMaxRange then
					FireQ(unit, pos, castSpell)
				end
			end)

		tpW = SetupPROdiction(_W, WRange, WSpeed, WDelay, WWidth, myHero,
			function(unit, pos, castSpell)
				if myHero:CanUseSpell(_W) == READY and GetDistance(unit) < WRange then
					FireW(unit, pos, castSpell)
				end
			end)

		tpE = SetupPROdiction(_E, ERange, ESpeed, EDelay, EWidth, myHero,
			function(unit, pos, castSpell)
				if myHero:CanUseSpell(_E) == READY and GetDistance(unit) < ERange then
					FireE(unit, pos, castSpell)
				end
			end)

		PrintChat("<font color='#CCCCCC'> >> Kain's Ziggs - PROdiction <</font>")
	end
else
	PrintChat("<font color='#CCCCCC'> >> Kain's Ziggs - Free Prediction <</font>")
end

local useDrawDamageLib = false
if FileExist(SCRIPT_PATH..'Common/DrawDamageLib.lua') then
	require "DrawDamageLib"
	useDrawDamageLib = true
else
	PrintChat("<font color='#CCCCCC'> >> Please install the DrawDamageLib for the best experience. <</font>")
end

local CurrentSpellInterrupts = {}
local SpellInterruptLists = {
    { charName = "Caitlyn", spellName = "CaitlynAceintheHole"},
    { charName = "FiddleSticks", spellName = "Crowstorm"},
    { charName = "FiddleSticks", spellName = "DrainChannel"},
    { charName = "Galio", spellName = "GalioIdolOfDurand"},
    { charName = "Karthus", spellName = "FallenOne"},
    { charName = "Katarina", spellName = "KatarinaR"},
    { charName = "Lucian", spellName = "LucianR"},
    { charName = "Malzahar", spellName = "AlZaharNetherGrasp"},
    { charName = "MissFortune", spellName = "MissFortuneBulletTime"},
    { charName = "Nunu", spellName = "AbsoluteZero"},
    { charName = "Pantheon", spellName = "Pantheon_GrandSkyfall_Jump"},
    { charName = "Shen", spellName = "ShenStandUnited"},
    { charName = "Urgot", spellName = "UrgotSwap2"},
    { charName = "Varus", spellName = "VarusQ"},
    { charName = "Warwick", spellName = "InfiniteDuress"}
}

local JungleMobs = {}
local JungleFocusMobs = {}
local JungleBuffs = {}

-- Twisted Treeline Jungle
local gameState = GetGame()
local TTMAP = false
if gameState.map.shortName == "twistedTreeline" then
	TTMAP = true
else
	TTMAP = false
end

-- Main

function Menu()
	Menu = AutoCarry.PluginMenu

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Info]", "ScriptInfo")
		Menu.ScriptInfo:addParam("sep", myHero.charName.." Auto Carry: Version "..version, SCRIPT_PARAM_INFO, "")
		Menu.ScriptInfo:addParam("sep1","Created By: Kain", SCRIPT_PARAM_INFO, "")

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Auto Carry]", "AutoCarry")
		Menu.AutoCarry:addParam("Combo", "Combo - Default Spacebar", SCRIPT_PARAM_INFO, "")
		Menu.AutoCarry:addParam("FullCombo", "Full Combo", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
		Menu.AutoCarry:addParam("SatchelJump", "Satchel Jump", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
		Menu.AutoCarry:addParam("Ultimate", "Use Ultimate with Combo", SCRIPT_PARAM_ONOFF, true)
		Menu.AutoCarry:addParam("SmartFarmWithQ", "Smart Farm With Q", SCRIPT_PARAM_ONOFF, true)
		Menu.AutoCarry:addParam("MousePosWE", "Use Mouse Direction for W/E", SCRIPT_PARAM_ONOFF, false)

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Mixed Mode]", "MixedMode")
		Menu.MixedMode:addParam("Harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
		Menu.MixedMode:addParam("AutoHarass", "Auto Harass (Mana Intensive)", SCRIPT_PARAM_ONOFF, false)
		Menu.MixedMode:addParam("HarassWithE", "Harass with E", SCRIPT_PARAM_ONOFF, false)

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Killsteal]", "Killsteal")
		Menu.Killsteal:addParam("KillstealW", "Killsteal with Satchel Jump", SCRIPT_PARAM_ONOFF, true)
		Menu.Killsteal:addParam("KillstealR", "Ultimate Mega Killsteal", SCRIPT_PARAM_ONOFF, true)

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Misc]", "Misc")
		Menu.Misc:addParam("SpellInterrupt", "Interrupt Enemy Spells", SCRIPT_PARAM_ONOFF, true)
		Menu.Misc:addParam("ManaManager", "Mana Manager %", SCRIPT_PARAM_SLICE, 40, 0, 100, 2)
		Menu.Misc:addParam("JungleSteal","Jungle Steal", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Draw]", "Draw")
		Menu.Draw:addParam("DrawKillablEenemy", "Draw Killable Enemy", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawTargetArrow","Draw Target Arrow", SCRIPT_PARAM_ONOFF, false)
		Menu.Draw:addParam("DrawTargetLine","Draw Target Line", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawTargetWaypoints","Draw Target Waypoints", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawText", "Draw Text", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawPrediction", "Draw Prediction", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawMousePos", "Draw Mouse Position", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DisableDraw", "Disable Draw Ranges", SCRIPT_PARAM_ONOFF, false)
		Menu.Draw:addParam("DrawFurthest", "Draw Furthest Spell Available", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawDmg", "Draw Damage Ticks", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawQ", "Draw Bouncing Bomb", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawW", "Draw Satchel Charge", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawE", "Draw Hexplosive Minefield", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawR", "Draw Mega Inferno Bomb", SCRIPT_PARAM_ONOFF, true)

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Advanced]", "Advanced")
		Menu.Advanced:addParam("WEDistanceFromEnemy", "E/W Enemy Cast Distance", SCRIPT_PARAM_SLICE, 125, 10, 600, 0)
		Menu.Advanced:addParam("DoubleIgnite", "Don't Double Ignite", SCRIPT_PARAM_ONOFF, true)
		Menu.Advanced:addParam("AvoidWallsWithQ", "Avoid Hitting Walls with Q (Beta)", SCRIPT_PARAM_ONOFF, false)
		Menu.Advanced:addParam("SatchelJumpWithE", "Satchel Jump with E", SCRIPT_PARAM_ONOFF, true)
		Menu.Advanced:addParam("KillstealMinHitchance", "Killsteal Min. Req. Hitchance", SCRIPT_PARAM_SLICE, 60, 0, 90, 0)
		Menu.Advanced:addParam("AFKKillstealDisable", "AFK Killsteal Disable", SCRIPT_PARAM_ONOFF, true)
		Menu.Advanced:addParam("AFKKillstealDisableSeconds", "AFK Killsteal Disable Seconds", SCRIPT_PARAM_SLICE, 120, 10, 600, 0)
end

function _G.PluginOnLoad()
	Menu()

	AutoCarry.SkillsCrosshair.range = QMaxRange

	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then ignite = SUMMONER_2 end
	for i=1, heroManager.iCount do waittxt[i] = i*3 end

	for _, enemy in ipairs(GetEnemyHeroes()) do
		for _, champ in pairs(SpellInterruptLists) do
			if enemy.charName == champ.charName then
				table.insert(CurrentSpellInterrupts, champ.spellName)
			end
		end
	end

	LoadJungle()
	if useDrawDamageLib then
		SetDamageTicks()
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

function IsSummonerAFK()
	-- Mark as AFK
	if not myHeroLastPos or not tick or not afkTick or myHero.x ~= myHeroLastPos.x or myHero.z ~= myHeroLastPos.z then
		myHeroLastPos = {x=myHero.x, y=myHero.y, z=myHero.z}
		afkTick = tick
		SummonerAFKNotify(false)
		return false
	elseif Menu.Advanced.AFKKillstealDisable and tick > (afkTick + (Menu.Advanced.AFKKillstealDisableSeconds * 1000)) and myHero.x == myHeroLastPos.x and myHero.z == myHeroLastPos.z then
		SummonerAFKNotify(true)
		return true
	end

	SummonerAFKNotify(false)
	return false
end

function SummonerAFKNotify(afk)
	if lastAFKStatus and not afk then
		PrintChat("Welcome back! Killsteal Enabled.")
		lastAFKStatus = false
	elseif afk and not lastAFKStatus then
		PrintChat("You are AFK. Killsteal temporarily Disabled.")
		lastAFKStatus = true
	end
end

function _G.PluginOnTick()
	tick = GetTickCount()

	if (webTrialVersion ~= nil and scriptTrialVersion ~= webTrialVersion) or webTrialVersion == nil then
		if webTrialVersion ~= nil then
			print("<font color='#c22e13'>This version of the script has been disabled, go to forums for update.</font>")
		end

		return
	end

	Target = AutoCarry.GetAttackTarget(true)

	if IsTickReady(500) then IsSummonerAFK() end

	SpellCheck()

	if IsTickReady(200) then
		CalculateDamage()
	end

	if Menu.AutoCarry.SatchelJump then
		SatchelJump()
	end

	if AutoCarry.MainMenu.AutoCarry then
		Combo()
		AutoIgnite()
	end
	
	if Menu.AutoCarry.FullCombo then
		FullCombo()
	end

	if Menu.MixedMode.Harass then
		Harass()
	end

	if Target and Menu.Killsteal.KillstealW and IsTickReady(60) and (Target.health + 30) < getDmg("W", Target, myHero) then
		CastW()
	end

	if Menu.Killsteal.KillstealR and IsTickReady(60) and not lastAFKStatus then
		KillSteal()
	end
	
	if (AutoCarry.MainMenu.LaneClear or AutoCarry.MainMenu.MixedMode) and IsTickReady(40) and Menu.AutoCarry.SmartFarmWithQ and not IsMyManaLow() then
		SmartFarmWithQ()
	end

	if Menu.Misc.JungleSteal then
		StealJungle()
	else
		JungleKill = nil
	end
end

function IsTickReady(tickFrequency)
	-- Improves FPS
	if tick ~= nil and math.fmod(tick, tickFrequency) == 0 then
		return true
	else
		return false
	end
end

function _G.PluginOnCreateObj(obj)
	if obj.name == "ZiggsW_mis_ground.troy" then
		satchelChargeExists = true
		CastWActivate()
	end

	if obj and obj.type == "obj_AI_Minion" then
		if JungleBuffNames[obj.name] then
			table.insert(JungleBuffs, obj)
		end
		if FocusJungleNames[obj.name] then
			table.insert(JungleFocusMobs, obj)
		elseif JungleMobNames[obj.name] then
			table.insert(JungleMobs, obj)
		end
	end
end

function _G.PluginOnDeleteObj(obj)
	if obj.name == "ZiggsW_mis_ground.troy" then
		satchelChargeExists = false
		pendingSatchelChargeActivation = nil
	end

	if obj and obj.type == "obj_AI_Minion" then
		for i, mob in pairs(JungleBuffs) do
			if obj.name == mob.name then
				table.remove(JungleBuffs, i)
			end
		end

		for i, mob in pairs(JungleMobs) do
			if obj.name == mob.name then
				table.remove(JungleMobs, i)
			end
		end

		for i, mob in pairs(JungleFocusMobs) do
			if obj.name == mob.name then
				table.remove(JungleFocusMobs, i)
			end
		end
	end
end

function GetJungleMob(range)
	for _, mob in pairs(JungleBuffs) do
		if ValidTarget(mob, range) then return mob end
	end

	for _, mob in pairs(JungleFocusMobs) do
		if ValidTarget(mob, range) then return mob end
	end

	for _, mob in pairs(JungleMobs) do
		if ValidTarget(mob, range) then return mob end
	end
end

function _G.OnAttacked()
	-- Auto AA > Q > AA
	if Menu.MixedMode.AutoHarass and not IsMyManaLow() then
		CastQ()
	end
end

function SpellCheck()
	DFGSlot, HXGSlot, BWCSlot, SheenSlot, TrinitySlot, LichBaneSlot = GetInventorySlotItem(3128),
	GetInventorySlotItem(3146), GetInventorySlotItem(3144), GetInventorySlotItem(3057),
	GetInventorySlotItem(3078), GetInventorySlotItem(3100)

	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)

	DFGReady = (DFGSlot ~= nil and myHero:CanUseSpell(DFGSlot) == READY)
	HXGReady = (HXGSlot ~= nil and myHero:CanUseSpell(HXGSlot) == READY)
	BWCReady = (BWCSlot ~= nil and myHero:CanUseSpell(BWCSlot) == READY)
	IReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
end

-- Handle SBTW Skill Shots

function Combo()
	CastSlots()

	if not IsMyManaLow() or IsTargetHealthLow() then
		CastE()
	end

	CastQ()
	Ultimate()
end

function Ultimate()
	if Target ~= nil and Menu.AutoCarry.Ultimate and (doUlt or ((Target.health + 30) < getDmg("R", Target, myHero) and IsValidHitChanceCustomR(Target))) then
		CastR()
	end
end

function FullCombo()
	CastSlots()
	CastE()
	CastQ()
	CastW()
	CastWActivate()
	Ultimate()
end

function CastSlots()
	if Target ~= nil and not Target.dead then
		if GetDistance(Target) <= QMaxRange then
			if DFGReady then CastSpell(DFGSlot, Target) end
			if HXGReady then CastSpell(HXGSlot, Target) end
			if BWCReady then CastSpell(BWCSlot, Target) end
		end
	end
end

function Harass()
	CastQ()
	if Menu.MixedMode.HarassWithE then CastE() end
end

function CastQ(enemy)
	if not enemy then enemy = Target end

	if enemy ~= nil and not enemy.dead then
		if QReady and ValidTarget(enemy, QRange) and IsQWallCheckOK(enemy) then
			if IsSACReborn then
				SkillQ:Cast(enemy)
			else
				AutoCarry.CastSkillshot(SkillQ, enemy)
			end
		elseif QReady and ValidTarget(enemy, QMaxRange) then
			-- Full Bouncing Bomb three bounce range
			if IsSACReborn then
				predic = tpQ:EnableTarget(enemy, true)
			else
				predic = AutoCarry.GetPrediction(SkillQ, enemy)
				FireQ(enemy, predic, myHero:GetSpellData(_Q))
			end
		end
	end
end

function FireQ(unit, predic, spell)
	if QReady and ValidTarget(unit, QMaxRange) and predic and GetDistance(predic) < QMaxRange and not unit.dead then
		local isEnemyRetreating = IsEnemyRetreating(unit, predic)
		if not isEnemyRetreating or (isEnemyRetreating and not IsNearRangeLimit(predic, QMaxRange)) then
			return CastSkillshotBounce(SkillQ, predic)
		end
	end

	return false
end

function IsQWallCheckOK(position)
	if position and not Menu.Advanced.AvoidWallsWithQ or (Menu.Advanced.AvoidWallsWithQ and not IsWall(D3DXVECTOR3(position.x, myHero.y, position.z))) then
		return true
	else
		return false
	end
end

function CastSkillshotBounce(skill, PredictedPos)
	if PredictedPos and AutoCarry.IsValidHitChance(SkillQ, Target) then
		local MyPos = Vector(myHero.x, myHero.y, myHero.z)
		local EnemyPos = Vector(PredictedPos.x, PredictedPos.y, PredictedPos.z)
		local CastPos = MyPos - ((MyPos - EnemyPos):normalized() * QRange)

		if GetDistance(EnemyPos) < QRange then
			if not skill.minions or not AutoCarry.GetCollision(skill, myHero, CastPos) then
				CastSpell(_Q, EnemyPos.x, EnemyPos.z)
				return true
			end
		elseif CastPos and GetDistance(PredictedPos) < QMaxRange and IsQWallCheckOK(CastPos) and IsQWallCheckOK(EnemyPos) then
			if CastPos and GetDistance(CastPos) <= QRange then
				if not skill.minions or not AutoCarry.GetCollision(skill, myHero, CastPos) then
					CastSpell(_Q, CastPos.x, CastPos.z)
					return true
				end
			end
		end
	end

	return false
end

function CastW(noTarget)
	if noTarget and WReady then
		-- Find vector from mousePos -> myHero
		local vectorX, y, vectorZ = (Vector(myHero) - Vector(mousePos)):normalized():unpack()

		-- Cast Satchel behind myHero by specified distance, where behind is determined relative to mousePos -> myHero vector.
		-- if hasBuff("Speed Shrine") then satchelDistance should be less, like 50.
		local satchelDistance = 125
		local posX = myHero.x + (vectorX * satchelDistance)
		local posZ = myHero.z + (vectorZ * satchelDistance)

		CastSpell(_W, posX, posZ)
	elseif Target ~= nil and ValidTarget(Target, WRange) and not Target.dead then
		if WReady and GetDistance(Target) <= WRange then
			predic = tpW:EnableTarget(Target, true)
		end
	end
end

function FireW(unit, predic, spell)
	if WReady and ValidTarget(unit, WRange) and predic and GetDistance(predic) < WRange and not unit.dead then
		if Menu.Killsteal.KillstealW and (unit.health + 30) < getDmg("W", unit, myHero) then
			-- Killsteal W
			CastSpell(_W, predic.x, predic.y)
		else
			local CastPos = CastEWLocation(unit, predic)

			if CastPos and GetDistance(CastPos) <= WRange then CastSpell(_W, CastPos.x, CastPos.y) end
		end
	end
end

function CastWActivate()
	if satchelChargeExists and pendingSatchelChargeActivation ~= nil then
		if pendingSatchelChargeActivation == "satcheljump" then
			-- Old delay method
			-- local delayTime = 100
			-- local endClockTime = GetTickCount() + delayTime
			-- while (GetTickCount() < endClockTime) do
			--	-- Sleep
			-- end

			CastSpell(_W)
			if Menu.Advanced.SatchelJumpWithE then CastE() end
			pendingSatchelChargeActivation = nil
		end
	end
end

function CastE()
	if Target ~= nil and ValidTarget(Target, ERange) and not Target.dead then
		if EReady and GetDistance(Target) <= ERange then
			predic = tpE:EnableTarget(Target, true)
		end
	end
end

function FireE(unit, predic, spell)
	if EReady and ValidTarget(unit, ERange) and predic and GetDistance(predic) < ERange and not unit.dead then
		local CastPos = CastEWLocation(unit, predic)

		if CastPos and GetDistance(CastPos) <= ERange then CastSpell(_E, CastPos.x, CastPos.y) end
	end
end

function CastEWLocation(unit, predic)
	if not unit or unit.dead then return false end

	if Menu.AutoCarry.MousePosWE then
		local TargetPos = Vector(predic.x, predic.y, predic.z)
		local MyPos = Vector(myHero.x, myHero.y, myHero.z)
		local MyMouse = Vector(mousePos.x, mousePos.y, mousePos.z)
		local mouseDistance = GetDistance(mousePos)

		if mouseDistance > 0 then
			local FirePos = TargetPos + (MyMouse - MyPos) * ((Menu.Advanced.WEDistanceFromEnemy / mouseDistance))
			return Point(FirePos.x, FirePos.z)
		end
	else
		local heroToEnemyX, y, heroToEnemyZ = (Vector(myHero) - Vector(predic)):normalized():unpack()
		local posX = predic.x - (heroToEnemyX * Menu.Advanced.WEDistanceFromEnemy)
		local posZ = predic.z - (heroToEnemyZ * Menu.Advanced.WEDistanceFromEnemy)

		return Point(posX, posZ)
	end

	return false
end

function CastR(enemy)
	if not enemy then enemy = Target end

	if enemy ~= nil and ValidTarget(enemy, RRange) and not enemy.dead then
		-- if RReady and GetDistance(enemy) <= RRange then
		-- enemyPos = AutoCarry.GetPrediction(SkillR, enemy)
		-- if RReady and enemyPos and ValidTarget(enemyPos, RRange) then
		if RReady and ValidTarget(enemy, RRange * .98) then
			if IsSACReborn then
				SkillR:Cast(enemy)
			else
				AutoCarry.CastSkillshot(SkillR, enemy)
			end
		end
	end
end

function KillSteal()
	if Menu.Advanced.AFKKillstealDisable and lastAFKStatus then return false end

	for _, enemy in pairs(AutoCarry.EnemyTable) do

		if ValidTarget(enemy, RRange) and not enemy.dead then
			if (enemy.health + 30) < getDmg("R", enemy, myHero) and IsValidHitChanceCustomR(enemy) then
				
				if RReady and GetDistance(enemy) < RRange then
					-- Message.AddMessage("Killsteal!", ColorARGB.Green, myHero)
					PrintFloatText(myHero, 10, "Ultimate Mega Killsteal!")
					if debugMode then PrintChat("Ultimate Mega Killsteal!") end

					if IsSACReborn then
						SkillR:Cast(enemy)
						break
					else
						local enemyPos = AutoCarry.GetPrediction(SkillR, enemy)
						if enemyPos and GetDistance(enemyPos) < RRange then
							AutoCarry.CastSkillshot(SkillR, enemy)
							break
						end
					end
				end
			end
		end
	end
end

function StealJungle()
	for i=1, #JungleBuffs do
		local monster = JungleBuffs[i]

		DmgOnObject = 0
		if QReady then DmgOnObject = DmgOnObject + getDmg("Q", monster, myHero) end
		if RReady then DmgOnObject = DmgOnObject + getDmg("R", monster, myHero) end

		if monster and monster.valid and monster.visible and not monster.dead and GetDistance(monster) <= RRange then
			if QReady and GetDistance(monster) < QMaxRange and monster.health  + 25 < getDmg("Q", monster, myHero) then
				CastSkillshotBounce(SkillQ, monster)
				return
			elseif RReady and GetDistance(monster) < RRange and monster.health + 50 < getDmg("R", monster, myHero) then
				CastSpell(_R, monster.x, monster.z)
				return
			elseif monster.health + 50 < DmgOnObject and GetDistance(monster) < QMaxRange and GetDistance(monster) < RRange then
				CastSkillshotBounce(SkillQ, monster)
				CastSpell(_R, monster.x, monster.z)
				return
			end
		end
	end
end

function IsNearRangeLimit(obj, range)
	if GetDistance(obj) >= (range * .98) then
		return true
	else
		return false
	end
end

function IsEnemyRetreating(target, predic)
	if GetDistance(predic) > GetDistance(target) then
		return true
	else
		return false
	end
end

function SatchelJump()
	-- E is cast before and after jump to insure that a target near either location can be hit.
	pendingSatchelChargeActivation = "satcheljump"
	CastW(true)
	if Menu.Advanced.SatchelJumpWithE then CastE() end
end

function SmartFarmWithQOld()
	local minions = {}
	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		if ValidTarget(minion) and QReady and GetDistance(minion) <= QMaxRange then
			if minion.health < getDmg("Q", minion, myHero) then 
				table.insert(minions, minion)
			end
		end
	end

	local pos1 = {x=0,z=0}
	local pos1Count = 0
	local pos2 = {x=0,z=0}
	local pos2Count = 0

	local closeMinion = QWidth * 1.5

	for _, minion in pairs(minions) do
		if pos1Count == 0 then
			pos1.x = minion.x
			pos1.z = minion.z
			pos1Count = 1
		elseif GetDistance(pos1, minion) < closeMinion then
			pos1.x = ((pos1.x * pos1Count) + minion.x) / (pos1Count + 1)
			pos1.z = ((pos1.z * pos1Count) + minion.z) / (pos1Count + 1)
			pos1Count = pos1Count + 1
		elseif pos2Count == 0 then
			pos2.x = minion.x
			pos2.z = minion.z
			pos2Count = 1
		elseif GetDistance(pos1, minion) < closeMinion then
			pos2.x = ((pos2.x * pos2Count) + minion.x) / (pos2Count + 1)
			pos2.z = ((pos2.z * pos2Count) + minion.z) / (pos2Count + 1)
			pos2Count = pos2Count + 1
		end
	end

	if debugMode and (pos1Count > 1 or pos2Count > 1) then
		PrintChat("pos1Count: "..pos1Count..", pos2Count: "..pos2Count)
	end

	if pos1Count > pos2Count and pos1Count >= 2 then
		CastSpell(_Q, pos1.x, pos1.z)
	elseif pos2Count >= 2 then
		CastSpell(_Q, pos2.x, pos2.z)
	end
end

function SmartFarmWithQ()
	local minions = {}
	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		if ValidTarget(minion) and QReady and GetDistance(minion) <= QMaxRange then
			if minion.health < getDmg("Q", minion, myHero) then 
				table.insert(minions, minion)
			end
		end
	end

	local minionClusters = {}

	local closeMinion = QWidth * 1.5

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

	if debugMode and largestClusterSize >= 2 then
		PrintChat("totalClusters: "..#minionClusters..", largestCluster: "..largestCluster..", largestClusterSize: "..largestClusterSize)
	end

	if largestClusterSize >= 2 then
		minionCluster = minionClusters[largestCluster]
		
		-- Needs to be in OnDraw to function.
		-- local minionClusterPoint = {x=minionCluster.x, y=myHero.y, z=minionCluster.z}
		-- DrawArrowsToPos(myHero, minionClusterPoint)

		CastSpell(_Q, minionCluster.x, minionCluster.z)
	end

	minions = nil
	minionClusters = nil
end

function IsMyManaLow()
	if myHero.mana < (myHero.maxMana * ( Menu.Misc.ManaManager / 100)) then
		return true
	else
		return false
	end
end

function IsTargetHealthLow()
	local targetLowHealth = .40

	if Target ~= nil and Target.health < (Target.maxHealth * targetLowHealth) then
		return true
	else
		return false
	end
end

function IsTargetManaLow()
	local targetLowMana = .15

	if Target ~= nil and Target.mana < (Target.maxMana * targetLowMana) then
		return true
	else
		return false
	end
end

-- Sliding scale hitchance based on target distance.
function getScalingHitChanceFromDistance(SkillRange, Target)
	local minHitChance = Menu.Advanced.KillstealMinHitchance
	local maxHitChance = 95

	if not Target or Target.dead then
		return 100
	end

	hitChance = minHitChance + ((1 - (SkillRange - GetDistance(Target)) / (SkillRange - 0))) * (maxHitChance - minHitChance)
	if debugMode then PrintChat("HitChance Info: skillrange="..SkillRange..", targetdistance="..GetDistance(Target)..", hitchance:"..hitChance) end
	return hitChance
end

function IsValidHitChanceCustomR(target)
	if VIP_USER then
		pred = TargetPredictionVIP(RRange, RSpeed*1000, RDelay/1000, RWidth)
		return pred and pred:GetHitChance(target) > getScalingHitChanceFromDistance(RRange, target)/100 and true or false
	elseif not VIP_USER then
		local nonVIPMaxHitChance = 70
		return getScalingHitChanceFromDistance(RRange, target) < nonVIPMaxHitChance and true or false
	end
end

--[[
function satchelChargeExistsDelete()
	for i=1, objManager.maxObjects do
		local obj = objManager:getObject(i)

		if obj ~= nil and obj.name:find("Satchel Charge") then
			return true
		end
	end	
	return false
end
--]]

-- Handle Manual Skill Shots

function _G.PluginOnWndMsg(msg,key)
	Target = AutoCarry.GetAttackTarget()
	if Target ~= nil then
		if msg == KEY_DOWN and key == KeyQ then CastQ() end
		if msg == KEY_DOWN and key == KeyW then CastW() end
		if msg == KEY_DOWN and key == KeyE then CastE() end
		if msg == KEY_DOWN and key == KeyR then CastR() end
	end
end

-- Draw
function _G.PluginOnDraw()
	if Target ~= nil and not Target.dead and (AutoCarry.MainMenu.AutoCarry or AutoCarry.MainMenu.MixedMode) then
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

	if not Menu.Draw.DisableDraw and not myHero.dead then
		local farSpell = FindFurthestReadySpell()
		-- if debugMode and farSpell then PrintChat("far: "..farSpell.configName) end

		-- Not needed as SAC has the same range draw.
		-- DrawCircle(myHero.x, myHero.y, myHero.z, getTrueRange(), 0x808080) -- Gray

		if Menu.Draw.DrawQ and QReady and ((Menu.Draw.DrawFurthest and farSpell and farSpell == QRange) or not Menu.Draw.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, QMaxRange, 0x0099CC) -- Blue
		end

		if Menu.Draw.DrawW and WReady and ((Menu.Draw.DrawFurthest and farSpell and farSpell == WRange) or not Menu.Draw.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, WRange, 0xFFFF00) -- Yellow
		end
		
		if Menu.Draw.DrawE and EReady and ((Menu.Draw.DrawFurthest and farSpell and farSpell == ERange) or not Menu.Draw.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, ERange, 0x00FF00) -- Green
		end

		if Menu.Draw.DrawR and RReady then
			DrawCircle(myHero.x, myHero.y, myHero.z, RRange, 0xFF0000) -- Red
		end

		Target = AutoCarry.GetAttackTarget()
		if Target ~= nil then
			for j=0, 10 do
				DrawCircle(Target.x, Target.y, Target.z, 40 + j*1.5, 0x00FF00) -- Green
			end
		end
	end

	DrawKillable()

	if Menu.Draw.DrawDmg and useDrawDamageLib then
		drawDamage()
	end

	if JungleKill ~= nil then
		DrawCircle(JungleKill.x, JungleKill.y, JungleKill.z, 1300, ARGB(255,0,255,0))
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
end

function FindFurthestReadySpell()
	local farSpell = nil

	if Menu.Draw.DrawW and WReady then farSpell = WRange end
	if Menu.Draw.DrawE and EReady and (not farSpell or ERange > farSpell) then farSpell = ERange end
	if Menu.Draw.DrawQ and QReady and (not farSpell or QMaxRange > farSpell) then farSpell = QRange end

	return farSpell
end

function getTrueRange()
    return myHero.range + GetDistance(myHero.minBBox)
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

function DrawArrowsToPos(pos1, pos2)
	if pos1 and pos2 then
		startVector = D3DXVECTOR3(pos1.x, pos1.y, pos1.z)
		endVector = D3DXVECTOR3(pos2.x, pos2.y, pos2.z)
		DrawArrows(startVector, endVector, 60, 0xE97FA5, 100)
	end
end

function CalculateDamage()
        if ValidTarget(Target) then
                local dfgdamage, hxgdamage, bwcdamage, ignitedamage, Sheendamage, Trinitydamage, LichBanedamage  = 0, 0, 0, 0, 0, 0, 0
                local pdamage = getDmg("P",Target,myHero)
                local qdamage = getDmg("Q",Target,myHero)
                local wdamage = getDmg("W",Target,myHero)
                local edamage = getDmg("E",Target,myHero)
                local rdamage = getDmg("R",Target,myHero)
                local hitdamage = getDmg("AD",Target,myHero)
                local dfgdamage = (DFGSlot and getDmg("DFG",Target,myHero) or 0)
                local hxgdamage = (HXGSlot and getDmg("HXG",Target,myHero) or 0)
                local bwcdamage = (BWCSlot and getDmg("BWC",Target,myHero) or 0)
                local ignitedamage = (ignite and getDmg("IGNITE",Target,myHero) or 0)
                local Sheendamage = (SheenSlot and getDmg("SHEEN",Target,myHero) or 0)
                local Trinitydamage = (TrinitySlot and getDmg("TRINITY",Target,myHero) or 0)
                local LichBanedamage = (LichBaneSlot and getDmg("LICHBANE",Target,myHero) or 0)
                local combo1 = hitdamage + qdamage + wdamage + edamage + rdamage + Sheendamage + Trinitydamage + LichBanedamage --0 cd
                local combo2 = hitdamage + Sheendamage + Trinitydamage + LichBanedamage
                local combo3 = hitdamage + Sheendamage + Trinitydamage + LichBanedamage
                local combo4 = 0
               
                if QReady then
                        combo2 = combo2 + qdamage
                        combo3 = combo3 + qdamage
                        --combo4 = combo4 + qdamage
                end
                if WReady then
                        combo2 = combo2 + wdamage
                        combo3 = combo3 + wdamage
                end
                if EReady then
                        combo2 = combo2 + edamage
                        combo3 = combo3 + edamage
                        --combo4 = combo4 + edamage
                end
                if RReady then
                        combo2 = combo2 + rdamage
                        combo3 = combo3 + rdamage
                        combo4 = combo4 + rdamage
                end
                if DFGReady then        
                        combo1 = combo1 + dfgdamage            
                        combo2 = combo2 + dfgdamage
                        combo3 = combo3 + dfgdamage
                        --combo4 = combo4 + dfgdamage
                end
                if HXGReady then              
                        combo1 = combo1 + hxgdamage    
                        combo2 = combo2 + hxgdamage
                        combo3 = combo3 + hxgdamage
                        --combo4 = combo4 + hxgdamage
                end
                if BWCReady then
                        combo1 = combo1 + bwcdamage
                        combo2 = combo2 + bwcdamage
                        combo3 = combo3 + bwcdamage
                        combo4 = combo4 + bwcdamage
                end
                if IReady then
                        combo1 = combo1 + ignitedamage
                        combo2 = combo2 + ignitedamage
                        combo3 = combo3 + ignitedamage
                end
                if combo4 >= Target.health then killable[calculationenemy] = 4 doUlt = true
                elseif combo3 >= Target.health then killable[calculationenemy] = 3 doUlt = false
                elseif combo2 >= Target.health then killable[calculationenemy] = 2 doUlt = false
                elseif combo1 >= Target.health then killable[calculationenemy] = 1  doCombo = true doUlt = false
                else killable[calculationenemy] = 0 doCombo = false doUlt = false end
        end
        if calculationenemy == 1 then calculationenemy = heroManager.iCount
        else calculationenemy = calculationenemy-1 end
end

function DrawKillable()
	-- if 1 == 2 and Target ~= nil and Menu.AutoCarry.DrawPrediction then -- QQQ
	if Target ~= nil and Menu.Draw.DrawPrediction then
		if VIP_USER then
			pred = TargetPredictionVIP(QRange, QSpeed*1000, QDelay/1000, QWidth)
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

	for i=1, heroManager.iCount do
		local enemydraw = heroManager:GetHero(i)
		if ValidTarget(enemydraw) then
			if Menu.Draw.DrawKillablEenemy then
				if killable[i] == 1 then
					for j=0, 20 do
						DrawCircle(enemydraw.x, enemydraw.y, enemydraw.z, 80 + j*1.5, 0x0000FF)
					end
				elseif killable[i] == 2 then
					for j=0, 10 do
						DrawCircle(enemydraw.x, enemydraw.y, enemydraw.z, 80 + j*1.5, 0xFF0000)
					end
				elseif killable[i] == 3 then
					for j=0, 10 do
						DrawCircle(enemydraw.x, enemydraw.y, enemydraw.z, 80 + j*1.5, 0xFF0000)
						DrawCircle(enemydraw.x, enemydraw.y, enemydraw.z, 110 + j*1.5, 0xFF0000)
					end
				elseif killable[i] == 4 then
					for j=0, 10 do
						DrawCircle(enemydraw.x, enemydraw.y, enemydraw.z, 80 + j*1.5, 0xFF0000)
						DrawCircle(enemydraw.x, enemydraw.y, enemydraw.z, 110 + j*1.5, 0xFF0000)
						DrawCircle(enemydraw.x, enemydraw.y, enemydraw.z, 140 + j*1.5, 0xFF0000)
					end
				end
			end
			if Menu.Draw.DrawText and waittxt ~= nil and waittxt[i] == 1 and killable ~= nil and killable[i] ~= 0 then
					PrintFloatText(enemydraw,0,floattext[killable[i]])
			end
		end
		if waittxt ~= nil then
			if waittxt[i] == 1 then waittxt[i] = 30
			else waittxt[i] = waittxt[i]-1 end
		end
	end
end

function AutoIgnite()
	if IReady and not myHero.dead then
		for _, enemy in ipairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) then
				if enemy ~= nil and enemy.team ~= myHero.team and not enemy.dead and enemy.visible and GetDistance(enemy) < IgniteRange and enemy.health < getDmg("IGNITE", enemy, myHero) then
					if Menu.Advanced.DoubleIgnite and not TargetHaveBuff("SummonerDot", enemy) then
						CastSpell(ignite, enemy)
					elseif not Menu.Advanced.DoubleIgnite then
						CastSpell(ignite, enemy)
					end
				end
			end
		end
	end
end

function _G.PluginOnProcessSpell(unit, spell)
	if #CurrentSpellInterrupts > 0 and Menu.Misc.SpellInterrupt and WReady then
		for _, ability in pairs(CurrentSpellInterrupts) do
			if spell.name == ability and unit.team ~= myHero.team then
				if GetDistance(unit) <= WRange then
					CastW(unit)
					break
				end
			end
		end
	end
end

function SetDamageTicks()
	setOrder("Q", 4)
	setOrder("W", 3)
	setOrder("E", 2)
	setOrder("R", 1)
	setFlipped(true)
end

webTrialVersion = nil
scriptTrialVersion = "g103"
GetAsyncWebResult("bolscripts.com","scriptauth/script_date_check.php?s=ziggs&v="..version, function(result) webTrialVersion = result end)

--[[
class 'ColorARGB' -- {

    function ColorARGB:__init(red, green, blue, alpha)
        self.R = red or 255
        self.G = green or 255
        self.B = blue or 255
        self.A = alpha or 255
    end

    function ColorARGB.FromArgb(red, green, blue, alpha)
        return Color(red,green,blue, alpha)
    end

    function ColorARGB:ToARGB()
        return ARGB(self.A, self.R, self.G, self.B)
    end

    ColorARGB.Red = ColorARGB(255, 0, 0, 255)
    ColorARGB.Yellow = ColorARGB(255, 255, 0, 255)
    ColorARGB.Green = ColorARGB(0, 255, 0, 255)
    ColorARGB.Aqua = ColorARGB(0, 255, 255, 255)
    ColorARGB.Blue = ColorARGB(0, 0, 255, 255)
    ColorARGB.Fuchsia = ColorARGB(255, 0, 255, 255)
    ColorARGB.Black = ColorARGB(0, 0, 0, 255)
    ColorARGB.White = ColorARGB(255, 255, 255, 255)
-- }

--Notification class
class 'Message' -- {

    Message.instance = ""

    function Message:__init()
        self.notifys = {} 

        AddDrawCallback(function(obj) self:OnDraw() end)
    end

    function Message.Instance()
        if Message.instance == "" then Message.instance = Message() end return Message.instance 
    end

    function Message.AddMessage(text, color, target)
        return Message.Instance():PAddMessage(text, color, target)
    end

    function Message:PAddMessage(text, color, target)
        local x = 0
        local y = 200 
        local tempName = "Screen" 
        local tempcolor = color or ColorARGB.Red

        if target then  
            tempName = target.networkID
        end

        self.notifys[tempName] = { text = text, color = tempcolor, duration = GetGameTimer() + 2, object = target}
    end

    function Message:OnDraw()
        for i, notify in pairs(self.notifys) do
            if notify.duration < GetGameTimer() then notify = nil 
            else
                notify.color.A = math.floor((255/2)*(notify.duration - GetGameTimer()))

                if i == "Screen" then  
                    local x = 0
                    local y = 200
                    local gameSettings = GetGameSettings()
                    if gameSettings and gameSettings.General then 
                        if gameSettings.General.Width then x = gameSettings.General.Width/2 end 
                        if gameSettings.General.Height then y = gameSettings.General.Height/4 - 100 end
                    end  
                    --PrintChat(tostring(notify.color))
                    local p = GetTextArea(notify.text, 40).x 
                    self:DrawTextWithBorder(notify.text, 40, x - p/2, y, notify.color:ToARGB(), ARGB(notify.color.A, 0, 0, 0))
                else    
                    local pos = WorldToScreen(D3DXVECTOR3(notify.object.x, notify.object.y, notify.object.z))
                    local x = pos.x
                    local y = pos.y - 25
					local p = GetTextArea(notify.text, 40).x 

					self:DrawTextWithBorder(notify.text, 30, x- p/2, y, notify.color:ToARGB(), ARGB(notify.color.A, 0, 0, 0))
                end
            end
        end
    end 

    function Message:DrawTextWithBorder(textToDraw, textSize, x, y, textColor, backgroundColor)
        DrawText(textToDraw, textSize, x + 1, y, backgroundColor)
        DrawText(textToDraw, textSize, x - 1, y, backgroundColor)
        DrawText(textToDraw, textSize, x, y - 1, backgroundColor)
        DrawText(textToDraw, textSize, x, y + 1, backgroundColor)
        DrawText(textToDraw, textSize, x , y, textColor)
    end
-- }
--]]

--UPDATEURL=https://bitbucket.org/KainBoL/bol/raw/master/Common/SidasAutoCarryPlugin%20-%20Ziggs.lua
--HASH=EE889CCF9CC2B320A06050F270A8B0E9
