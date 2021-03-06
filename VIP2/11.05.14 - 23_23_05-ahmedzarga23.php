<?php exit() ?>--by ahmedzarga23 197.0.95.8
--[[ Chancey's Script Updater
--UPDATEURL=
--HASH=68D3EA1B8B8EFB5B1DDB4A35E0E2044A
--UPDATE=False
Don't remove any of these lines or script may become corrupted]]
--[[
	Cassiopeia
	-Requeriments:
		-VPrediction

	-Features: 
		-Auto-update
		-Combo
			-Q and W using prodiction
			-E on poisoned enemies
			-Own target selecting system
			-Uses R and Ignite if the enemy is killable with the default combo ({_Q, _Q, _W, _E, _E, _R, _IGNITE})
		-Harass
			-Uses Q, W and E to harass the enemy
		-Farming
			-2 farm modes: Freeze mode and Laneclear mode
			-Uses all Q, W and E in diferent ways depending on the mode.
		-Jungle Farm
			-Uses spells to farm the jungle
			-Tries to steal baron and dragon with E if its killable
			-Option to use "Advanced farming" (see video below)
		-Ultimate
			-Auto Cast ultimate if > X targets are in range
			-Auto Aim the ultimate using 1 hotkey
			-Don't let the manual ultimate to be casted if it wont hit any champion
		-Misc.
			-Auto charge tear if there are not enemies or minions arround
		-Drawing
			-Draw Q, E and AA range
			-Draw remaining health after combo ({_Q, _Q, _W, _E, _E, _R, _IGNITE}) in healthbars
			-Warn if there is not enought mana to cast the combo ({_Q, _Q, _W, _E, _E, _R, _IGNITE})
		
	TODO:
		-Take into account if the targets are going to die poisoned before using spells on them
]]
if myHero.charName ~= "Cassiopeia" then return end
--[[AutoUpdate Settings]]
local version = "1.08"

require "VPrediction"

--the l33tultr4s3cr3tXpl01t function, used to cast spells without facing the enemy.
LoadProtectedScript('VjUzEzdFTURpN0NFYN50TGhvRUxAbTNLRXlNeER2ZUVMRm1zSyB5TXlMMuXFU0DtM0lFeU19RXJlRRMHbTddRXlNFXVBETAgNB8HOHYaP0oyKhUpfHEZM0pFeU14RnJlQkxAbTBLQm5NeUbpZUVMV60xy4N5DXlHM2VFB4FtMwFEecz+R7NlD83B7LTKBHgH+MfwuMXMQaHzikSkDXlHZaVEzIZtc0tEOE15DfNlRQZBbbIBBHnJpMbyZImMgWzuC0V4UnnGcmxFTEBpNEtFeR0YJRkAMUxEajNLRSoSOgchMUVISG0zSzYJKBUqOwFFSERtM0sxFhV5QnBlRUw4bTdPRXlNDSkrZUFOQG0zMUV9SHlGchYgIiRtN1tFeU0NJwACIDgOCEc8KgsmMCJyZUVMQGwzS0V5TXlGcmVFTEBtM0tFeU15RnJkRUxAbDNLRXlNeUZyZUVMQG0zS0V54019E9871316D1C882A7D3FD0764CE9D')

--[[Spell data]]
local Qrange = 850 + 75
local Wrange = 850 + 75
local Erange = 700
local Rrange = 800 --?
local AArange = 600 + 60 -- 60 sized hitbox, should change depending on the champion but it's not a big deal :p

local Qradius = 75
local Wradius = 125
local Rangle = (80 - 10) * math.pi / 180


local Qdelay = 600
local Wdelay = 700
local Rdelay = 600

local Qdamage = {75, 115, 155, 195, 235}
local Qscaling = 0.8
local Wdamage = {25, 35, 45, 55, 65} -- 1 tick
local Wscaling = 0.15
local Edamage = {50, 85, 120, 155, 190}
local Escaling = 0.55
local Rdamage = {200, 325, 450}
local Rscaling = 0.6

local Qmana = {35, 45, 55, 65, 75}
local Wmana = {70, 80, 90, 100, 110}
local Emana = {50, 60, 70, 80, 90}
local Rmana = {100, 100, 100}

local Qready = false
local Wready = false
local Eready = false
local Rready = false

--[[Config]]
local Config = nil

--[[Targetting]]
local Etarget = nil
local Qtarget = nil
local SelectedTarget = nil
local TheCombo = {_Q, _Q, _W, _E, _E, _R, _IGNITE} --Combo to calculate the damage

--[[Farming]]
local CheckedQ = 0
local CheckedW = 0

local JungleLocations = {
Vector(5830,0,4900), --1
Vector(7167,0,5588),
Vector(4103,0,6117),
Vector(7054,0,8772),
Vector(8104,0,9657),
Vector(9896,0,8497),
}

local JungleWCastLocations = {
Vector(6442,0,4833),
Vector(6934,0,4884),
Vector(3380,0,5967),
Vector(7160,0,9490),
Vector(7531,0,9660),
Vector(10580,0,8382),
}

local JungleQCastLocations = {
Vector(6467,0,5253),
Vector(6467,0,5253),
Vector(3380,0,6230), 
Vector(7471,0,9213), 
Vector(7527,0,9200), 
Vector(10473,0,8096), 
}
	
--[[VPrediction]]
local VP = nil
--[[Tracker]]
local DamageToHeros = {}
local lastrefresh = 0
local LastSpellTime = 0
local RecallTime = 0

--[[Orbwalk]]
local LastAttack = 0
local LastAttackAnimation = 0
local LastAttackWindup = 0
local AAtime = 0
function OnLoad()
	Menu = scriptConfig("ProCassio", "ProCassio")

	Menu:addSubMenu("Combo", "Combo")
	Menu.Combo:addParam("UseQ", "Use Q in combo", SCRIPT_PARAM_ONOFF , true)
	Menu.Combo:addParam("UseW", "Use W in combo", SCRIPT_PARAM_ONOFF, true)
	Menu.Combo:addParam("UseE", "Use E on poisoned targets", SCRIPT_PARAM_ONOFF, true)
	Menu.Combo:addParam("UseEP", "Use E using packets", SCRIPT_PARAM_ONOFF, false)
	Menu.Combo:addParam("UseEKS", "Use smart KS using E", SCRIPT_PARAM_ONOFF, true)
	Menu.Combo:addParam("UseR", "Use smart R if enemy is killable", SCRIPT_PARAM_ONOFF, true)
	Menu.Combo:addParam("UseIgnite", "Use ignite if the target is killable", SCRIPT_PARAM_ONOFF, true)
	Menu.Combo:addParam("MoveTo", "Move to mouse / Orbwalk while comboing", SCRIPT_PARAM_ONOFF, true)
	Menu.Combo:addParam("Enabled", "Use Combo!", SCRIPT_PARAM_ONKEYDOWN, false, 32)

	Menu:addSubMenu("Harass", "Harass")
	Menu.Harass:addParam("UseQ", "Harass using Q", SCRIPT_PARAM_ONOFF, true)
	Menu.Harass:addParam("UseW", "Harass using W", SCRIPT_PARAM_ONOFF, false)
	Menu.Harass:addParam("UseE", "Harass using E on poisoned", SCRIPT_PARAM_ONOFF, true)
	Menu.Harass:addParam("MoveTo", "Move to mouse / Orbwalk while harassing", SCRIPT_PARAM_ONOFF, false)
	Menu.Harass:addParam("Enabled", "Harass! (hold)", SCRIPT_PARAM_ONKEYDOWN, false,   string.byte("C"))
	Menu.Harass:addParam("Enabled2", "Harass! (toggle)", SCRIPT_PARAM_ONKEYTOGGLE, false,   string.byte("Y"))
	
	Menu:addSubMenu("Orbwalking", "Orbwalking")
	Menu.Orbwalking:addParam("RedB", "Orbwalk with red buff", SCRIPT_PARAM_ONOFF, true)
	Menu.Orbwalking:addParam("LowHE", "Orbwalk if target health < %", SCRIPT_PARAM_SLICE, 0, 0, 100)
	Menu.Orbwalking:addParam("LowHS", "Don't orbwalk if my health < %", SCRIPT_PARAM_SLICE, 0, 0, 100)
	
	Menu:addSubMenu("Farm", "Farm")
	Menu.Farm:addParam("UseQ",  "Use Q", SCRIPT_PARAM_LIST, 4, { "No", "Freeze", "LaneClear", "Both" })
	Menu.Farm:addParam("UseW",  "Use W", SCRIPT_PARAM_LIST, 3, { "No", "Freeze", "LaneClear", "Both" })
	Menu.Farm:addParam("UseE",  "Use E", SCRIPT_PARAM_LIST, 3, { "No", "Freeze", "LaneClear", "Both" })

	
	Menu.Farm:addParam("Freeze", "Farm freezing", SCRIPT_PARAM_ONKEYDOWN, false,   string.byte("C"))
	Menu.Farm:addParam("MoveTo", "Move to mouse while farming", SCRIPT_PARAM_ONOFF, false)
	Menu.Farm:addParam("LaneClear", "Farm LaneClear", SCRIPT_PARAM_ONKEYDOWN, false,   string.byte("V"))

	Menu:addSubMenu("JungleFarm", "JungleFarm")
	Menu.JungleFarm:addParam("UseQ", "Jungle farm using Q", SCRIPT_PARAM_ONOFF, true)
	Menu.JungleFarm:addParam("UseW", "Jungle farm using W", SCRIPT_PARAM_ONOFF, false)
	Menu.JungleFarm:addParam("UseE", "Jungle farm using E on poisoned", SCRIPT_PARAM_ONOFF, false)
	Menu.JungleFarm:addParam("Advanced", "Advanced jungle farming", SCRIPT_PARAM_ONOFF, true)
	Menu.JungleFarm:addParam("Steal", "Auto Steal Dragon and baron using E", SCRIPT_PARAM_ONOFF, false)
	Menu.JungleFarm:addParam("MoveTo", "Move to mouse while farming", SCRIPT_PARAM_ONOFF, false)
	Menu.JungleFarm:addParam("Enabled", "Farm jungle!", SCRIPT_PARAM_ONKEYDOWN, false,   string.byte("V"))

	Menu:addSubMenu("Ultimate", "Ultimate")
	Menu.Ultimate:addParam("Auto",  "Auto ultimate if ", SCRIPT_PARAM_LIST, 1, { "No", ">0 targets", ">1 targets", ">2 targets", ">3 targets", ">4 targets" })
	Menu.Ultimate:addParam("AutoAim", "Cast ultimate!", SCRIPT_PARAM_ONKEYDOWN, false,   string.byte("R"))
	Menu.Ultimate:addParam("Block", "Assisted ultimate", SCRIPT_PARAM_ONOFF, true)

	Menu:addSubMenu("Misc", "Misc")
	Menu.Misc:addParam("AOE", "Use MEC in teamfights", SCRIPT_PARAM_ONOFF, true)
	Menu.Misc:addParam("Selected", "Focus selected target by left clicking", SCRIPT_PARAM_ONOFF, true)
	Menu.Misc:addParam("Tear", "Charge Tear", SCRIPT_PARAM_ONKEYTOGGLE, false,   string.byte("T"))
	Menu.Misc:addParam("Poisoned2", "Predict if the enemies are going to be poisoned", SCRIPT_PARAM_ONOFF, false)
	Menu.Misc:addParam("WOnlyNonPoisoned", "Use W only if Q fails", SCRIPT_PARAM_ONOFF, false)
	

	Menu:addSubMenu("Drawing", "Drawing")
	Menu.Drawing:addSubMenu("Ranges", "Ranges")
	Menu.Drawing.Ranges:addParam("Qrange", "Draw Q-W range", SCRIPT_PARAM_ONOFF, true)
	Menu.Drawing.Ranges:addParam("Erange", "Draw E range", SCRIPT_PARAM_ONOFF, true)
	Menu.Drawing.Ranges:addParam("AArange", "Draw AA range", SCRIPT_PARAM_ONOFF, false)
	Menu.Drawing.Ranges:addParam("Qcolor", "Q range color", SCRIPT_PARAM_COLOR, {255, 0, 255, 0})
	Menu.Drawing.Ranges:addParam("Ecolor", "E range color", SCRIPT_PARAM_COLOR, {255, 0, 255, 0})
	Menu.Drawing.Ranges:addParam("AAcolor", "AA range color", SCRIPT_PARAM_COLOR, {255, 0, 255, 0})
	Menu.Drawing.Ranges:addParam("LowC", "Use anti-lag circles", SCRIPT_PARAM_ONOFF, true)
	
	Menu.Drawing:addParam("BarHealth",  "Draw Remaining health after combo on bar", SCRIPT_PARAM_ONOFF, true)
	Menu.Drawing:addParam("BarHealthT",  "Draw Remaining health (Text) on bar", SCRIPT_PARAM_ONOFF, true)
	Menu.Drawing:addParam("NoMana",  "Warn if no mana", SCRIPT_PARAM_ONOFF, true)
	
	Menu:addParam("Version", "Version", SCRIPT_PARAM_INFO, version)
	EnemyMinions = minionManager(MINION_ENEMY, 2000, myHero, MINION_SORT_HEALTH_ASC)
	JungleMinions = minionManager(MINION_JUNGLE, Qrange, myHero, MINION_SORT_MAXHEALTH_DEC)


	VP = VPrediction() --Load VPrediction

 	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		_IGNITE = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		_IGNITE = SUMMONER_2
	else
		_IGNITE = -1
	end
	PrintChat("<font color=\"#81BEF7\">Cassiopeia ("..version..") loaded successfully</font>")
end

--[[Damage calculation]]
function GetDamage(target, spell)
	if      spell == _Q and myHero:GetSpellData(_Q).level ~= 0 then
		return myHero:CalcMagicDamage(target, Qdamage[myHero:GetSpellData(_Q).level] + myHero.ap * Qscaling)
	elseif spell == _W and myHero:GetSpellData(_W).level ~= 0 and myHero:CanUseSpell(_W) then
		return myHero:CalcMagicDamage(target, Wdamage[myHero:GetSpellData(_W).level] + myHero.ap * Wscaling)
	elseif spell == _E and myHero:GetSpellData(_E).level ~= 0 then
		return myHero:CalcMagicDamage(target, Edamage[myHero:GetSpellData(_E).level] + myHero.ap * Escaling)
	elseif spell == _R and myHero:GetSpellData(_R).level ~= 0 and myHero:CanUseSpell(_R) then
		return myHero:CalcMagicDamage(target, Rdamage[myHero:GetSpellData(_R).level] + myHero.ap * Rscaling)
	elseif spell == _IGNITE and IgniteReady then
		return (50 + 20 * myHero.level)
	end
	return 0
end

function GetComboDamage(target, combo)
	local totaldamage = 0
	for i, spell in ipairs(combo) do
		totaldamage = totaldamage + GetDamage(target, spell)
	end
	return totaldamage
end

function GetManaCost(spell)
	if      spell == _Q and myHero:GetSpellData(_Q).level ~= 0 then
		return Qmana[myHero:GetSpellData(_Q).level]
	elseif      spell == _W and myHero:GetSpellData(_W).level ~= 0 then
		return Wmana[myHero:GetSpellData(_W).level]
	elseif      spell == _E and myHero:GetSpellData(_E).level ~= 0 then
		return Emana[myHero:GetSpellData(_E).level]
	elseif      spell == _R and myHero:GetSpellData(_R).level ~= 0 then
		return Rmana[myHero:GetSpellData(_R).level]
	end
		
	return 0
end

function ComboManaCost(combo)
	local totalmana = 0
	for i, spell in ipairs(combo) do
		totalmana = totalmana + GetManaCost(spell)
	end
	return totalmana
end

--[[TODO: Take into account the poison and ignite ticks]]
function GetRealHealth(target)
	return target.health
end

function IsKillableWith(target, combo)
	return GetRealHealth(target) < GetComboDamage(target, combo)
end

function GetBestTarget(Range)
	local LessToKill = 100
	local LessToKilli = 0
	local target = nil
	
	--	LESS_CAST	
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(enemy, Range) then
			DamageToHero = myHero:CalcMagicDamage(enemy, 200)
			ToKill = enemy.health / DamageToHero
			if ((ToKill < LessToKill) or (LessToKilli == 0)) then
				LessToKill = ToKill
				LessToKilli = i
				target = enemy
			end
		end
	end
	
	if Menu.Misc.Selected and SelectedTarget ~= nil and ValidTarget(SelectedTarget, Range) then
		target = SelectedTarget
	end
	
	return target
end

--[[Ultimate calculations]]
function CountVectorsBetween(V1, V2, Vectors)
	local result = 0	 
	for i, test in ipairs(Vectors) do
		local NVector = V1:crossP(test)
		local NVector2 = test:crossP(V2)
		if NVector.y >= 0 and NVector2.y >= 0 then
			result = result + 1
		end
	end
	return result
end

function MidPointBetween(V1, V2) 
	return Vector((V1.x + V2.x)/2, 0, (V1.z + V2.z)/2)
end

function GetBestCone(Radius, Angle)
	local Targets = {}
	local PosibleCastPoints = {}

	for i, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) then
			local Position = VP:GetPredictedPos(enemy, Rdelay/1000)
			if Position and (GetDistance(myHero.visionPos, Position) <= Radius) and (GetDistance(myHero.visionPos, enemy) <= Radius) then
				table.insert(Targets, Vector(Position.x - myHero.x, 0, Position.z - myHero.z))
			end
		end
	end
	
	local Best = 0
	local BestCastPos = nil

	if #Targets == 1 then
		Best = 1
		BestCastPos = Radius*Vector(Targets[1].x,0,Targets[1].z):normalized()
	elseif #Targets > 1  then
		for i, edge in ipairs(Targets) do
			local Edge1 = Radius*Vector(edge.x,0,edge.z):normalized()
			local Edge2 = Edge1:rotated(0, Angle, 0)
			local Edge3 = Edge1:rotated(0, -Angle, 0)
			
			Count1 = CountVectorsBetween(Edge1, Edge2, Targets)
			Count2 = CountVectorsBetween(Edge3, Edge1, Targets)
			
			if Count1 >= Best then
				Best = Count1
				BestCastPos = MidPointBetween(Edge1, Edge2)
			end
			if Count2 >= Best then
				Best = Count2
				BestCastPos = MidPointBetween(Edge3, Edge1)
			end
		end
	end
	

	if BestCastPos then
		BestCastPos = Vector(myHero.x + BestCastPos.x, 0, myHero.z+BestCastPos.z)
	end
	return Best, BestCastPos
end	

function CountEnemiesInCone(CastPoint, Radius, Angle)
	local Direction = Radius * (-Vector(myHero.x, 0, myHero.z) + Vector(CastPoint.x,0,CastPoint.z)):normalized()
	local Vector1 = Direction:rotated(0, Angle/2, 0) 
	local Vector2 = Direction:rotated(0, -Angle/2, 0)
	local Targets = {}

	for i, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) then
			local Position = VP:GetPredictedPos(enemy, Rdelay/1000)
			if Position and (GetDistance(myHero.visionPos, Position) <= Radius) and GetDistance(myHero.visionPos, enemy) <= Radius then
				table.insert(Targets, Vector(Position.x - myHero.x, 0, Position.z - myHero.z))
			end
		end
	end
	return CountVectorsBetween(Vector2, Vector1, Targets)
end

function GetBestTargetPoisoned(Range)
	local LessToKill = 100
	local LessToKilli = 0
	local target = nil
	--	LESS_CAST	
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(enemy, Range) and isPoisoned(enemy) then
			DamageToHero = myHero:CalcMagicDamage(enemy, 200)
			ToKill = enemy.health / DamageToHero
			if (ToKill < LessToKill)  or (LessToKilli == 0) then
				LessToKill = ToKill
				LessToKilli = i
				target = enemy
			end
		end
	end
	return target
end

function isPoisoned(target)
	for i = 1, target.buffCount do
		local tBuff = target:getBuff(i)
		if BuffIsValid(tBuff) and tBuff.name:find("poison") and (tBuff.endT - (math.min(GetDistance(myHero.visionPos, target.visionPos), 700)/1900 + 0.25 + GetLatency()/2000) - GetGameTimer() > 0) then
			return true
		end
	end
	if Menu.MiscPoisoned2 then
		if LastQTime and (os.clock() - LastQTime) <= ((math.min(GetDistance(myHero.visionPos, target.visionPos), 700)/(1900 + target.ms) + 0.25)) then
			if (90 + VP:GetHitBox(target) - GetDistance(target.visionPos, LastQLocation)) > (os.clock() - LastQTime) * target.ms then
				return true
			end
		end
	end
	return false
end

function GetAOE(unit, position, radius, Sdelay, Sradius, Srange)
	local points = {}
	local targets = {}
	table.insert(points, position)
	table.insert(targets, unit)
	
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(unit, Qrange + Qradius*3) and enemy.networkID ~= unit.networkID then
			CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(enemy, Sdelay/1000, Sradius, Srange)
			table.insert(points, Position)
			table.insert(targets, enemy)
		end
	end
	
	while true do
		local MECa = MEC(points)
		local Circle = MECa:Compute()
		
		if Circle.radius <= radius and #points > 1 then
			return Circle.center
		end
		
		if #points <= 1 then
			return nil
		end
		
		local Dist = -1
		local MyPoint = points[1]
		local index = 0
		
		for i=2, #points, 1 do
			if GetDistance(points[i], MyPoint) >= Dist then
				Dist = GetDistance(points[i], MyPoint)
				index = i
			end
		end
		if index > 0 then
			table.remove(points, index)
		end
	end
end

function UseQC(unit)
	if not  (myHero:CanUseSpell(_Q) == READY) then return end
	local CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(unit, Qdelay/1000, Qradius, Qrange)
	if (HitChance < 2) then return end
	
	local predictedpos = Vector(CastPosition.x, 0, CastPosition.z)
	local mypos = Vector(myHero.x, 0, myHero.z)
	
	if Menu.Misc.AOE then
		local predictedpos2 = GetAOE(unit, Position, Qradius + 50, Qdelay, Qradius, Qrange)
		if predictedpos2 then
			predictedpos = predictedpos2
		end
	end
	
	if GetDistance(myHero.visionPos, predictedpos) < Qrange + Qradius then
		if ((Menu.Combo.Enabled and Menu.Combo.UseQ) or ((Menu.Harass.Enabled or Menu.Harass.Enabled2) and Menu.Harass.UseQ))  then		
				CastSpell(_Q, predictedpos.x, predictedpos.z)
		end
	end
end

function UseW(unit)
	if not (myHero:CanUseSpell(_W) == READY) then return end
	if Menu.Misc.WOnlyNonPoisoned and (isPoisoned(unit) or (myHero:CanUseSpell(_Q) == READY) or (os.clock() - LastQTime) < 0.9) then return end
	local CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(unit, Wdelay/1000, Wradius, Wrange)
	if HitChance < 1 then return end
	
	if Menu.Misc.AOE then
		local predictedpos2 = GetAOE(unit, Position, Wradius + 50, Wdelay, Wradius, Wrange)
		if predictedpos2 then
			CastPosition = predictedpos2
		end
	end
	
	local mypos = Vector(myHero.x, 0, myHero.z)
	if GetDistance(myHero.visionPos, CastPosition) < Wrange + Wradius then
		if ((Menu.Combo.Enabled and Menu.Combo.UseW) or  ((Menu.Harass.Enabled or Menu.Harass.Enabled2) and Menu.Harass.UseW)) then
			CastSpell(_W, CastPosition.x, CastPosition.z)
		end
	end
end

function UseSmartR(unit)
	if not Rready then return end
	local CastPosition,  HitChance, heroPos = VP:GetCircularCastPosition(unit, Rdelay/1000, 1)
	if HitChance < 2 then return end
	
	local unitpos = Vector(unit.x, 0, unit.z)
	local mypos = Vector(myHero.x, 0, myHero.z)
	if (not Rready) or not Menu.Combo.Enabled then return end

	if (CountEnemyHeroInRange(Rrange+700) == 2) and (IsKillableWith(unit, {_Q, _W, _R}) or (myHero.health < myHero.maxHealth*0.4)) then
		Count, RCastPosition = GetBestCone(Rrange, Rangle)
		if Count == 2 then
			Packet("S_CAST", {spellId = _R, toX = RCastPosition.x, toY = RCastPosition.z}):send()
		end
	end

	if CountEnemyHeroInRange(Rrange+700) == 1 and ((IsKillableWith(unit, TheCombo) and not IsKillableWith(unit, {_Q, _W, _E, _E})) or (myHero.health < myHero.maxHealth*0.3)) then
		Count, RCastPosition = GetBestCone(Rrange, Rangle)
		if Count >= 1 then
			Packet("S_CAST", {spellId = _R, toX = RCastPosition.x, toY = RCastPosition.z}):send()
		end
	end
end

function CastE(target)
	if Menu.Combo.UseEP then
		l33tultr4s3cr3tXpl01t(_E, target.networkID)
	else
		CastSpell(_E, target)
	end
end

function CastIgnite(target)
		CastSpell(_IGNITE, target)
end

function Combo()
	Qtarget = GetBestTarget(Qrange + Qradius / 2)
	if not Qtarget then
		Qtarget = GetBestTarget(Qrange*1.5 + Qradius / 2)
	end
	Etarget = GetBestTargetPoisoned(Erange)
	Etarget2 = GetBestTarget(Erange)
	IgniteTarget = GetBestTarget(650)
			
	--[[Q and W]]
	if Qtarget then
		UseQC(Qtarget)
		UseW(Qtarget)
		if Menu.Combo.UseR then
			UseSmartR(Qtarget)
		end
	end
	
	if Menu.Combo.Enabled then
		if Etarget2 and Menu.Combo.UseEKS then
			if Eready then
				if IsKillableWith(Etarget2, {_E}) then
					CastE(Etarget2)
				end
				if IsKillableWith(Etarget2, {_Q, _E}) and GetDistance(myHero.visionPos, Etarget2) > 300 then
					CastE(Etarget2)
				end
			end
		end

		if Etarget then
			if Eready then
				CastE(Etarget)
			end
		end
		
		if IgniteTarget and (_IGNITE ~= -1) then
			if IgniteReady then
				if IsKillableWith(IgniteTarget, {_Q,_E,_R,_IGNITE})  then
					CastIgnite(IgniteTarget)
				end
			end
		end
		if Menu.Combo.MoveTo then
			Orbwalk(Etarget)
		end
	end
end

function Harass() 
	Etarget = GetBestTargetPoisoned(Erange)
	
	--[[E]]
	if (Menu.Harass.Enabled or Menu.Harass.Enabled2) then
		if Etarget then
			if Eready and  Menu.Harass.UseE then
				CastE(Etarget)
			end
		end
		if Menu.Harass.MoveTo then
			Orbwalk(nil)
		end
	end
end


function GetNMinionsHit(Pos, radius)
	local count = 0
	for i, minion in pairs(EnemyMinions.objects) do
		if GetDistance(minion, Pos) < (radius + 50) then
			count = count + 1
		end
	end
	return count
end

function FarmQ(options)
	local Max = 0
	local MaxPos = Vector(0, 0, 0)
	if ((GetTickCount() - CheckedW) < 1000) and options[2] then return end --Wait for W
	for i, minion in pairs(EnemyMinions.objects) do
		--Freeze: LastHit minions between AArange and Qrange with Q
		if Menu.Farm.Freeze and (GetDistance(myHero.visionPos, minion) < Qrange) and minion.health < 50 then
			local Position = VP:GetCircularCastPosition(minion, Qdelay/1000, Qradius/2, Qrange)
			if Position then
				CastSpell(_Q, Position.x, Position.z)	
			else
				CastSpell(_Q, minion.x, minion.z)	
			end
		end
	
		--Laneclear: hit as many minions as we can with Q
		if Menu.Farm.LaneClear then
			if (GetDistance(myHero.visionPos, minion) < Qrange) then
				Count = GetNMinionsHit(minion, Qradius)
				if Count > Max then
					Max = Count
					local Position = VP:GetCircularCastPosition(minion, Qdelay/1000, Qradius/2, Qrange)
					if Position then
						MaxPos = Vector(Position.x, 0, Position.z)
					else
						MaxPos = Vector(minion.x, 0, minion.z)
					end
				end
			end
		end
	end
	
	if Menu.Farm.LaneClear and (Max ~= 0) then
		CastSpell(_Q, MaxPos.x, MaxPos.z)	
	end
	if (GetTickCount() - CheckedQ) > 500 then CheckedQ = GetTickCount() end
end

function FarmW(options)
	local Max = 0
	local MaxPos = Vector(0, 0, 0)
	for i, minion in pairs(EnemyMinions.objects) do
		--Laneclear or freeze hit as many caster minions as we can with W
		if Menu.Farm.LaneClear or Menu.Farm.Freeze then
			if (GetDistance(myHero.visionPos, minion) < Wrange) and (minion.charName:find("Wizard") or minion.charName:find("Caster")) then
				Count = GetNMinionsHit(minion, Wradius)
				if Count > Max then
					Max = Count
					local Position = VP:GetCircularCastPosition(minion, Wdelay/1000, Wradius/2, Wrange)
					if Position then
						MaxPos = Vector(Position.x, 0, Position.z)
					else
						MaxPos = Vector(minion.x, 0, minion.z)
					end
				end
			end
		end
		--if i > 4 then break end -- anti lag
	end
	
	if (Max > 2) then
		CastSpell(_W, MaxPos.x, MaxPos.z)
		if options[1] then
			CastSpell(_Q, MaxPos.x, MaxPos.z)
		end
	end
	if (GetTickCount() - CheckedW) > 1500 then CheckedW = GetTickCount() end
end

function FarmE(options)
	for i, minion in pairs(EnemyMinions.objects) do
		if Menu.Farm.Freeze then
			if (GetDistance(myHero.visionPos, minion) < Erange) and (minion.health < GetDamage(minion, _E)) and isPoisoned(minion) then
				CastE(minion)
			end
		end

		if Menu.Farm.LaneClear then
			if (GetDistance(myHero.visionPos, minion) < Erange)  and (minion.health < GetDamage(minion, _E)) and isPoisoned(minion) then
				CastE(minion)
			end
		end
	end
end

function Farm(options)
	local useQ = options[1]
	local useW = options[2]
	local useE = options[3]
	
	if useQ and Qready then FarmQ(options) end
	if useW and Wready then FarmW(options) end
	if useE and Eready then FarmE(options) end
	
	if Menu.Farm.MoveTo then
		Orbwalk(nil)
	end	
end

function JungleFarm()
	if Menu.JungleFarm.Advanced then
		for i, location in ipairs(JungleLocations) do
			if (GetDistance(myHero.visionPos, location) < 150) and Qready and Wready then
				local wcastloc = JungleWCastLocations[i]
				local qcastloc = JungleQCastLocations[i]
				CastSpell(_W, wcastloc.x, wcastloc.z)
				CastSpell(_Q, qcastloc.x, qcastloc.z)
			end
		end
	end
	for i, minion in pairs(JungleMinions.objects) do
		if (GetDistance(myHero.visionPos, minion) <= Erange) and isPoisoned(minion) and Eready and Menu.JungleFarm.UseE then
			CastE(minion)
		end
	
		if (GetDistance(myHero.visionPos, minion) <= Qrange) and Qready and Menu.JungleFarm.UseQ then
				local Position = VP:GetCircularCastPosition(minion, Qdelay/1000, Qradius/2, Qrange)
				if Position then
					CastSpell(_Q, Position.x, Position.z)
				else
					CastSpell(_Q, minion.x, minion.z)
				end
		end
		
		if (GetDistance(myHero.visionPos, minion) <= Wrange) and Wready and Menu.JungleFarm.UseW then
				local Position = VP:GetCircularCastPosition(minion, Wdelay/1000, Wradius/2, Wrange)
				if Position then
					CastSpell(_W, Position.x, Position.z)
				else
					CastSpell(_W, minion.x, minion.z)
				end
		end	
	end
	if Menu.JungleFarm.MoveTo then
		Orbwalk(JungleMinions.objects[1])
	end	
end

function OnTick()
	Qready = (myHero:CanUseSpell(_Q) == READY)
	Wready = (myHero:CanUseSpell(_W) == READY)
	Eready = (myHero:CanUseSpell(_E) == READY)
	Rready = (myHero:CanUseSpell(_R) == READY)
	if TargetHaveBuff("Recall", myHero) then
		RecallTime = GetTickCount()
	end
	IgniteReady = (_IGNITE ~= -1) and (myHero:CanUseSpell(_IGNITE) == READY) or false
	RefreshKillableTexts()
	Combo()
	Harass()
	
	if Menu.JungleFarm.Enabled and not Menu.Combo.Enabled then
		JungleFarm()
	end
	
	if (Menu.JungleFarm.Enabled or Menu.JungleFarm.Steal) and not Menu.Combo.Enabled then
		JungleMinions:update()
		if JungleMinions.objects[1] ~= nil then
			
		end
	end
	
	if Menu.JungleFarm.Steal and not Menu.Combo.Enabled  then
		if JungleMinions.objects[1] ~= nil and JungleMinions.objects[1].maxHealth > 5000 and JungleMinions.objects[1].health < 100 and GetDistance(myHero.visionPos, JungleMinions.objects[1]) < Erange then
			CastE(JungleMinions.objects[1])
		end
	end
	
	if (Menu.Farm.Freeze or Menu.Farm.LaneClear) and not Menu.Combo.Enabled then
		local options = {} --Q, W, E
		EnemyMinions:update()
		if (Menu.Farm.Freeze and (Menu.Farm.UseQ == 2 or Menu.Farm.UseQ == 4)) or (Menu.Farm.LaneClear and (Menu.Farm.UseQ == 3 or Menu.Farm.UseQ == 4)) then
			table.insert(options, true)
		else
			table.insert(options, false)
		end

		if (Menu.Farm.Freeze and (Menu.Farm.UseW == 2 or Menu.Farm.UseW == 4)) or (Menu.Farm.LaneClear and (Menu.Farm.UseW == 3 or Menu.Farm.UseW == 4)) then
			table.insert(options, true)
		else
			table.insert(options, false)
		end

		if (Menu.Farm.Freeze and (Menu.Farm.UseE == 2 or Menu.Farm.UseE == 4)) or (Menu.Farm.LaneClear and (Menu.Farm.UseE == 3 or Menu.Farm.UseE == 4)) then
			table.insert(options, true)
		else
			table.insert(options, false)
		end
		Farm(options)
	end
	
	if ((Menu.Ultimate.Auto) >= 2) and Rready then
		local Mintargets = Menu.Ultimate.Auto - 1
		Count, RCastPosition = GetBestCone(Rrange, Rangle)
		if Count >= Mintargets then
			Packet("S_CAST", {spellId = _R, toX = RCastPosition.x, toY = RCastPosition.z}):send()
		end
	end
	if (Menu.Ultimate.AutoAim) then
		Count, RCastPosition = GetBestCone(Rrange, Rangle)
		if Count >= 1 then
			Packet("S_CAST", {spellId = _R, toX = RCastPosition.x, toY = RCastPosition.z}):send()
		end
	end
	if Menu.Misc.Tear then
		if (CountEnemyHeroInRange(2*Qrange) == 0) and (LastSpellTime > 4100) and Qready and (GetTickCount() - RecallTime) >= 10000 then
			CastSpell(_Q, myHero.x + math.random(-400, 400) , myHero.z + math.random(-400, 400))
		end
	end
end


--[[Functions related with drawing]]

--[[Update the bar texts]]
function RefreshKillableTexts()
	if ((GetTickCount() - lastrefresh) > 100) and (Menu.Drawing.BarHealth or Menu.Drawing.BarHealthT) then
		for i=1, heroManager.iCount do
			local enemy = heroManager:GetHero(i)
			if ValidTarget(enemy) then
				DamageToHeros[i] =  GetComboDamage(enemy, TheCombo) 
			end
		end
		lastrefresh = GetTickCount()
	end
end
	
--[[	Credits to zikkah	]]
function GetHPBarPos(enemy)
	enemy.barData = GetEnemyBarData()
	local barPos = GetUnitHPBarPos(enemy)
	local barPosOffset = GetUnitHPBarOffset(enemy)
	local barOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
	local barPosPercentageOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
	local BarPosOffsetX = 171
	local BarPosOffsetY = 46
	local CorrectionY =  0
	local StartHpPos = 31
	barPos.x = barPos.x + (barPosOffset.x - 0.5 + barPosPercentageOffset.x) * BarPosOffsetX + StartHpPos
	barPos.y = barPos.y + (barPosOffset.y - 0.5 + barPosPercentageOffset.y) * BarPosOffsetY + CorrectionY 
						
	local StartPos = Vector(barPos.x , barPos.y, 0)
	local EndPos =  Vector(barPos.x + 108 , barPos.y , 0)

	return Vector(StartPos.x, StartPos.y, 0), Vector(EndPos.x, EndPos.y, 0)
end

function DrawIndicator(unit, health)
	local SPos, EPos = GetHPBarPos(unit)
	local barlenght = EPos.x - SPos.x
	local Position = SPos.x + (health / unit.maxHealth) * barlenght
	if Position < SPos.x then
		Position = SPos.x
	end
	DrawText("|", 13, Position, SPos.y+10, ARGB(255,0,255,0))
end

function DrawOnHPBar(unit, health)
	local Pos = GetHPBarPos(unit)
	if health < 0 then
		DrawCircle2(unit.x, unit.y, unit.z, 100, ARGB(255, 255, 0, 0))	
		DrawText("HP: "..health,13, Pos.x, Pos.y, ARGB(255,255,0,0))
	else
		DrawText("HP: "..health,13, Pos.x, Pos.y, ARGB(255,0,255,0))
	end
end

function DrawNoMana()
	local myPos = GetHPBarPos(myHero)
	timetoregen = (ComboManaCost(TheCombo) - myHero.mana) / myHero.mpRegen
	DrawText("No Mana ("..math.floor(timetoregen).."s) !!", 13, myPos.x, myPos.y, ARGB(255,0,225,255))
end

--[[Credits to barasia, vadash and viseversa for anti-lag circles]]
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
	if not Menu.Drawing.Ranges.LowC then
		return DrawCircle(x, y, z, radius, color)
	end
	if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y })  then
		DrawCircleNextLvl(x, y, z, radius, 1, color, 75)	
	end
end

function OnDraw()
	--DrawText(mousePos.x..", "..mousePos.z, 13, 10, 10, ARGB(255,0,255,0))
	if Menu.JungleFarm.Enabled then
		for i, location in ipairs(JungleLocations) do
			DrawCircle(location.x, location.y, location.z, 100, 0x19A712)
		end
	end

	if Menu.Drawing.Ranges.Qrange then
		DrawCircle2(myHero.x, myHero.y, myHero.z, Qrange, ARGB(Menu.Drawing.Ranges.Qcolor[1], Menu.Drawing.Ranges.Qcolor[2], Menu.Drawing.Ranges.Qcolor[3], Menu.Drawing.Ranges.Qcolor[4] ))
	end
	if Menu.Drawing.Ranges.Erange then
		DrawCircle2(myHero.x, myHero.y, myHero.z, Erange, ARGB(Menu.Drawing.Ranges.Ecolor[1], Menu.Drawing.Ranges.Ecolor[2], Menu.Drawing.Ranges.Ecolor[3], Menu.Drawing.Ranges.Ecolor[4] ))
	end
	if Menu.Drawing.Ranges.AArange then
		DrawCircle2(myHero.x, myHero.y, myHero.z, AArange, ARGB(Menu.Drawing.Ranges.AAcolor[1], Menu.Drawing.Ranges.AAcolor[2], Menu.Drawing.Ranges.AAcolor[3], Menu.Drawing.Ranges.AAcolor[4] ))
	end

	--[[HealthBar HP tracker]]
	if Menu.Drawing.BarHealth and false then
		for i=1, heroManager.iCount do
			local enemy = heroManager:GetHero(i)
			if ValidTarget(enemy) then
				if DamageToHeros[i] ~= nil then
					RemainingHealth = enemy.health - DamageToHeros[i]
				end
				if RemainingHealth ~= nil then
					DrawIndicator(enemy, math.floor(RemainingHealth))
				end
			end
		end
	end
	
	--[[Killable text tracker]]
	if Menu.Drawing.BarHealthT and false then
		for i=1, heroManager.iCount do
			local enemy = heroManager:GetHero(i)
			if ValidTarget(enemy) then
				if DamageToHeros[i] ~= nil then
					RemainingHealth = enemy.health - DamageToHeros[i]
				end
				if RemainingHealth ~= nil then
					DrawOnHPBar(enemy, math.floor(RemainingHealth))
				end
			end
		end
	end

	--[[Draw low mana text]]
	if Menu.Drawing.NoMana and false then
		if myHero.mana < ComboManaCost(TheCombo) then
			DrawNoMana()
		end
	end
	
	if Menu.Misc.Selected and SelectedTarget ~= nil and ValidTarget(SelectedTarget) then
		DrawCircle2(SelectedTarget.x, SelectedTarget.y, SelectedTarget.z, 103, ARGB(255,0,255,0))
	end
end

function OnWndMsg(Msg, Key)
	if Msg == WM_LBUTTONDOWN then
		local minD = 0
		local starget = nil
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) then
				if GetDistance(enemy, mousePos) <= minD or starget == nil then
					minD = GetDistance(enemy, mousePos)
					starget = enemy
				end
			end
		end
		
		if starget and minD < 100 then
			if SelectedTarget and starget.charName == SelectedTarget.charName then
				SelectedTarget = nil
			else
				SelectedTarget = starget
				print("<font color=\"#FF0000\">Cassiopeia: New target selected: "..starget.charName.."</font>")
			end
		end
	end
end

function IHaveRed()
	for i = 1, myHero.buffCount do
		local tBuff = myHero:getBuff(i)
		if BuffIsValid(tBuff) and tBuff.name:lower():find("lizard") and (tBuff.endT - GetGameTimer() > 0) then
			return true
		end
	end
	return false
end

function MoveToMouse()
	local Mvector = Vector(myHero.x, 0, myHero.z) + 300 * Vector(mousePos.x - myHero.x, 0, mousePos.z - myHero.z):normalized()
	dontspam = dontspam or 0
	if os.clock() - dontspam > 0.1 and not _G.Evade and not _G.evade then
		myHero:MoveTo(Mvector.x, Mvector.z)
	end
end

function Orbwalk(target)
	local NoOrbwalk = true
	
	if target and (target.type ~= "obj_AI_Hero" or (not ((myHero.health / myHero.maxHealth)*100 < Menu.Orbwalking.LowHS) and ((Menu.Orbwalking.RedB and IHaveRed()) or ((target.health / target.maxHealth)*100 < Menu.Orbwalking.LowHE) or (false)))) then
		NoOrbwalk = false
	end
	
	if myHero.isCasting or myHero:CanUseSpell(_Q) == READY or myHero:CanUseSpell(_W) == READY or myHero:CanUseSpell(_E) == READY or ((myHero:CanUseSpell(_Q) == COOLDOWN) and (myHero:GetSpellData(_Q).currentCd < LastAttackAnimation + GetLatency()/2000 )) or ((myHero:CanUseSpell(_W) == COOLDOWN) and (myHero:GetSpellData(_W).currentCd < LastAttackAnimation + GetLatency()/2000 ))  or ((myHero:CanUseSpell(_E) == COOLDOWN) and (myHero:GetSpellData(_E).currentCd < LastAttackAnimation + GetLatency()/2000 )) then  
		 NoOrbwalk = true
	end
	
	if ((target == nil) or not ValidTarget(target) or GetDistance(myHero.visionPos, target) > AArange or NoOrbwalk) then
		if  (os.clock() + GetLatency()/2000 > LastAttack + LastAttackWindup + 0.05) then
			MoveToMouse()
		end
	else
		if os.clock() + GetLatency()/2000 > LastAttack + LastAttackAnimation and not _G.Evade and not _G.evade then
			Packet('S_MOVE', {type = 3, targetNetworkId=target.networkID}):send()
			if os.clock() - LastAttack > LastAttackWindup then
				LastAttack = os.clock()
			end
		elseif os.clock() + GetLatency()/2000 > LastAttack + LastAttackWindup + 0.05 and not _G.Evade and not _G.evade then
			MoveToMouse()
		end
	end
end

function OnSendPacket(p)
	if Menu.Ultimate.Block then
		local packet = Packet(p)
		if packet:get('name') == 'S_CAST' then
		        if packet:get('spellId') == _R then
					CastPoint = Vector(packet:get('toX'), 0, packet:get('toY'))
					if CountEnemiesInCone(CastPoint,Rrange, Rangle) == 0 then
						packet:block()
					end
		        end
		end
	end
end

function OnProcessSpell(unit, spell)
	 if unit.isMe and not spell.name:lower():find("attack") then
		LastSpellTime = GetTickCount()
	end
	if unit.isMe and spell.name:lower():find("attack") then
		LastAttack = os.clock() - GetLatency()/2000
		LastAttackAnimation = spell.animationTime
		LastAttackWindup = spell.windUpTime
	end

	--[[Casting E resets the auto attacck timer]]
	if unit.isMe and spell.name:lower():find("twin") then
		LastAttack = 0
	end

	if unit.isMe and spell.name:lower():find("noxiousblast") then
		LastQLocation = Vector(spell.endPos.x, 0, spell.endPos.z)
		LastQTime = os.clock()
	end
end
--EOS--
