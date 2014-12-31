<?php exit() ?>--by Tux 70.160.53.227
if myHero.charName ~= "Thresh" then return end

local Users = 
{
	["tux"] = true, -- Me
	["vex"] = true, -- Carl
	["easyhiv"] = true, --Alex
	["ires"] = true, --Icy
	["xpliclt"] = true, --Gurinder
	["ryosu"] = true, --Matt
	["apraxia"] = true, --Steven
	["skinalt"] = true, --Terence
	["adamhacks"] = true, --Silent Man
	["getsnipeddown"] = true, --David
	["qqq"] = true, --Daniel
	["ahmedzarga23"] = true --Ahmed
}

if not Users[GetUser():lower()] then return end

if debug.getinfo and debug.getinfo(_G.GetUser).what == "C" then
	cBa = _G.GetUser
	_G.GetUser = function() return end
	if debug.getinfo(_G.GetUser).what == "Lua" then
		_G.GetUser = cBa
	end
else
	PrintSystemMessage("Stop messing with shit!")
end

require "VPrediction"
require "Collision"

local qRange = 1055
local qSpeed = 1200
local qDelay = 0.500
local qWidth = 60
local eRange = 500
local eSpeed = 1200
local eDelay = 0.333
local eWidth = 180
local rRadius = 450
local wRange = 950

local qCastAt = 0

--Auto Flay Start
local shouldFlay =
{
	{charName = "Aatrox", spellName = "AatroxQ", missileName = "AatroxQ.troy", radius = 145, delay = 250},
	{charName = "Ahri", spellName = "AhriTumble", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Akali", spellName = "AkaliShadowDance", missileName = nil, radius = 0, delay = 0},
	{charName = "Alistar", spellName = "Headbutt", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Amumu", spellName = "BandageToss", missileName = "Bandage_beam.troy", radius = 80, delay = 250},
	{charName = "Corki", spellName = "CarpetBomb", missileName = nil, radius = 40, delay = 0},
	{charName = "Diana", spellName = "DianaTeleport", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Elise", spellName = "elisespideredescent", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Ezreal", spellName = "EzrealArcaneShift", missileName = nil, radius = 0, delay = 0.5},
	{charName = "FiddleSticks", spellName = "Crowstorm", missileName = nil, radius = 150, delay = 0.5},
	{charName = "Fiora", spellName = "FioraQ", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Fizz", spellName = "FizzPiercingStrike", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Gragas", spellName = "GragasBodySlam", missileName = nil, radius = 25, delay = 0.3},
	{charName = "Graves", spellName = "GravesMove", missileName = nil, radius = 25, delay = 0.3},
	{charName = "Hecarim", spellName = "HecarimUlt", missileName = nil, radius = 100, delay = 0.5},
	{charName = "Irelia", spellName = "IreliaGatotsu", missileName = nil, radius = 0, delay = 0},
	--{charName = "JarvanIV", spellName = "JarvonIVDragonStrike", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Jax", spellName = "JaxLeapStrike", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Jayce", spellName = "JayceToTheSkies", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Kassadin", spellName = "RiftWalk", missileName = nil, radius = 75, delay = 0.5},
	{charName = "Katarina", spellName = "KatarinaE", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Khazix", spellName = "KhazixE" or "khazixelong", missileName = nil, radius = 150, delay = 0.5},
	{charName = "Leblanc", spellName = "LeblancSlide" or "LeblanceSlideM", missileName = nil, radius = 110, delay = 0.5},
	{charName = "LeeSin", spellName = "blindmonkqtwo" or "BlindMonkWOne", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Leona", spellName = "LeonaZenithBlade", missileName = "Leona_ZenithBlade_mis.troy", radius = 80, delay = 250},
	{charName = "Lissandra", spellName = "LissandraW", missileName = nil, radius = 40, delay = 0.5},
	{charName = "Lucian", spellName = "LucianE", missileName = nil, radius = 25, delay = 0.5},
	{charName = "Nautilus", spellName = "NautilusAnchorDrag", missileName = "Nautilus_Q_mis.troy", radius = 80, delay = 250},
	--{charName = "Nocturne", spellName = "Paranoia", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Pantheon", spellName = "Pantheon_LeapBash", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Poppy", spellName = "PoppyHeroicCharge", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Quinn", spellName = "QuinnE", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Renekton", spellName = "RenektonSliceAndDice", missileName = nil, radius = 25, delay = 0.5},
	--{charName = "Rengar", spellName = "RengarQ", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Riven", spellName = "RivenTriCleave_03", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Sejuani", spellName = "SejuaniArcticAssault", missileName = nil, radius = 37.5, delay = 0.5},
	{charName = "Shen", spellName = "ShenShadowDash", missileName = "shen_shadowDash_mis.troy", radius = 50, delay = 0},
	{charName = "Shyvana", spellName = "ShyvanaTransformCast", missileName = nil, radius = 80, delay = 0.5},
	{charName = "Talon", spellName = "TalonCutthroat", missileName = nil, radius = 0, delay = 0},
	{charName = "Tristana", spellName = "RocketJump", missileName = nil, radius = 135, delay = 250},
	{charName = "Tryndamere", spellName = "slashCast", missileName = nil, radius = 112.5, delay = 0.5},
	{charName = "Vi", spellName = "ViQ", missileName = "Vi_Q_mis.troy", radius = 55, delay = 250},
	{charName = "MonkeyKing", spellName = "MonkeyKingNimbus", missileName = nil, radius = 0, delay = 0},
	{charName = "Xin Zhao", spellName = "XenZhaoSweep", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Yasuo", spellName = "YasuoDashWrapper", missileName = nil, radius = 0, delay = 0.5},
	{charName = "Zac", spellName = "ZacE", missileName = nil, radius = 0, delay = 0.5}
}

local targetedDistanceBuffer = 75*75

local spellCastTick = 0
local minDelay = 0
local maxDelay = 2000

local particleFound
local spellParticle = {valid = false}
local maxParticleDistance = 250*250

function IsOnEnemyTeam(charName)
	local onEnemyTeam = false
	local hero
	local i = 1
	while i <= heroManager.iCount and not onEnemyTeam do
		hero = heroManager:GetHero(i)
		if hero.team ~= player.team and hero.charName == charName then onEnemyTeam = true end
		i = i + 1
		end
	return onEnemyTeam
end

function CleanTable()
	local i = 1
	while i <= #shouldFlay do
		if not IsOnEnemyTeam(shouldFlay[i].charName) then table.remove(shouldFlay, i)
		else i = i + 1 
		end
	end
end

function GetSpellInfo(spell)
	local detected = false
	local radius
	local spellDelay
	local particleName
	local i = 1
	while i <= #shouldFlay and not detected do
		if shouldFlay[i].spellName == spell.name then
			detected = true
			radius = shouldFlay[i].radius
			spellDelay = shouldFlay[i].delay
			particleName = shouldFlay[i].missileName
		end
		i = i + 1
	end
	return radius, spellDelay, particleName
end

function AffectsMe(spell, radius)
	local willAffectMe
	local radius = 0
	if radius == 0 then
		willAffectMe = GetDistanceSqr(spell.endPos) <= targetedDistanceBuffer
	else
		willAffectMe = GetDistanceSqr(spell.endPos) <= radius*radius
	end
	return willAffectMe
end

function OnProcessSpell(caster, spell)
	if ThreshConfig.AutoFlay then
		if caster.team ~= player.team and string.find(spell.name, "Basic") == nil then
			radius, spellDelay, particleName = GetSpellInfo(spell)
			if AffectsMe(spell, radius) then
				if particleName then
					particleFound = particleName
				else
					spellCastTick = GetTickCount()
					minDelay = spellDelay
				end
			end
		end
	end
end

function OnCreateObj(particle)
	if not spellParticle.valid and particle.team ~= player.team and particle.name == particleFound then
		spellParticle = particle
		particleFound = nil
	end
end

--Auto Flay End

ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, qRange, DAMAGE_MAGIC, true)

function OnLoad()
    ThreshConfig = scriptConfig("The Play Maker", "Thresh")
    ThreshConfig:addParam("Engage", "Engage them", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
	ThreshConfig:addParam("TeamFight", "Team Fight Combo", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
	ThreshConfig:addParam("Push", "Push with Flay", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("E"))
	ThreshConfig:addParam("Harass", "Harass with Hook", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
	ThreshConfig:addParam("AutoFlay", "Auto Flay Gap Closer", SCRIPT_PARAM_ONOFF, false)
	ThreshConfig:addParam("Box", "Auto Ultimate in Combo", SCRIPT_PARAM_ONOFF, false)
	ThreshConfig:addParam("Lantern", "Auto Lantern Most Armour in Combo", SCRIPT_PARAM_ONOFF, false)
	ThreshConfig:addParam("BoxCount", "Enemy Count before Using Ulti", SCRIPT_PARAM_SLICE, 3, 0, 5, 0)
    ThreshConfig:addParam("qHitChance", "Q Hit Chance Buffer", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
	ThreshConfig:addParam("qRangeSlider", "Q Range", SCRIPT_PARAM_SLICE, 955, 0, 1055, 0)
	ThreshConfig:addParam("wRangeSlider", "W Range - Actual Max 950", SCRIPT_PARAM_SLICE, 950, 0, 1500, 0)
	ThreshConfig:addParam("eRangeSlider", "E Range", SCRIPT_PARAM_SLICE, 450, 0, 500, 0)
	ThreshConfig:addParam("rRangeSlider", "Use Auto Ultimate at this range", SCRIPT_PARAM_SLICE, 400, 0, 450, 0)
	ts.name = "Thresh"
    ThreshConfig:addTS(ts)
	PrintChat("The Play Maker - Pulling more bitches than you ever could!")
	
	VP = VPrediction()
end

function OnGainBuff(unit, buff)
	if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "threshqfakeknockup" then
		hooked = true
	end
end

function OnLoseBuff(unit, buff)
	if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "threshqfakeknockup" then
		hooked = false
	end
end

function CanCast(Spell)
	return (player:CanUseSpell(Spell) == READY)
end

function Engage()
	local spellQ = myHero:GetSpellData(_Q).name
	if CanCast(_Q) and GetDistance(ts.target) <= ThreshConfig.qRangeSlider and spellQ == "ThreshQ" then
		local CastPosition, HitChance, Position = VP:GetLineCastPosition(ts.target, qDelay, qWidth, qRange, qSpeed, myHero, true)
		if HitChance >= ThreshConfig.qHitChance then
			CastSpell(_Q, CastPosition.x, CastPosition.z)
		end
	end
	if CanCast(_E) and GetDistance(ts.target) < ThreshConfig.eRangeSlider then
		xPos = myHero.x + (myHero.x - ts.target.x)
		zPos = myHero.z + (myHero.z - ts.target.z)
		CastSpell(_E, xPos, zPos)
	elseif GetDistance(ts.target) > ThreshConfig.eRangeSlider and os.time() > qCastAt + 1400 and spellQ == "threshqleap" then
		CastSpell(_Q)
	end
	if hooked == true then
		LanternMostAr()
	end
	if ThreshConfig.Box then AutoBox() end
end

function TeamFight()
	local spellQ = myHero:GetSpellData(_Q).name
	if CanCast(_Q) and GetDistance(ts.target) <= ThreshConfig.qRangeSlider and spellQ == "ThreshQ" then
		local CastPosition, HitChance, Position = VP:GetLineCastPosition(ts.target, qDelay, qWidth, qRange, qSpeed, myHero, true)
		if HitChance >= ThreshConfig.qHitChance then
			CastSpell(_Q, CastPosition.x, CastPosition.z)
		end
	end
	if CanCast(_E) and GetDistance(ts.target) < ThreshConfig.eRangeSlider then
		xPos = myHero.x + (myHero.x - ts.target.x)
		zPos = myHero.z + (myHero.z - ts.target.z)
		CastSpell(_E, xPos, zPos)
	elseif GetDistance(ts.target) > ThreshConfig.eRangeSlider and os.time() > qCastAt + 1400 and spellQ == "threshqleap" then
		CastSpell(_Q)
	end
	if ThreshConfig.Lantern then LanternMostAr() end
	if ThreshConfig.Box then AutoBox() end
end

function GetHighestArmorAlly()
	local mostArmor = 1
	local mostArmorChamp = nil
	for _, Ally in pairs(GetAllyHeroes()) do
		if Ally.armor > mostArmor and GetDistance(Ally) <= ThreshConfig.wRangeSlider and Ally.dead == false then
			mostArmor = Ally.armor
			mostArmorChamp = Ally
		end
	end
	if mostArmor ~= 1 and mostArmorChamp ~= nil then 
		return mostArmorChamp
	else
		return false
	end
end

function LanternMostAr()
	local allyPos = GetHighestArmorAlly()
	if myHero.team ~= nil and allyPos ~= false and CanCast(_W) then
		CastSpell(_W, allyPos.x, allyPos.z)
	end
end

function HookHarass()
	local spellQ = myHero:GetSpellData(_Q).name
	if CanCast(_Q) and  GetDistance(ts.target) <= ThreshConfig.qRangeSlider and spellQ == "ThreshQ" then
		local CastPosition, HitChance, Position = VP:GetLineCastPosition(ts.target, qDelay, qWidth, qRange, qSpeed, myHero, true)
		if HitChance >= ThreshConfig.qHitChance then
			CastSpell(_Q, CastPosition.x, CastPosition.z)
		end
	end
end

function FlayPush()
	for j, enemy in pairs(GetEnemyHeroes()) do
		if CanCast(_E) and GetDistance(enemy) <= eRange then
			CastSpell(_E, enemy.x, enemy.z)
		end
	end
end

function AutoBox()
	if CanCast(_R) and CountEnemyHeroInRange(ThreshConfig.rRangeSlider) >= ThreshConfig.BoxCount then
		CastSpell(_R)
	end
end

function OnTick()
	ts:update()
	actualDelay = GetTickCount() - spellCastTick
	shouldCast = (spellParticle.valid and GetDistanceSqr(spellParticle) <= maxParticleDistance)
	if ts.target ~= nil then
		if ValidTarget(ts.target) and ThreshConfig.Engage then Engage() end
		if ValidTarget(ts.target) and ThreshConfig.TeamFight then TeamFight() end
		if ValidTarget(ts.target) and ThreshConfig.Harass then HookHarass() end
		if ThreshConfig.Push then FlayPush() end
	end
	if ThreshConfig.AutoFlay then
		if shouldCast and CanCast(_E) then 
			CastSpell(_E, ts.target.x, ts.target.z)
		end
	end
end