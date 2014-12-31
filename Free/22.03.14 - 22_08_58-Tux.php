<?php exit() ?>--by Tux 70.160.53.227
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
	["qqq"] = true --Daniel
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

ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, qRange, DAMAGE_MAGIC, true)

function OnLoad()
    ThreshConfig = scriptConfig("The Play Maker", "Thresh")
    ThreshConfig:addParam("Engage", "Engage them", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
	ThreshConfig:addParam("TeamFight", "Team Fight Combo", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
	ThreshConfig:addParam("Push", "Push with Flay", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("E"))
	ThreshConfig:addParam("Harass", "Harass with Hook", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
	ThreshConfig:addParam("Box", "Auto Ultimate in Combo", SCRIPT_PARAM_ONOFF, false)
	ThreshConfig:addParam("AutoFlay", "Auto Push Flay Gap Closers", SCRIPT_PARAM_ONOFF, false)
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

function AutoPushFlay()
	for i, target in pairs(GetEnemyHeroes()) do
		if CanCast(_E) and GetDistance(target) <= ThreshConfig.eRangeSlider then
			local CastPosition, HitChance, Position = VP:GetLineCastPosition(target, eDelay, eWidth, eRange, eSpeed, myHero, true)
			if HitChance >= 5 then
				CastSpell(_E, CastPosition.x, CastPosition.z)
			end
		end
	end
end

function FlayPush()
	if ValidTarget(ts.target, ThreshConfig.eRangeSlider) and CanCast(_E) then
		CastSpell(_E, ts.target.x, ts.target.z)
	end
end

function AutoBox()
	if CanCast(_R) and CountEnemyHeroInRange(ThreshConfig.rRangeSlider) >= ThreshConfig.BoxCount then
		CastSpell(_R)
	end
end

function OnTick()
	ts:update()
	if ts.target ~= nil then
		if ValidTarget(ts.target) and ThreshConfig.Engage then Engage() end
		if ValidTarget(ts.target) and ThreshConfig.TeamFight then TeamFight() end
		if ValidTarget(ts.target) and ThreshConfig.Harass then HookHarass() end
		if ThreshConfig.Push then FlayPush() end
	end
	if ThreshConfig.AutoFlay then AutoPushFlay() end
end

--Manc's VMA modified

function OnProcessSpell(unit, spell)
    if ThreshConfig.AutoFlay then
    local jarvanAddition = unit.charName == "JarvanIV" and unit:CanUseSpell(_Q) ~= READY and _R or _Q -- Did not want to break the table below.
    local isAGapcloserUnit = {
--      ['Ahri']        = {true, spell = _R, 				  range = 450,   projSpeed = 2200},
        ['Aatrox']      = {true, spell = _Q,                  range = 1000,  projSpeed = 1200, },
        ['Akali']       = {true, spell = _R,                  range = 800,   projSpeed = 2200, }, -- Targeted ability
        ['Alistar']     = {true, spell = _W,                  range = 650,   projSpeed = 2000, }, -- Targeted ability
        ['Diana']       = {true, spell = _R,                  range = 825,   projSpeed = 2000, }, -- Targeted ability
        ['Gragas']      = {true, spell = _E,                  range = 600,   projSpeed = 2000, },
        ['Graves']      = {true, spell = _E,                  range = 425,   projSpeed = 2000, exeption = true },
        ['Hecarim']     = {true, spell = _R,                  range = 1000,  projSpeed = 1200, },
        ['Irelia']      = {true, spell = _Q,                  range = 650,   projSpeed = 2200, }, -- Targeted ability
        ['JarvanIV']    = {true, spell = jarvanAddition,      range = 770,   projSpeed = 2000, }, -- Skillshot/Targeted ability
        ['Jax']         = {true, spell = _Q,                  range = 700,   projSpeed = 2000, }, -- Targeted ability
        ['Jayce']       = {true, spell = 'JayceToTheSkies',   range = 600,   projSpeed = 2000, }, -- Targeted ability
        ['Khazix']      = {true, spell = _E,                  range = 900,   projSpeed = 2000, },
        ['Leblanc']     = {true, spell = _W,                  range = 600,   projSpeed = 2000, },
        ['LeeSin']      = {true, spell = 'blindmonkqtwo',     range = 1300,  projSpeed = 1800, },
        ['Leona']       = {true, spell = _E,                  range = 900,   projSpeed = 2000, },
        ['Malphite']    = {true, spell = _R,                  range = 1000,  projSpeed = 1500 + unit.ms},
        ['Maokai']      = {true, spell = _Q,                  range = 600,   projSpeed = 1200, }, -- Targeted ability
        ['MonkeyKing']  = {true, spell = _E,                  range = 650,   projSpeed = 2200, }, -- Targeted ability
        ['Pantheon']    = {true, spell = _W,                  range = 600,   projSpeed = 2000, }, -- Targeted ability
        ['Poppy']       = {true, spell = _E,                  range = 525,   projSpeed = 2000, }, -- Targeted ability
--		['Quinn']       = {true, spell = _E,                  range = 725,   projSpeed = 2000, }, -- Targeted ability
        ['Renekton']    = {true, spell = _E,                  range = 450,   projSpeed = 2000, },
        ['Sejuani']     = {true, spell = _Q,                  range = 650,   projSpeed = 2000, },
        ['Shen']        = {true, spell = _E,                  range = 575,   projSpeed = 2000, },
        ['Tristana']    = {true, spell = _W,                  range = 900,   projSpeed = 2000, },
        ['Tryndamere']  = {true, spell = 'Slash',             range = 650,   projSpeed = 1450, },
        ['XinZhao']     = {true, spell = _E,                  range = 650,   projSpeed = 2000, }, -- Targeted ability
    }
		if unit.type == 'obj_AI_Hero' and unit.team == TEAM_ENEMY and isAGapcloserUnit[unit.charName] and GetDistance(unit) < 2000 and spell ~= nil then
			if spell.name == (type(isAGapcloserUnit[unit.charName].spell) == 'number' and unit:GetSpellData(isAGapcloserUnit[unit.charName].spell).name or isAGapcloserUnit[unit.charName].spell) then
				if spell.target ~= nil and spell.target.name == myHero.name or isAGapcloserUnit[unit.charName].spell == 'blindmonkqtwo' then
	--                print('Gapcloser: ',unit.charName, ' Target: ', (spell.target ~= nil and spell.target.name or 'NONE'), " ", spell.name, " ", spell.projectileID)
			CastSpell(_E, unit.x, unit.z)
				else
					spellExpired = false
					informationTable = {
						spellSource = unit,
						spellCastedTick = GetTickCount(),
						spellStartPos = Point(spell.startPos.x, spell.startPos.z),
						spellEndPos = Point(spell.endPos.x, spell.endPos.z),
						spellRange = isAGapcloserUnit[unit.charName].range,
						spellSpeed = isAGapcloserUnit[unit.charName].projSpeed,
						spellIsAnExpetion = isAGapcloserUnit[unit.charName].exeption or false,
					}
				end
			end
		end
	end
end