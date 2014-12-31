<?php exit() ?>--by ragehunter3 46.117.73.179
require 'VPrediction'
require 'Collision'
if debug.getinfo(GetUser).linedefined > -1 then print("<font color='#FF0000'>Nidalee: Denied</font>") return end
UserContent = {"xPain", "dontstephere", "ragehunter3"}
local authed = false
local UserName = GetUser()
for _, users in pairs(UserContent) do
	if UserName == users then
		authed = true
		break
	end
end
if not authed then print("<font color='#FF0000'>Nidalee: Denied</font>") return end
local combatTable = 									{
Nidalee	 =				{
Q = {1450,1300,0.25,75,true,"Line",2},
								}
																	}
-- DATA KEY --
--[[
1 = range
2 = speed
3 = delay
4 = width
5 = collision
6 = spell type
7 = spell priority
]]
if combatTable[myHero.charName] == nil then print("<font color='#FF0000'>"..myHero.charName.." is not supported.</font>") print("<font color='#FF0000'>Nidalee: Denied</font>") return end
local VP = nil
function OnLoad()
	print("Nidalee - Spear Thrower, Loaded")
	VP = VPrediction()
	local cd = combatTable[myHero.charName]
	for i, combat in pairs(combatTable) do
		if combat == cd then
			if cd.Q ~= nil then
				qRange = cd.Q[1]
				qSpeed = cd.Q[2]
				qDelay = cd.Q[3]
				qWidth = cd.Q[4]
				qCol = cd.Q[5]
				qType = cd.Q[6]
				qHC = cd.Q[7]
			end
			break
		end
	end	
	Menu = scriptConfig("Nidalee Spears","Nidalee")
	Menu:addParam("Active", "Activate the script", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu:addParam("qHC", "Q HitChance", SCRIPT_PARAM_SLICE, 2,0,5,0)
	Menu:addParam("drawc", "Draw Cast Position", SCRIPT_PARAM_ONOFF, true)
	ts = TargetSelector(TARGET_NEAR_MOUSE, 1475, DAMAGE_MAGICAL, false)
	ts.name = myHero.charName
	Menu:addTS(ts)
end
function OnTick()
	QREADY = (myHero:CanUseSpell(_Q) == READY and myHero:GetSpellData(_Q).name == "JavelinToss")
	ts:update()
	Target = ts.target
	if not Target then qCastPosition = nil end
	if Target and QREADY then
		if qType == "Line" then qCastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, qDelay, qWidth, qRange, qSpeed, myHero) end
		if Menu.Active then
			if qType ~= nil then
				if (HitChance >= Menu.qHC or HitChance == 0) and (GetDistance(qCastPosition) <= qRange) then
					if qCol then
						local Col = Collision(qRange, qSpeed, qDelay, qWidth)
						if not Col:GetMinionCollision(qCastPosition, myHero) then
							if QREADY then 
								CastSpell(_Q, qCastPosition.x, qCastPosition.z)
							end
						end
					else
						if QREADY then 
								CastSpell(_Q, qCastPosition.x, qCastPosition.z)
							end
					end
				end
			end
		end
	end
end
function OnDraw()
	if Menu.drawc then
		if QREADY then
			if qCastPosition ~= nil then
				DrawCircle(qCastPosition.x, qCastPosition.y, qCastPosition.z,  100, 0xFF0000)
			end
			DrawCircle(myHero.x,myHero.y,myHero.z, qRange, 0xFF0000)
		end
	end
end