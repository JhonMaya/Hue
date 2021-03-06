<?php exit() ?>--by 16hex16 105.236.253.215
if myHero.charName ~= "Yasuo" then return end

--Ahri Q
--Ashe W, E
--Brand Q
--Caitlyn Q, E
--Mundo Q
--Ezreal Q, W
--Galio Q, E
--Gragas Q, R
--Graves Q, R
--Sivir Q
--Talon W
--Twisted Fate Q
--Twitch W
--Viktor E

--Heimerdinger W, E(Need Empowered)

--Jayce Q (Normal + Gate)
--Karma Q (Normal + Mantra)
--Khazix W
--Le Blanc E(Normal + Ult)
--Leona E
--Lissandra Q, E
--Lucian W, R
--Lux E
--Miss Fortune R
--Nami Q, R
--Nocturne Q
--Olaf Q
--Oriana Q
--Quinn Q
--Riven R
--Rumble E
--Urgot E, Q(Normal and Homing)

local halfHitBoxSize = GetDistance(myHero.minBBox, myHero.maxBBox)/2
_G.evading = false
local evadingSkillShot, evadingPosition, skillshotToAdd, Allies, Enemies
local DetectedSkillshots = {}

function OnLoad()
	generateTables()
	
	if _G.allowSpells then _G.allowSpells.Yasuo = {_W} end
	
	YasuoBlock = scriptConfig("Yasuo - Particle Ninja", "Yasuo_Particle_Ninja")
	YasuoBlock:addParam("blockEnabled", "Block Skills", SCRIPT_PARAM_ONOFF, true)
	YasuoBlock:addParam("dangerousOnly", "Only Block Dangerous Skills", SCRIPT_PARAM_ONOFF, true)
	YasuoBlock:permaShow("blockEnabled")
	PrintChat("<font color='#CCCCCC'> >> Yasuo - Particle Ninja loaded! <<</font>")
	
	CleanTable()
end

function OnTick()
	if YasuoBlock.blockEnabled then EvadeSkillShots() end
	if _G.evading then
		if not inDangerousArea(evadingSkillShot, myHero) or evadingSkillShot.endTick < GetTickCount() or not YasuoBlock.blockEnable then
			_G.evading = false
		end
	end
	if skillshotToAdd then AddSkillShot() end
	CleanSkills()
end

function OnProcessSpell(caster, spell)
	if caster.team ~= player.team and not player.dead then
		if YasuoBlock.blockEnabled and spell.target == myHero then
			for i, ability in pairs(AvoidList) do
				if spell.name == ability.spellName then
					if (YasuoBlock.dangerousOnly and ability.dangerous) or not YasuoBlock.dangerousOnly then
						CastSpell(_W, caster.x, caster.z)
					end
				end
			end
		end
		for i, skillShotChampion in pairs(Champions) do
			if skillShotChampion.charName == caster.charName then
				for i, skillshot in pairs(skillShotChampion.skillshots) do
					if skillshot.spellName == spell.name then
						startPosition = Point(caster.x, caster.z)
						endPosition = Point(spell.endPos.x, spell.endPos.z)
						endTick = GetTickCount() + skillshot.spellDelay + skillshot.range / skillshot.projectileSpeed * 1000
						endPosition = GetExtendedEndPos(startPosition, endPosition, skillshot.range)
						table.insert(DetectedSkillshots, {startPosition = startPosition, endPosition = endPosition, skillshot = skillshot, endTick = endTick})
					end
				end
			end
		end
	end
end

function CleanTable()
	for i, champion in pairs(Champions) do
		if champion.team == myHero.team then Champions[i] = nil end
	end
end

function EvadeSkillShots()
	for i, skillshot in ipairs(DetectedSkillshots) do
		if skillshot and inDangerousArea(skillshot, myHero) then
			if (YasuoBlock.dangerousOnly and skillshot.skillshot.dangerous) or not YasuoBlock.dangerousOnly then
				CastSpell(_W, skillshot.startPosition.x, skillshot.startPosition.y)
				evadingSkillShot = skillshot
			end
		end
	end
end

function GetExtendedEndPos(startPos, endPos, distance)
	direction = endPos - startPos
	direction:normalize()
	direction = direction * distance
	return startPos + direction
end

function inDangerousArea(skillshot, unit)
	playerPoint = Point(unit.x, unit.z)
	if skillshot then
		skillshotSegment = LineSegment(skillshot.startPosition, skillshot.endPosition)
		projection = playerPoint:perpendicularFoot(Line(skillshot.startPosition, skillshot.endPosition))
		return playerPoint:distance(projection) <= skillshot.skillshot.radius + halfHitBoxSize and skillshotSegment:distance(projection) < 3
	end
end

function GetLinealPosition(skillshot)
	playerPoint = Point(player.x, player.z)
	projection = playerPoint:perpendicularFoot(Line(skillshot.startPosition, skillshot.endPosition))
	return GetExtendedEndPos(projection, playerPoint, skillshot.skillshot.radius + halfHitBoxSize)
end

function CleanSkills()
	local i = 1
	local detectedSkillshot
	while i <= #DetectedSkillshots do
		detectedSkillshot = DetectedSkillshots[i]
		if detectedSkillshot.endTick < GetTickCount() then
			table.remove(DetectedSkillshots, i)
		else
			i = i + 1
		end
	end
end

function AddSkillShot()
	if not skillshotToAdd.object.valid then
		skillshotToAdd = nil
		return
	end
	
	if GetTickCount() - skillshotToAdd.startTick > 2 * GetLatency() then
		actualPosition = Point(skillshotToAdd.object.x, skillshotToAdd.object.z)
		endPosition = GetExtendedEndPos(skillshotToAdd.startPosition, actualPosition, skillshotToAdd.skillshot.range)
		table.insert(DetectedSkillshots, {startPosition = skillshotToAdd.startPosition, endPosition = endPosition, skillshot = skillshotToAdd.skillshot, startTick = skillshotToAdd.startTick, endTick = skillshotToAdd.endTick})
		skillshotToAdd = nil
	end
end

function generateTables()
	AvoidList = {
		["AkaliQ"] = {spellName = "AkaliMota", dangerous = false},
		["AnnieQ"] = {spellName = "Disintegrate", dangerous = false},
		["CaitlynR"] = {spellName = "CaitlynAceintheHole", dangerous = true},
		["CassiopeiaE"] = {spellName = "CassiopeiaTwinFang", dangerous = false},
		["KalyeQ"] = {spellName = "JudicatorReckoning", dangerous = true},
		["MissFortuneQ"] = {spellName = "MissFortuneRicochetShot", dangerous = false},
		["PantheonQ"] = {spellName = "Pantheon_Throw", dangerous = false},
		["SonaAttack"] = {spellName = "SonaSongofDiscordAttack", dangerous = false},
		["SonaAttack2"] = {spellName = "SonaHymnofValorAttack", dangerous = false},
		["SonaAttack3"] = {spellName = "SonaAriaofPerseveranceAttack", dangerous = false},
		["SonaAttack4"] = {spellName = "SonaPowerChordMissile", dangerous = false},
		["SonaQ"] = {spellName = "SonaHymnofValor", dangerous = false},
		["SyndraR"] = {spellName = "SyndraR", dangerous = true},
		["TristnaE"] = {spellName = "DetonatingShot", dangerous = false},
		["TristnaR"] = {spellName = "BusterShot", dangerous = true},
		["TaricE"] = {spellName = "Dazzle", dangerous = true},
		["SionQ"] = {spellName = "CrypticGaze", dangerous = true},
		["VayneE"] = {spellName = "VayneCondemn", dangerous = true},
		["NunuE"] = {spellName = "IceBlast", dangerous = false},
		["MalphiteQ"] = {spellName = "SeismicShard", dangerous = false},
		["KassadinQ"] = {spellName = "NullLance", dangerous = false},
		["ShacoE"] = {spellName = "TwoShivPoison", dangerous = false},
		["TeemoQ"] = {spellName = "BlindingDart", dangerous = false},
		["VeigarR"] = {spellName = "VeigarPrimordialBurst", dangerous = true},
		["DFG"] = {spellName = "DeathfireGrasp", dangerous = true},
		["LeblancQ"] = {spellName = "LeblancChaosOrb", dangerous = false},
		["LeblancQM"] = {spellName = "LeblancChaosOrbM", dangerous = false},
		["FiddleSticksE"] = {spellName = "FiddlesticksDarkWind", dangerous = false},
		["AniviaE"] = {spellName = "Frostbite", dangerous = false},
		["BrandR"] = {spellName = "BrandWildfire", dangerous = true},
		["RyzeQ"] = {spellName = "Overload", dangerous = false},
		["RyzeE"] = {spellName = "SpellFlux", dangerous = false},
		["TwistedFateG"] = {spellName = "GoldCardAttack", dangerous = true},
		["TwistedFateR"] = {spellName = "RedCardAttack", dangerous = true},
		["TwistedFateG2"] = {spellName = "GoldCardPreAttack", dangerous = true},
		["TwistedFateR2"] = {spellName = "RedCardPreAttack", dangerous = true},
		["DravenQCrit"] = {spellName = "DravenSpinningAttackCrit", dangerous = false},
		["EliseQ"] = {spellName = "EliseHumanQ", dangerous = false},
		["GankplankQ"] = {spellName = "Parley", dangerous = false},
		["SwainQ"] = {spellName = "SwainTorment", dangerous = false},
		["RengarE"] = {spellName = "RengarE", dangerous = false},
		["RengarE2"] = {spellName = "RengarEFinal", dangerous = false},
		["RengarE3"] = {spellName = "RengarEFinalMAX", dangerous = true},
		["MissFortuneQ"] = {spellName = "MissFortuneRicochetShot", dangerous = false},
		["MissFortuneQ2"] = {spellName = "MissFortuneRShotExtra", dangerous = true},
		["KatarinaQ"] = {spellName = "KatarinaQ", dangerous = false},
		["KogmawQ"] = {spellName = "KogMawCausticSpittle", dangerous = false},
		["ViktorQ"] = {spellName = "ViktorPowerTransfer", dangerous = false},
	}

	Champions = {
		--[[	Additional	]]--
		["Ahri"] = {charName = "Ahri", skillshots = {
			["Orb of Deception"] = {spellName = "AhriOrbofDeception", spellDelay = 230, projectileName = "Ahri_Orb_mis.troy", projectileSpeed = 1485, range = 900, radius = 100, dangerous = false},
			["Charm"] = {spellName = "AhriSeduce", spellDelay = 250, projectileSpeed = 1600, range = 1000, radius = 60, dangerous = true},
		}},
		["Ashe"] = {charName = "Ashe", skillshots = { 
			["Volley"] = {spellName = "Volley", spellDelay = 250, projectileSpeed = 1500, range = 1200, radius = 100, dangerous = false},
			["Hawkshot"] = {spellName = "AsheSpiritOfTheHawk", spellDelay = 250, projectileSpeed = 1400, range = 6000, radius = 300, dangerous = false },
			["Enchanted Arrow"] = {spellName = "EnchantedCrystalArrow", spellDelay = 250, projectileSpeed = 1600, range = 25000, radius = 130, dangerous = true},
		}},
		["Brand"] = {charName = "Brand", skillshots = { 
			["Sear"] = {spellName = "BrandBlaze", spellDelay = 250, projectileSpeed = 1200, range = 1100, radius = 210, dangerous = false},
		}},
		["Caitlyn"] = {charName = "Caitlyn", skillshots = { 
			["Piltover Peacemaker"] = {spellName = "CaitlynPiltoverPeacemaker", spellDelay = 650, projectileSpeed = 2200, range = 1300, radius = 90, dangerous = false}, 
			["90 Caliber Net"] = {spellName = "CaitlynEntrapment", spellDelay = 500, projectileSpeed = 2000, range = 80, radius = 20, dangerous = false}, 
		}},
		["DrMundo"] = {charName = "DrMundo", skillshots = { 
			["Infected Cleaver"] = {spellName = "InfectedCleaverMissileCast", spellDelay = 200, projectileSpeed = 1500, range = 1050, radius = 100, dangerous = false},
		}},
		["Ezreal"] = {charName = "Ezreal", skillshots = { 
			["Mystic Shot"] = {spellName = "EzrealMysticShot", spellDelay = 500, projectileSpeed = 1200, range = 1200, radius = 120, dangerous = false},
			["Essence Flux"] = {spellName = "EzrealEssenceFlux", spellDelay = 500, projectileSpeed = 1200, range = 1100, radius = 210, dangerous = false},
			["Trueshot Barrage"] = {spellName = "EzrealTrueshotBarrage", spellDelay = 1000, projectileSpeed = 2000, range = 20000, radius = 160, dangerous = true},
		}},
		["Galio"] = {charName = "Galio", skillshots = { 
			["Resolute Smite"] = {spellName = "GalioResoluteSmite", spellDelay = 250, projectileSpeed = 1300, range = 900, radius = 210, dangerous = false},
			["Righteous Gust"] = {spellName = "GalioRighteousGust", spellDelay = 250, projectileSpeed = 1200, range = 1180, radius = 90, dangerous = false},
		}},
		["Gragas"] = {charName = "Gragas", skillshots = { 
			["Barrel Roll"] = {spellName = "GragasBarrelRoll", spellDelay = 250, projectileSpeed = 1000, range = 950, radius = 210, dangerous = false},
			["Explosive Cask"] = {spellName = "GragasExplosiveCask", spellDelay = 250, projectileSpeed = 1800, range = 1100, radius = 350, dangerous = true},
		}},
		["Graves"] = {charName = "Graves", skillshots = { 
			["Cluster Shot"] = {spellName = "GravesClusterShot", spellDelay = 250, projectileSpeed = 2000, range = 700, radius = 210, dangerous = false},
			["Charge Shot"] = {spellName = "GravesChargeShot", spellDelay = 250, projectileSpeed = 1200, range = 1000, radius = 90, dangerous = true},
		}},
		["Heimerdinger"] = {charName = "Heimerdinger", skillshots = { 
			["Grenade"] = {spellName = "HeimerdingerW", spellDelay = 250, projectileSpeed = 1800, range = 1200, radius = 90, dangerous = true},
			["Missile Barrage"] = {spellName = "HeimerdingerE", spellDelay = 250, projectileSpeed = 2500, range = 925, radius = 120, dangerous = false},
		}},
		["Sivir"] = {charName = "Sivir", skillshots = { 
			["Boomerang Blade"] = {spellName = "SivirQ", spellDelay = 500, projectileSpeed = 1350, range = 1075, radius = 150, dangerous = false},
		}},    
		["Talon"] = {charName = "Talon", skillshots = { 
			["Rake"] = {spellName = "TalonRake", spellDelay = 500, projectileSpeed = 900, range = 650, radius = 100, dangerous = false},
		}},
		["Twisted Fate"] = {charName = "TwistedFate", skillshots = { 
			["WildCards"] = {spellName = "WildCards", spellDelay = 500, projectileSpeed = 1450, range = 1500, radius = 210, dangerous = false},
		}},
		["Twitch"] = {charName = "Twitch", skillshots = { 
			["Venom Cask"] = {spellName = "TwitchVenomCask", spellDelay = 500, projectileSpeed = 1750, range = 950, radius = 275, dangerous = false},
		}},
		["Viktor"] = {charName = "Viktor", skillshots = { 
			["Deathray"] = {spellName = "ViktorDeathRay", spellDelay = 250, projectileSpeed = 1200, range = 550, radius = 100, dangerous = false},
		}},
	--[[	Additional	]]--
		["Yasuo"] = {charName = "Yasuo", skillshots = {
			["Yasuo Whirlwind"] = {spellName = "yasuoq3w", spellDelay = 250, projectileSpeed = 1600, range = 900, radius = 80, dangerous = true},
		}},
		["Aatrox"] = {charName = "Aatrox", skillshots = {
			["Blade of Torment"] = {spellName = "AatroxE", spellDelay = 250, projectileSpeed = 1200, range = 1075, radius = 100, dangerous = false},
		}},
		["Amumu"] = {charName = "Amumu", skillshots = {
			["Bandage Toss"] = {spellName = "BandageToss", spellDelay = 250, projectileSpeed = 2000, range = 1100, radius = 80, dangerous = true}
		}},
		["Anivia"] = {charName = "Anivia", skillshots = {
			["Flash Frost"] = {spellName = "FlashFrostSpell", spellDelay = 250, projectileSpeed = 850, range = 1100, radius = 110, dangerous = true}
		}},
		["Blitzcrank"] = {charName = "Blitzcrank", skillshots = {
			["Rocket Grab"] = {"RocketGrabMissile", spellDelay = 250, projectileSpeed = 1800, range = 1050, radius = 70, dangerous = true}
		}},
		["Draven"] = {charName = "Draven", skillshots = {
			["Stand Aside"] = {spellName = "DravenDoubleShot", spellDelay = 250, projectileSpeed = 1400, range = 1100, radius = 130, dangerous = true},
			["DravenR"] = {spellName = "DravenRCast", spellDelay = 500, projectileSpeed = 2000, range = 25000, radius = 160, dangerous = true},
		}},
		["Elise"] = {charName = "Elise", skillshots = {
			["Cocoon"] = {spellName = "EliseHumanE", spellDelay = 250, projectileSpeed = 1450, range = 1100, radius = 70, dangerous = true}
		}},
		["Fizz"] = {charName = "Fizz", skillshots = {
			["Fizz Ultimate"] = {spellName = "FizzMarinerDoom", spellDelay = 250, projectileSpeed = 1350, range = 1275, radius = 80, dangerous = true},
		}},
		["Jinx"] = {charName = "Jinx", skillshots = {
			["W"] =  {spellName = "JinxWMissile", spellDelay = 600, projectileSpeed = 3300, range = 1450, radius = 70, dangerous = false},
			["R"] =  {spellName = "JinxRWrapper", spellDelay = 600, projectileSpeed = 2200, range = 20000, radius = 120, dangerous = true},
		}}, 
		["Lee Sin"] = {charName = "LeeSin", skillshots = {
			["Sonic Wave"] = {spellName = "BlindMonkQOne", spellDelay = 250, projectileSpeed = 1800, range = 1100, radius = 70, dangerous = true}
		}},
		["Lux"] = {charName = "Lux", skillshots = {
			["Light Binding"] =  {spellName = "LuxLightBinding", spellDelay = 250, projectileSpeed = 1200, range = 1300, radius = 80, dangerous = true},
		}},
		["Morgana"] = {charName = "Morgana", skillshots = {
			["Dark Binding Missile"] = {spellName = "DarkBindingMissile", spellDelay = 250, projectileSpeed = 1200, range = 1300, radius = 80, dangerous = true},
		}},
		["Nautilus"] = {charName = "Nautilus", skillshots = {
			["Dredge Line"] = {spellName = "NautilusAnchorDrag", spellDelay = 250, projectileSpeed = 2000, range = 1080, radius = 80, dangerous = true},
		}},
		["Nidalee"] = {charName = "Nidalee", skillshots = {
			["Javelin Toss"] = {spellName = "JavelinToss", spellDelay = 125, projectileSpeed = 1300, range = 1500, radius = 60, dangerous = true},
		}},
		["Olaf"] = {charName = "Olaf", skillshots = {
			["Undertow"] = {spellName = "OlafAxeThrow", spellDelay = 250, projectileSpeed = 1600, range = 1000, radius = 90, dangerous = true},
		}},
		["Sejuani"] = {charName = "Sejuani", skillshots = {
			["SejuaniR"] = {spellName = "SejuaniGlacialPrisonCast", spellDelay = 250, projectileSpeed = 1600, range = 1200, radius = 110, dangerous = true},
		}},
		["Sona"] = {charName = "Sona", skillshots = {
			["Crescendo"] = {spellName = "SonaCrescendo", spellDelay = 250, projectileSpeed = 2400, range = 1000, radius = 140, dangerous = true},		
		}},
		["Thresh"] = {charName = "Thresh", skillshots = {
			["ThreshQ"] = {spellName = "ThreshQ", spellDelay = 500, projectileSpeed = 1900, range = 1100, radius = 65, dangerous = true}
		}},
		["Varus"] = {charName = "Varus", skillshots = {
			["Varus Q Missile"] = {spellName = "VarusQ!", spellDelay = 0, projectileSpeed = 1900, range = 1600, radius = 70, dangerous = true},
			["VarusR"] = {spellName = "VarusR", spellDelay = 250, projectileSpeed = 1950, range = 1250, radius = 100, dangerous = true},
		}},
		["Zyra"] = {charName = "Zyra", skillshots = {
			["Grasping Roots"] = {spellName = "ZyraGraspingRoots", spellDelay = 250, projectileSpeed = 1150, range = 1150, radius = 70, dangerous = true},
			["Zyra Passive Death"] = {spellName = "zyrapassivedeathmanager", spellDelay = 500, projectileSpeed = 2000, range = 1474, radius = 60, dangerous = true},
		}},
		["Ziggs"] = {charName = "Ziggs", skillshots = {
			["ZiggsQ"] = {spellName = "ZiggsQ", spellDelay = 250, projectileSpeed = 1750, range = 1400, radius = 90, dangerous = false},
			["ZiggsW"] = {spellName = "ZiggsW", spellDelay = 250, projectileSpeed = 1750, range = 1000, radius = 150, dangerous = false},
			["ZiggsE"] = {spellName = "ZiggsE", spellDelay = 250, projectileSpeed = 1750, range = 900, radius = 200, dangerous = false},
			["ZiggsR"] = {spellName = "ZiggsR", spellDelay = 375, projectileSpeed = 1750, range = 5300, radius = 500, dangerous = true},
		}}
	}
end