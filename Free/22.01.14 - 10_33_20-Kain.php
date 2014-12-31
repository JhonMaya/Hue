<?php exit() ?>--by Kain 97.90.203.108
--[[
 
        Auto Carry Plugin - Lucian Edition
		Author: Kain
		Version: 1.1g
		Copyright 2013

		Dependency: Sida's Auto Carry: Revamped
 
		How to install:
			Make sure you already have AutoCarry installed.
			Name the script EXACTLY "SidasAutoCarryPlugin - Lucian.lua" without the quotes.
			Place the plugin in BoL/Scripts/Common folder.

		Download: https://bitbucket.org/KainBoL/bol/raw/master/Common/SidasAutoCarryPlugin%20-%20Lucian.lua

		Version History:
			Version: 2.0:
				Added PROdiction v.3.
				Added proper Reborn support.
				Increased Q range per patch 3.15.
				Better use of Piercing Light (Q) minion extension to get far targets.
				Draw on Piercing Light (Q) minion extension.
				Improved spell weave.
				Added collision on Ardent Blaze (W).
				Uses FastCollision.
				Add Draw Damage library for showing damage ticks on enemies.
				Reworked Menu.
				Tweaked a few speed/delay settings for slightly better aim.
				Added auto ignite and avoid double ignite.
				Support for more items' active uses.
				Added showing target waypoints and line.
				Show mouse position.
			Version: 1.1g: Moved to BitBucket
				Fixed hero multishot bugs.
				Added checks for missing or old collision lib.
				Added "BoL Studio Script Updater" url and hash.
			Version: 1.1e: http://pastebin.com/Z3ipPQDE
				Added Multi-shot.
				Added Smart Farm with Q.
				Added Mana Manager checks.
				Split menu into two menus.
				Fixed non-vip smart farming bug.
			Version: 1.08c: http://pastebin.com/D5NwQUae
				Fixed Combo weaving.
				Added spell initiation if AA unavailable.
				Fixed auto attack before Q.
				Fixed Killsteal.
				Added Smart Cast angles to Q. Won't fire if probability of missing is too high.
				Added minimum range to shoot Ultimate to avoid complete misses.
				Fixed stand still and shoot range.
				Fixed a bug with R.
				Using SAC with "Enabled Jungle Clearing" enabled doesn't use R now.
			Version: 1.07e: http://pastebin.com/kgFKdwuS
				Sword of the Divine supported.
				Improved dynamic draw ranges.
				Harass fixed. Fires Q.
				Hack fixed killsteal until BoL finally gets updated properly.
				Killsteal fix resolved firing spells without key pressed.
			Version: 1.07c: http://pastebin.com/tK2WNXsy
				Relentless Pursuit Out of Enemy slows.
				Tweaked prediction variables.
				Killsteal enabled.
			Version: 1.06c: http://pastebin.com/sdvmmNyR
				Spell weaving.
				Draw range logic improvement.
				Menu updates.
			Version: 1.05d Beta: http://pastebin.com/rE5X8puE
				Improvements to mechanics of Culling Lockon. (Still more to do here.)
				Fix issue with Ultimate ending early.
				Miscellaneous bug fixes.
			Version: 1.04 Beta Pre-Release: http://pastebin.com/ayEX5Bfq
				Culling Lockon added. Considered experimental, but seems to be working pretty well.
			Version: 1.02 Beta Pre-Release: http://pastebin.com/tXxr95g9
			Version: 1.0 Alpha: http://pastebin.com/xWbRz89K
--]]

if myHero.charName ~= "Lucian" then return end

version = "2.0"

-- Check to see if user failed to read the forum...
if VIP_USER then
	if FileExist(SCRIPT_PATH..'Common/Collision.lua') then
		require "Collision"

		if type(Collision) ~= "userdata" then
			PrintChat("Your version of Collision.lua is incorrect. Please install v1.1.1 or later in Common folder.")
			return
		else
			assert(type(Collision.GetMinionCollision) == "function")
			assert(type(Collision.GetHeroCollision) == "function")
		end
	else
		PrintChat("Please install Collision.lua v1.1.1 or later in Common folder.")
		return
	end

	if FileExist(SCRIPT_PATH..'Common/2DGeometry.lua') then
		PrintChat("Please delete 2DGeometry.lua from your Common folder.")
	end

	assert(type(Point.closestPoint) == "function")
end

function _G.PluginOnLoad()
	Vars()
	Menu()

	if IsSACReborn then
		AutoCarry.Crosshair:SetSkillCrosshairRange(RRange)
	else
		AutoCarry.SkillsCrosshair.range = RRange
	end

	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then ignite = SUMMONER_2 end
	for i=1, heroManager.iCount do waittxt[i] = i*3 end

	if useDrawDamageLib then
		SetDamageTicks()
	end
end

function Vars()
	tick = nil
	Target = nil

	-- Reborn
	if AutoCarry.Skills then IsSACReborn = true else IsSACReborn = false end

	-- Disable SAC Reborn's skills. Ours are better.
	if IsSACReborn then
		AutoCarry.Skills:DisableAll()
	end

	-- Confirm ranges on release.
	QRange = 550 -- Old: 550, changed to 680 in 3.15.
	QMaxRange = 1100
	WRange = 1000
	ERange = 425
	RRange = 1400

	QSpeed = math.huge -- Old: 19.346
	WSpeed = 1.47 -- Old: 1.009
	ESpeed = 3.867
	RSpeed = 2.9 -- Old: 1.3

	QWidth = 65 -- Old: 250
	WWidth = 80
	EWidth = 250
	RWidth = 90

	QDelay = 405
	WDelay = 288 -- Old: 256
	EDelay = 1070
	RDelay = 200

	RRefresh = 0.1
	RDuration = 3.2 -- Old: 3.0 2.871 - 3.167

	ParticleRProjectileName = "Lucian_R_mis.troy"
	ParticleRProjectileNameOld = "bowMaster_volley_mis.troy"
	ParticleR = "Lucian_R_tar.troy"
	ParticleRFiring = "Lucian_R_self.troy"

	-- Start New
	BuffPassive = "lucianpassivebuff" -- Old: "Lightslinger"
	BuffW = "lucianwcastingbuff"
	BuffR = "LucianR"

	-- End New
--[[
	Raw data dump:

	spellName = LucianBasicAttack
	projectileName = ???
	castDelay = 4001.50 (827.00-7176.00)
	projectileSpeed = 6321.08 (724.19-11917.96)
	range = 2594.85 (541.70-4648.01)

	spellName = LucianW
	projectileName = Lucian_W_mis.troy
	castDelay = 288.67 (265.00-297.00)
	projectileSpeed = 1484.95 (1391.22-1640.62)
	range = 661.85 (304.68-1000.83)

	spellName = LucianE
	projectileName = Lucian_W_mis.troy
	castDelay = 8189.75 (0.00-32245.00)
	projectileSpeed = 25590.51 (703.58-79656.61)
	range = 3772.70 (372.90-8054.26)

	spellName = LucianR
	projectileName = Lucian_R_mis.troy
	castDelay = 210.50 (187.00-234.00)
	projectileSpeed = 2850.25 (2342.64-3357.86)
	range = 405.05 (261.91-548.18)

	Raw data dump #2:

	spellName = LucianW
	projectileName = Lucian_W_mis.troy
	castDelay = 313.81 (265.00-453.00)
	projectileSpeed = 1459.60 (558.14-1797.76)
	range = 648.24 (17.30-1007.14)

	spellName = LucianE
	projectileName = Lucian_W_mis.troy
	castDelay = 2147.83 (0.00-10530.00)
	projectileSpeed = 4680.15 (632.65-14746.30)
	range = 3349.24 (809.79-8272.67)

	spellName = LucianR
	projectileName = Lucian_R_mis.troy
	castDelay = 199.80 (172.00-218.00)
	projectileSpeed = 3023.31 (2816.79-3321.66)
	range = 757.80 (205.94-1063.38)

--]]

	if IsSACReborn then
		SkillQ = AutoCarry.Skills:NewSkill(false, _Q, QRange, "Piercing Light", AutoCarry.SPELL_TARGETED, 0, false, false, QSpeed, QDelay, QWidth, false)
		SkillW = AutoCarry.Skills:NewSkill(false, _W, WRange, "Ardent Blaze", AutoCarry.SPELL_LINEAR_COL, 0, false, false, WSpeed, WDelay, WWidth, true)
		SkillE = AutoCarry.Skills:NewSkill(false, _E, ERange, "Relentless Pursuit", AutoCarry.SPELL_SELF_AT_MOUSE, 0, false, false, ESpeed, EDelay, EWidth, false)
		SkillR = AutoCarry.Skills:NewSkill(false, _R, RRange, "The Culling", AutoCarry.SPELL_LINEAR, 0, false, false, RSpeed, RDelay, RWidth, false)
	else
		SkillQ = {spellKey = _Q, range = QRange, speed = QSpeed, delay = QDelay, width = QWidth, configName = "piercinglight", displayName = "Q (Piercing Light)", enabled = true, skillShot = true, minions = false, reset = false, reqTarget = true }
		SkillW = {spellKey = _W, range = WRange, speed = WSpeed, delay = WDelay, width = WWidth, configName = "ardentblaze", displayName = "W (Ardent Blaze)", enabled = true, skillShot = true, minions = true, reset = false, reqTarget = false }
		SkillE = {spellKey = _E, range = ERange, speed = ESpeed, delay = EDelay, width = EWidth, configName = "relentlesspursuit", displayName = "E (Relentless Pursuit)", enabled = true, skillShot = true, minions = false, reset = false, reqTarget = false }
		SkillR = {spellKey = _R, range = RRange, speed = RSpeed, delay = RDelay, width = RWidth, configName = "theculling", displayName = "R (The Culling)", enabled = true, skillShot = true, minions = false, reset = false, reqTarget = true }
	end

	KeyQ = string.byte("Q")
	KeyW = string.byte("W")
	KeyE = string.byte("E")
	KeyR = string.byte("R")

	KeyTest = string.byte("U")

	-- Items
	ignite = nil
	DFGSlot, HXGSlot, BWCSlot, STDSlot, SheenSlot, TrinitySlot, LichBaneSlot = nil, nil, nil, nil, nil, nil, nil
	QReady, WReady, EReady, RReady, DFGReady, HXGReady, BWCReady, STDReady, IReady = false, false, false, false, false, false, false, false
	IgniteRange = 600

	ultCastTick = 0
	ultCastTarget = nil
	ultVectorX = nil
	ultVectorZ = nil

	isSlowed = false

	healthLastTick = 0
	healthLastHealth = 0

	isUltFiring = false
	lastUltMessage = 0

	comboActive = false
	nextAttack = 0
	lastSpell = 0

	-- Angles
	angle_towards = 180
	angle_away = 0
	angle_parallel_towards = 90
	angle_parallel_away = 270
	angle_unknown = -1

	-- Collision
    TYPE_ALL = 1
    TYPE_HERO = 2
	TYPE_MINION = 3

	lastText = 0

	-- Draw
	waittxt = {}
	calculationenemy = 1
	floattext = {"Skills not available", "Able to fight", "Killable", "Murder him!"}
	killable = {}

	QDrawLeft, QDrawRight, QDrawTop = nil, nil, nil

	wpm = WayPointManager()

	debugMode = false
	debugModeDisableNonR = false

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

wp = ProdictManager.GetInstance()
			tpQ = wp:AddProdictionObject(_Q, QMaxRange, math.huge, QDelay / 1000, QWidth)
--[[
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

			tpR = SetupPROdiction(_R, RRange, RSpeed, RDelay, RWidth, myHero,
				function(unit, pos, castSpell)
					if myHero:CanUseSpell(_R) == READY and GetDistance(unit) < RRange then
						FireR(unit, pos, castSpell)
					end
				end)
]]--
			PrintChat("<font color='#CCCCCC'> >> Kain's Ziggs - PROdiction <</font>")
		end
	else
		PrintChat("<font color='#CCCCCC'> >> Kain's Ziggs - Free Prediction <</font>")
	end

	useDrawDamageLib = true
end

function Menu()
Menu = AutoCarry.PluginMenu

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Info]", "ScriptInfo")
		Menu.ScriptInfo:addParam("sep", myHero.charName.." Auto Carry: Version "..version, SCRIPT_PARAM_INFO, "")
		Menu.ScriptInfo:addParam("sep1","Created By: Kain", SCRIPT_PARAM_INFO, "")

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Combo]", "Combo")
		Menu.Combo:addParam("sep", "----- [ Combo ] -----", SCRIPT_PARAM_INFO, "")
		Menu.Combo:addParam("Combo", "Combo - Default Spacebar", SCRIPT_PARAM_INFO, "")
		Menu.Combo:addParam("FullCombo", "Insta Blast Combo (No AA)", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
		Menu.Combo:addParam("ComboQ", "Use Piercing Light", SCRIPT_PARAM_ONOFF, true)
		Menu.Combo:addParam("ComboW", "Use Ardent Blaze", SCRIPT_PARAM_ONOFF, true)
		Menu.Combo:addParam("ComboE", "Use Relentless Pursuit", SCRIPT_PARAM_ONOFF, true)
		Menu.Combo:addParam("ComboR", "Use The Culling", SCRIPT_PARAM_ONOFF, true)
		Menu.Combo:addParam("SmartE", "Smart Relentless Pursuit", SCRIPT_PARAM_ONOFF, true)
		Menu.Combo:addParam("QMinAoE", "Q Min. Enemies for AoE Multishot >=", SCRIPT_PARAM_SLICE, 3, 3, 5, 0)
		Menu.Combo:addParam("UltLockOn", "Use Ultimate Lock On (Beta)", SCRIPT_PARAM_ONOFF, true)

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Misc]", "Misc")
		Menu.Misc:addParam("PursuitSlow", "Pursuit Out of Slows", SCRIPT_PARAM_ONOFF, true)
		Menu.Misc:addParam("AutoHarass", "Auto Harass", SCRIPT_PARAM_ONOFF, false)
		Menu.Misc:addParam("sep", "----- [ Killsteal ] -----", SCRIPT_PARAM_INFO, "")
		Menu.Misc:addParam("Killsteal", "Killsteal", SCRIPT_PARAM_ONOFF, true)
	--	Menu.Misc:addParam("KillstealUlt", "Killsteal with Ult", SCRIPT_PARAM_ONOFF, false)

	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Advanced]", "Advanced")
		Menu.Advanced:addParam("EMinMouseDiff", "Pursuit Min. Mouse Diff.", SCRIPT_PARAM_SLICE, 600, 100, 1000, 0)
		Menu.Advanced:addParam("ProMode", "Use Auto QWER Keys", SCRIPT_PARAM_ONOFF, true)
		Menu.Advanced:addParam("MaxSpellAngle", "Max Spell Angle", SCRIPT_PARAM_SLICE, 35, 5, 90, 0)
		Menu.Advanced:addParam("UltClose", "Don't Ult Closer Than", SCRIPT_PARAM_SLICE, 150, 50, 800, 0)
		Menu.Advanced:addParam("DoubleIgnite", "Don't Double Ignite", SCRIPT_PARAM_ONOFF, true)
		Menu.Advanced:addParam("ManaManager", "Mana Manager %", SCRIPT_PARAM_SLICE, 40, 0, 100, 2)
		Menu.Advanced:addParam("HealthPercentage", "Health Drop %", SCRIPT_PARAM_SLICE, 30, 0, 100, 0)
		Menu.Advanced:addParam("HealthTime", "Health Tracking Time", SCRIPT_PARAM_SLICE, 2, 2, 5, 0)
		Menu.Advanced:addParam("sep", "----- [ Performance ] -----", SCRIPT_PARAM_INFO, "")
	--	Menu.Advanced:addParam("PerformanceRatio", "<-- Performance vs FPS -->", SCRIPT_PARAM_SLICE, 5, 1, 10, 0)
		Menu.Advanced:addParam("UseGoodAngles", "Use Good Shot Angles", SCRIPT_PARAM_ONOFF, true)
		Menu.Advanced:addParam("UseMultishot", "Use Multishot", SCRIPT_PARAM_ONOFF, true)
		Menu.Advanced:addParam("SmartFarmWithQ", "Smart Farm With Q", SCRIPT_PARAM_ONOFF, true)


	Menu:addSubMenu("["..myHero.charName.." Auto Carry: Draw]", "Draw")

		Menu.Draw:addParam("DrawKillablEenemy", "Draw Killable Enemy", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawTargetArrow","Draw Target Arrow", SCRIPT_PARAM_ONOFF, false)
		Menu.Draw:addParam("DrawTargetLine","Draw Target Line", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawTargetWaypoints","Draw Target Waypoints", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawText", "Draw Text", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawPrediction", "Draw Prediction", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawMousePos", "Draw Mouse Position", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("QAoEDraw", "Draw Q AoE", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DisableDraw", "Disable Draw", SCRIPT_PARAM_ONOFF, false)
		Menu.Draw:addParam("DrawFurthest", "Draw Furthest Spell Available", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawQ", "Draw Piercing Light", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawW", "Draw Ardent Blaze", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawE", "Draw Relentless Pursuit", SCRIPT_PARAM_ONOFF, true)
		Menu.Draw:addParam("DrawR", "Draw The Culling", SCRIPT_PARAM_ONOFF, true)
end

function _G.PluginOnTick()
	tick = GetTickCount()

	if (webTrialVersion ~= nil and scriptTrialVersion ~= webTrialVersion) or webTrialVersion == nil then
		if webTrialVersion ~= nil then
			print("<font color='#c22e13'>This version of the script has been disabled, go to forums for update.</font>")
		end

		return
	end

	Target = GetTarget()

	SpellCheck()

	if IsTickReady(200) then
		CalculateDamage()
	end

	-- tick % 5 == 0) and 
	if IsTickReady(25) and IsFiringUlt() then
		CheckRPosition()
	else
		if AutoCarry.MainMenu.AutoCarry then
			comboActive = true
			Combo()
			AutoIgnite()
		else
			comboActive = false
		end

		if Menu.Combo.FullCombo then
			FullCombo()
		end

		if AutoCarry.MainMenu.MixedMode or Menu.Misc.AutoHarass then
			Harass()
		end

		if Menu.Misc.Killsteal then
			KillSteal()
		end

		if (AutoCarry.MainMenu.LaneClear or AutoCarry.MainMenu.MixedMode) and Menu.Advanced.SmartFarmWithQ and IsTickReady(70) and not IsMyManaLow()  then
			SmartQFarm()
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

function _G.PluginOnCreateObj(obj)
	-- Nothing to do here.
	if obj.name == ParticleRFiring then
		if debugMode then PrintChat("Ult Particle Started") end
		isUltFiring = true
		if debugMode then PrintChat("start: "..(tick / 1000)) end
	end
end

function _G.PluginOnDeleteObj(obj)
	if obj.name == ParticleRFiring then
		if debugMode then PrintChat("Ult Particle Stopped") end
		isUltFiring = false
		UltFireStop()
		if debugMode then PrintChat("stop: "..(tick / 1000)) end
	end
end

function _G.OnAttacked()
	-- AA > Q > AA
	ComboWeave(true)
end

--[[
function CustomAttackEnemy(enemy)
		if enemy.dead or not enemy.valid then return end
		-- myHero:Attack(enemy)
		-- AutoCarry.shotFired = true
end
--]]

function _G.OnGainBuff(unit, buff)
	if buff and buff.type ~= nil and unit.name == myHero.name and unit.team == myHero.team and (buff.type == 5 or buff.type == 10 or buff.type == 11) then
		-- BUFF_STUN = 5 BUFF_SLOW = 10 BUFF_ROOT = 11
		if Menu.Misc.PursuitSlow then
			isSlowed = true
			CastE()
		end
	end 
end

function SpellCheck()
	DFGSlot, HXGSlot, BWCSlot, BRKSlot, STDSlot, SheenSlot, TrinitySlot, LichBaneSlot = GetInventorySlotItem(3128),
	GetInventorySlotItem(3146), GetInventorySlotItem(3144), GetInventorySlotItem(3153), GetInventorySlotItem(3131),
	GetInventorySlotItem(3057), GetInventorySlotItem(3078), GetInventorySlotItem(3100)

	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)

	DFGReady = (DFGSlot ~= nil and myHero:CanUseSpell(DFGSlot) == READY)
	HXGReady = (HXGSlot ~= nil and myHero:CanUseSpell(HXGSlot) == READY)
	BWCReady = (BWCSlot ~= nil and myHero:CanUseSpell(BWCSlot) == READY)
	BRKReady = (BRKSlot ~= nil and myHero:CanUseSpell(BRKSlot) == READY)
	STDReady = (STDSlot ~= nil and myHero:CanUseSpell(BRKSlot) == READY)

	IReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
end

-- Handle SBTW Skill Shots

function Combo()
	if not Target then return end
	ComboWeave()
end

function ComboWeave(attacked)
	if (AutoCarry.MainMenu.MixedMode or Menu.Misc.AutoHarass) and IsTickReady(60) then CastQ() end

	if AutoCarry.MainMenu.AutoCarry then
		CastSlots()

		if not attacked and tick > (lastSpell + 3000) then
			if not Menu.Combo.ComboW or (Menu.Combo.ComboW and not CastW()) then
				if Menu.Combo.ComboQ then CastQ() end
			end
		elseif comboActive then -- and tick > nextAttack then
			nextAttack = AutoCarry.GetNextAttackTime()

			if Menu.Combo.ComboW then
				if CastW() then return end
			end

			if Menu.Combo.ComboE then
				if CastE() then return end
			end

			if Menu.Combo.ComboQ then
				if CastQ() then return end
			end

			if Menu.Combo.ComboR and IsTickReady(50) then
				if CastR() then return end
			end

			comboActive = false
		end
	end
end

function FullCombo()
	CastSlots()
	CastW()
	CastE()
	CastQ()
	CastR()
end

function CastSlots()
	if Target ~= nil then
		if GetDistance(Target) <= QRange then
			if DFGReady then CastSpell(DFGSlot, Target) end
			if HXGReady then CastSpell(HXGSlot, Target) end
			if BWCReady then CastSpell(BWCSlot, Target) end
			if BRKReady then CastSpell(BRKSlot, Target) end
			if STDReady then CastSpell(STDSlot, Target) end
		end
	end
end

function Harass()
	if IsTickReady(20) then CastQ() end
end

function IsTickReady(tickFrequency)
	-- Improves FPS
	-- Disabled for now.
--	local frequency = tickFrequency
--	if Menu.Advanced.PerformanceRatio > 0 then
--		frequency = (Menu.Advanced.PerformanceRatio + 1) * tickFrequency
--	elseif Menu.Advanced.PerformanceRatio < 0 then
--		frequency = tickFrequency / (abs(Menu.Advanced.PerformanceRatio) + 1)
--	end
	
	-- if tick ~= nil and math.fmod(tick, (tickFrequency * Menu.Advanced.PerformanceRatio)) == 0 then
	if tick ~= nil and math.fmod(tick, tickFrequency) == 0 then
		return true
	else
		return false
	end
end

function UpdateLastSpellTime()
	lastSpell = tick
end

function CastQ(enemy)
	CastQNew(enemy)
end

function CastQNew(enemy)
        -- if not Menu.qLogicCarry then SkillQ:Cast(Target) return end
        -- if (Menu.qPassiveWeave and pFlag) or (Menu.qSheenWeave and sFlag) then return end
       
        local Units = {}
        local Champs = {}
       
        local i = 1
        for h, hero in pairs(AutoCarry.EnemyTable) do
                if GetDistance(hero) < QRange then
                        table.insert(Units, hero)
                end
                if GetDistance(hero) < QMaxRange then
                        table.insert(Champs, hero)
                        Champs[i].prod = tpQ:GetPrediction(hero)
                        i = i + 1
                end
        end
        if Champs == {} then return end
       
        for m, minion in pairs(AutoCarry.EnemyMinions().objects) do
                table.insert(Units, minion)
        end
       
        local bestshot, bestshotHits, bestshotHitsTarget = nil, 0, 0
        for u, unit in pairs(Units) do
                local V = Vector(unit) - Vector(myHero)
               
                local Vn = V:normalized()
                local Vp = V:perpendicular():normalized()
                local a, b, c = Vn:unpack()
                local x, y, z = Vp:unpack()
               
                local startLeftX = myHero.x + (x * QWidth)
                local startLeftY = myHero.y + (y * QWidth)
                local startLeftZ = myHero.z + (z * QWidth)
                local startRightX = myHero.x - (x * QWidth)
                local startRightY = myHero.y - (y * QWidth)
                local startRightZ = myHero.z - (z * QWidth)
               
                local endLeftX = myHero.x + (x * QWidth) + (a * QMaxRange)
                local endLeftY = myHero.y + (y * QWidth) + (b * QMaxRange)
                local endLeftZ = myHero.z + (z * QWidth) + (c * QMaxRange)
                local endRightX = myHero.x - (x * QWidth) + (a * QMaxRange)
                local endRightY = myHero.y - (y * QWidth) + (b * QMaxRange)
                local endRightZ = myHero.z - (z * QWidth) + (c * QMaxRange)
               
                local startLeft = WorldToScreen(D3DXVECTOR3(startLeftX, startLeftY, startLeftZ))
                local startRight = WorldToScreen(D3DXVECTOR3(startRightX, startRightY, startRightZ))
                local endLeft = WorldToScreen(D3DXVECTOR3(endLeftX, endLeftY, endLeftZ))
                local endRight = WorldToScreen(D3DXVECTOR3(endRightX, endRightY, endRightZ))
               
                local Poly = Polygon(Point(startLeft.x, startLeft.y), Point(endLeft.x, endLeft.y), Point(endRight.x, endRight.y), Point(startRight.x, startRight.y))
               
                local hits, hitsTarget = 0, 0
                for c, champ in pairs(Champs) do
                        local Pos = champ.prod
                        if Pos == nil then Pos = champ end
                       
                        local toScreen = WorldToScreen(D3DXVECTOR3(Pos.x, Pos.y, Pos.z))
                        local toPoint = Point(toScreen.x, toScreen.y)
                       
                        if Poly:contains(toPoint) then
                                bestshot = unit
                                hits = hits + 1
                                if champ == Target then hitsTarget = 1 end
                        end
                end
               
                if (hits > bestshotHits and hitsTarget >= bestshotHitsTarget) or hits >= Menu.Combo.QMinAoE then
                        bestshot, bestshotHits, bestshotHitsTarget = unit, hits, hitsTarget
                end
        end
       
        if bestshot ~= nil then
                SkillQ:Cast(bestshot)
        end
       
        if Menu.Draw.QAoEDraw and Target then
            local V = Vector(Target) - Vector(myHero)
           
            local Vn = V:normalized()
            local Distance = GetDistance(Target)
            local tx, ty, tz = Vn:unpack()
            local TopX = Target.x - (tx * Distance)
            local TopY = Target.y - (ty * Distance)
            local TopZ = Target.z - (tz * Distance)
           
            local Vr = V:perpendicular():normalized()
            local Radius = GetDistance(Target, Target.minBBox)
            local tx, ty, tz = Vr:unpack()
           
            local LeftX = Target.x + (tx * Radius)
            local LeftY = Target.y + (ty * Radius)
            local LeftZ = Target.z + (tz * Radius)
            local RightX = Target.x - (tx * Radius)
            local RightY = Target.y - (ty * Radius)
            local RightZ = Target.z - (tz * Radius)

            QDrawLeft = WorldToScreen(D3DXVECTOR3(LeftX, LeftY, LeftZ))
            QDrawRight = WorldToScreen(D3DXVECTOR3(RightX, RightY, RightZ))
            QDrawTop = WorldToScreen(D3DXVECTOR3(TopX, TopY, TopZ))
        end       
end

function CastQOld(enemy)
	-- Q (Piercing Light)
	if not enemy then enemy = Target end

	if enemy and not enemy.dead and QReady and ValidTarget(enemy, QMaxRange) then
		local distance = GetDistance(enemy)
		local range = QRange
		if distance > QRange and Menu.Advanced.UseMultishot then
			range = QMaxRange
		end

		local tpQ = VIP_USER and TargetPredictionVIP(range, QSpeed*1000, 0, QWidth) or TargetPrediction(range, QSpeed, 0, QWidth)

		local hitCount, hitEnemy = 0, nil
		local angleDiff = nil
		local predPos = nil

		if enemy.type == myHero.type then
			if Menu.Advanced.UseGoodAngles or Menu.Advanced.UseMultishot then
				if tpQ then
					predPos = tpQ:GetPrediction(enemy)
				end
			end

			-- Check angle of prediction.
			if Menu.Advanced.UseGoodAngles then
				local goodAngle = false
				if predPos then
					angleDiff = GetAngle(enemy, predPos)
					if angleDiff then
						goodAngle = IsGoodAngle(angleDiff, Menu.Advanced.MaxSpellAngle)
					end
				end
				
				if not angleDiff or not goodAngle then return false end
			end
		

			-- Find multishot possibilities.
			if distance > QRange and Menu.Advanced.UseMultishot then
				hitCount, hitEnemy = FindQMultishot(enemy, predPos, false)

				if hitEnemy and hitCount > 0 then
					-- We have a winner.
				end
			end
		end

		-- if not angleDiff or (IsGoodAngle(angleDiff, Menu.Advanced.MaxSpellAngle)) then
		if hitCount > 0 and hitEnemy then
			if debugMode then PrintChat("Q Mode: Multishot") end
			
			if lastText == 0 or (tick > lastText + 2000) then
				lastText = tick
				PrintFloatText(myHero, 20, "Multishot!")
			end

			CastSpell(_Q, hitEnemy)
			UpdateLastSpellTime()
			return true
		else
			if debugMode then PrintChat("Q Mode: normal") end
			CastSpell(_Q, enemy)
			UpdateLastSpellTime()
			return true
		end
	end

	return false
end

function SmartQFarm()
	if not QReady then return false end

	local minions = {}
	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		if ValidTarget(minion) and GetDistance(minion) <= QRange then
			if minion.health < getDmg("Q", minion, myHero) then 
				table.insert(minions, minion)
			end
		end
	end

	local hitCount, hitEnemy
	for _, minion in pairs(minions) do
		hitCount, hitEnemy = FindQMultishot(minion, minion, true)
		if hitCount >= 2 then
			local count = 0
			for _, enemy in pairs(hitEnemy) do
				if enemy.health < getDmg("Q", enemy, myHero) then
					count = count + 1
				end
			end

			if count >= 2 then
				CastQ(minion)
				break
			end
		end
	end
end

function FindQMultishot(enemy, predPos, multiple)
	local hitCount = 0
	local hitEnemy = nil

	if multiple then
		hitEnemy = {}
	end

	if not QReady or not enemy or enemy.dead or not predPos then return hitCount, hitEnemy end

	local m, minions = GetMinionCollision(QRange, QSpeed, QDelay, QWidth, QMaxRange, myHero, predPos)

	local h, heroes = false, nil

	if VIP_USER then
		h, heroes = GetHeroCollision(QRange, QSpeed, QDelay, QWidth, QMaxRange, myHero, predPos)
	end

	if not m and not h then return hitCount, hitEnemy end

	if m then
		for index, minion in pairs(minions) do
			hitCount = hitCount + 1
			if GetDistance(minion) < QRange and (multiple or GetDistance(minion) > 150) then
				if multiple then
					table.insert(hitEnemy, minion)
				else
					hitEnemy = minion
				end
			end
		end 
	end

	if h then
		for index, hero in pairs(heroes) do
			hitCount = hitCount + 1
			if GetDistance(hero) < QRange then
				if multiple then
					table.insert(hitEnemy, hero)
				else
					hitEnemy = hero
				end
			end
		end
	end

	return hitCount, hitEnemy
end

function GetHeroCollision(range, speed, delay, width, altRange, source, destination)
	if not altRange then altRange = range end

	if VIP_USER then
		local col = Collision(altRange, speed * 1000, delay / 1000, width)

		if col then
			local ret, heroes = col:GetHeroCollision(source, destination, 2) -- 2 = HERO_ENEMY
			return ret, heroes
		else
			return false, nil
		end
	else
		return false, nil
	end
end

function GetMinionCollision(range, speed, delay, width, altRange, source, destination)
	if not altRange then altRange = range end

	if VIP_USER then
		local col = Collision(altRange, speed * 1000, delay / 1000, width)

		if col then
			local ret, minions = col:GetMinionCollision(source, destination)
			return ret, minions
		else
			return false, nil
		end
	else
		local minion = GetNonVIPMinionCollision(destination, width)
		if minion then
			return true, minion
		else
			return false, nil
		end
	end
end

function GetNonVIPMinionCollision(predic, width)
	local minions = {}
	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		if minion ~= nil and minion.valid and string.find(minion.name,"Minion_") == 1 and minion.team ~= player.team and minion.dead == false then
			if predic ~= nil and predic.x ~= nil and predic.z ~= nil then
				ex = player.x
				ez = player.z
				tx = predic.x
				tz = predic.z

				if ex ~= nil and ez ~= nil and tx ~= nil and tz ~= nil then
					dx = ex - tx
					dz = ez - tz
					if dx ~= 0 then
						m = dz/dx
						c = ez - m*ex
					end
					mx = minion.x
					mz = minion.z
					distanc = (math.abs(mz - m*mx - c))/(math.sqrt(m*m+1))
					if distanc < width and math.sqrt((tx - ex)*(tx - ex) + (tz - ez)*(tz - ez)) > math.sqrt((tx - mx)*(tx - mx) + (tz - mz)*(tz - mz)) then
						table.insert(minions, minion)
					end
				end
			end
		end
	end

	return minions
end

function CastW(enemy)
	-- W (Ardent Blaze)
	if not enemy then enemy = Target end

	if enemy and not enemy.dead and WReady and ValidTarget(enemy, WRange) then
		if debugMode then PrintChat("Cast W") end
		if IsSACReborn then
			SkillW:Cast(enemy)
		else
			AutoCarry.CastSkillshot(SkillW, enemy)
		end
		UpdateLastSpellTime()
		return true
	end

	return false
end

--[[
function CastEOld()
	-- E (Relentless Pursuit)
	if debugMode then
		-- PrintChat("Mouse Distance: "..GetDistance(mousePos))
	end

	if EReady then
		-- if ( math.abs(mousePos.x - myHero.x) > PursuitMinMouseDiff or math.abs(mousePos.z - myHero.z) > PursuitMinMouseDiff) then
		if ((GetDistance(mousePos) > PursuitMinMouseDiff) and IsEnemyInRange(ERange + RRange)) then
			if debugMode then PrintChat("Cast E") end
			CastSpell(_E, mousePos.x, mousePos.z)
		end
	end
end
--]]

function CastE()
	if EReady then
		if Menu.Combo.SmartE then
			if ((GetDistance(mousePos) > Menu.Advanced.EMinMouseDiff or isSlowed) and IsEnemyInRange(ERange + RRange)) then
				local dashSqr = math.sqrt((mousePos.x - myHero.x)^2+(mousePos.z - myHero.z)^2)
				local dashX = myHero.x + ERange*((mousePos.x - myHero.x)/dashSqr)
				local dashZ = myHero.z + ERange*((mousePos.z - myHero.z)/dashSqr)
				CastSpell(_E, dashX, dashZ)
				UpdateLastSpellTime()
				isSlowed = false
				return true
			end
		else
			CastSpell(_E, mousePos.x, mousePos.z)
			UpdateLastSpellTime()
			isSlowed = false
			return true
		end
	end

	return false
end

function CastR(enemy)
	if not enemy then enemy = Target end

	-- R (The Culling)
	if enemy and not enemy.dead and RReady and ValidTarget(enemy, RRange) and enemy.type == myHero.type then
		if GetDistance(enemy) < Menu.Advanced.UltClose then return false end

		if not IsFiringUlt() then
			if debugMode then PrintChat("Cast R") end
			if not isUltFiring then
				if IsSACReborn then
					SkillR:Cast(enemy)
				else
					AutoCarry.CastSkillshot(SkillR, enemy)
				end
				UpdateLastSpellTime()
				if Menu.Combo.UltLockOn and tick > (lastUltMessage + 2000) then
					lastUltMessage = tick
					PrintFloatText(myHero, 10, "Ultimate Locked On!")
				end
			end

			ultCastTarget = enemy
			ultCastTick = tick

			-- Vector from target -> myHero
			if ultVectorX == nil and ultVectorZ == nil then
				if debugMode and false then PrintChat("update x,z") end
				ultVectorX,y,ultVectorZ = (Vector(myHero) - Vector(ultCastTarget)):normalized():unpack()
			end
		end

		CheckRPosition()
	end
end

function CheckRPosition()
	if (ultCastTick > 0) then
		if debugMode and IsFiringUlt() then burp = "true" else burp = "false" end
		if debugMode and false then PrintChat("Tick: "..tick..", ultCastTick: "..ultCastTick..", RDuration: "..(ultCastTick + (RDuration * 1000))..", IsFiringUlt: "..burp) end
	end

	if Menu.Combo.UltLockOn and IsFiringUlt() then
		MoveMyHeroToRPosition()
	end
end

function MoveMyHeroToRPosition()
	-- Please don't steal this function!
	local tpR = VIP_USER and TargetPredictionVIP(RRange, RSpeed * 1000, RRefresh, RWidth) or TargetPrediction(RRange, RSpeed, RRefresh*1000, RWidth)

	local target = nil
	if ultCastTarget ~= nil then
		target = ultCastTarget
	else
		target = Target
	end

	local predR = tpR:GetPrediction(target)

	if not predR then return end

	local RRangeBuffered = RRange * .98 -- Allow a bit of slippage for targets running away.
	local posX = predR.x + (ultVectorX * RRangeBuffered)
	local posZ = predR.z + (ultVectorZ * RRangeBuffered)

	-- Thank you, Pythagoras
	-- local distanceBetweenPoints = math.sqrt((predR.x - posX)^2 + (predR.z - posZ)^2)
	-- local distanceBetweenPoints = GetDistance(Point(posX, posZ), predR)
	-- local posXRanged = (posX + (posX - predR.x)) / distanceBetweenPoints * RRangeBuffered
	-- local posZRanged = (posZ + (posZ - predR.z)) / distanceBetweenPoints * RRangeBuffered

	local predRPath = LineSegment(Point(predR.x, predR.z), Point(posX, posZ))

	local closePoint = nil
	if predRPath ~= nil then
		closePoint = Point(myHero.x, myHero.z):closestPoint(predRPath)
	else
		if debugMode then PrintChat("predRPath is nil") end
	end

	if debugMode and closePoint ~= nil then
		--	PrintChat("moveto: "..posX..", "..posZ.."... targetat: "..ultCastTarget.x..", "..ultCastTarget.z..", ultVectorX: "..ultVectorX..", ultVectorZ: "..ultVectorZ..", RDuration: "..RDuration)
		--	PrintChat("posXRanged: "..posXRanged..", posZRanged: "..posZRanged..", closePoint: "..closePoint.x..", "..closePoint.y)
		PrintChat("moveto: "..closePoint.x..", "..closePoint.y.."; targetat: "..predR.x..", "..predR.z..", ultVectorX: "..ultVectorX..", ultVectorZ: "..ultVectorZ)
	end

	if debugMode then
		PrintChat("distance: method 1: "..GetDistance(closePoint, predR)..", method 2: "..GetDistance(Point(posX, posZ), predR))
	end

	if predRPath ~= nil and closePoint ~= nil and not IsWall(D3DXVECTOR3(closePoint.x, myHero.y, closePoint.y)) and GetDistance(closePoint, predR) > ERange then
		-- Closest Point Method
		if debugMode then PrintChat("Move: Method 1") end
		myHero:MoveTo(closePoint.x, closePoint.y)
	elseif predR ~= nil and posX ~= nil and posZ ~= nil and not IsWall(D3DXVECTOR3(posX, myHero.y, posZ))
		-- and GetDistance(Point(posX, posZ), predR) > 500
		and GetDistance(Point(posX, posZ), predR) < RRange and GetDistance(Point(posX, posZ), predR) > 100 then
		-- Full Vector Method
		if debugMode then PrintChat("Move: Method 2") end
		if (tick > (lastUltMessage + 2000)) then
			-- lastUltMessage = tick
			-- PrintFloatText(myHero, 10, "Ultimate Manual Mode!")
		end
		myHero:MoveTo(posX, posZ)
--	elseif not IsWall(D3DXVECTOR3(posXRanged, myHero.y, posZRanged)) then
--		-- Last Ditch Method
--		if debugMode then PrintChat("Move: Method 3") end
--		myHero:MoveTo(posXRanged, posZRanged)
	else
		-- Fallback to User Aiming
		if debugMode then PrintChat("Move: Wall") end
		myHero:MoveTo(mousePos.x, mousePos.z)
	end
	-- myHero:MoveTo(posXRanged, posZRanged)
end

function getDistanceBetweenPoints(pointA, pointB)
	-- Thank you, Pythagoras
	local distanceBetweenPoints = math.sqrt((predR.x - posX)^2 + (predR.z - posZ)^2)
end

function IsFiringUlt()
	local tickCount = tick

	-- if (ultCastTick > 0) and (tickCount >= ultCastTick) and (tickCount <= (ultCastTick + (RDuration * 1000))) and not RReady and ultCastTarget ~= nil and not ultCastTarget.dead then
	if (ultCastTick > 0) and (tickCount >= ultCastTick) and isUltFiring and ultCastTarget ~= nil and not ultCastTarget.dead then
		AutoCarry.CanMove = false
		return true
	else
		-- UltFireStop()
		return false
	end
end

function UltFireStop()
	ultCastTick = 0
	ultVectorX = nil
	ultVectorZ = nil
	AutoCarry.CanMove = true
end

function IsEnemyInRange(range)
	for _, enemy in pairs(AutoCarry.EnemyTable) do
		if ValidTarget(enemy, range) and not enemy.dead then
			return true
		end
	end

	return false
end

--[[
function TakingRapidDamage()
	if (tick - healthLastTick) > (Menu.Advanced.HealthTime * 1000) then
		-- Check amount of health lost
		-- PrintChat((myHero.health - healthLastHealth)..", "..(myHero.maxHealth * (Menu.Advanced.HealthPercentage / 100))..", "..((tick - healthLastTick) / 1000))
		if healthLastTick ~= 0 and (myHero.health - healthLastHealth) > (myHero.maxHealth * (Menu.Advanced.HealthPercentage / 100)) then
			-- PrintChat("true")
			return true
		else
			-- Reset counters
			healthLastTick = tick
			healthLastHealth = myHero.health
		end
	end

	return false
end
--]]

function KillSteal()
	-- Will try to perform a killsteam using any spell.
	if not Menu.Misc.Killsteal then return end

	-- Killsteal disabled until Bol update for Lucian

	for _, enemy in pairs(AutoCarry.EnemyTable) do
		if enemy and not enemy.dead then
			-- getDmg broken in BoL for Lucian right now.
			local qDmg = GetDamage(enemy, _Q) -- getDmg("Q", enemy, myHero)
			local wDmg = GetDamage(enemy, _W) -- getDmg("W", enemy, myHero)
			-- local rDmg = 50 -- getDmg("R", enemy, myHero)

			if QReady and ValidTarget(enemy, QRange) and enemy.health < qDmg then
				if debugMode then PrintChat("Cast Q KS") end
				CastQ(enemy)
			elseif WReady and ValidTarget(enemy, WRange) and enemy.health < wDmg then
				if debugMode then PrintChat("Cast W KS") end
				CastW(enemy)
			elseif QReady and WReady and ValidTarget(enemy, QRange) and ValidTarget(enemy, WRange) and enemy.health < (qDmg + wDmg) then
				if debugMode then PrintChat("Cast Q + W KS") end
				CastW(enemy)
				if not enemy.dead then
					CastQ(enemy)
				end
			-- Ultimate is not a viable killsteal since the number of individual shots to hit can not be pre-determined.
			-- elseif Menu.Misc.KillstealUlt and RReady and ValidTarget(enemy, RRange) and (enemy.health + 20) < rDmg then
			-- 	if debugMode then PrintChat("Cast R KS") end
			--	CastR(enemy)
			end

		end
	end
end

function GetDamage(enemy, spell)
	if spell == _Q then
		return myHero:CalcDamage(enemy, ((40*(myHero:GetSpellData(_Q).level-1) + 80) + (((.15 * (myHero:GetSpellData(_Q).level-1)) + .60) * myHero.addDamage)))
	elseif spell == _W then
		return myHero:CalcMagicDamage(enemy, ((40*(myHero:GetSpellData(_Q).level-1) + 60) + (.90 * myHero.ap) + (.60 * myHero.addDamage)))
	end
end

function _G.PluginOnWndMsg(msg,key)
	Target = GetTarget()
	if Target ~= nil and Menu.Advanced.ProMode then
		-- if msg == KEY_DOWN and key == KeyQ then CastQ() end
		if msg == KEY_DOWN and key == KeyW then CastW() end
		if msg == KEY_DOWN and key == KeyE then CastE() end
		if msg == KEY_DOWN and key == KeyR then CastR() end
		if msg == KEY_DOWN and key == KeyTest and debugMode then
			if ultVectorX == nil and ultVectorZ == nil then
				ultCastTarget = Target
				ultVectorX,y,ultVectorZ = (Vector(myHero) - Vector(ultCastTarget)):normalized():unpack()
			end
			MoveMyHeroToRPosition()
		end
		
		if msg == KEY_UP and key == KeyTest and debugMode then
			ultVectorX = nil
			ultVectorZ = nil
		end
	end
end

-- Draw
function _G.PluginOnDraw()
	-- if Target ~= nil and not Target.dead and QReady and ValidTarget(Target, QMaxRange) then
	if Target ~= nil and not Target.dead and (AutoCarry.MainMenu.AutoCarry or AutoCarry.MainMenu.MixedMode) then
		DrawArrowsToPos(myHero, Target)

		if Menu.Draw.DrawTargetLine then
			local x1, y1, OnScreen1 = get2DFrom3D(myHero.x, myHero.y, myHero.z)
			local x2, y2, OnScreen2 = get2DFrom3D(Target.x, Target.y, Target.z)
			DrawLine(x1, y1, x2, y2, 3, RGB(255,255,0))
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
			DrawCircle(myHero.x, myHero.y, myHero.z, QRange, 0x0099CC) -- Blue
		end

		if Menu.Draw.DrawW and WReady and ((Menu.Draw.DrawFurthest and farSpell and farSpell == WRange) or not Menu.Draw.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, WRange, 0xFFFF00) -- Yellow
		end
		
		if Menu.Draw.DrawE and EReady and ((Menu.Draw.DrawFurthest and farSpell and farSpell == ERange) or not Menu.Draw.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, ERange, 0x00FF00) -- Green
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

	if Menu.Draw.QAoEDraw and QDrawLeft and QDrawRight and QDrawTop then
        DrawLine(QDrawLeft.x, QDrawLeft.y, QDrawRight.x, QDrawRight.y, 1, RGB(255,153,153))
        DrawLine(QDrawLeft.x, QDrawLeft.y, QDrawTop.x, QDrawTop.y, 1, RGB(255,153,153))
        DrawLine(QDrawRight.x, QDrawRight.y, QDrawTop.x, QDrawTop.y, 1, RGB(255,153,153))
        QDrawLeft, QDrawRight, QDrawTop = nil, nil, nil
	end

	DrawKillable()

	if Menu.Draw.DrawDmg and useDrawDamageLib then
		drawDamage()
	end

	-- if JungleKill ~= nil then
	--	DrawCircle(JungleKill.x, JungleKill.y, JungleKill.z, 1300, ARGB(255,0,255,0))
	-- end

	if Menu.Draw.DrawMousePos then
		local circleSize = 50
		local trueRange = getTrueRange()
		if GetDistance(mousePos) < trueRange then
			local myPosV = Vector(myHero.x, myHero.z)
			local mousePosV = Vector(mousePos.x, mousePos.z)
			local distanceV = (Vector(mousePosV) - Vector(myPosV)):normalized() * (trueRange - circleSize - 5)
			local finalV = myPosV + distanceV

			local x1, y1, OnScreen1 = get2DFrom3D(myHero.x, myHero.y, myHero.z)
			local x2, y2, OnScreen2 = get2DFrom3D(finalV.x, myHero.y, finalV.y)
			DrawLine(x1, y1, x2, y2, 1, RGB(255,255,204))
			DrawCircle(finalV.x, myHero.y, finalV.y, circleSize - 10, 0x00FF00)
			DrawCircle(finalV.x, myHero.y, finalV.y, circleSize, 0xFFFF00) 
		end
	end
end

function FindFurthestReadySpell()
	local farSpell = nil

	if Menu.Draw.DrawQ and QReady then farSpell = QRange end
	if Menu.Draw.DrawW and WReady and (not farSpell or WRange > farSpell) then farSpell = WRange end
	if Menu.Draw.DrawE and EReady and (not farSpell or ERange > farSpell) then farSpell = ERange end
	if Menu.Draw.DrawR and RReady and (not farSpell or RRange > farSpell) then farSpell = RRange end

	return farSpell
end

function getTrueRange()
    return myHero.range + GetDistance(myHero.minBBox)
end

function DrawArrowsToPos(pos1, pos2)
	if pos1 and pos2 then
		startVector = D3DXVECTOR3(pos1.x, pos1.y, pos1.z)
		endVector = D3DXVECTOR3(pos2.x, pos2.y, pos2.z)
		-- directionVector = (endVector-startVector):normalized()
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

--[[
function test1(skill, enemy)
	local tp = VIP_USER and TargetPredictionVIP(8000, skill.speed*1000, 0, RWidth) or TargetPrediction(skill.range, skill.speed, 0, skill.width)
	-- local tp = TargetPredictionVIP(8000, math.huge, skill.delay, RWidth)
	--local tp = TargetPredictionVIP(8000, math.huge, 0.535, 75)
	GetEnemyPrediction(enemy, tp)
end
--]]

function GetEnemyPrediction(enemy, tp)
	-- local tpQ = VIP_USER and TargetPredictionVIP(QRange, QSpeed * 1000, QDelay, RWidth) or TargetPrediction(QRange, QSpeed, QDelay * 1000, QWidth)

	if not enemy or enemy.dead or not tp or not QReady then return nil end

	-- local predPos, p2, p3 = tp:GetPrediction(enemy)
	local predPos = tp:GetPrediction(enemy)
	
	if not predPos then return nil end

	GetAngle(enemy, predPos)
end

-- Angle Functions
function IsGoodAngle(angleDiff, variance)
    local direction = Directionality(angleDiff, variance)
    
    if direction == angle_towards or direction == angle_away then
        return true
    else
        return false
    end
end

function GetAngle(enemy, predPos)
	-- ultVectorX,y,ultVectorZ = (Vector(myHero) - Vector(ultCastTarget)):normalized():unpack()

		-- v1 = Vector(predPos.x - myHero.x, predPos.z - myHero.z):normalized()
	-- v2 = Vector((Vector(predPos) - Vector(enemy)):normalized())
		-- v2 = Vector(predPos.x - enemy.x, predPos.z - enemy.z):normalized()
	-- v2 = Vector(coneTargetsTable[j].x-player.x , coneTargetsTable[j].z-player.z)

	v1 = (Vector(predPos) - Vector(myHero)):normalized()

	if predPos.x == enemy.x and predPos.z == enemy.z then
		-- Enemy is standing still.
		return false
	end
	

	v2 = (Vector(predPos) - Vector(enemy)):normalized()
-- PrintChat("a"..predPos.x.."!"..enemy.x.."!"..v2.x.."!"..v2.z)
--	if not v2 or v2 == -1 then
--		return false
--	end
	-- shootTheta = sign(v1.z)*90-math.deg(math.atan2(v1.z, v1.x))
	-- enemyTheta = sign(v2.z)*90-math.deg(math.atan2(v2.z, v2.x))
	-- angle = enemyTheta-shootTheta

	shootTheta = math.deg(math.atan2(v1.z, v1.x))
	enemyTheta = math.deg(math.atan2(v2.z, v2.x))

	if shootTheta < 0 then
		shootTheta = shootTheta + 360  
	end

	if enemyTheta < 0 then
		enemyTheta = enemyTheta + 360
	end

	angleDiff = math.abs(enemyTheta - shootTheta)

	local angleView = ""
	local angleVariance = 30

	angleView = Directionality(angleDiff, Menu.Advanced.MaxSpellAngle)
	
	if angleView ~= nil then
		-- PrintChat("v1: "..shootTheta..", v2: "..enemyTheta..", angle: "..angle..", angleView: "..angleView..", predx: "..predPos.x..", predz: "..predPos.z..", hittime: "..p2..", posx: "..p3.x..", posz: "..p3.z)
		-- PrintChat("v1: "..shootTheta..", v2: "..enemyTheta..", angleDiff: "..angleDiff..", angleView: "..angleView..", predx: "..predPos.x..", predz: "..predPos.z)
	end

	return angleDiff
end

function Directionality(angleDiff, variance)
    if betweenRounded(angleDiff, variance, angle_away) then
        return angle_away
    elseif betweenRounded(angleDiff, variance, angle_towards) then
        return angle_towards
    elseif betweenRounded(angleDiff, variance, angle_parallel_towards) then
        return angle_parallel_towards
    elseif betweenRounded(angleDiff, variance, angle_parallel_away) then
        return angle_parallel_away
    else
        return angle_unknown
    end
end

function betweenRounded(angleDiff, variance, angle) -- diff = known angle between two people, variance is allowed slippage, angle = goal you want if true
    low = angle - variance
    high = angle + variance

    if low <= 0 then
        return (low + 360 <= angleDiff or angleDiff <= high)
    else
        return (low <= angleDiff and angleDiff <= high)
    end
end

function sign(x)
    if x > 0 then return 1
    elseif x < 0 then return -1
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

function IsMyManaLow()
	if myHero.mana < (myHero.maxMana * ( Menu.Advanced.ManaManager / 100)) then
		return true
	else
		return false
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
scriptTrialVersion = "g104"
GetAsyncWebResult("bolscripts.com","scriptauth/script_date_check.php?s=lucian&v="..version, function(result) webTrialVersion = result end)

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

--UPDATEURL=https://bitbucket.org/KainBoL/bol/raw/master/Common/SidasAutoCarryPlugin%20-%20Lucian.lua
--HASH=A53C73B454F5FC3BE00663EE2958F848
