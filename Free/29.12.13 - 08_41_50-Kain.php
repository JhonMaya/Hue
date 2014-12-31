<?php exit() ?>--by Kain 97.90.203.108
--[[
 
        Auto Carry Plugin - Heimerdonger Reborn PROdiction Edition
		Author: Kain
		Version: See version variable below.
		Copyright 2013

		Dependency: Sida's Auto Carry
 
		How to install:
			Make sure you already have AutoCarry installed.
			Name the script EXACTLY "SidasAutoCarryPlugin - Heimerdinger.lua" without the quotes.
			Place the plugin in BoL/Scripts/Common folder.

		Features:

		
		
		Version History:
				Version: 1.0:
				Release

		To Do: 
--]]

if myHero.charName ~= "Heimerdinger" then return end

-- Check to see if user failed to read the forum...
if VIP_USER then
	if FileExist(SCRIPT_PATH..'Common/Collision.lua') then
		require "Collision"

		if type(Collision) ~= "userdata" then
			PrintChat("Your version of Collision.lua is incorrect. Please install v1.1.1 or later in Common folder.")
			return
		else
			assert(type(Collision.GetMinionCollision) == "function")
		end
	else
		PrintChat("Please install Collision.lua v1.1.1 or later in Common folder.")
		return
	end

	if FileExist(SCRIPT_PATH..'Common/2DGeometry.lua') then
		PrintChat("Please delete 2DGeometry.lua from your Common folder.")
	end
end

function Vars()
	curVersion = 1.0

	if AutoCarry.Skills then IsSACReborn = true else IsSACReborn = false end

	-- Disable SAC Reborn's skills. Ours are better.
	if IsSACReborn then
		AutoCarry.Skills:DisableAll()
	end
      
	KeyW = string.byte("W")
	KeyE = string.byte("E")
        
	QRange, WRange, ERange, RRange = 450, 1350, 925, 1000
	QSpeed, WSpeed, ESpeed, RSpeed = 1.2, 1.2, 750, 1.2
	QDelay, WDelay, EDelay, RDelay = .25, 0.2, 0.5, .25
	QWidth, WWidth, EWidth, RWidth = 1100, 85, 80, 50
        
	if IsSACReborn then
		SkillQ = AutoCarry.Skills:NewSkill(false, _Q, QRange, "Donger Turret", AutoCarry.SPELL_CIRCLE, 0, false, false, QSpeed, QDelay, QWidth, false)
		SkillW = AutoCarry.Skills:NewSkill(false, _W, WRange, "Hextech Rockets", AutoCarry.SPELL_LINEAR_COL, 0, false, false, WSpeed, WDelay, WWidth, true)
		SkillE = AutoCarry.Skills:NewSkill(false, _E, ERange, "Grenade", AutoCarry.SPELL_LINEAR, 0, false, false, ESpeed, EDelay, EWidth, false)
		SkillR = AutoCarry.Skills:NewSkill(true, _R, RRange, "Upgrade", AutoCarry.SPELL_SELF, 0, false, false, RSpeed, RDelay, RWidth, false)
	else
		SkillQ = {spellKey = _Q, range = QRange, speed = QSpeed, delay = QDelay, width = QWidth, minions = false }
		SkillW = {spellKey = _W, range = WRange, speed = WSpeed, delay = WDelay, width = WWidth, minions = true }
		SkillE = {spellKey = _E, range = ERange, speed = ESpeed, delay = EDelay, width = EWidth, minions = false }
		SkillR = {spellKey = _R, range = RRange, speed = RSpeed, delay = RDelay, width = RWidth, minions = false }
	end

	HeimerUpgradeActive = false
	
	if VIP_USER then
		AdvancedCallback:bind('OnGainBuff', function(unit, buff) OnGainBuff(unit, buff) end)
		AdvancedCallback:bind('OnLoseBuff', function(unit, buff) OnLoseBuff(unit, buff) end)
	end
  
  -- Items
	ignite = nil
	DFGSlot, HXGSlot, BWCSlot, SheenSlot, TrinitySlot, LichBaneSlot = nil, nil, nil, nil, nil, nil
	QReady, WReady, EReady, RReady, DFGReady, HXGReady, BWCReady, IReady = false, false, false, false, false, false, false, false

	wTimer = 0
	eTimer = 0
	mainTimer = 0 -- delay between spells
			
	floattext = {"Harass him","Fight him","Kill him","Murder him"} -- text assigned to enemys

	killable = {} -- our enemy array where stored if people are killable
	waittxt = {} -- prevents UI lags, all credits to Dekaron

	for i=1, heroManager.iCount do waittxt[i] = i*3 end -- All credits to Dekaron

	tick = nil

	Target = nil

	debugMode = false

	
	if VIP_USER then
		if IsSACReborn then
			PrintChat("<font color='#CCCCCC'> >> Kain's Heimerdonger - PROdiction 2.0 <</font>")
		else
			PrintChat("<font color='#CCCCCC'> >> Kain's Heimerdonger - VIP Prediction <</font>")
		end
	else
		PrintChat("<font color='#CCCCCC'> >> Kain's Heimerdonger - Free Prediction <</font>")
	end
end

-- Turret Config

local highEnabled         = nil -- Enable High Priority MuTurret

local medEnabled          = nil -- Enable Medium Priority MuTurret

local lowEnabled          = nil -- Enable Low Priority MuTurret

local blueEnabled         = nil -- Enable Blue Team MuTurret (in and around blue jungle)

local purpEnabled         = nil -- Enable Purple Team MuTurret (in and around purple jungle)

 

local Turretpots     = nil

 

local showLocationsInRange = 3000 -- When you press R, locations in this range will be shown

local showClose = true -- Show Turret locations that are close to you

local showCloseRange = 800

 
-- Keep track of settings changes

local snapTurret = nil

local autoTurretHigh = nil

local autoTurretMedium = nil

 

-- Main


function Menu()
	AutoCarry.PluginMenu:addParam("sep", "----- "..myHero.charName.." by Kain: v"..curVersion.." -----", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("space", "", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("sep", "----- [ Combo ] -----", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("ComboQ", "Use Q ", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("ComboW", "Use W", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("ComboE", "Use E ", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("sep", "----- [ Harass ] -----", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("HarassW", "Use W (Hextech Rockets)", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("HarassE", "Use E (Grenade)", SCRIPT_PARAM_ONOFF, true)
  AutoCarry.PluginMenu:addParam("sep", "----- [ Turret Advisor ] -----", SCRIPT_PARAM_INFO, "")
  AutoCarry.PluginMenu:addParam("DrawTurret", "Turret Placement Helper", SCRIPT_PARAM_ONOFF, true)
  AutoCarry.PluginMenu:addParam("SnapTurret", "Snap Turret into place", SCRIPT_PARAM_ONOFF, true)
  AutoCarry.PluginMenu:addParam("AutoTurretsHigh", "Auto-Turret high priority locations", SCRIPT_PARAM_ONOFF, true)
  AutoCarry.PluginMenu:addParam("AutoTurretsMedium", "Auto-Turret medium priority locations", SCRIPT_PARAM_ONOFF, false)
  AutoCarry.PluginMenu:addParam("Draw", "Draw range circles", SCRIPT_PARAM_ONOFF, true)
  
--[[
	-- Farming
	AutoCarry.PluginMenu:addParam("sep", "----- [ Farming ] -----", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("FarmUseW", "Use W", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("FarmMinMana", "Farm if my mana > %", SCRIPT_PARAM_SLICE, 35, 0, 100, 0)
	AutoCarry.PluginMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	
	-- Draw Death Timer
	AutoCarry.PluginMenu:addParam("sep", "["..myHero.charName.." Auto Carry: Draw]", SCRIPT_PARAM_INFO, "")
    AutoCarry.PluginMenu:addParam("DrawText", "Draw Text", SCRIPT_PARAM_ONOFF, true)
--]]
	-- Extras Menu
	ExtraConfig = scriptConfig("Sida's Auto Carry Plugin: "..myHero.charName..": Extras", myHero.charName)
	ExtraConfig:addParam("sep", "----- [ Misc ] -----", SCRIPT_PARAM_INFO, "")
	ExtraConfig:addParam("ProMode", "Use Auto QWER Keys", SCRIPT_PARAM_ONOFF, true)
	ExtraConfig:addParam("SmartQ", "Q to Mouse Pos.", SCRIPT_PARAM_ONOFF, true)
	ExtraConfig:addParam("ManaManager", "Mana Manager %", SCRIPT_PARAM_SLICE, 40, 0, 100, 2)
	ExtraConfig:addParam("sep", "----- [ Draw ] -----", SCRIPT_PARAM_INFO, "")
	ExtraConfig:addParam("DrawKillable", "Draw Killable Enemies", SCRIPT_PARAM_ONOFF, true)
	ExtraConfig:addParam("DrawTargetArrow", "Draw Arrow to Target", SCRIPT_PARAM_ONOFF, false)
	ExtraConfig:addParam("DisableDrawCircles", "Disable Draw", SCRIPT_PARAM_ONOFF, false)
	ExtraConfig:addParam("DrawFurthest", "Draw Furthest Spell Available", SCRIPT_PARAM_ONOFF, true)
	ExtraConfig:addParam("DrawQ", "Draw Turret", SCRIPT_PARAM_ONOFF, true)
	ExtraConfig:addParam("DrawW", "Draw Micro-Rockets", SCRIPT_PARAM_ONOFF, true)
	ExtraConfig:addParam("DrawE", "Draw Grenade", SCRIPT_PARAM_ONOFF, true)
end

function TurretConfig()

        highEnabled = AutoCarry.PluginMenu.DrawTurrets

        medEnabled      = AutoCarry.PluginMenu.DrawTurrets

        lowEnabled      = AutoCarry.PluginMenu.DrawTurrets

        blueEnabled = AutoCarry.PluginMenu.DrawTurrets

        purpEnabled = AutoCarry.PluginMenu.DrawTurrets

end

function _G.PluginOnLoad()
	if IsSACReborn then
		AutoCarry.Crosshair:SetSkillCrosshairRange(1100)
	else
		AutoCarry.SkillsCrosshair.range = 1100
	end

	Vars()
	Menu()
  TurretConfig()
  InitializeTurrets()
end

function _G.PluginOnTick()
	Target = GetTarget()
	tick = GetTickCount()

	SpellCheck()

	if Target then
		if AutoCarry.MainMenu.AutoCarry then
			Combo()
			if HeimerUpgradeActive and Menu.autocarry.CastR then CastR() end
			-- CastW()
			-- if upgradeCheck() then return end
			-- CastE()
			-- CastQ()
		end
	end

	if (AutoCarry.MainMenu.MixedMode or AutoCarry.MainMenu.LaneClear) and not IsMyManaLow() then
		if AutoCarry.PluginMenu.HarassE then CastE() end
		if AutoCarry.PluginMenu.HarassW then CastW() end
	end
end

function GetTarget()
	if IsSACReborn then
		return AutoCarry.Crosshair:GetTarget()
	else
		return AutoCarry.GetAttackTarget()
	end
end

function Combo()
	local calcenemy = 1

	if not Target or not ValidTarget(Target) then return true end

	for i=1, heroManager.iCount do
    	local Unit = heroManager:GetHero(i)
    	if Unit.charName == Target.charName then
    		calcenemy = i
        
                -- Turrets
        PlaceAutoTurrets()
        
    	end
   	end
    
    function CheckSettingsChange()

        -- Re-Initalize Turrets settings if user changes them.

        if snapTurrets == nil or snapTurrets ~= AutoCarry.PluginMenu.SnapTurrets

                or autoTurretsHigh == nil or autoTurretsHigh ~= AutoCarry.PluginMenu.AutoTurretsHigh

                or autoTurretsMedium == nil or autoTurretsMedium ~= AutoCarry.PluginMenu.AutoTurretsMedium then

                snapTurrets = AutoCarry.PluginMenu.SnapTurrets

                autoTurretsHigh = AutoCarry.PluginMenu.AutoTurretsHigh

                autoTurretsMedium = AutoCarry.PluginMenu.AutoTurretsMedium

                InitializeTurrets()
      end
        end
   	
	if IGNITEReady and killable[calcenemy] == 3 then CastSpell(IGNITESlot, Target) end

	if AutoCarry.PluginMenu.UseItems then
		if BWCReady and (killable[calcenemy] == 2 or killable[calcenemy] == 3) then CastSpell(BWCSlot, Target) end
		if RUINEDKINGReady and (killable[calcenemy] == 2 or killable[calcenemy] == 3) then CastSpell(RUINEDKINGSlot, Target) end
		if RANDUINSReady then CastSpell(RANDUINSSlot) end
    if dfg == 1 then
					if DFGReady then CastSpell(DFGSlot, Target) end
				end
	end

	if AutoCarry.PluginMenu.ComboW then CastW() end
	if AutoCarry.PluginMenu.ComboE then CastE() end
	if AutoCarry.PluginMenu.ComboE then CastQ() end

	if RReady and AutoCarry.PluginMenu.ComboR and GetDistance(Target) <= AutoCarry.PluginMenu.WMaxDistance and ((getDmg("W", Target, myHero) >= Target.health + 20) or killable[calcenemy] == 2 or killable[calcenemy] == 3) then
		CastR()
	end
end
function CastQ(enemy)
	if not enemy then enemy = Target end

	if QReady and IsValid(enemy, QRange) then
		if IsSACReborn then
			SkillQ:Cast(enemy)
		else
			AutoCarry.CastSkill(SkillQ, enemy)
		end
	end
end
function CastW(enemy)
	if not enemy then enemy = Target end

	if WReady and IsValid(enemy, WRange) then
		if IsSACReborn then
			SkillW:Cast(enemy)
		else
			AutoCarry.CastSkillshot(SkillW, enemy)
		end
	end
end

function CastE()
	if not enemy then enemy = Target end

	if EReady and ValidTarget(enemy, ERange) then 
		if IsSACReborn then

			SkillE:Cast(enemy)
		else
			AutoCarry.CastSkillshot(SkillE, enemy)
		end
	end
end
function IsValid(enemy, dist)
	if enemy and enemy.valid and not enemy.dead and enemy.bTargetable and ValidTarget(enemy, dist) then
		return true
	else
		return false
	end
end


function PlaceAutoTurrets()

        if not Turretspots then return end

 

        for i,group in pairs(Turretspots) do

                for x, Turretspot in pairs(group.Locations) do

                        if group.Enabled and group.Auto and GetDistance(Turretspot) <= 250 and not TurretExists(Turretspot) then

                                CastSpell(SkillQ.spellKey, Turretspot.x, Turretspot.z)

                        end

                end

        end

end

 

function _G.PluginOnDraw()
-- if 1 == 1 then return end
	if Target and not Target.dead and ExtraConfig.DrawTargetArrow and (AutoCarry.MainMenu.AutoCarry or AutoCarry.MainMenu.MixedMode) then
		DrawArrowsToPos(myHero, Target)
	end

	if IsTickReady(75) then DMGCalculation() end
	DrawKillable()
	DrawRanges()

 -- Draw Turrets

        if AutoCarry.PluginMenu.DrawTurrets and TurretSpots then

                for i,group in pairs(TurretSpots) do

                        if group.Enabled == true then

                                if drawTurretSpots then

                                        for x, TurretSpot in pairs(group.Locations) do

                                                if GetDistance(TurretSpot) < showLocationsInRange then

                                                        if GetDistance(TurretSpot, mousePos) <= 250 then

                                                                TurretColour = 0xFFFFFF

                                                        else

                                                                TurretColour = group.Colour

                                                        end

                                                        drawTurretCircles(TurretSpot.x, TurretSpot.y, TurretSpot.z,TurretColour)

                                                end

                                        end

                                elseif showClose then

                                        for x, TurretSpot in pairs(group.Locations) do

                                                if GetDistance(TurretSpot) <= showCloseRange then

                                                        if GetDistance(TurretSpot, mousePos) <= 250 then

                                                                TurretColour = 0xFFFFFF

                                                        else

                                                                TurretColour = group.Colour

                                                        end

                                                        drawTurretCircles(TurretSpot.x, TurretSpot.y, TurretSpot.z,TurretColour)

                                                end

                                        end

                                end

                        end

                end

        end

end

 -- Turrets Main

function InitializeTurrets()

        red, yellow, green, blue, purple = 0x990000, 0x993300, 0x00FF00, 0x000099, 0x660066

 

        Turretspots = {

                -- High priority for both sides

                HighPriority =  {

                                                        Locations = {

                                                                                        { x = 3316.20,  y = -74.06, z = 9334.85},

                                                                                        { x = 4288.76,  y = -71.71, z = 9902.76},

                                                                                        { x = 3981.86,  y = 39.54,      z = 11603.55},

                                                                                        { x = 6435.51,  y = 47.51,      z = 9076.02},

                                                                                        { x = 9577.91,  y = 45.97,      z = 6634.53},

                                                                                        { x = 7635.25,  y = 45.09,      z = 5126.81},

                                                                                        { x = 10731.51, y = -30.77, z = 5287.01},

                                                                                        { x = 9662.24,  y = -70.79, z = 4536.15},

                                                                                        { x = 10080.45, y = 44.48,      z = 2829.56}  

                                                                                },

                                                        Colour = red,

                                                        Enabled = highEnabled,

                                                        Auto = AutoCarry.PluginMenu.AutoTurretsHigh,

                                                        Snap = false

                                                },

        -- Medium priority for both sides

                MediumPriority ={

                                                        Locations = {

                                                                                        { x = 3283.18,  y = -69.64, z = 10975.15},

                                                                                        { x = 2595.85,  y = -74.00, z = 11044.66},

                                                                                        { x = 2524.10,  y = 23.36,      z = 11912.28},

                                                                                        { x = 4347.64,  y = 43.34,      z = 7796.28},

                                                                                        { x = 6093.20,  y = -67.90, z = 8067.45},

                                                                                        { x = 7960.99,  y = -73.41, z = 6233.09},

                                                                                        { x = 10652.57, y = -58.96, z = 3507.64},

                                                                                        { x = 11460.14, y = -63.94, z = 3544.83},

                                                                                        { x = 11401.81, y = -11.72, z = 2626.61}  

                                                                                },

                                                        Colour = yellow,

                                                        Enabled = medEnabled,

                                                        Auto = AutoCarry.PluginMenu.AutoTurretsMedium,

                                                        Snap = false

                                                },

        -- Low priority/situational for both sides

                LowPriority =   {

                                                        Locations = {

                                                                                        { x = 1346.10,  y = 26.56,      z = 11064.81},

                                                                                        { x = 705.87,   y = 26.93,      z = 11359.88},

                                                                                        { x = 762.80,   y = 26.15,      z = 12210.61},

                                                                                        { x = 1355.53,  y = 24.13,      z = 12936.99},

                                                                                        { x = 1926.92,  y = 25.14,      z = 11567.44},

                                                                                        { x = 1752.22,  y = 24.02,      z = 13176.95},

                                                                                        { x = 2512.96,  y = 21.74,      z = 13524.44},

                                                                                        { x = 3577.42,  y = 25.27,      z = 12429.88},

                                                                                        { x = 5246.01,  y = 30.91,      z = 12508.33},

                                                                                        { x = 5549.60,  y = 42.94,      z = 10917.27},

                                                                                        { x = 6552.56,  y = 47.09,      z = 9688.99},

                                                                                        { x = 5806.41,  y = 46.01,      z = 9918.99},

                                                                                        { x = 7112.27,  y = 46.86,      z = 8443.55},

                                                                                        { x = 4896.10,  y = -72.08, z = 8964.81},

                                                                                        { x = 3096.10,  y = 45.41,      z = 8164.81},

                                                                                        { x = 2390.53,  y = 46.57,      z = 5232.34},

                                                                                        { x = 4358.81,  y = 45.83,      z = 5834.64},

                                                                                        { x = 5746.10,  y = 42.52,      z = 4864.81},

                                                                                        { x = 6307.66,  y = 46.07,      z = 7165.92},

                                                                                        { x = 5443.82,  y = 45.64,      z = 7110.85},

                                                                                        { x = 5153.75,  y = 45.41,      z = 3358.76},

                                                                                        { x = 6876.07,  y = 46.44,      z = 5897.48},

                                                                                        { x = 6881.30,  y = 46.08,      z = 6555.85},

                                                                                        { x = 8555.10,  y = 46.36,      z = 7267.04},

                                                                                        { x = 7946.10,  y = 44.19,      z = 7214.81},

                                                                                        { x = 9088.99,  y = -73.12, z = 5441.11},

                                                                                        { x = 7687.96,  y = 46.12,      z = 5203.08},

                                                                                        { x = 8559.97,  y = 47.97,      z = 3477.87},

                                                                                        { x = 8841.04,  y = 52.28,      z = 1944.09},

                                                                                        { x = 10582.93, y = 43.25,      z = 1707.35},

                                                                                        { x = 11046.10, y = 43.26,      z = 964.81},

                                                                                        { x = 11682.20, y = 43.40,      z = 1061.03},

                                                                                        { x = 12420.51, y = 46.87,      z = 1532.34},

                                                                                        { x = 12819.32, y = 45.74,      z = 1931.32},

                                                                                        { x = 13275.52, y = 45.38,      z = 2873.69},

                                                                                        { x = 11978.71, y = 45.49,      z = 2914.69},

                                                                                        { x = 13379.36, y = 45.37,      z = 3499.62},

                                                                                        { x = 12818.08, y = 45.38,      z = 3625.44},

                                                                                        { x = 10985.17, y = 45.69,      z = 6305.81},

                                                                                        { x = 11580.80, y = 41.26,      z = 9214.09},

                                                                                        { x = 9574.88,  y = 44.40,      z = 8679.65},

                                                                                        { x = 8359.96,  y = 44.37,      z = 9595.58},

                                                                                        { x = 8927.12,  y = 48.17,      z = 11175.70}  

                                                                                },

                                                        Colour = green,

                                                        Enabled = lowEnabled,

                                                        Auto = false,

                                                        Snap = false

                                                },

        -- blue team areas

                BlueOnly = {

                                                Locations = {

                                                                                { x = 2112.87, y = 43.81, z = 7047.48},

                                                                                { x = 2646.25, y = 45.84, z = 7545.78},

                                                                                { x = 1926.95, y = 44.83, z = 9515.71},

                                                                                { x = 4239.97, y = 44.40, z = 7132.02},

                                                                                { x = 6149.34, y = 42.51, z = 4481.88},

                                                                                { x = 6630.28, y = 46.56, z = 2836.88},

                                                                                { x = 7687.62, y = 45.54, z = 3210.98},

                                                                                { x = 7050.22, y = 46.46, z = 2351.33}  

                                                                        },

                                                Colour = blue,

                                                Enabled = blueEnabled,

                                                Auto = false,

                                                Snap = false

                                        },

        -- purple team areas

                PurpleOnly =    {

                                                Locations = {

                                                                                { x = 7466.52, y = 41.54, z = 11720.22},

                                                                                { x = 6945.85, y = 43.53, z = 11901.30},

                                                                                { x = 6636.28, y = 45.03, z = 11079.65},

                                                                                { x = 7878.53, y = 43.83, z = 10042.65},

                                                                                { x = 9701.57, y = 45.72, z = 7298.22},

                                                                                { x = 11358.86, y = 45.71, z = 6872.10},

                                                                                { x = 11946.10, y = 45.80, z = 7414.81},

                                                                                { x = 12169.52, y = 44.03, z = 4858.85}  

                                                                        },

                                                Colour = purple,

                                                Enabled = purpEnabled,

                                                Auto = false,

                                                Snap = false

                                        }

        }

end

 

    DrawTurretspots = false

 
function drawTurretCircles(x,y,z,colour)

        DrawCircle(x, y, z, 28, colour)

        DrawCircle(x, y, z, 29, colour)

        DrawCircle(x, y, z, 30, colour)

        DrawCircle(x, y, z, 31, colour)

        DrawCircle(x, y, z, 32, colour)

        DrawCircle(x, y, z, 250, colour)

        if colour == red or colour == blue

                or colour == purple or colour == yellow then

                DrawCircle(x, y, z, 251, colour)

                DrawCircle(x, y, z, 252, colour)

                DrawCircle(x, y, z, 253, colour)

                DrawCircle(x, y, z, 254, colour)

        end

end

 

function TurretExists(Turretspot)

        for i=1, objManager.maxObjects do

        local obj = objManager:getObject(i)

                if obj ~= nil and obj.name:find("Noxious Trap") then

                        if GetDistance(obj) <= 260 then

                                return true

                        end

                end

        end    

        return false

end

 

function DrawKillable()
	if ExtraConfig.DrawKillable and not myHero.dead then
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

				 if waittxt[i] == 1 and killable[i] ~= nil and killable[i] ~= 0 and killable[i] ~= 1 then
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
	if not ExtraConfig.DisableDrawCircles and not myHero.dead then
		local farSpell = FindFurthestReadySpell()

		-- DrawCircle(myHero.x, myHero.y, myHero.z, getTrueRange(), 0x808080) -- Gray

		if ExtraConfig.DrawQ and QReady and ((ExtraConfig.DrawFurthest and farSpell and farSpell == SkillQ.Range) or not ExtraConfig.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, SkillQ.Range, 0x0099CC) -- Blue
		end
		if ExtraConfig.DrawW and WReady and ((ExtraConfig.DrawFurthest and farSpell and farSpell == SkillW.Range) or not ExtraConfig.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, SkillW.Range, 0xFFFF00) -- Yellow
		end

		if ExtraConfig.DrawE and EReady and ((ExtraConfig.DrawFurthest and farSpell and farSpell == SkillE.Range) or not ExtraConfig.DrawFurthest) then
			DrawCircle(myHero.x, myHero.y, myHero.z, SkillE.Range, 0x00FF00) -- Green
		end

		Target = AutoCarry.GetAttackTarget()
		if Target ~= nil then
			for j=0, 10 do
				DrawCircle(Target.x, Target.y, Target.z, 40 + j*1.5, 0x00FF00) -- Green
			end
		end
	end
end

function DMGCalculation()
	for i=1, heroManager.iCount do
        local Unit = heroManager:GetHero(i)
        if ValidTarget(Unit) then
        	local RUINEDKINGDamage, IGNITEDamage, BWCDamage = 0, 0, 0
        local WDamage = getDmg("W", Unit, myHero)
			local EDamage = getDmg("E", Unit, myHero)
			local HITDamage = getDmg("AD", Unit, myHero)
			local IGNITEDamage = (IGNITESlot and getDmg("IGNITE", Unit, myHero) or 0)
			local BWCDamage = (BWCSlot and getDmg("BWC", Unit, myHero) or 0)
			local RUINEDKINGDamage = (RUINEDKINGSlot and getDmg("RUINEDKING", Unit, myHero) or 0)
			local combo1 = HITDamage
			local combo2 = HITDamage
			local combo3 = HITDamage
			local mana = 0

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

function FindFurthestReadySpell()
	local farSpell = nil

	if ExtraConfig.DrawQ and QReady then farSpell = SkillQ.Range end
	if ExtraConfig.DrawW and WReady and (not farSpell or SkillW.Range > farSpell) then farSpell = SkillW.Range end
	if ExtraConfig.DrawE and EReady and (not farSpell or SkillE.Range > farSpell) then farSpell = SkillE.Range end

	return farSpell
end

function DrawArrowsToPos(pos1, pos2)
	if pos1 and pos2 then
		startVector = D3DXVECTOR3(pos1.x, pos1.y, pos1.z)
		endVector = D3DXVECTOR3(pos2.x, pos2.y, pos2.z)
		DrawArrows(startVector, endVector, 60, 0xE97FA5, 100)
	end
end

function SpellCheck()
	DFGSlot, HXGSlot, BWCSlot, SheenSlot, TrinitySlot, LichBaneSlot = GetInventorySlotItem(3128),
	GetInventorySlotItem(3146), GetInventorySlotItem(3144), GetInventorySlotItem(3057),
	GetInventorySlotItem(3078), GetInventorySlotItem(3100)

	RUINEDKINGSlot, QUICKSILVERSlot, RANDUINSSlot, BWCSlot = GetInventorySlotItem(3153), GetInventorySlotItem(3140), GetInventorySlotItem(3143)

	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)

	RUINEDKINGReady = (RUINEDKINGSlot ~= nil and myHero:CanUseSpell(RUINEDKINGSlot) == READY)
	QUICKSILVERReady = (QUICKSILVERSlot ~= nil and myHero:CanUseSpell(QUICKSILVERSlot) == READY)
	RANDUINSReady = (RANDUINSSlot ~= nil and myHero:CanUseSpell(RANDUINSSlot) == READY)

	DFGReady = (DFGSlot ~= nil and myHero:CanUseSpell(DFGSlot) == READY)
	HXGReady = (HXGSlot ~= nil and myHero:CanUseSpell(HXGSlot) == READY)
	BWCReady = (BWCSlot ~= nil and myHero:CanUseSpell(BWCSlot) == READY)
	IReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
end
function _G.OnGainBuff(unit, buff)
	if unit.isMe then
		if buff.name == "HeimerUpgrade" then
			HeiimerUpgradeActive = true
		end
	end
end

function _G.OnLoseBuff(unit, buff)
	if unit.isMe then
		if buff.name == "HeimerUpgrade" then
			HeimerUpgradeActive = false
		end
	end
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
	if myHero.mana < (myHero.maxMana * ( ExtraConfig.ManaManager / 100)) then
		return true
	else
		return false
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
function round(num, idp)

        local mult = 10^(idp or 0)

        return math.floor(num * mult + 0.5) / mult

end

--[[
function _G.PluginOnWndMsg(msg,key)
	Target = AutoCarry.GetAttackTarget(true)
	if Target ~= nil and ExtraConfig.ProMode then
		if msg == KEY_DOWN and key == KeyQ then CastQ() end
		if msg == KEY_DOWN and key == KeyW then CastW() end
		if msg == KEY_DOWN and key == KeyE then CastE() end
			if ExtraConfig.SmartE then
                CastE()
			end
		end
		if msg == KEY_DOWN and key == KeyQ then CastQ() end
					        if msg == KEY_DOWN and key == string.byte("Q") then

                if player:CanUseSpell(SkillQ.spellKey) == READY then

                        drawTurretSpots = true

                end

        elseif msg == WM_LBUTTONDOWN and drawTurretSpots and TurretSpots then

                for i,group in pairs(TurretSpots) do

                        for x, TurretSpot in pairs(group.Locations) do

                                if group.Snap or AutoCarry.PluginMenu.SnapTurrets then

                                        if GetDistance(TurretSpot, mousePos) <= 250 then

                                                CastSpell(SkillQ.spellKey, TurretSpot.x, TurretSpot.z)

                                        end

                                end

                        end

                end

        elseif msg == WM_QBUTTONDOWN and drawTurretSpots then

                drawTurretSpots = false

        end

end
--]]