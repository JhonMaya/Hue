<?php exit() ?>--by ZTempAccount 62.153.10.46
if myHero.charName ~= "Lux" then return end

require "VPrediction"

local QRange, QSpeed, QDelay, QWidth = 1150, 1200, 0.25, 40
local WRange = 1050
local ERange, ESpeed, EDelay, EWidth = 1100, 1300, 0.15, 275
local RRange, RSpeed, RDelay, RWidth = 3000, 3000, 0.50, 250

local QReady, WReady, EReady, RReady = false, false, false, false

local LastPing = 0
local EObject = nil
local VP = nil

function OnLoad()
	configMenu = scriptConfig("Lux", "Lux Combo")

	configMenu:addSubMenu("Combo", "Combo")
	configMenu.Combo:addParam("Combo" , "Combo", 		  SCRIPT_PARAM_ONKEYDOWN, false, 32)
	configMenu.Combo:addParam("UseQ"  , "Use Q in Combo", SCRIPT_PARAM_ONOFF, 	  true)
	configMenu.Combo:addParam("UseW"  , "Use W in Combo", SCRIPT_PARAM_ONOFF, 	  true)
	configMenu.Combo:addParam("UseE"  , "Use E in Combo", SCRIPT_PARAM_ONOFF, 	  true)
	configMenu.Combo:addParam("UseM"  , "Move to mouse",  SCRIPT_PARAM_ONOFF,     true)


	configMenu:addSubMenu("Harass", "Harass")
	configMenu.Harass:addParam("Harass" , "Harass", 		 SCRIPT_PARAM_ONKEYDOWN,   false,   string.byte("X"))
	configMenu.Harass:addParam("UseQ"   , "Use Q in Harass", SCRIPT_PARAM_ONOFF, 	   true)
	configMenu.Harass:addParam("UseE"   , "Use E in Harass", SCRIPT_PARAM_ONOFF,	   true)
	configMenu.Harass:addParam("Toggle" , "Toggled Harass",  SCRIPT_PARAM_ONKEYTOGGLE, false,   string.byte("T"))
	configMenu.Harass:addParam("UseM"  , "Move to mouse",    SCRIPT_PARAM_ONOFF,     true)

	configMenu:addSubMenu("Ultimate", "Ultimate")
	configMenu.Ultimate:addParam("Print", "Print Killable Enemys",  SCRIPT_PARAM_ONOFF, true)
	configMenu.Ultimate:addParam("Ping",  "Ping Killable Enemys",   SCRIPT_PARAM_ONOFF, true)
	configMenu.Ultimate:addParam("Auto",  "Auto Ult Killable Enemy", SCRIPT_PARAM_ONOFF, true)

	configMenu:addSubMenu("Drawing", "Drawing")
	configMenu.Drawing:addParam("QRange", "Draw Q Range",	         SCRIPT_PARAM_ONOFF, true)
	configMenu.Drawing:addParam("WRange", "Draw W Range", 			 SCRIPT_PARAM_ONOFF, true)
	configMenu.Drawing:addParam("ERange", "Draw E Range", 			 SCRIPT_PARAM_ONOFF, true)
	configMenu.Drawing:addParam("Aenemy", "Draw current Enemy",		 SCRIPT_PARAM_ONOFF, true)
	configMenu.Drawing:addParam("Inform", "Draw Ult Notification",   SCRIPT_PARAM_ONOFF, true)

	VP = VPrediction()

	ts = TargetSelector(TARGET_LESS_CAST, QRange , DAMAGE_MAGIC)
	ts.name = "Lux"
	configMenu:addTS(ts)

	PrintChat("Lux 1.0 - ZeroX")
end

function PingLocal(X, Y) -- Thanks Honda	
	Packet("R_PING", {x = X, y = Y, type = PING_FALLBACK}):receive()
end

function OnTick()
	ts:update()
	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)

	if EObject ~= nil and not EObject.valid then
		EObject = nil
	end

	if ValidTarget(ts.target) then
		if EObject ~= nil and EObject.valid and GetDistance(EObject, ts.target) < EWidth then
			CastSpell(_E)
		end
	end

    Combo()
	Harass()
	Ultimate()
end

function CastQ()
	CastPosition,  HitChance,  Position = VP:GetLineCastPosition(ts.target, QDelay, QWidth, QRange, QSpeed, myHero, true)
	if HitChance >= 2 and GetDistance(CastPosition) < QRange then
		CastSpell(_Q, CastPosition.x, CastPosition.z)
	end
end

function CastE()
	CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(ts.target, EDelay, EWidth, ERange, ESpeed, myHero, false)
	if HitChance >= 2 and GetDistance(CastPosition) < ERange then
		CastSpell(_E, CastPosition.x, CastPosition.z)
	end
end

function OnCreateObj(object)
	if object.name:find("LuxLightstrike_tar") then
		EObject = object
	end
end
 
function OnDeleteObj(object)
	if object.name:find("LuxLightstrike_tar") or (EObject and EObject.rawHash == object.rawHash) then
		EObject = nil
	end
end

function OnProcessSpell(object, spell)
	if object == nil or spell == nil or not object.valid then return end

 	if ValidTarget(object) and not myHero.dead and not (object.name:find("Minion_") or object.name:find("Odin")) then
 		if object.type == "obj_AI_Hero" then
			if configMenu.Combo.Combo and configMenu.Combo.UseW then
				if WReady then
				-- Thanks Apple lol
					local bestAlly = nil
					for i, tempAlly in pairs(GetAllyHeroes()) do
						if GetDistance(tempAlly) < WRange then
							if bestAlly == nil or bestAlly.health > tempAlly.health then
								bestAlly = tempAlly
							end
						end
					end
						CastSpell(_W, bestAlly ~= nil and bestAlly.x or object.x, bestAlly ~= nil and bestAlly.z or object.z)
				end
			end
		end
	end
end

function Combo()
	if configMenu.Combo.Combo then

		if configMenu.Combo.UseM then
			myHero:MoveTo(mousePos.x, mousePos.z)
		end

		if ValidTarget(ts.target) then
			if configMenu.Combo.UseQ then
				if QReady then
					CastQ()
				end
			end

			if configMenu.Combo.UseE then
				if EReady then
					CastE()
				end
			end
		end
	end
end


function Harass()
	if configMenu.Harass.Harass and not configMenu.Combo.Combo then
		if configMenu.Harass.UseM then
			myHero:MoveTo(mousePos.x, mousePos.z)
		end
		if ValidTarget(ts.target) then
			if configMenu.Harass.UseQ then
				if QReady then
					CastQ()
				end
			end

			if configMenu.Harass.UseE then
				if EReady then 
					CastE()
				end
			end
		end
	elseif configMenu.Harass.Toggle and not configMenu.Combo.Combo then
		if ValidTarget(ts.target) then
			if configMenu.Harass.UseQ then
				if QReady then
					CastQ()
				end
			end

			if configMenu.Harass.UseE then
				if EReady then 
					CastE()
				end
			end
		end
	end
end

function Ultimate()
	for i, enemy in pairs(GetEnemyHeroes()) do
	CastPosition,  HitChance,  Position = VP:GetLineCastPosition(enemy, RDelay, RWidth, RRange, RSpeed, myHero, false)
		if ValidTarget(enemy) and RReady then
			ultDamage  = getDmg("R", enemy, myHero)
			if ultDamage >= enemy.health then
				if configMenu.Ultimate.Auto then
					if HitChance >= 2 and GetDistance(CastPosition) < RRange then
						CastSpell(_R, CastPosition.x, CastPosition.z)
					end
				end
				
				if configMenu.Ultimate.Print then
					PrintChat(enemy.charName .. " Health: " .. math.ceil(enemy.health) .. " UltDamage: " .. math.ceil(ultDamage))
				end

				if configMenu.Ultimate.Ping then
					DelayAction(PingLocal,  1000 * 0.3 * i/1000, {enemy.x, enemy.z})
					LastPing = GetGameTimer()
				end
			end
		end
	end
end

function OnDraw()
	if configMenu.Drawing.QRange and QReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, QRange, 0x19A712 )
	end

	if configMenu.Drawing.WRange and WReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, WRange, 0x19A712 )
	end

	if configMenu.Drawing.ERange and EReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, ERange, 0x19A712 )
	end

	if ts.target ~= nil then
		if configMenu.Drawing.Aenemy then
			DrawCircle(ts.target.x, ts.target.y, ts.target.z, 90, 0x19A712 )
		end
	end

	if configMenu.Drawing.Inform and RReady then
		for i, enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) then
				ultDamage  = getDmg("R", enemy, myHero)
				HPtoUlt = math.ceil(enemy.health - ultDamage)

				if HPtoUlt < 0 then
					DrawText3D("Killable with ult!", enemy.x, enemy.y, enemy.z, 20, 4294967295, center)
				else
					DrawText3D("Ult in: " .. HPtoUlt .. "HP", enemy.x, enemy.y, enemy.z, 20, 4294967295, center)
				end
			end
		end
	end
end