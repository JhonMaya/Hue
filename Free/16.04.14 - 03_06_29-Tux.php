<?php exit() ?>--by Tux 70.160.53.227

---------------------------------- START CHECK ---------------------------------------------------------------
local function abs(k) if k < 0 then return -k else return k end end
if tonumber == nil or tonumber("223") ~= 223 or -9 ~= "-10" + 1 then return end
if tostring == nil or tostring(220) ~= "220" then return end
if string.sub == nil or string.sub("imahacker", 4) ~= "hacker" then return end
last1 = tonumber(string.sub(tostring(GetUser), 11), 16)
last2 = tonumber(string.sub(tostring(GetAsyncWebResult), 11), 16)
last3 = tonumber(string.sub(tostring(CastSpell), 11), 16)
local function rawset3(table, value, id) end
local function protect(table) return setmetatable({}, { __index = table, __newindex = function(table, key, value) end, __metatable = false }) end
--overload check (addresses should be almost equal)
if _G.GetAsyncWebResult == nil or _G.GetUser == nil or _G.CastSpell == nil then print("Thresh Prince: Unauthorized User") return end
local a1 = tonumber(string.sub(tostring(_G.GetAsyncWebResult), 11), 16)
local a2 = tonumber(string.sub(tostring(_G.GetUser), 11), 16)
local a3 = tonumber(string.sub(tostring(_G.CastSpell), 11), 16)
if abs(a2-a1) > 500000 and abs(a3-a2) > 500000 then print("Thresh Prince: Unauthorized User") return end
if abs(a2-a1) < 500 and abs(a3-a2) < 500 then print("Thresh Prince: Unauthorized User") return end
_G.rawset = rawset3
namez = protect {
	["dekaron2"] = true, ["sniperbro"] = true, ["tux"] = true, ["vex"] = true, ["easyhiv"] = true, ["ires"] = true, 
	["xpliclt"] = true, ["ryosu"] = true, ["apraxia"] = true, ["skinalt"] = true, ["adamhacks"] = true, ["getsnipeddown"] = true, 
	["qqq"] = true, ["149kg"] = true, ["sida"] = true, ["weeeqt"] = true, ["olorin"] = true, ["klokje"] = true,
	["spudgy"] = true, ["ottohr"] = true, ["xlain"] = true, ["dansa"] = true, ["thezone"]
}
_G.rawset = rawset3
if namez[_G.GetUser():lower()] == nil or namez[_G.GetUser():lower()] == false then print("Thresh Prince: Unauthorized User") return end
---------------------------------- END CHECK --------------------------------------------------------------------


local priorityTable = {
	AP = {
		"Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
		"Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
		"Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra", "Velkoz",
	},
	Support = {
		"Blitzcrank", "Janna", "Karma", "Lulu", "Nami", "Sona", "Soraka", "Thresh", "Zilean",
	},
     
	Tank = {
		"Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Shen", "Singed", "Skarner", "Volibear",
		"Warwick", "Yorick", "Zac", "Nunu", "Taric", "Alistar", "Leona",
	},
     
	AD_Carry = {
		"Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "Jinx", "KogMaw", "Lucian", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
		"Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Zed", "MasterYi", "Yasuo",
	},
     
	Bruiser = {
		"Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",
		"Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao", "Aatrox"
	},
}

local escapeList =
{
--Blue Side
	--Wight Camp
	{x = 1684, y = 55, z = 8207  },
	--Golem Camp
	{x = 8217, y = 54, z = 2534  },
	{x = 7917, y = 54, z = 2534  },
	--Wolf Camp
	{x = 3324, y = 56, z = 6373  },
	{x = 3524, y = 56, z = 6223  },
	{x = 3374, y = 56, z = 6223  },
	--Wraith Camp
	{x = 6583, y = 53, z = 5108  },
	{x = 6654, y = 59, z = 5278  },
	{x = 6496, y = 61, z = 5365  },
	{x = 6446, y = 56, z = 5215  },

--Red Side
	--Wight Camp
	{x = 12337, y = 55, z = 6263 },
	--Golem Camp
	{x = 6140, y = 40, z = 11935 },
	{x = 5846, y = 40, z = 11915 },
	--Wolf Camp
	{x = 10452, y = 66, z = 8116 },
	{x = 10696, y = 65, z = 7965 },
	{x = 10652, y = 64, z = 8116 },
	--Wraith Camp
	{x = 7450, y = 55, z = 9350  },
	{x = 7350, y = 56, z = 9230  },
	{x = 7480, y = 56, z = 9091  },
	{x = 7580, y = 55, z = 9250  },

--River
	--Dragon
	{x = 9460, y = -61, z = 4193 },
	--Baron
	{x = 4600, y = -63, z = 10250},
}

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
	--{charName = "Rengar", spellName = "rengarnewpassivebuff", missileName = nil, radius = 0, delay = 0.5},
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

--[[
--Channel Flay Table
local isChanneling = 
{
	{charName = "Caitlyn", spellName = "CaitlynAceintheHole", delay = 0.5},
	{charName = "FiddleSticks", spellName = "Crowstorm" or "Drain", delay = 0.5},
	{charName = "Galio", spellName = "GalioIdolOfDurand", delay = 0.5},
	{charName = "Janna", spellName = "ReapTheWhirlwind", delay = 0.5},
	{charName = "Katarina", spellName = "KatarinaR", delay = 0.5},
	{charName = "Lucian", spellName = "LucianR", delay = 0.5},
	{charName = "Malzahar", spellName = "AlZaharNetherGrasp", delay = 0.5},
	{charName = "MissFortune", spellName = "MissFortuneBulletTime", delay = 0.5},
	{charName = "Morgana", spellName = "SoulShackles", delay = 0.5},
	{charName = "Nunu", spellName = "AbsoluteZero", delay = 0.5},
	{charName = "Urgot", spellName = "UrgotSwap2", delay = 0.5},
	{charName = "Velkoz", spellName = "VelkozR", delay = 0.5},
	{charName = "Warwick", spellName = "InfiniteDuress", delay = 0.5},
	{charName = "Xerath", spellName = "XerathLocusOfPower2", delay = 0.5},
}
--]]

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
	if Config.autoFlay then
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
    LoadConfigHeader()
    LoadMenus()
	ts.name = "Thresh"
    Config:addTS(ts)
	PrintFloatText(myHero,12,"Thresh Prince Loaded!")
	VP = VPrediction()
end

function OnUnload()
	PrintFloatText(myHero,12,"Thresh Prince UnLoaded!")
end

function LoadConfigHeader()
	Config = scriptConfig("ThreshPrince", "Thresh Prince")
	Config:addParam("ThreshPrinceInfo", "Thresh Prince Version: ", SCRIPT_PARAM_INFO, versionTHRESH)
end

function LoadMenus()
    Config:addParam("Engage", "Engage them", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
	Config:addParam("TeamFight", "Team Fight Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config:addParam("Push", "Push with Flay", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("E"))
	Config:addParam("Harass", "Harass with Hook", SCRIPT_PARAM_ONKEYDOWN, false, 84)
	Config:addParam("jungleEscape", "Escape using Jungle Camps", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("J"))
	Config:addParam("autoFlay", "Auto Flay Gap Closer", SCRIPT_PARAM_ONOFF, false)
	Config:addParam("Box", "Auto Ultimate in Combo", SCRIPT_PARAM_ONOFF, false)
	Config:addParam("Lantern", "Auto Lantern Most Armour in Combo", SCRIPT_PARAM_ONOFF, false)
	Config:addParam("BoxCount", "Enemy Count before Using Ulti", SCRIPT_PARAM_SLICE, 3, 0, 5, 0)
    Config:addParam("qHitChance", "Q Hit Chance Buffer", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
	Config:addParam("qRangeSlider", "Q Range", SCRIPT_PARAM_SLICE, 955, 0, 1055, 0)
	Config:addParam("wRangeSlider", "W Range - Actual Max 950", SCRIPT_PARAM_SLICE, 950, 0, 1500, 0)
	Config:addParam("eRangeSlider", "E Range", SCRIPT_PARAM_SLICE, 450, 0, 500, 0)
	Config:addParam("rRangeSlider", "Use Auto Ultimate at this range", SCRIPT_PARAM_SLICE, 400, 0, 450, 0)
end

function AdvancedCallBacks()
	AdvancedCallBack:bind('OnGainBuff', function(unit, buff)
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "threshqfakeknockup" then --Thresh Hook, on target
			isHooked = true end
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "BlackShield" then --Morgana Shield
			morganaShield = true end
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "SivirE" then --Sivir Shield
			sivirShield = true end
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "NocturneShroudofDarkness" then --Nocturne Shield
			nocturneShield = true end
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "rengarnewpassivebuff" then
			rengarLeap = true end
		if unit and unit.valid and unit.team == myHero.team and not unit.isMe and (buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinRecallImproved") then
			recalling = true end
	end)
	AdvancedCallBack:bind('OnLoseBuff', function(unit, buff)
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "threshqfakeknockup" then --Thresh Hook, on target
			isHooked = false end
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "BlackShield" then --Morgana Shield
			morganaShield = false end
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "SivirE" then --Sivir Shield
			sivirShield = false end
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "NocturneShroudofDarkness" then --Nocturne Shield
			nocturneShield = false end
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "rengarnewpassivebuff" then
			rengarLeap = false end
		if unit and unit.valid and unit.team == myHero.team and not unit.isMe and (buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinRecallImproved") then
			recalling = false end
	end)
end

function CanCast(Spell)
	return (player:CanUseSpell(Spell) == READY)
end

function Engage()
	local spellQ = myHero:GetSpellData(_Q).name
	if CanCast(_Q) and not (morganaShield == true or sivirShield == true or nocturneShield == true) and GetDistance(ts.target) <= Config.qRangeSlider and spellQ == "ThreshQ" then
		local CastPosition, HitChance, Position = VP:GetLineCastPosition(ts.target, qDelay, qWidth, qRange, qSpeed, myHero, true)
		if HitChance >= Config.qHitChance then
			CastSpell(_Q, CastPosition.x, CastPosition.z)
		end
	end
	if CanCast(_E) and GetDistance(ts.target) < Config.eRangeSlider then
		xPos = myHero.x + (myHero.x - ts.target.x)
		zPos = myHero.z + (myHero.z - ts.target.z)
		CastSpell(_E, xPos, zPos)
	elseif GetDistance(ts.target) > Config.eRangeSlider and os.time() > qCastAt + 1400 and spellQ == "threshqleap" then
		CastSpell(_Q)
	end
	if isHooked == true and not (morganaShield == true or sivirShield == true or nocturneShield == true) then
		LanternMostAr()
	end
	if Config.Box then AutoBox() end
end

function TeamFight()
	local spellQ = myHero:GetSpellData(_Q).name
	if CanCast(_Q) and not (morganaShield == true or sivirShield == true or nocturneShield == true) and GetDistance(ts.target) <= Config.qRangeSlider and spellQ == "ThreshQ" then
		local CastPosition, HitChance, Position = VP:GetLineCastPosition(ts.target, qDelay, qWidth, qRange, qSpeed, myHero, true)
		if HitChance >= Config.qHitChance then
			CastSpell(_Q, CastPosition.x, CastPosition.z)
		end
	end
	if CanCast(_E) and GetDistance(ts.target) < Config.eRangeSlider then
		xPos = myHero.x + (myHero.x - ts.target.x)
		zPos = myHero.z + (myHero.z - ts.target.z)
		CastSpell(_E, xPos, zPos)
	elseif GetDistance(ts.target) > Config.eRangeSlider and os.time() > qCastAt + 1400 and spellQ == "threshqleap" then
		CastSpell(_Q)
	end
	if Config.Lantern then LanternMostAr() end
	if Config.Box then AutoBox() end
end

function GetHighestArmorAlly()
	local mostArmor = 1
	local mostArmorChamp = nil
	for _, Ally in pairs(GetAllyHeroes()) do
		if Ally.armor > mostArmor and GetDistance(Ally) <= Config.wRangeSlider and Ally.dead == false then
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
	if CanCast(_Q) and not (morganaShield == true or sivirShield == true or nocturneShield == true) and GetDistance(ts.target) <= Config.qRangeSlider and spellQ == "ThreshQ" then
		local CastPosition, HitChance, Position = VP:GetLineCastPosition(ts.target, qDelay, qWidth, qRange, qSpeed, myHero, true)
		if HitChance >= Config.qHitChance then
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
	if CanCast(_R) and CountEnemyHeroInRange(Config.rRangeSlider) >= Config.BoxCount then
		CastSpell(_R)
	end
end

function JungleEscape()
	local spellQ = myHero:GetSpellData(_Q).name
	for i, creepLocation in pairs(escapeList) do
		if CanCast(_Q) and GetDistance(creepLocation) <= 1075 and spellQ == "ThreshQ" then
			CastSpell(_Q, creepLocation.x, creepLocation.z)
		end
		if CanCast(_W) and spellQ == "threshqleap" then
			CastSpell(_W, myHero.x, myHero.z)
		end
		if spellQ == "threshqleap" then
			CastSpell(_Q)
		end
	end
end

function OnTick()
	ts:update()
	actualDelay = GetTickCount() - spellCastTick
	shouldCast = (spellParticle.valid and GetDistanceSqr(spellParticle) <= maxParticleDistance)
	if ts.target ~= nil then
		if ValidTarget(ts.target) and Config.Engage then Engage() end
		if ValidTarget(ts.target) and Config.TeamFight then TeamFight() end
		if ValidTarget(ts.target) and Config.Harass then HookHarass() end
		if Config.Push then FlayPush() end
	end
	if Config.jungleEscape then JungleEscape() end
	if Config.autoFlay then
		if shouldCast and CanCast(_E) then 
			CastSpell(_E, ts.target.x, ts.target.z)
		end
	end
end

--[[function checkShowGameConfig()
	if Config.ShowGame.Skills.Harass or Config.ShowGame.Skills.harassToggle or Config.ShowGame.Skills.Teamfight or Config.ShowGame.Skills.AutoE or 
	Config.ShowGame.Skills.TeamfightQ or Config.ShowGame.Skills.TeamfightW or Config.ShowGame.Skills.TeamfightE or Config.ShowGame.Skills.HarassQ or 
	Config.ShowGame.Skills.HarassW or Config.ShowGame.Skills.HarassE or	Config.ShowGame.ShowKS.ksQ or Config.ShowGame.ShowKS.ksW or 
	Config.ShowGame.ShowKS.ksE or Config.ShowGame.ShowUlt.manualR or Config.ShowGame.ShowUlt.smartUlt or Config.ShowGame.ShowUlt.autor or 
	Config.ShowGame.ShowUlt.autoDefensive or Config.ShowGame.ShowUlt.UltKill or	Config.ShowGame.ShowUlt.UltEnemiesFacing or 
	Config.ShowGame.ShowUlt.UltEnemiesRange or Config.ShowGame.ShowItems.apItems or	Config.ShowGame.ShowItems.APMode or Config.ShowGame.ShowItems.adItems or 
	Config.ShowGame.ShowItems.ADMode or Config.ShowGame.ShowFarm.farm or Config.ShowGame.ShowFarm.farmToggle or Config.ShowGame.ShowFarm.laneClear or 
	Config.ShowGame.ShowFarm.laneClearToggle or Config.ShowGame.ShowFarm.farmOrb or	Config.ShowGame.ShowFarm.farmAA or Config.ShowGame.ShowFarm.farmQ or 
	Config.ShowGame.ShowFarm.farmW or Config.ShowGame.ShowFarm.farmE or	Config.ShowGame.ShowFarm.farmQclear or Config.ShowGame.ShowFarm.farmWclear or 
	Config.ShowGame.ShowFarm.farmEclear or Config.ShowGame.ShowFarm.manaPercent or Config.ShowGame.ShowFarm.Jungle or Config.ShowGame.ShowFarm.jungleOrb or 
	Config.ShowGame.ShowFarm.jungleAA or Config.ShowGame.ShowFarm.JungleQ or Config.ShowGame.ShowFarm.JungleW or Config.ShowGame.ShowFarm.JungleE or 
	Config.ShowGame.ShowOrb.MoveToMouse or Config.ShowGame.ShowOrb.AA or Config.ShowGame.ShowOrb.MoveToMouseHarass or Config.ShowGame.ShowOrb.AAHarass or 
	Config.ShowGame.ShowDraw.LagFree or Config.ShowGame.ShowDraw.Target or Config.ShowGame.ShowDraw.Killable or Config.ShowGame.ShowDraw.DrawTracker or 
	Config.ShowGame.ShowPass.autoPT or Config.ShowGame.ShowPass.autoAA  then
		Config.ShowGame.Version = true
	end	
	if ignite ~= nil then
		if Config.ShowGame.ShowIgnite.useIgnite or Config.ShowGame.ShowIgnite.IgniteMode then
	    	Config.ShowGame.Version = true
	    end  
	end
end]]

--------------------------------------------- REPLACE DRAWCIRCLE START ----------------------------------------------
function DrawCircle2(x, y, z, radius, color, width, n)
    radius = radius*.92 or 300
    n = n or math.floor(10 + math.min(radius, 600)/600 * 20)
    local arrayx = {}
    local arrayz = {}
	for i = 1, n, 1 do
		if i == n then
			finisharray = true
		end
		tot=(360/n)*i
		arrayx[i] = x + radius * math.cos((tot*math.pi)/180)
		arrayz[i] = z + radius * math.sin((tot*math.pi)/180) 
	end
	if finisharray == true then	
		for i = 1, n, 1 do
			if i < n then
			DrawLine3Dcustom(arrayx[i], y, arrayz[i], arrayx[i+1], y, arrayz[i+1], width, color)
			else
			DrawLine3Dcustom(arrayx[i], y, arrayz[i], arrayx[1], y, arrayz[1], width, color)
			end
		end
	end	
end
function DrawLine3Dcustom(x1, y1, z1, x2, y2, z2, width, color)
    local p = WorldToScreen(D3DXVECTOR3(x1, y1, z1))
    local px, py = p.x, p.y
    local c = WorldToScreen(D3DXVECTOR3(x2, y2, z2))
    local cx, cy = c.x, c.y
    DrawLine(cx, cy, px, py, width or 1, color or 4294967295)
end
--[[function LoadShowGameConfig()
	------------------version----------------
	if  Config.ShowGame.Version then
		Config:permaShow("VerInfo")
	end
	---------------show skills ---------------
	if  Config.ShowGame.Skills.Harass then
		Config.combatKeys:permaShow("harass")
	end
	if Config.ShowGame.Skills.HarassToggle then 
		Config.combatKeys:permaShow("harassToggle")
	end
	if Config.ShowGame.Skills.Teamfight then
		Config.combatKeys:permaShow("teamFight")
	end
	if Config.ShowGame.Skills.TeamfightQ then
		Config.skillActivation:permaShow("UseQ")
	end
	if Config.ShowGame.Skills.TeamfightW then
		Config.skillActivation:permaShow("UseW")
	end
	if Config.ShowGame.Skills.TeamfightE then
		Config.skillActivation:permaShow("UseE")
	end
	if Config.ShowGame.Skills.HarassQ then
		Config.skillActivation:permaShow("HarassQ")
	end
	if Config.ShowGame.Skills.HarassW then
		Config.skillActivation:permaShow("HarassW")
	end
	if Config.ShowGame.Skills.HarassE then
		Config.skillActivation:permaShow("HarassE")
	end
	---------------------Show KS------------------
	if Config.ShowGame.ShowKS.ksQ then
		Config.KS:permaShow("KsQ")
	end
	if Config.ShowGame.ShowKS.ksW then
		Config.KS:permaShow("KsW")
	end
	if Config.ShowGame.ShowKS.ksE then
		Config.KS:permaShow("KsE")
	end
	-------------------Show ult options-----------------
	if Config.ShowGame.ShowUlt.manualR then
		Config.ultSettings:permaShow("manualR")
	end
	if Config.ShowGame.ShowUlt.smartUlt then
		Config.ultSettings:permaShow("smartUlt")
	end
	if Config.ShowGame.ShowUlt.autor then
		Config.ultSettings:permaShow("autoUlt")
	end
	if Config.ShowGame.ShowUlt.autoDefensive then
		Config.ultSettings:permaShow("autoDefensive")
	end
	if Config.ShowGame.ShowUlt.UltKill then
		Config.ultSettings:permaShow("useUltKillable")
	end
	if Config.ShowGame.ShowUlt.UltEnemiesFacing then
		Config.ultSettings:permaShow("setUltEnemies")
	end
	if Config.ShowGame.ShowUlt.UltEnemiesRange then
		Config.ultSettings:permaShow("setUltEnemiesInRange")
	end
	---------------------show Item settings---------------------
	if Config.ShowGame.ShowItems.apItems 
    	and (Config.Items.APItems.useBilgewaterCutlass 
    	or Config.Items.APItems.useBlackfireTorch 
    	or Config.Items.APItems.useDFG 
    	or Config.Items.APItems.useHextechGunblade 
    	or Config.Items.APItems.useTwinShadows) then
    		Config.Items.APItems:permaShow("useAPItems")
    end  
	if Config.ShowGame.ShowItems.APMode and Config.Items.APItems.useAPItems then
		Config.Items.APItems:permaShow("APItemMode")
	end
	 if Config.ShowGame.ShowItems.adItems 
    	and (Config.Items.ADItems.useBOTRK 
    	or Config.Items.ADItems.useEntropy 
    	or Config.Items.ADItems.useRavenousHydra 
    	or Config.Items.ADItems.useSwordOfTheDevine 
    	or Config.Items.ADItems.useTiamat 
    	or Config.Items.ADItems.useYoumuusGhostblade 
    	or Config.Items.ADItems.useMuramana) then
    		Config.Items.ADItems:permaShow("useADItems")
    end	
    if Config.ShowGame.ShowItems.ADMode and Config.Items.APItems.useADItems then
    	Config.Items.ADItems:permaShow("ADItemMode")
    end
	-----------------show lane farm -----------------------------
	if Config.ShowGame.ShowFarm.farm then
		Config.Farm.laneFarm:permaShow("farm")
	end
	if Config.ShowGame.ShowFarm.farmToggle then
		Config.Farm.laneFarm:permaShow("farmToggle")
	end
	if Config.ShowGame.ShowFarm.laneClear then
		Config.Farm.laneFarm:permaShow("laneClear")
	end
	if Config.ShowGame.ShowFarm.laneClearToggle then
		Config.Farm.laneFarm:permaShow("laneClearToggle")
	end
	if Config.ShowGame.ShowFarm.farmOrb then
		Config.Farm.laneFarm:permaShow("farmOrb")
	end
	if Config.ShowGame.ShowFarm.farmAA then
		Config.Farm.laneFarm:permaShow("farmAA")
	end
	if Config.ShowGame.ShowFarm.farmQ then
		Config.Farm.laneFarm:permaShow("farmQ")
	end
	if Config.ShowGame.ShowFarm.farmW then
		Config.Farm.laneFarm:permaShow("farmW")
	end
	if Config.ShowGame.ShowFarm.farmE then
		Config.Farm.laneFarm:permaShow("farmE")
	end
	if Config.ShowGame.ShowFarm.farmR then
		Config.Farm.laneFarm:permaShow("farmR")
	end
	if Config.ShowGame.ShowFarm.farmQclear then
		Config.Farm.laneFarm:permaShow("farmQclear")
	end
	if Config.ShowGame.ShowFarm.farmWclear then
		Config.Farm.laneFarm:permaShow("farmWclear")
	end
	if Config.ShowGame.ShowFarm.farmEclear then
		Config.Farm.laneFarm:permaShow("farmEclear")
	end
	if Config.ShowGame.ShowFarm.manaPercent then
		Config.Farm:permaShow("manaPercent")
	end
	-----------------show jungle farm --------------------------
	if Config.ShowGame.ShowFarm.Jungle then
		Config.Farm.jungleFarm:permaShow("jungleFarming")
	end
	if Config.ShowGame.ShowFarm.jungleOrb then
		Config.Farm.jungleFarm:permaShow("jungleOrb")
	end
	if Config.ShowGame.ShowFarm.jungleAA then
		Config.Farm.jungleFarm:permaShow("jungleFarmAA")
	end
	if Config.ShowGame.ShowFarm.JungleQ then
		Config.Farm.jungleFarm:permaShow("jungleFarmQ")
	end
	if Config.ShowGame.ShowFarm.JungleW  then
		Config.Farm.jungleFarm:permaShow("jungleFarmW")
	end
	if Config.ShowGame.ShowFarm.JungleE  then
		Config.Farm.jungleFarm:permaShow("jungleFarmE")
	end
	-------------show summoner spells-----------
	if ignite ~= nil then
		if Config.ShowGame.ShowIgnite.useIgnite then
	    	Config.SummonerSpells.Ignite:permaShow("useIgnite")
	    end  
		if Config.ShowGame.ShowIgnite.IgniteMode then
	    	Config.SummonerSpells.Ignite:permaShow("IgniteMode")
	    end  
	end
	---------------------show Orbwalking ---------------
	if Config.ShowGame.ShowOrb.MoveToMouse then
		Config.OrbWalk.champOrbWalk:permaShow("moveToMouse")
	end
	if Config.ShowGame.ShowOrb.AA then
		Config.OrbWalk.champOrbWalk:permaShow("AA")
	end
	if Config.ShowGame.ShowOrb.MoveToMouseHarass then
		Config.OrbWalk.champOrbWalk:permaShow("moveToMouseHarass")
	end
	if Config.ShowGame.ShowOrb.AAHarass then
		Config.OrbWalk.champOrbWalk:permaShow("AAHarass")
	end
	----------------show draw settings -----------------
	if Config.ShowGame.ShowDraw.LagFree then
		Config.Draw.drawSkillKillRanges:permaShow("LagFree")
	end   
	if Config.ShowGame.ShowDraw.Target then
    	Config.Draw.drawFocusedTarget:permaShow("DrawFocusedTarget")
    end 
    if Config.ShowGame.ShowDraw.Killable then
    	Config.Draw.drawEnemyKillableText:permaShow("enemyKillableText")
    end   
    if Config.ShowGame.ShowDraw.DrawTracker then
    	Config.Draw.drawTracker:permaShow("drawEnemyTracker")
    end
    --if Config.ShowGame.ShowDraw.OOM then
    --	Config.Draw.drawOOMText:permaShow("OOMText")
    --end 
    --if Config.ShowGame.ShowDraw.DrawTimer then
    --	Config.Draw.drawKillTimer:permaShow("DrawTimer")
    --end
end]]