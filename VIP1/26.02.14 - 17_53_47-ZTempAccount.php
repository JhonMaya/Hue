<?php exit() ?>--by ZTempAccount 62.153.10.46
if myHero.charName ~= "Karthus" then return end

require "VPrediction"  


local EActivated     = false
	
local QRange, QSpeed, QDelay, QWidth = 875,   1750,      0.60,  50
local WRange = 1000
local ERange = 425
local QReady, WReady, EReady, RReady = false, false,     false, false

local UltDamageFlat = {250, 400, 500}
local UltDamageScaling = 0.6

local LastPing = 0
local VP = nil

function OnLoad()
	configMenu = scriptConfig("Karthus", "Karthus Combo")

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
	configMenu.Harass:addParam("UseM"  , "Move to mouse",  SCRIPT_PARAM_ONOFF,     true)

	configMenu:addSubMenu("Ultimate", "Ultimate")
	configMenu.Ultimate:addParam("Print", "Print Killable Enemys", SCRIPT_PARAM_ONOFF, true)
	configMenu.Ultimate:addParam("Ping",  "Ping Killable Enemys",  SCRIPT_PARAM_ONOFF, true)
	configMenu.Ultimate:addParam("Auto",   "Auto Ult on x Enemys", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)

	configMenu:addSubMenu("Drawing", "Drawing")
	configMenu.Drawing:addParam("QRange", "Draw Q Range",	         SCRIPT_PARAM_ONOFF, true)
	configMenu.Drawing:addParam("WRange", "Draw W Range", 			 SCRIPT_PARAM_ONOFF, true)
	configMenu.Drawing:addParam("ERange", "Draw E Range", 			 SCRIPT_PARAM_ONOFF, true)
	configMenu.Drawing:addParam("Aenemy", "Draw current Enemy",		 SCRIPT_PARAM_ONOFF, true)
	configMenu.Drawing:addParam("Inform", "Draw Ult Notification",   SCRIPT_PARAM_ONOFF, true)

	configMenu:addSubMenu("Misc", "Misc")
	configMenu.Misc:addParam("Tear",    "Charge Tear", 		SCRIPT_PARAM_ONOFF, false,   string.byte("V"))
	configMenu.Misc:addParam("AutoE",   "Auto off. E",      SCRIPT_PARAM_ONOFF, true)

	configMenu.Combo:permaShow("Combo")
	configMenu.Harass:permaShow("Harass")
	configMenu.Ultimate:permaShow("Auto")

	VP = VPrediction()

	ts = TargetSelector(TARGET_LESS_CAST, QRange , DAMAGE_MAGIC)
	ts.name = "Karthus"
	configMenu:addTS(ts)


	PrintChat("Karthus 1.0 - ZeroX")
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

   Combo()
	Harass()

	if configMenu.Misc.Tear then
		if (CountEnemyHeroInRange(1500) == 0) and QReady and not configMenu.Combo.Combo and not configMenu.Harass.Harass then
			CastSpell(_Q, myHero.x + math.random(-400,400), myHero.z + math.random(-400, 400))
		end
	end

	for i, enemy in pairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) and RReady then
			local killable_units = 0
			ultDamage = myHero:CalcMagicDamage(enemy,UltDamageFlat[myHero:GetSpellData(_R).level] + myHero.ap * UltDamageScaling ) -- Thanks Honda
			if ultDamage >= enemy.health then
				killable_units = killable_units + 1

				if killable_units >= configMenu.Ultimate.Auto and RReady and (GetDistance(myHero, target) > 1000 or myHero.dead) then 
					CastSpell(_R)
				end

				if configMenu.Ultimate.Print and RReady then
					PrintChat(enemy.charName .. " Health: " .. math.ceil(enemy.health) .. " UltDamage: " .. math.ceil(ultDamage))
				end

				if configMenu.Ultimate.Ping and RReady and (GetGameTimer() - LastPing > 30) then
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

	if ValidTarget(ts.target) then
		if configMenu.Drawing.Aenemy then
			DrawCircle(ts.target.x, ts.target.y, ts.target.z, 90, 0x19A712 )
		end
	end

	if configMenu.Drawing.Inform and RReady then
		for i, enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) then
				ultDamage = myHero:CalcMagicDamage(enemy,UltDamageFlat[myHero:GetSpellData(_R).level] + myHero.ap * UltDamageScaling ) -- Thanks Honda
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

function OnGainBuff(unit, buff)
	if unit.isMe and buff.name == "Defile" then
		EActivated = true
	end
end

function OnLoseBuff(unit, buff)
	if unit.isMe and buff.name == "Defile" then
		EActivated = false
	end
end

function CastQ()	
	local CastPosition, HitChance, Position = VP:GetCircularCastPosition(ts.target, 0.6, 60, 880, math.huge, myHero, false)
	if HitChance >= 2 and GetDistance(CastPosition) < QRange then
		CastSpell(_Q, CastPosition.x, CastPosition.z)
	end
end

function CastW()
	local CastPosition = VP:GetPredictedPos(ts.target, 0.25)
	if GetDistance(CastPosition) < WRange then
		CastSpell(_W, CastPosition.x, CastPosition.z)
	end 
end

function Harass()
	if configMenu.Harass.Harass and not configMenu.Combo.Combo then
		if configMenu.Harass.UseM then 
			myHero:MoveTo(mousePos.x, mousePos.z)	
		end

		if ValidTarget(ts.target) then
			if EReady and configMenu.Harass.UseE and GetDistance(ts.target) < ERange and EActivated == false then
				CastSpell(_E)
			end

			if QReady and configMenu.Harass.UseQ and GetDistance(ts.target) < QRange then
				CastQ()
			end
		end
	elseif configMenu.Harass.Toggle and not configMenu.Combo.Combo then
		if ValidTarget(ts.target) then
			if EReady and configMenu.Harass.UseE and GetDistance(ts.target) < ERange and EActivated == false then
				CastSpell(_E)
			end

		if QReady and configMenu.Harass.UseQ and GetDistance(ts.target) < QRange then
			CastQ()
		end
	end
end

function Combo()
	if configMenu.Combo.Combo then
		if configMenu.Combo.UseM then
			myHero:MoveTo(mousePos.x, mousePos.z)
		end

		if ValidTarget(ts.target) then
			if EReady and configMenu.Combo.UseE and GetDistance(ts.target) < ERange and EActivated == false then
				CastSpell(_E)
			end

			if QReady and configMenu.Combo.UseQ and GetDistance(ts.target) < QRange then
				CastQ()	
			end

			if WReady and configMenu.Combo.UseW and GetDistance(ts.target) < WRange then
				CastW()
			end
		end
	end
	if EReady and CountEnemyHeroInRange(ERange) == 0 and EActivated == true and configMenu.Misc.AutoE then
		CastSpell(_E)
	end
end