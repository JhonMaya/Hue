<?php exit() ?>--by ragehunter3 46.117.73.179
if debug.getinfo(GetUser).linedefined > -1 then print("<font color='#FF0000'>Viktor: Denied</font>") return end
UserContent = {"wukeokok", "lukeyboy89", "tacD", "xPain", "Darkood", "geheim", "sovngarde", "pqmailer", "Gintoci", "Erwinbeck", "woddystory", "Dyrone", "blm95", "changster109", "Errinqq", "sehcure", "catbert", "asianone89", "thizz4win", "Supreme", "heel", "taka9999", "Shivan", "Melody", "Frosttfire", "xuzhe56828608", "kry0", "CLG Ahpromoo", "Solenrus", "ddsq1226", "xtony211", "ejdernefesi", "ballsdeep", "iRes", "jsteez123", "kyk", "verajicus", "frinshy","Yeezus", "gianmaranon", "Phexon", "kpone53", "khicon", "Niekas", "tipanaya", "wildflower1", "WEEDZOR", "kihan112", "Cabana", "kelwynn", "justinchoy", "pyryoer", "ragehunter3", "Bugmenot1337", "Dobby", "Rileylol"}
local authed = false
local UserName = GetUser()
for _, users in pairs(UserContent) do
	if UserName == users then
		authed = true
		break
	end
end
if not authed then print("<font color='#FF0000'>Viktor: Denied</font>") return end
if authed then print("<font color='#FFFF00'>Viktorious Viktor: Loaded</font>") end

local combatTable = 									{
Viktor	 =				{
Q = {600},
W = {625,math.huge,0.5,100,false,"Circular",2},
E = {540,1200,0.25,50,false,"Line",2},
R = {700,math.huge,0.250,100,false,"Circular",2},
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

if combatTable[myHero.charName] == nil then print("<font color='#FF0000'>"..myHero.charName.." is not supported.</font>") print("<font color='#FF0000'>Viktor: Denied</font>") return end
local VP = nil
function OnLoad()

	VP = VPrediction()
	local cd = combatTable[myHero.charName]
	for i, combat in pairs(combatTable) do
		if combat == cd then
			if cd.Q ~= nil then
				qRange = cd.Q[1]
			end
			if cd.W ~= nil then
				wRange = cd.W[1]
				wSpeed = cd.W[2]
				wDelay = cd.W[3]
				wWidth = cd.W[4]
				wCol = cd.W[5]
				wType = cd.W[6]
				wHC = cd.W[7]
			end
			if cd.E ~= nil then
				eRange = cd.E[1]
				eSpeed = cd.E[2]
				eDelay = cd.E[3]
				eWidth = cd.E[4]
				eCol = cd.E[5]
				eType = cd.E[6]
				eHC = cd.E[7]
			end
			if cd.R ~= nil then
				rRange = cd.R[1]
				rSpeed = cd.R[2]
				rDelay = cd.R[3]
				rWidth = cd.R[4]
				rCol = cd.R[5]
				rType = cd.R[6]
				rHC = cd.R[7]
			end
			break
		end
	end

 

 
 
 
	TRUEeRange = 1225
	Menu = scriptConfig("Viktorious Viktor", "Viktor")
	Menu:addSubMenu("VV: General", "General")
	Menu.General:addParam("Active", "Relinquish the flesh", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu.General:addParam("Poke", "Poke the target", SCRIPT_PARAM_ONKEYDOWN, false, 88) -- X
	Menu.General:addParam("SafetyNet", "Use Safety net", SCRIPT_PARAM_ONOFF, true)
	Menu.General:addParam("MoveToMouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)

	Menu:addSubMenu("VV: Skills", "Skills")
	Menu.Skills:addParam("autoQ", "Automatically use Q", SCRIPT_PARAM_ONOFF, true)
	Menu.Skills:addParam("autoW", "Automatically use W", SCRIPT_PARAM_ONOFF, true)
	Menu.Skills:addParam("wHC", "W HitChance", SCRIPT_PARAM_SLICE, 2,0,5,0)
	Menu.Skills:addParam("wHCinfo", "Will use W if: ", SCRIPT_PARAM_INFO, wHCvar)
	Menu.Skills:addParam("autoE", "Automatically use E", SCRIPT_PARAM_ONOFF, true)
	Menu.Skills:addParam("eHC", "E HitChance", SCRIPT_PARAM_SLICE, 2,0,5,0)
	Menu.Skills:addParam("eHCinfo", "Will use E if: ", SCRIPT_PARAM_INFO, eHCvar)
	Menu.Skills:addParam("autoR", "Automatically use R", SCRIPT_PARAM_ONOFF, false)
	Menu.Skills:addParam("rHC", "R HitChance", SCRIPT_PARAM_SLICE, 4,0,5,0)
	Menu.Skills:addParam("rHCinfo", "Will use R if: ", SCRIPT_PARAM_INFO, rHCvar)
	
	Menu:addSubMenu("VV: Kill Steal", "KS")
	Menu.KS:addParam("KSQ", "Kill Steal with Q", SCRIPT_PARAM_ONOFF, true)
	Menu.KS:addParam("KSE", "Kill Steal with E", SCRIPT_PARAM_ONOFF, true)
	Menu.KS:addParam("KSR", "Kill Steal with R", SCRIPT_PARAM_ONOFF, false)

	Menu:addSubMenu("VV: Drawing", "Draw")
	Menu.Draw:addParam("ECP", "Draw E Cast Position", SCRIPT_PARAM_ONOFF, false)
	Menu.Draw:addParam("DrawQ", "Draw Q range", SCRIPT_PARAM_ONOFF, true)
	Menu.Draw:addParam("DrawW", "Draw W range", SCRIPT_PARAM_ONOFF, true)
	Menu.Draw:addParam("DrawE", "Draw E range", SCRIPT_PARAM_ONOFF, true)
	Menu.Draw:addParam("DrawR", "Draw R range", SCRIPT_PARAM_ONOFF, true)
	Menu.Draw:addParam("SafetyNetRange", "Draw Safety Net", SCRIPT_PARAM_ONOFF, true)
	
	Menu.General:permaShow("Poke")
	Menu.General:permaShow("Active")
	Menu.General:permaShow("SafetyNet")

	ts = TargetSelector(TARGET_LESS_CAST, 1250, DAMAGE_MAGICAL, false)
	ts.name = myHero.charName
	Menu:addTS(ts)
end

function OnTick()
	  if Menu.Skills.wHC == 1 then 
  Menu.Skills.wHCinfo = "Low Hit Chance"
 elseif Menu.Skills.wHC == 2 then
  Menu.Skills.wHCinfo = "High Hit Chance"
 elseif Menu.Skills.wHC == 3 then
  Menu.Skills.wHCinfo = "Target Slow/Close"
 elseif Menu.Skills.wHC == 4 then
  Menu.Skills.wHCinfo = "Target Immobilised"
 elseif Menu.Skills.wHC == 5 then
  Menu.Skills.wHCinfo = "Target Dashing"
 elseif Menu.Skills.wHC == 0 then
  Menu.Skills.wHCinfo = "No Waypoints Found"
 end
 
	  if Menu.Skills.eHC == 1 then 
  Menu.Skills.eHCinfo = "Low Hit Chance"
 elseif Menu.Skills.eHC == 2 then
  Menu.Skills.eHCinfo = "High Hit Chance"
 elseif Menu.Skills.eHC == 3 then
  Menu.Skills.eHCinfo = "Target Slow/Close"
 elseif Menu.Skills.eHC == 4 then
  Menu.Skills.eHCinfo = "Target Immobilised"
 elseif Menu.Skills.eHC == 5 then
  Menu.Skills.eHCinfo = "Target Dashing"
 elseif Menu.Skills.eHC == 0 then
  Menu.Skills.eHCinfo = "No Waypoints Found"
 end
 
	  if Menu.Skills.rHC == 1 then 
  Menu.Skills.rHCinfo = "Low Hit Chance"
 elseif Menu.Skills.rHC == 2 then
  Menu.Skills.rHCinfo = "High Hit Chance"
 elseif Menu.Skills.rHC == 3 then
  Menu.Skills.rHCinfo = "Target Slow/Close"
 elseif Menu.Skills.rHC == 4 then
  Menu.Skills.rHCinfo = "Target Immobilised"
 elseif Menu.Skills.rHC == 5 then
  Menu.Skills.rHCinfo = "Target Dashing"
 elseif Menu.Skills.rHC == 0 then
  Menu.Skills.rHCinfo = "No Waypoints Found"
 end
 
 
 
 
	QREADY = (myHero:CanUseSpell(_Q) == READY and Menu.Skills.autoQ)
	WREADY = (myHero:CanUseSpell(_W) == READY and Menu.Skills.autoW)
	EREADY = (myHero:CanUseSpell(_E) == READY and Menu.Skills.autoE)
	RREADY = (myHero:CanUseSpell(_R) == READY and Menu.Skills.autoR)
	ts:update()
	Target = ts.target

	if Menu.General.Poke and Target then
		Poke()
	end

	if Menu.General.SafetyNet and Target then
		SafetyNet()
	end

	if Menu.General.Active and Target then
		if QREADY then CastSpell(_Q, Target) end
		if WREADY then
			if wType == "Circular" then wCastPosition, HitChance, Position = VP:GetCircularCastPosition(Target, wDelay, wWidth, wRange) end
			if wType ~= nil then
				if (HitChance >= Menu.Skills.wHC or HitChance == 0) then	
					if GetDistance(myHero,Target) < wRange then
						CastSpell(_W, wCastPosition.x,wCastPosition.z)
					end
				end
			end
		end
		if EREADY then
			alpha = math.atan(math.abs(ts.target.z-myHero.z)/math.abs(ts.target.x-myHero.x))
			locX = math.cos(alpha)*(eRange-100) -- returns fromX
			locZ = math.sin(alpha)*(eRange-100)-- returns fromY
			ExPos = math.sign(ts.target.x-myHero.x)*locX+myHero.x-- fromX var
			EzPos = math.sign(ts.target.z-myHero.z)*locZ+myHero.z -- fromY var
			VikVector = Vector(ExPos,Target.y,EzPos)
			if eType == "Line" then eCastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, eDelay, eWidth, TRUEeRange, eSpeed, VikVector) end
			if eType ~= nil then
				if (HitChance >= Menu.Skills.eHC or HitChance == 0) then
					if(GetDistance(VikVector) <= (eRange)) and GetDistance(VikVector,eCastPosition) <= 675 then
						Packet('S_CAST', { spellId = _E, fromX = VikVector.x, fromY = VikVector.z, toX = eCastPosition.x, toY = eCastPosition.z }):send() --Pakcet cast of Viktor`s E
					end
				end
			end
		end
		if RREADY then
			if rType == "Circular" then rCastPosition, HitChance, Position = VP:GetCircularCastPosition(Target, rDelay, rWidth, rRange) end
			if rType ~= nil then
				if (HitChance >= Menu.Skills.rHC or HitChance == 0) then
					if GetDistance(myHero,Target) < rRange then
						CastSpell(_R, rCastPosition.x,rCastPosition.z)
					end
				end
			end
		end
	end
	if not Target or not RREADY then rCastPosition = nil end	
	if not Target or not WREADY then wCastPosition = nil end
	if not Target or not EREADY then eCastPosition = nil end
	KS()
	
		if Menu.General.MoveToMouse and Menu.General.Active then
			myHero:MoveTo(mousePos.x, mousePos.z)
	end
end

function SafetyNet()
	if WREADY then
			if wType == "Circular" then wCastPosition, HitChance, Position = VP:GetCircularCastPosition(Target, wDelay, wWidth, wRange) end
			if wType ~= nil then
				--if (HitChance >= Menu.Skills.wHC or HitChance == 0) then
					if GetDistance(myHero,Target) < 325 then
						CastSpell(_W, wCastPosition.x,wCastPosition.z)
					end
				--end
			end
		end
end

function Poke()
		if EREADY then
			alpha = math.atan(math.abs(ts.target.z-myHero.z)/math.abs(ts.target.x-myHero.x))
			locX = math.cos(alpha)*(eRange-100) -- returns fromX
			locZ = math.sin(alpha)*(eRange-100)-- returns fromY
			ExPos = math.sign(ts.target.x-myHero.x)*locX+myHero.x -- fromX var
			EzPos = math.sign(ts.target.z-myHero.z)*locZ+myHero.z -- fromY var
			VikVector = Vector(ExPos,Target.y,EzPos)
			if eType == "Line" then eCastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, eDelay, eWidth, TRUEeRange, eSpeed, VikVector) end
			if eType ~= nil then
				if (HitChance >= Menu.Skills.eHC or HitChance == 0) then
					if(GetDistance(VikVector) <= (eRange)) and GetDistance(VikVector,eCastPosition) <= 675 then
						Packet('S_CAST', { spellId = _E, fromX = VikVector.x, fromY = VikVector.z, toX = eCastPosition.x, toY = eCastPosition.z }):send() --Pakcet cast of Viktor`s E
					end
				end
			end
		end	
end

function KS()
	for _, enemy in pairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) then
			if myHero:CanUseSpell(_Q) == READY and GetDistance(enemy) < qRange and getDmg("Q", enemy, myHero) > enemy.health and Menu.KS.KSQ then
				CastSpell(_Q, enemy)
			elseif myHero:CanUseSpell(_R) == READY and GetDistance(enemy) < rRange and getDmg("R", enemy, myHero) > enemy.health and Menu.KS.KSR then
				CastSpell(_R, enemy.x, enemy.z)
			end
		end
		if EREADY and getDmg("E", enemy, myHero) > enemy.health and Target then
			alpha = math.atan(math.abs(ts.target.z-myHero.z)/math.abs(ts.target.x-myHero.x))
			locX = math.cos(alpha)*(eRange-100) -- returns fromX
			locZ = math.sin(alpha)*(eRange-100)-- returns fromY
			ExPos = math.sign(ts.target.x-myHero.x)*locX+myHero.x -- fromX var
			EzPos = math.sign(ts.target.z-myHero.z)*locZ+myHero.z -- fromY var
			VikVector = Vector(ExPos,Target.y,EzPos)
			if eType == "Line" then eCastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, eDelay, eWidth, TRUEeRange, eSpeed, VikVector) end
			if eType ~= nil then
				if (HitChance >= Menu.Skills.eHC or HitChance == 0) then
					if(GetDistance(VikVector) <= (eRange)) and GetDistance(VikVector,eCastPosition) <= 675 then
						Packet('S_CAST', { spellId = _E, fromX = VikVector.x, fromY = VikVector.z, toX = eCastPosition.x, toY = eCastPosition.z }):send() --Pakcet cast of Viktor`s E
					end
				end
			end
		end
	end 
end

function OnDraw()
	if Menu.Draw.ECP then
		if eCastPosition ~= nil and Target then
			DrawCircle(eCastPosition.x, eCastPosition.y, eCastPosition.z,  100, 0xFF0000)
			if GetDistance(VikVector,eCastPosition) <= 675 then
				DrawCircle(VikVector.x,VikVector.y,VikVector.z, 100, 0x00FFFF)
			end
			DrawCircle(myHero.x,myHero.y,myHero.z, eRange, 0x0000FF)
		end
	end
	
		if Menu.Draw.DrawQ then
		DrawCircle(myHero.x,myHero.y,myHero.z, qRange, 0x0000FF)
	end

	if Menu.Draw.DrawW then
		DrawCircle(myHero.x,myHero.y,myHero.z, wRange, 0x9900CC)
	end

	if Menu.Draw.DrawE then
		DrawCircle(myHero.x,myHero.y,myHero.z, 1225, 0xFF0000)
	end

	if Menu.Draw.DrawR then
		DrawCircle(myHero.x,myHero.y,myHero.z, rRange, 0x888888)
	end
	
	if Menu.General.SafetyNet and Menu.Draw.SafetyNetRange then
	DrawCircle(myHero.x, myHero.y, myHero.z, 325, 0xFF0000)
	end
end

function math.sign(x)
 if x < 0 then
  return -1
 elseif x > 0 then
  return 1
 else
  return 0
 end
end