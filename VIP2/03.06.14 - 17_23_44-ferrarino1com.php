<?php exit() ?>--by ferrarino1com 81.35.127.181
--Metadata
local version = "1.0"
local author = "eh meh"
local testOnAllies = false
---------------------

if os.time{year=2014, month=6, day=7} < os.time() then return end

local evading = false
local cantevade = false
local evadePoint = Point(0, 0)
local gj = "Hi, who are you and what are you searching for ^_^, PM me if you want the source."

local circularPolygonSides = 30 --circles are 30 sided polygons.
local evadeBuffer = 5
local moveDelay = function() return menu.advanced.edelay + GetLatency()/2000 end

local lastTickPosition
local imEnabled = true
local imDodgingOnlyDangerous = false

local activeSkillshotTable = {}                               

--SkillShotDatabase
local posibleSkillshotable = {
	["AatroxQ"] = {charName = "Aatrox", skillshotType = "circular", delay = 600, missileSpeed = 2000, range = 650, radius = 150, addHitBox = true, fixedRange = false, collision = false, },
	["AhriOrbofDeception"] = {charName = "Ahri", skillshotType = "linem", delay = 250, missileSpeed = 1600, range = 1000, radius = 100, addHitBox = true, fixedRange = true, collision = false, },
	--["AhriOrbReturn"] = {charName = "Ahri", skillshotType = "linem", delay = 250, missileSpeed = 1600, range = 1000, radius = 100, addHitBox = true, fixedRange = true, collision = false, },
	["AhriSeduce"] = {charName = "Ahri", skillshotType = "linem", delay = 250, missileSpeed = 1500, range = 1000, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["FlashFrost"] = {charName = "Anivia", skillshotType = "linem", delay = 250, missileSpeed = 850, range = 1100, radius = 110, addHitBox = true, fixedRange = false, collision = true, },
	["InfernalGuardian"] = {charName = "Annie", skillshotType = "circular", delay = 250, missileSpeed = math.huge, range = 600, radius = 251, addHitBox = true, fixedRange = false, collision = false, },
	["BandageToss"] = {charName = "Amumu", skillshotType = "linem", delay = 250, missileSpeed = 2000, range = 1100, radius = 130, addHitBox = true, fixedRange = true, collision = true, },
	["CurseoftheSadMummy"] = {charName = "Amumu", skillshotType = "circular", delay = 250, missileSpeed = math.huge, range = 0, radius = 550, addHitBox = false, fixedRange = true, collision = false, },
	["Volley"] = {charName = "Ashe", skillshotType = "linem", delay = 250, missileSpeed = 1500, range = 1200, radius = 35, addHitBox = true, fixedRange = true, collision = false, },
	["EnchantedCrystalArrow"] = {charName = "Ashe", skillshotType = "linem", delay = 250, missileSpeed = 1600, range = 25000, radius = 130, addHitBox = true, fixedRange = true, collision = true, },
	["BrandBlaze"] = {charName = "Brand", skillshotType = "linem", delay = 250, missileSpeed = 1600, range = 1100, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["BrandFissure"] = {charName = "Brand", skillshotType = "circular", delay = 850, missileSpeed = math.huge, range = 900, radius = 240, addHitBox = true, fixedRange = false, collision = false, },
	["BraumQ"] = {charName = "Braum", skillshotType = "linem", delay = 250, missileSpeed = 1700, range = 1050, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["BraumRWrapper"] = {charName = "Braum", skillshotType = "linem", delay = 500, missileSpeed = 1400, range = 1200, radius = 115, addHitBox = true, fixedRange = true, collision = false, },
	["RocketGrab"] = {charName = "Blitzcrank", skillshotType = "linem", delay = 250, missileSpeed = 1800, range = 1050, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	["StaticField"] = {charName = "Blitzcrank", skillshotType = "circular", delay = 250, missileSpeed = math.huge, range = 0, radius = 600, addHitBox = false, fixedRange = true, collision = false, },
	["CaitlynPiltoverPeacemaker"] = {charName = "Caitlyn", skillshotType = "linem", delay = 625, missileSpeed = 2200, range = 1300, radius = 90, addHitBox = true, fixedRange = true, collision = false, },
	["CaitlynEntrapment"] = {charName = "Caitlyn", skillshotType = "linem", delay = 125, missileSpeed = 2000, range = 1000, radius = 80, addHitBox = true, fixedRange = true, collision = true, },
	["CassiopeiaNoxiousBlast"] = {charName = "Cassiopeia", skillshotType = "circular", delay = 600, missileSpeed = math.huge, range = 850, radius = 150, addHitBox = true, fixedRange = false, collision = false, },
	["Rupture"] = {charName = "Chogath", skillshotType = "circular", delay = 1200, missileSpeed = math.huge, range = 950, radius = 250, addHitBox = true, fixedRange = false, collision = false, },
	["PhosphorusBomb"] = {charName = "Corki", skillshotType = "circular", delay = 500, missileSpeed = 1125, range = 825, radius = 250, addHitBox = true, fixedRange = false, collision = false, },
	["MissileBarrage"] = {charName = "Corki", skillshotType = "linem", delay = 200, missileSpeed = 2000, range = 1300, radius = 40, addHitBox = true, fixedRange = true, collision = true, },
	["InfectedCleaverMissileCast"] = {charName = "DrMundo", skillshotType = "linem", delay = 250, missileSpeed = 2000, range = 1050, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["DravenDoubleShot"] = {charName = "Draven", skillshotType = "linem", delay = 250, missileSpeed = 1400, range = 1100, radius = 130, addHitBox = true, fixedRange = true, collision = true, },
	["DravenRCast"] = {charName = "Draven", skillshotType = "linem", delay = 1000, missileSpeed = 2000, range = 20000, radius = 160, addHitBox = true, fixedRange = true, collision = false, },
	["EliseHumanE"] = {charName = "Elise", skillshotType = "linem", delay = 250, missileSpeed = 1450, range = 1100, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	["EvelynnR"] = {charName = "Evelynn", skillshotType = "circular", delay = 250, missileSpeed = math.huge, range = 650, radius = 350, addHitBox = true, fixedRange = false, collision = false, },
	["EzrealMysticShot"] = {charName = "Ezreal", uniqueId2 = 229, skillshotType = "linem", delay = 250, missileSpeed = 2000, range = 1200, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["EzrealEssenceFlux"] = {charName = "Ezreal", skillshotType = "linem", delay = 250, missileSpeed = 1600, range = 1050, radius = 80, addHitBox = true, fixedRange = true, collision = false, },
	["EzrealTrueshotBarrage"] = {charName = "Ezreal", isGlobal = true, uniqueId2 = 245, skillshotType = "linem", delay = 1000, missileSpeed = 2000, range = 20000, radius = 160, addHitBox = true, fixedRange = true, collision = false, },
	["FizzMarinerDoom"] = {charName = "Fizz", skillshotType = "linem", delay = 250, missileSpeed = 1350, range = 1300, radius = 120, addHitBox = true, fixedRange = false, collision = true, },
	["GalioResoluteSmite"] = {charName = "Galio", skillshotType = "circular", delay = 250, missileSpeed = 1300, range = 900, radius = 200, addHitBox = true, fixedRange = false, collision = false, },
	["GalioIdolOfDurand"] = {charName = "Galio", skillshotType = "circular", delay = 250, missileSpeed = math.huge, range = 0, radius = 550, addHitBox = false, fixedRange = true, collision = false, },
	["GragasQ"] = {charName = "Gragas", extraDuration = 4000, skillshotType = "circular", delay = 250, missileSpeed = 1300, range = 1100, radius = 275, addHitBox = true, fixedRange = false, collision = false, },
	["GragasE"] = {charName = "Gragas", skillshotType = "linem", delay = 0, missileSpeed = 1200, range = 700, radius = 50, addHitBox = true, fixedRange = false, collision = true, },
	["GragasR"] = {charName = "Gragas", skillshotType = "circular", delay = 700, missileSpeed = math.huge, range = 1050, radius = 375, addHitBox = true, fixedRange = false, collision = false, },
	["GravesClusterShot"] = {charName = "Graves", skillshotType = "linem", delay = 250, missileSpeed = 2000, range = 1000, radius = 50, addHitBox = true, fixedRange = true, collision = false, },
	["GravesChargeShot"] = {charName = "Graves", skillshotType = "linem", delay = 250, missileSpeed = 2100, range = 1100, radius = 100, addHitBox = true, fixedRange = true, collision = false, },
	["HeimerdingerE"] = {charName = "Heimerdinger", skillshotType = "circular", delay = 250, missileSpeed = 1200, range = 925, radius = 100, addHitBox = true, fixedRange = false, collision = false, },
	["IreliaTranscendentBlades"] = {charName = "Irelia", skillshotType = "linem", delay = 0, missileSpeed = 1600, range = 1200, radius = 0, addHitBox = true, fixedRange = true, collision = false, },
	["JarvanIVDragonStrike"] = {charName = "JarvanIV", skillshotType = "linem", delay = 250, missileSpeed = 1450, range = 1000, radius = 70, addHitBox = true, fixedRange = true, collision = false, },
	["JarvanIVDemacianStandard"] = {charName = "JarvanIV", skillshotType = "circular", delay = 500, missileSpeed = math.huge, range = 860, radius = 175, addHitBox = true, fixedRange = false, collision = false, },
	["JinxW"] = {charName = "Jinx", skillshotType = "linem", delay = 600, missileSpeed = 3300, range = 1500, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["JinxRWrapper"] = {charName = "Jinx", skillshotType = "linem", delay = 600, missileSpeed = 1700, range = 20000, radius = 140, addHitBox = true, fixedRange = true, collision = true, },
	["KarmaQ"] = {charName = "Karma", skillshotType = "linem", delay = 250, missileSpeed = 1700, range = 950, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["LayWaste"] = {charName = "Karthus", skillshotType = "circular", delay = 625, missileSpeed = math.huge, range = 875, radius = 160, addHitBox = true, fixedRange = false, collision = false, },
	["RiftWalk"] = {charName = "Kassadin", skillshotType = "circular", delay = 250, missileSpeed = math.huge, range = 700, radius = 270, addHitBox = true, fixedRange = false, collision = false, },
	["KennenShurikenHurlMissile1"] = {charName = "Kennen", skillshotType = "linem", delay = 125, missileSpeed = 1700, range = 1050, radius = 50, addHitBox = true, fixedRange = true, collision = true, },
	["KhazixW"] = {charName = "Khazix", skillshotType = "linem", delay = 250, missileSpeed = 1700, range = 1025, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	["khazixwlong"] = {charName = "Khazix", skillshotType = "linem", delay = 250, missileSpeed = 1700, range = 1025, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	["KhazixE"] = {charName = "Khazix", skillshotType = "circular", delay = 250, missileSpeed = 1500, range = 600, radius = 300, addHitBox = true, fixedRange = false, collision = false, },
	["KogMawQ"] = {charName = "KogMaw", skillshotType = "linem", delay = 250, missileSpeed = 1650, range = 1000, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	["KogMawVoidOoze"] = {charName = "KogMaw", skillshotType = "linem", delay = 250, missileSpeed = 1400, range = 1500, radius = 120, addHitBox = true, fixedRange = true, collision = false, },
	["KogMawLivingArtillery"] = {charName = "KogMaw", skillshotType = "circular", delay = 1000, missileSpeed = math.huge, range = 1800, radius = 225, addHitBox = true, fixedRange = false, collision = false, },
	["LeblancSlide"] = {charName = "Leblanc", skillshotType = "circular", delay = 0, missileSpeed = 1500, range = 600, radius = 220, addHitBox = true, fixedRange = false, collision = false, },
	["LeblancSlideM"] = {charName = "Leblanc", skillshotType = "circular", delay = 0, missileSpeed = 1500, range = 600, radius = 220, addHitBox = true, fixedRange = false, collision = false, },
	["LeblancSoulShackle"] = {charName = "Leblanc", skillshotType = "linem", delay = 250, missileSpeed = 1600, range = 950, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	["LeblancSoulShackleM"] = {charName = "Leblanc", skillshotType = "linem", delay = 250, missileSpeed = 1600, range = 950, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	["BlindMonkQOne"] = {charName = "LeeSin", skillshotType = "linem", delay = 250, missileSpeed = 1800, range = 1100, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["LeonaZenithBlade"] = {charName = "Leona", skillshotType = "linem", delay = 250, missileSpeed = 2000, range = 900, radius = 90, addHitBox = true, fixedRange = true, collision = false, },
	["LeonaSolarFlare"] = {charName = "Leona", skillshotType = "circular", delay = 1000, missileSpeed = math.huge, range = 1200, radius = 120, addHitBox = true, fixedRange = false, collision = false, },
	["LuxLightBinding"] = {charName = "Lux", skillshotType = "linem", delay = 250, missileSpeed = 1200, range = 1300, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	["LuxLightStrikeKugel"] = {charName = "Lux", extraDuration = 5000, skillshotType = "circular", delay = 250, missileSpeed = 1300, range = 1100, radius = 275, addHitBox = true, fixedRange = false, collision = false, },
	["LuxMaliceCannon"] = {charName = "Lux", skillshotType = "line", delay = 1350, missileSpeed = math.huge, range = 3500, radius = 190, addHitBox = true, fixedRange = true, collision = false, },
	["UFSlash"] = {charName = "Malphite", skillshotType = "circular", delay = 250, missileSpeed = 1500, range = 1000, radius = 270, addHitBox = true, fixedRange = false, collision = false, },
	["AlZaharCalloftheVoidS1"] = {charName = "Malzahar", skillshotType = "linem", delay = 700, missileSpeed = 1600, range = 700, radius = 85, addHitBox = true, fixedRange = true, collision = false, },
	["AlZaharCalloftheVoidS2"] = {charName = "Malzahar", skillshotType = "linem", delay = 700, missileSpeed = 1600, range = 700, radius = 85, addHitBox = true, fixedRange = true, collision = false, },
	["DarkBindingMissile"] = {charName = "Morgana", skillshotType = "linem", delay = 250, missileSpeed = 1200, range = 1300, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	["JavelinToss"] = {charName = "Nidalee", skillshotType = "linem", delay = 125, missileSpeed = 1300, range = 1500, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["NautilusAnchorDrag"] = {charName = "Nautilus", skillshotType = "linem", delay = 250, missileSpeed = 2000, range = 1100, radius = 90, addHitBox = true, fixedRange = true, collision = true, },
	["OlafAxeThrowCast"] = {charName = "Olaf", skillshotType = "linem", delay = 250, missileSpeed = 1600, range = 1000, radius = 90, addHitBox = true, fixedRange = false, collision = true, },
	["OriannaQs"] = {charName = "Orianna", skillshotType = "linem", delay = 0, missileSpeed = 1200, range = 1500, radius = 80, addHitBox = true, fixedRange = false, collision = false, },
	["QuinnQ"] = {charName = "Quinn", skillshotType = "linem", delay = 250, missileSpeed = 1550, range = 1050, radius = 80, addHitBox = true, fixedRange = true, collision = true, },
	["RengarE"] = {charName = "Rengar", skillshotType = "linem", delay = 250, missileSpeed = 1500, range = 1000, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	["RumbleGrenade"] = {charName = "Rumble", skillshotType = "linem", delay = 0, missileSpeed = 2000, range = 950, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["SivirQ"] = {charName = "Sivir", uniqueId = 53, skillshotType = "linem", delay = 250, missileSpeed = 1350, range = 1175, radius = 90, addHitBox = true, fixedRange = true, collision = false, },
	--["SivirQReturn"] = {charName = "Sivir", uniqueId = 54, skillshotType = "linem", delay = 250, missileSpeed = 1350, range = 1175, radius = 100, addHitBox = true, fixedRange = true, collision = false, },
	["SkarnerFracture"] = {charName = "Skarner", skillshotType = "linem", delay = 250, missileSpeed = 1500, range = 1000, radius = 70, addHitBox = true, fixedRange = true, collision = false, },
	["SonaCrescendo"] = {charName = "Sona", skillshotType = "linem", delay = 250, missileSpeed = 2400, range = 1000, radius = 140, addHitBox = true, fixedRange = true, collision = false, },
	["SyndraQ"] = {charName = "Syndra", skillshotType = "circular", delay = 600, missileSpeed = math.huge, range = 800, radius = 150, addHitBox = true, fixedRange = false, collision = false, },
	["syndrawcast"] = {charName = "Syndra", skillshotType = "circular", delay = 900, missileSpeed = math.huge, range = 950, radius = 210, addHitBox = true, fixedRange = false, collision = false, },
	["ShenShadowDash"] = {charName = "Shen", skillshotType = "linem", delay = 0, missileSpeed = 1600, range = 650, radius = 50, addHitBox = true, fixedRange = false, collision = false, },
	["ShyvanaFireball"] = {charName = "Shyvana", skillshotType = "linem", delay = 250, missileSpeed = 1700, range = 950, radius = 60, addHitBox = true, fixedRange = true, collision = false, },
	["ShyvanaTransformCast"] = {charName = "Shyvana", skillshotType = "linem", delay = 250, missileSpeed = 1500, range = 1000, radius = 150, addHitBox = true, fixedRange = true, collision = false, },
	["SwainShadowGrasp"] = {charName = "Swain", skillshotType = "circular", delay = 900, missileSpeed = math.huge, range = 900, radius = 180, addHitBox = true, fixedRange = false, collision = false, },
	["slashCast"] = {charName = "Tryndamere", skillshotType = "linem", delay = 0, missileSpeed = 1300, range = 660, radius = 93, addHitBox = true, fixedRange = false, collision = false, },
	["RocketJump"] = {charName = "Tristana", skillshotType = "circular", delay = 500, missileSpeed = 1500, range = 900, radius = 270, addHitBox = true, fixedRange = false, collision = false, },
	["TwitchVenomCask"] = {charName = "Twitch", skillshotType = "circular", delay = 250, missileSpeed = 1400, range = 900, radius = 275, addHitBox = true, fixedRange = false, collision = false, },
	["WildCards"] = {charName = "TwistedFate", skillshotType = "linem", delay = 250, missileSpeed = 1000, range = 1450, radius = 40, addHitBox = true, fixedRange = true, collision = false, },
	["ThreshQ"] = {charName = "Thresh", skillshotType = "linem", delay = 500, missileSpeed = 1900, range = 1100, radius = 70, addHitBox = true, fixedRange = true, collision = true, },
	--["ThreshE"] = {charName = "Thresh", skillshotType = "linem", delay = 125, missileSpeed = 2000, range = 1075, radius = 110, addHitBox = true, fixedRange = true, collision = false, },
	["UrgotHeatseekingLineMissile"] = {charName = "Urgot", skillshotType = "linem", delay = 125, missileSpeed = 1600, range = 1000, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["UrgotPlasmaGrenade"] = {charName = "Urgot", skillshotType = "circular", delay = 250, missileSpeed = 1500, range = 1100, radius = 210, addHitBox = true, fixedRange = false, collision = false, },
	["VarusQMissilee"] = {charName = "Varus", skillshotType = "linem", delay = 250, missileSpeed = 1900, range = 1800, radius = 70, addHitBox = true, fixedRange = true, collision = false, },
	["VarusE"] = {charName = "Varus", skillshotType = "circular", delay = 1000, missileSpeed = 1500, range = 925, radius = 235, addHitBox = true, fixedRange = false, collision = false, },
	["VarusR"] = {charName = "Varus", skillshotType = "linem", delay = 250, missileSpeed = 1950, range = 1200, radius = 100, addHitBox = true, fixedRange = true, collision = true, },
	["VelkozQ"] = {charName = "Velkoz", skillshotType = "linem", delay = 250, missileSpeed = 1300, range = 1100, radius = 50, addHitBox = true, fixedRange = true, collision = true, },
	["VelkozQSplit"] = {charName = "Velkoz", skillshotType = "linem", delay = 250, missileSpeed = 2100, range = 900, radius = 45, addHitBox = true, fixedRange = true, collision = true, },
	["VelkozW"] = {charName = "Velkoz", skillshotType = "linem", delay = 250, missileSpeed = 1700, range = 1200, radius = 65, addHitBox = true, fixedRange = true, collision = false, },
	["VelkozE"] = {charName = "Velkoz", skillshotType = "circular", delay = 500, missileSpeed = 1500, range = 800, radius = 225, addHitBox = false, fixedRange = false, collision = false, },
	["VeigarDarkMatter"] = {charName = "Veigar", skillshotType = "circular", delay = 1350, missileSpeed = math.huge, range = 900, radius = 225, addHitBox = true, fixedRange = false, collision = false, },
	["ViQ"] = {charName = "Vi", skillshotType = "linem", delay = 250, missileSpeed = 1500, range = 1000, radius = 90, addHitBox = true, fixedRange = true, collision = false, },
	["ViktorDeathRayF"] = {charName = "Viktor", skillshotType = "linem", delay = 250, missileSpeed = 780, range = 1500, radius = 80, addHitBox = true, fixedRange = false, collision = false, },
	["xeratharcanopulse2"] = {charName = "Xerath", skillshotType = "line", delay = 600, missileSpeed = math.huge, range = 1600, radius = 100, addHitBox = true, fixedRange = true, collision = false, },
	["XerathArcaneBarrage2"] = {charName = "Xerath", skillshotType = "circular", delay = 900, missileSpeed = math.huge, range = 1000, radius = 200, addHitBox = true, fixedRange = false, collision = false, },
	["XerathMageSpear"] = {charName = "Xerath", skillshotType = "linem", delay = 250, missileSpeed = 1400, range = 1100, radius = 60, addHitBox = true, fixedRange = true, collision = true, },
	["xerathrmissilewrapper"] = {charName = "Xerath", skillshotType = "circular", delay = 600, missileSpeed = math.huge, range = 5600, radius = 110, addHitBox = true, fixedRange = false, collision = false, },
	["yasuoq"] = {charName = "Yasuo", mirror = true, skillshotType = "line", delay = 500, missileSpeed = math.huge, range = 520, radius = 15, addHitBox = true, fixedRange = true, collision = false, },
	["yasuoq2"] = {charName = "Yasuo", mirror = true, skillshotType = "line", delay = 500, missileSpeed = math.huge, range = 520, radius = 15, addHitBox = true, fixedRange = true, collision = false, },
	["yasuoq3w"] = {charName = "Yasuo", skillshotType = "linem", delay = 250, missileSpeed = 1500, range = 1150, radius = 90, addHitBox = true, fixedRange = true, collision = false, },
	["ZacQ"] = {charName = "Zac", skillshotType = "line", delay = 500, missileSpeed = math.huge, range = 550, radius = 120, addHitBox = true, fixedRange = true, collision = false, },
	["ZedShuriken"] = {charName = "Zed", skillshotType = "linem", delay = 250, missileSpeed = 1700, range = 925, radius = 50, addHitBox = true, fixedRange = true, collision = false, },
	["ZyraQFissure"] = {charName = "Zyra", skillshotType = "circular", delay = 1000, missileSpeed = math.huge, range = 800, radius = 220, addHitBox = true, fixedRange = false, collision = false, },
	["ZyraGraspingRoots"] = {charName = "Zyra", skillshotType = "linem", delay = 250, missileSpeed = 1150, range = 1150, radius = 70, addHitBox = true, fixedRange = true, collision = false, },
	["zyrapassivedeathmanager"] = {charName = "Zyra", skillshotType = "linem", delay = 500, missileSpeed = 2000, range = 1474, radius = 60, addHitBox = true, fixedRange = true, collision = false, },
}

function iMsg(msg)
	print("<font color=\"#FF0000\">iVade:</font> <font color=\"#FFFFFF\">"..msg.."</font>")
end

function iDebug(msg)
	--print("<font color=\"#FF0000\">DEBUG:</font> <font color=\"#FFFFFF\">"..msg.."</font>")
end

local dashes = {
	["Ezreal"] = {slot = _E,  isBlink = true, fixedRange = false, maxRange = 450, delay = 0.25, isHP = true, dashType = "skillShot"},
	["Vayne"] =  {slot = _Q,  isBlink = false, fixedRange = true, maxRange = 300, speed = 800, delay = 0.25, isHP = false, dashType = "skillshot"},
}

getFlashSlot = function()
	if myHero:GetSpellData(SUMMONER_2).name == "SummonerFlash" then return SUMMONER_2 end
	if myHero:GetSpellData(SUMMONER_1).name == "SummonerFlash" then return SUMMONER_1 end
end

if getFlashSlot() then
	iDebug("Flash detected")
	dashes["Flash"] = {slot = getFlashSlot(),  isBlink = true, fixedRange = false, maxRange = 400, delay = 0, dashType = "skillshot", isFlash = true}
end

for skillshot, data in pairs(posibleSkillshotable) do
	posibleSkillshotable[skillshot].name = skillshot
end

function OnLoad()
	iMsg("Loaded succesfully!")
	menu = scriptConfig("iVade", "iVade")

	menu:addSubMenu("Advanced", "advanced")
		menu.advanced:addParam("edelay", "Move delay", SCRIPT_PARAM_SLICE, 0, 0, 100, 0)

	menu:addSubMenu("Drawings", "drawings")
		menu.drawings:addParam("lineColor", "Enabled skillshot color", SCRIPT_PARAM_COLOR, {255,255,255,255})
		menu.drawings:addParam("lineColor2", "Disabled skillshot color", SCRIPT_PARAM_COLOR, {255,255,255,255})

		menu.drawings:addParam("border", "Border width", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
		menu.drawings:addParam("addHitBox", "Add hitbox to the skillshot size.", SCRIPT_PARAM_ONOFF, true)

	menu:addSubMenu("Skillshots", "Skillshots")
	for i, enemy in ipairs(getChampionsTable()) do
		for skillshot, skillshotinfo in pairs(posibleSkillshotable) do
			if skillshotinfo.charName == enemy.charName then
				menu.Skillshots:addSubMenu(enemy.charName.." - "..skillshot, skillshot)

				menu.Skillshots[skillshot]:addParam("danger",    "Dont dodge towards danger (dw yet.)", SCRIPT_PARAM_ONOFF, true)
				menu.Skillshots[skillshot]:addParam("enemyLimit", "Dont evade if more than this enemies around", SCRIPT_PARAM_SLICE, 1, 5, 5)

				menu.Skillshots[skillshot]:addParam("sep1", " ", SCRIPT_PARAM_INFO, " ")

				menu.Skillshots[skillshot]:addParam("walking",    "Evade walking", SCRIPT_PARAM_ONOFF, true)
				menu.Skillshots[skillshot]:addParam("lpdash", "Use LP dashes", SCRIPT_PARAM_ONOFF, true)
				menu.Skillshots[skillshot]:addParam("hpdash", "Use HP dashes", SCRIPT_PARAM_ONOFF, skillshotinfo.useHPDashes and true or false)
				menu.Skillshots[skillshot]:addParam("flash",    "Use Flash", SCRIPT_PARAM_ONOFF, skillshotinfo.useFlash and true or false)
				menu.Skillshots[skillshot]:addParam("isDangerous",    "Is Dangerous", SCRIPT_PARAM_ONOFF, skillshotinfo.isDangerous and true or false)

				menu.Skillshots[skillshot]:addParam("sep2", " ", SCRIPT_PARAM_INFO, " ")

				menu.Skillshots[skillshot]:addParam("draw",    "Draw skillshot", SCRIPT_PARAM_ONOFF, true)
				menu.Skillshots[skillshot]:addParam("enabled", "Dodge skillshot", SCRIPT_PARAM_ONOFF, true)
			end
		end
	end

	menu:addParam("draw", "Draw skillshots", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("U"))
	menu:addParam("enabled", "Dodge skillshots", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("I"))
	menu:addParam("dodgeOnlyDangerous", "Dodge only dangerous", SCRIPT_PARAM_ONKEYDOWN, false, 32)

	menu:addParam("Author", "Author", SCRIPT_PARAM_INFO, author)
	menu:addParam("Version", "Version", SCRIPT_PARAM_INFO, version)
end

function OnTick()
	_G.evade = evading
	for i, sshot in ipairs(activeSkillshotTable) do
		sshot:OnTick()
	end

	for i = #activeSkillshotTable, 1, -1 do
		if not activeSkillshotTable[i]:isActive() then
			table.remove(activeSkillshotTable, i)
		end
	end

	if lastTickPosition then
		if GetDistanceSqr(lastTickPosition, myHero.visionPos) > 50 * 50 then
			iDebug("Blink detected")
			evading = false
		end
	end
	lastTickPosition = Vector(myHero.visionPos)

	if not imEnabled and menu.enabled then
		iMsg("Reenabled")
		evading = false
	end
	imEnabled = menu.enabled

	if not menu.enabled then return end

	if imDodgingOnlyDangerous ~= menu.dodgeOnlyDangerous then
		iDebug("Dodge only dangerous mode toggled")
		evading = false
	end
	imDodgingOnlyDangerous = menu.dodgeOnlyDangerous

	local myWaypoints = getWayPoints(myHero)
	local imSafe, hitBy = IsSafe(Point(myHero.visionPos.x, myHero.visionPos.z)) 
	
	cantevade = false
	--Not Safe
	if not imSafe then
		if evading then
			--Continue evading
			if IsSafe(evadePoint) then
				Packet("S_MOVE", {x = evadePoint.x, y = evadePoint.y}):send()
			else
				evading = false
			end
		else
			--Start evading.
			local evadeTo = myWaypoints[#myWaypoints]
			--Walking
			local posiblePoints = hitBy:getWalkingEvadePoints()
			if #posiblePoints > 0 then
				local closest = getClosestPoint(posiblePoints, evadeTo)
				evadePoint = closest --+ 100*(closest - Point(myHero.visionPos.x, myHero.visionPos.z)):normalized()
				evading = true
				do return end 
			end

			local myDashes = {}
			if dashes[myHero.charName] then table.insert(myDashes, dashes[myHero.charName]) end
			if dashes["Flash"] then table.insert(myDashes, dashes["Flash"]) end

			for name, dash in pairs(myDashes) do
				if dash and myHero:CanUseSpell(dash.slot) == READY then
					local posiblePoints = hitBy:getDashingEvadePoints(dash)
					if #posiblePoints > 0 then
						local closest = getClosestPoint(posiblePoints, evadeTo)
						evadePoint = closest
						evading = true
						CastSpell(dash.slot, evadePoint.x, evadePoint.y)
						do return end 
					end
				end
			end
			cantevade = true
			iDebug("Can't evade")
		end
	else
		if evading then
			evading = false
		end

		--Prevent entering into dangerous area.
		if not cantevade then
			cutPath(myWaypoints)
		end 
	end

end

function OnSendPacket(p)
	if cantevade then return end
	if not menu.enabled then return end
	if p.header == Packet.headers.S_MOVE then
		local decodedPacket = Packet(p)
		local wayPoints = decodedPacket:get("wayPoints")

		if evading and decodedPacket:get("type") ~= 3 then
			local ip, intersection = cutPath(wayPoints, true, true)

			if not ip or GetDistanceSqr(evadePoint, intersection) >= 50*50 then
				p:Block()
			else
				evadePoint = Point(decodedPacket:get("x"), decodedPacket:get("y"))
			end
			
			if intersection then
				--drawThis = intersection
			end
			do return end

		elseif evading then
			p:Block()
			do return end
		end

		local notSafe, intersection = cutPath(wayPoints, true)
		if notSafe then
			if decodedPacket:get("type") ~= 3 then
				p:Block()
			end
			if decodedPacket:get("type") == 3 and not IsSafe(Point(decodedPacket:get("x"), decodedPacket:get("y"))) then
				local target = objManager:GetObjectByNetworkId(decodedPacket:get("targetNetworkId"))
				if not ValidTarget(target) or GetDistanceSqr(myHero.visionPos, target.visionPos) >= math.pow(myHero.range + getHitBox(myHero) + getHitBox(target) - 30, 2) then
					p:Block()
				end
				Packet("S_MOVE", {x = intersection.x, y = intersection.y}):send()
			end
		end
	end
end

function OnDash(unit, dash)
	if unit.isMe then
		local t = dash.endT - dash.startT
		iDebug("New dash:")
		DelayAction(function() evading = false end, t  - GetLatency() / 1000)
	end
end

-----------------------------------Skillshot detection
function OnProcessSpell(unit, spell)
	if unit.team == myHero.team and not testOnAllies then return end
	if posibleSkillshotable[spell.name] then
		iDebug("New skillshot detection from OnProcessSpell")
		local sdata = posibleSkillshotable[spell.name]
		local endPoint = (sdata.range == 0) and Point(unit.visionPos.x, unit.visionPos.z)  or Point(spell.endPos.x, spell.endPos.z)


		if ((GetDistanceSqr(unit.visionPos, endPoint) > sdata.range * sdata.range) or sdata.fixedRange) and sdata.range ~= 0 then
			local C = 1
			if sdata.mirror then
				C = -1
			end
			endPoint = Point(unit.visionPos.x, unit.visionPos.z) + C * sdata.range * (Point(endPoint.x, endPoint.y) - Point(unit.visionPos.x, unit.visionPos.z)):normalized()
		end

		
		local newSkillshot = skillshot("OnProcessSpell", posibleSkillshotable[spell.name], GetTickCount() - GetLatency()/2, Point(unit.visionPos.x, unit.visionPos.z), endPoint, unit)
		if spell.projectileID then
			table.insert(newSkillshot.projectiles, spell.projectileID)
		end
		table.insert(activeSkillshotTable, newSkillshot)
		iDebug("New skillshot Added")

	end
	
end

function OnRecvPacket(p)

	--Skillshot detection from FoW
	if p.header == 0x3A then
		p.pos = 1
		local projectileNetworkId = p:DecodeF()
		local projectilePosition = Vector(p:DecodeF(), p:DecodeF(), p:DecodeF())
		local startPos = Vector(p:DecodeF(), p:DecodeF(), p:DecodeF())

		p.pos = p.size - 119
		local projectileSpeed = p:DecodeF()
		
		p.pos = 65
		local endPos = Vector(p:DecodeF(), p:DecodeF(), p:DecodeF())

		p.pos = 112
		local Id2 = p:Decode1()

		p.pos = p.size - 83
		local unit = objManager:GetObjectByNetworkId(p:DecodeF())
		if unit and unit.valid then
			if unit.team == myHero.team and not testOnAllies then return end
			iDebug("New skillshot from FoW detected. Id2: " ..  Id2)

			for name, sdata in pairs(posibleSkillshotable) do
				if sdata.charName == unit.charName and sdata.missileSpeed == projectileSpeed and (not sdata.uniqueId2 or (sdata.uniqueId2 == Id2)) then
					--Found, check if its already added
					local alreadyAdded = false
					for i, skillshot in ipairs(activeSkillshotTable) do
						if skillshot.caster.networkID == unit.networkID and skillshot.detectionType == "OnProcessSpell" and skillshot.data.name == sdata.name and GetDistanceSqr(endPos, skillshot.endPos) < 50 then
							alreadyAdded = true
						end
					end

					if not alreadyAdded then
						--Add if its not already added
						local timeOffSet = GetDistance(projectilePosition, startPos) / projectileSpeed * 1000 + sdata.delay
						local newSkillshot = skillshot("OnRecvPacket", posibleSkillshotable[name], GetTickCount() - GetLatency()/2 - timeOffSet, Point(startPos.x, startPos.z), Point(endPos.x, endPos.z), unit)
						table.insert(newSkillshot.projectiles, projectileNetworkId)
						table.insert(activeSkillshotTable, newSkillshot)
					end
				end
			end
		end
	end

	--Delete projectiles that collide
	if p.header == 0x25 then

	end
end
-----------------------------------End of Skillshot detection

function OnDraw()
	for i, sshot in ipairs(activeSkillshotTable) do
		sshot:OnDraw()
	end
	local wpoints = getWayPoints(myHero)

	for i=1, #wpoints-1 do
		DrawLineBorder3D(wpoints[i].x, myHero.y, wpoints[i].y, wpoints[i+1].x, myHero.y, wpoints[i+1].y, 2, ARGB(255, 255, 255, 255), 1) 
	end
end

function cutPath(path, fsp, notSend)
	local intersection, comingFrom, intersectionDistance = getFirstSkillshotIntersection(path)

	if intersection then
		local intersectionMPoint = intersection + 5 * (comingFrom - intersection):normalized()
		if (not fsp or GetDistanceSqr(intersectionMPoint, myHero.visionPos) > 50*50) and not notSend then
			Packet("S_MOVE", {type = 2, x = intersectionMPoint.x, y = intersectionMPoint.y}):send()
		end
		return true, intersection
	end

	return false
end

function IsSafe(point)
	for i, sshot in ipairs(activeSkillshotTable) do
		if not sshot:IsSafe(point) then
			return false, sshot
		end
	end

	return true
end

function getFirstSkillshotIntersection(path)
	local foundIntersections = {}
	for i, sshot in ipairs(activeSkillshotTable) do
		local intersection, fromS, distanceOnPath = sshot:getFirstIntersection(path)
		if intersection then
			table.insert(foundIntersections, {intersection = intersection, from = fromS, dist = distanceOnPath})
		end
	end
	
	if (#foundIntersections > 0) then
		local closest
		local closestK = math.huge
		
		for k = 1, #foundIntersections do
			if foundIntersections[k].dist <= closestK then
				closestK = foundIntersections[k].dist
				closest = foundIntersections[k]
			end
		end

		return closest.intersection, closest.from, closest.dist
	end
end

class "skillshot"
function skillshot:__init(detectionType, spellData, startT, startPos, endPos, caster)

	self.detectionType = detectionType
	self.projectiles = {}
	--Constant spell data
	self.data = {}
	
	for i, j in pairs(spellData) do
		self.data[i] = j
	end

	if self.data.addHitBox then
		self.data.radius = self.data.radius + getHitBox(myHero)
	end

	self.data.radiusSqr = self.data.radius * self.data.radius

	self.menu = menu.Skillshots[self.data.name]

	--Variable cast data (changes for each skillshot cast)
	self.startT           = startT                                       --The tick when the skillshot starts
	
	self.startPos         = startPos                                     --The position from where the skillshot was casted
	self.missilePos       = startPos                                     --The position where the skillshot missile is.
	self.endPos           = endPos                                       --The end position.
	self.realEndPos       = endPos                                       --The end position.
	
	self.endT             = self.startT + self.data.delay + GetDistance(self.startPos, self.endPos) / self.data.missileSpeed * 1000 + (self.data.extraDuration or 0)
	
	self.direction        = (endPos - startPos):normalized()             --The skillshot direction
	self.normal           = Point(- self.direction.y, self.direction.x)  --The skillshot direction normal
	
	self.caster           = caster                                       --The enemy that casted the skillshot.
end

function skillshot:isActive()
	return GetTickCount() <= self.endT
end

function skillshot:isEnabled()
	if not self.menu.isDangerous and menu.dodgeOnlyDangerous then
		return false	
	end

	return self.menu.enabled
end

function skillshot:OnTick()
	if self.data.skillshotType == "linem" then
		local elapsedTime = math.max(0, GetTickCount() - self.startT - self.data.delay)
		if elapsedTime ~= 0 then
			self.missilePos = self.startPos + self.direction * math.min(GetDistance(self.endPos, self.startPos) , elapsedTime / 1000 * self.data.missileSpeed)
		end

		if self.data.isGlobal then
			self.endPos = self.missilePos + self.direction * math.min(((self.data.radius * 2 / myHero.ms) + 0.1 + GetLatency()/2000) * self.data.missileSpeed, GetDistance(self.missilePos, self.realEndPos))
		end
	end
end

--Returns true if the point is safe, false otherwise.
function skillshot:IsSafe(point)
	if not self:isEnabled() then
		return true
	end

	--Circular skillshot
	if self.data.skillshotType == "circular" then
		if GetDistanceSqr(point, self.endPos) <= math.pow(self.data.radius / math.cos(2 * math.pi / circularPolygonSides), 2) then
			return false
		end
	end

	--Line skillshot
	if self.data.skillshotType == "line" or self.data.skillshotType == "linem" then
		--Calculate the projection to the skillshot line (the line that passes from the startPos to the endPos).
		local pointSegment, pointLine, isOnSegment = VectorPointProjectionOnLineSegment(self.missilePos, self.endPos, point)
		
		--The projection is on the line segment between startPos and endPos and the distance is less than the radius -> the point is NOT safe
		if isOnSegment and GetDistanceSqr(pointSegment, point) <= self.data.radiusSqr then
			return false
		end
	end

	return true
end

function skillshot:getDashingEvadePoints(dash)
	local result = {}

	if not self.menu.hpdash and dash.isHP then
		return result
	end

	if not self.menu.flash and dash.isFlash then
		return result
	end

	if not self.menu.lpdash and not dash.isHP and not dash.isFlash then
		return result
	end

	if dash.dashType == "skillshot" then
		if dash.isBlink then

			if self.endT - GetTickCount() - moveDelay() - dash.delay < 0 then return result end
			
			if self.skillshotType == "linem" then 
				local lsStart = self.missilePos + self.radius * self.normal
				local lsEnd = self.missilePos - self.radius * self.normal
				local pointSegment, pointLine, isOnSegment = VectorPointProjectionOnLineSegment(lsStart, lsEnd, myHero.visionPos)
				if GetDistanceSqr(myHero.visionPos, pointSegment) <= math.pow(self.missileSpeed * dash.delay, 2) then
					return result
				end
			end
			
			local centerPoint = Point(myHero.visionPos.x, myHero.visionPos.z)
			for angle = 0, 2 * math.pi + 0.2, 0.2 do
				local candidate = centerPoint + dash.maxRange * Point(math.cos(angle), math.sin(angle))
				if self:IsSafe(candidate) then
					table.insert(result, candidate)
				end
			end
		end

		if not dash.isBlink then
			return self:getWalkingEvadePoints(dash.speed, dash.delay)
		end
	end

	--Delete the wall points
	for i = #result, 1, -1 do
		if IsWall(D3DXVECTOR3(result[i].x, myHero.y, result[i].y)) then
			table.remove(result, i)
		end
	end

	return result
end

--Returns a table with the posible walking evade points.
function skillshot:getWalkingEvadePoints(moveSpeed, extraDelay)
	local result = {}
	moveSpeed = moveSpeed or myHero.ms
	extraDelay = extraDelay or 0
	if not self:isEnabled() then
		return result
	end

	if not self.menu.walking then
		return result
	end

	if self.data.skillshotType == "line" then
		--SidePoints
		local sPoints = {}
		table.insert(sPoints, self.missilePos + self.normal * (self.data.radius + evadeBuffer))
		table.insert(sPoints, self.endPos + self.direction * evadeBuffer + self.normal * (self.data.radius + evadeBuffer))
		table.insert(sPoints, self.endPos + self.direction * evadeBuffer - self.normal * (self.data.radius + evadeBuffer))
		table.insert(sPoints, self.missilePos - self.normal * (self.data.radius + evadeBuffer))
		table.insert(sPoints, self.missilePos + self.normal * (self.data.radius + evadeBuffer))

		for i=1, #sPoints - 1 do
			local candidate,  pointLine,   isOnSegment = VectorPointProjectionOnLineSegment(sPoints[i], sPoints[i + 1], myHero.visionPos)
			local pointSegment2, pointLine2, isOnSegment2 = VectorPointProjectionOnLineSegment(self.missilePos, self.endPos, candidate)

			local timeToReachPoint  = GetDistance(candidate, myHero.visionPos) / moveSpeed + moveDelay() + extraDelay 

			if timeToReachPoint < (self.endT - GetTickCount())/1000 then
				--Valid candidate.
				table.insert(result, candidate)
			end
		end
	end

	if self.data.skillshotType == "linem" then
		--SidePoints
		local sPoints = {}
		table.insert(sPoints, self.missilePos + self.normal * (self.data.radius + evadeBuffer))
		table.insert(sPoints, self.endPos + self.direction * evadeBuffer + self.normal * (self.data.radius + evadeBuffer))
		table.insert(sPoints, self.endPos + self.direction * evadeBuffer - self.normal * (self.data.radius + evadeBuffer))
		table.insert(sPoints, self.missilePos - self.normal * (self.data.radius + evadeBuffer))

		for i=1, #sPoints - 1 do
			local candidate,  pointLine,   isOnSegment = VectorPointProjectionOnLineSegment(sPoints[i], sPoints[i + 1], myHero.visionPos)
			local pointSegment2, pointLine2, isOnSegment2 = VectorPointProjectionOnLineSegment(self.missilePos, self.endPos, candidate)

			local timeToReachPoint  = GetDistance(candidate, myHero.visionPos) / moveSpeed + moveDelay() + extraDelay
			local timeToMReachPoint = GetDistance(pointSegment2, self.missilePos) / self.data.missileSpeed

			if timeToReachPoint < timeToMReachPoint then
				--Valid candidate.
				table.insert(result, candidate)
			end
		end
	end

	if self.data.skillshotType == "circular" then
		table.insert(result, self.endPos + (self.data.radius / math.cos(2 * math.pi / circularPolygonSides) + 10) * (Point(myHero.visionPos.x, myHero.visionPos.z) - self.endPos):normalized())
		
		for i = 1, # result do
			if GetDistance(result[i], myHero.visionPos) / moveSpeed + moveDelay() + extraDelay > (self.endT - GetTickCount())/1000 then
				table.remove(result, i)
			end
		end
	end

	--Delete the wall points
	for i = #result, 1, -1 do
		if IsWall(D3DXVECTOR3(result[i].x, myHero.y, result[i].y)) then
			table.remove(result, i)
		end
	end

	return result
end

--Returns the dashing evade points


--Returns the first intersection between a path and the skillshot itself.
function skillshot:getFirstIntersection(path)
	local polygonLineSegments = {}
	local totalDistance = 0
	if not self:isEnabled() then
		return nil
	end

	--Add the line segments from the polygon to the list, this is like this because in the future when adding gpc or other polygon clipping library this will be needed.
	if self.data.skillshotType == "line" or self.data.skillshotType == "linem" then

		--Left side first.
		table.insert(polygonLineSegments, {})
		polygonLineSegments[#polygonLineSegments][1]     = self.missilePos + self.data.radius * self.normal
		polygonLineSegments[#polygonLineSegments][2]     = self.endPos     + self.data.radius * self.normal

		--Right side.
		table.insert(polygonLineSegments, {})
		polygonLineSegments[#polygonLineSegments][1]     = self.missilePos - self.data.radius * self.normal
		polygonLineSegments[#polygonLineSegments][2]     = self.endPos     - self.data.radius * self.normal
		
		--Top side.
		table.insert(polygonLineSegments, {})
		polygonLineSegments[#polygonLineSegments][1]     = self.endPos + self.data.radius * self.normal
		polygonLineSegments[#polygonLineSegments][2]     = self.endPos - self.data.radius * self.normal
		
		--Bottom side.
		table.insert(polygonLineSegments, {})
		polygonLineSegments[#polygonLineSegments][1]     = self.missilePos + self.data.radius * self.normal
		polygonLineSegments[#polygonLineSegments][2]     = self.missilePos - self.data.radius * self.normal
	end

	if self.data.skillshotType == "circular" then
		local CRadius = self.data.radius / math.cos(2 * math.pi / circularPolygonSides)

		local lastPoint
		for i = 1, circularPolygonSides do
			local currentPoint = Point(self.endPos.x + CRadius * math.sin(2 * math.pi / circularPolygonSides * i) , self.endPos.y + CRadius * math.cos(2 * math.pi / circularPolygonSides * i))
			if lastPoint then
				table.insert(polygonLineSegments, {})
				polygonLineSegments[#polygonLineSegments][1]     = currentPoint
				polygonLineSegments[#polygonLineSegments][2]     = lastPoint
			end
			lastPoint = currentPoint
		end

	end

	for i = 1, #path - 1 do
		local lsStart = path[i]      --The line segment start point.
		local lsEnd   = path[i + 1]  --The line segment end point.
			
		local foundIntersections = {}
		for j = 1, #polygonLineSegments do
			local intersection = LineSegmentIntersection(lsStart, lsEnd, polygonLineSegments[j][1], polygonLineSegments[j][2])
			if intersection then
				table.insert(foundIntersections, intersection)
			end
		end

		--Return the closest point from the found intersections
		if #foundIntersections > 0 then
			local cPoint = getClosestPoint(foundIntersections, lsStart)
			return cPoint, lsStart, totalDistance + GetDistance(lsStart, cPoint)
		end

		totalDistance = totalDistance + GetDistance(lsStart, lsEnd)
	end
end

function skillshot:OnDraw()
	if not menu.draw then return end
	if not self.menu.draw then return end
	local color = self:isEnabled() and TARGB(menu.drawings.lineColor) or TARGB(menu.drawings.lineColor2)

	if self.data.skillshotType == "line" or self.data.skillshotType == "linem" then
		DrawLineBorder3D(self.endPos.x, myHero.y, self.endPos.y, self.missilePos.x, myHero.y, self.missilePos.y, (self.data.radius - (not menu.drawings.addHitBox and self.data.addHitBox and getHitBox(myHero) or 0)) * 2, color, menu.drawings.border) 
	end

	if self.data.skillshotType == "circular" then
		DrawCircle2(self.endPos.x, myHero.y, self.endPos.y, self.data.radius - (not menu.drawings.addHitBox and self.data.addHitBox and getHitBox(myHero) or 0), color)
	end

	if drawThis then
		DrawCircle2(drawThis.x, myHero.y, drawThis.y, 100, color)
	end
end

--Helper functions

function getChampionsTable()
	local result = {}
	if testOnAllies then
		table.insert(result, myHero)
		for i, hero in ipairs(GetAllyHeroes()) do
			table.insert(result, hero)
		end
	end

	for i, hero in ipairs(GetEnemyHeroes()) do
		table.insert(result, hero)
	end

	return result
end

function TARGB(colorTable)
    return ARGB(colorTable[1], colorTable[2], colorTable[3], colorTable[4])
end

--Returns the current waypoints from a unit
function getWayPoints(object)
	local result = {}
	table.insert(result, Point(object.visionPos.x, object.visionPos.z))
	if object.hasMovePath then
		for i = object.pathIndex, object.pathCount do
			path = object:GetPath(i)
			table.insert(result, Point(path.x, path.z))
		end
	end
	return result
end

--Returns the closest point from a table of points.
function getClosestPoint(tableOfPoints, from)
	local closest
	local distanceToClosest = math.huge
	
	for k = 1, #tableOfPoints do
		local testDist = GetDistanceSqr(tableOfPoints[k], from)
		if testDist <= distanceToClosest then
			distanceToClosest = testDist
			closest = tableOfPoints[k]
		end
	end

	return closest
end

--function that returns unit's hitbox size
function getHitBox(unit)
	if not hitboxess then
		hitboxes = {['RecItemsCLASSIC'] = 65, ['TeemoMushroom'] = 50.0, ['TestCubeRender'] = 65, ['Xerath'] = 65, ['Kassadin'] = 65, ['Rengar'] = 65, ['Thresh'] = 55.0, ['RecItemsTUTORIAL'] = 65, ['Ziggs'] = 55.0, ['ZyraPassive'] = 20.0, ['ZyraThornPlant'] = 20.0, ['KogMaw'] = 65, ['HeimerTBlue'] = 35.0, ['EliseSpider'] = 65, ['Skarner'] = 80.0, ['ChaosNexus'] = 65, ['Katarina'] = 65, ['Riven'] = 65, ['SightWard'] = 1, ['HeimerTYellow'] = 35.0, ['Ashe'] = 65, ['VisionWard'] = 1, ['TT_NGolem2'] = 80.0, ['ThreshLantern'] = 65, ['RecItemsCLASSICMap10'] = 65, ['RecItemsODIN'] = 65, ['TT_Spiderboss'] = 200.0, ['RecItemsARAM'] = 65, ['OrderNexus'] = 65, ['Soraka'] = 65, ['Jinx'] = 65, ['TestCubeRenderwCollision'] = 65, ['Red_Minion_Wizard'] = 48.0, ['JarvanIV'] = 65, ['Blue_Minion_Wizard'] = 48.0, ['TT_ChaosTurret2'] = 88.4, ['TT_ChaosTurret3'] = 88.4, ['TT_ChaosTurret1'] = 88.4, ['ChaosTurretGiant'] = 88.4, ['Dragon'] = 100.0, ['LuluSnowman'] = 50.0, ['Worm'] = 100.0, ['ChaosTurretWorm'] = 88.4, ['TT_ChaosInhibitor'] = 65, ['ChaosTurretNormal'] = 88.4, ['AncientGolem'] = 100.0, ['ZyraGraspingPlant'] = 20.0, ['HA_AP_OrderTurret3'] = 88.4, ['HA_AP_OrderTurret2'] = 88.4, ['Tryndamere'] = 65, ['OrderTurretNormal2'] = 88.4, ['Singed'] = 65, ['OrderInhibitor'] = 65, ['Diana'] = 65, ['HA_FB_HealthRelic'] = 65, ['TT_OrderInhibitor'] = 65, ['GreatWraith'] = 80.0, ['Yasuo'] = 65, ['OrderTurretDragon'] = 88.4, ['OrderTurretNormal'] = 88.4, ['LizardElder'] = 65.0, ['HA_AP_ChaosTurret'] = 88.4, ['Ahri'] = 65, ['Lulu'] = 65, ['ChaosInhibitor'] = 65, ['HA_AP_ChaosTurret3'] = 88.4, ['HA_AP_ChaosTurret2'] = 88.4, ['ChaosTurretWorm2'] = 88.4, ['TT_OrderTurret1'] = 88.4, ['TT_OrderTurret2'] = 88.4, ['TT_OrderTurret3'] = 88.4, ['LuluFaerie'] = 65, ['HA_AP_OrderTurret'] = 88.4, ['OrderTurretAngel'] = 88.4, ['YellowTrinketUpgrade'] = 1, ['MasterYi'] = 65, ['Lissandra'] = 65, ['ARAMOrderTurretNexus'] = 88.4, ['Draven'] = 65, ['FiddleSticks'] = 65, ['SmallGolem'] = 80.0, ['ARAMOrderTurretFront'] = 88.4, ['ChaosTurretTutorial'] = 88.4, ['NasusUlt'] = 80.0, ['Maokai'] = 80.0, ['Wraith'] = 50.0, ['Wolf'] = 50.0, ['Sivir'] = 65, ['Corki'] = 65, ['Janna'] = 65, ['Nasus'] = 80.0, ['Golem'] = 80.0, ['ARAMChaosTurretFront'] = 88.4, ['ARAMOrderTurretInhib'] = 88.4, ['LeeSin'] = 65, ['HA_AP_ChaosTurretTutorial'] = 88.4, ['GiantWolf'] = 65.0, ['HA_AP_OrderTurretTutorial'] = 88.4, ['YoungLizard'] = 50.0, ['Jax'] = 65, ['LesserWraith'] = 50.0, ['Blitzcrank'] = 80.0, ['brush_D_SR'] = 65, ['brush_E_SR'] = 65, ['brush_F_SR'] = 65, ['brush_C_SR'] = 65, ['brush_A_SR'] = 65, ['brush_B_SR'] = 65, ['ARAMChaosTurretInhib'] = 88.4, ['Shen'] = 65, ['Nocturne'] = 65, ['Sona'] = 65, ['ARAMChaosTurretNexus'] = 88.4, ['YellowTrinket'] = 1, ['OrderTurretTutorial'] = 88.4, ['Caitlyn'] = 65, ['Trundle'] = 65, ['Malphite'] = 80.0, ['Mordekaiser'] = 80.0, ['ZyraSeed'] = 65, ['Vi'] = 50, ['Tutorial_Red_Minion_Wizard'] = 48.0, ['Renekton'] = 80.0, ['Anivia'] = 65, ['Fizz'] = 65, ['Heimerdinger'] = 55.0, ['Evelynn'] = 65, ['Rumble'] = 80.0, ['Leblanc'] = 65, ['Darius'] = 80.0, ['OlafAxe'] = 50.0, ['Viktor'] = 65, ['XinZhao'] = 65, ['Orianna'] = 65, ['Vladimir'] = 65, ['Nidalee'] = 65, ['Tutorial_Red_Minion_Basic'] = 48.0, ['ZedShadow'] = 65, ['Syndra'] = 65, ['Zac'] = 80.0, ['Olaf'] = 65, ['Veigar'] = 55.0, ['Twitch'] = 65, ['Alistar'] = 80.0, ['Akali'] = 65, ['Urgot'] = 80.0, ['Leona'] = 65, ['Talon'] = 65, ['Karma'] = 65, ['Jayce'] = 65, ['Galio'] = 80.0, ['Shaco'] = 65, ['Taric'] = 65, ['TwistedFate'] = 65, ['Varus'] = 65, ['Garen'] = 65, ['Swain'] = 65, ['Vayne'] = 65, ['Fiora'] = 65, ['Quinn'] = 65, ['Kayle'] = 65, ['Blue_Minion_Basic'] = 48.0, ['Brand'] = 65, ['Teemo'] = 55.0, ['Amumu'] = 55.0, ['Annie'] = 55.0, ['Odin_Blue_Minion_caster'] = 48.0, ['Elise'] = 65, ['Nami'] = 65, ['Poppy'] = 55.0, ['AniviaEgg'] = 65, ['Tristana'] = 55.0, ['Graves'] = 65, ['Morgana'] = 65, ['Gragas'] = 80.0, ['MissFortune'] = 65, ['Warwick'] = 65, ['Cassiopeia'] = 65, ['Tutorial_Blue_Minion_Wizard'] = 48.0, ['DrMundo'] = 80.0, ['Volibear'] = 80.0, ['Irelia'] = 65, ['Odin_Red_Minion_Caster'] = 48.0, ['Lucian'] = 65, ['Yorick'] = 80.0, ['RammusPB'] = 65, ['Red_Minion_Basic'] = 48.0, ['Udyr'] = 65, ['MonkeyKing'] = 65, ['Tutorial_Blue_Minion_Basic'] = 48.0, ['Kennen'] = 55.0, ['Nunu'] = 65, ['Ryze'] = 65, ['Zed'] = 65, ['Nautilus'] = 80.0, ['Gangplank'] = 65, ['shopevo'] = 65, ['Lux'] = 65, ['Sejuani'] = 80.0, ['Ezreal'] = 65, ['OdinNeutralGuardian'] = 65, ['Khazix'] = 65, ['Sion'] = 80.0, ['Aatrox'] = 65, ['Hecarim'] = 80.0, ['Pantheon'] = 65, ['Shyvana'] = 50.0, ['Zyra'] = 65, ['Karthus'] = 65, ['Rammus'] = 65, ['Zilean'] = 65, ['Chogath'] = 80.0, ['Malzahar'] = 65, ['YorickRavenousGhoul'] = 1.0, ['YorickSpectralGhoul'] = 1.0, ['JinxMine'] = 65, ['YorickDecayedGhoul'] = 1.0, ['XerathArcaneBarrageLauncher'] = 65, ['Odin_SOG_Order_Crystal'] = 65, ['TestCube'] = 65, ['ShyvanaDragon'] = 80.0, ['FizzBait'] = 65, ['ShopKeeper'] = 65, ['Blue_Minion_MechMelee'] = 65.0, ['OdinQuestBuff'] = 65, ['TT_Buffplat_L'] = 65, ['TT_Buffplat_R'] = 65, ['KogMawDead'] = 65, ['TempMovableChar'] = 48.0, ['Lizard'] = 50.0, ['GolemOdin'] = 80.0, ['OdinOpeningBarrier'] = 65, ['TT_ChaosTurret4'] = 88.4, ['TT_Flytrap_A'] = 65, ['TT_Chains_Order_Periph'] = 65, ['TT_NWolf'] = 65.0, ['ShopMale'] = 65, ['OdinShieldRelic'] = 65, ['TT_Chains_Xaos_Base'] = 65, ['LuluSquill'] = 50.0, ['TT_Shopkeeper'] = 65, ['redDragon'] = 100.0, ['MonkeyKingClone'] = 65, ['Odin_skeleton'] = 65, ['OdinChaosTurretShrine'] = 88.4, ['Cassiopeia_Death'] = 65, ['OdinCenterRelic'] = 48.0, ['Ezreal_cyber_1'] = 65, ['Ezreal_cyber_3'] = 65, ['Ezreal_cyber_2'] = 65, ['OdinRedSuperminion'] = 55.0, ['TT_Speedshrine_Gears'] = 65, ['JarvanIVWall'] = 65, ['DestroyedNexus'] = 65, ['ARAMOrderNexus'] = 65, ['Red_Minion_MechCannon'] = 65.0, ['OdinBlueSuperminion'] = 55.0, ['SyndraOrbs'] = 65, ['LuluKitty'] = 50.0, ['SwainNoBird'] = 65, ['LuluLadybug'] = 50.0, ['CaitlynTrap'] = 65, ['TT_Shroom_A'] = 65, ['ARAMChaosTurretShrine'] = 88.4, ['Odin_Windmill_Propellers'] = 65, ['DestroyedInhibitor'] = 65, ['TT_NWolf2'] = 50.0, ['OdinMinionGraveyardPortal'] = 1.0, ['SwainBeam'] = 65, ['Summoner_Rider_Order'] = 65.0, ['TT_Relic'] = 65, ['odin_lifts_crystal'] = 65, ['OdinOrderTurretShrine'] = 88.4, ['SpellBook1'] = 65, ['Blue_Minion_MechCannon'] = 65.0, ['TT_ChaosInhibitor_D'] = 65, ['Odin_SoG_Chaos'] = 65, ['TrundleWall'] = 65, ['HA_AP_HealthRelic'] = 65, ['OrderTurretShrine'] = 88.4, ['OriannaBall'] = 48.0, ['ChaosTurretShrine'] = 88.4, ['LuluCupcake'] = 50.0, ['HA_AP_ChaosTurretShrine'] = 88.4, ['TT_Chains_Bot_Lane'] = 65, ['TT_NWraith2'] = 50.0, ['TT_Tree_A'] = 65, ['SummonerBeacon'] = 65, ['Odin_Drill'] = 65, ['TT_NGolem'] = 80.0, ['Shop'] = 65, ['AramSpeedShrine'] = 65, ['DestroyedTower'] = 65, ['OriannaNoBall'] = 65, ['Odin_Minecart'] = 65, ['Summoner_Rider_Chaos'] = 65.0, ['OdinSpeedShrine'] = 65, ['TT_Brazier'] = 65, ['TT_SpeedShrine'] = 65, ['odin_lifts_buckets'] = 65, ['OdinRockSaw'] = 65, ['OdinMinionSpawnPortal'] = 1.0, ['SyndraSphere'] = 48.0, ['TT_Nexus_Gears'] = 65, ['Red_Minion_MechMelee'] = 65.0, ['SwainRaven'] = 65, ['crystal_platform'] = 65, ['MaokaiSproutling'] = 48.0, ['Urf'] = 65, ['TestCubeRender10Vision'] = 65, ['MalzaharVoidling'] = 10.0, ['GhostWard'] = 1, ['MonkeyKingFlying'] = 65, ['LuluPig'] = 50.0, ['AniviaIceBlock'] = 65, ['TT_OrderInhibitor_D'] = 65, ['yonkey'] = 65, ['Odin_SoG_Order'] = 65, ['RammusDBC'] = 65, ['FizzShark'] = 65, ['LuluDragon'] = 50.0, ['OdinTestCubeRender'] = 65, ['OdinCrane'] = 65, ['TT_Tree1'] = 65, ['ARAMOrderTurretShrine'] = 88.4, ['TT_Chains_Order_Base'] = 65, ['Odin_Windmill_Gears'] = 65, ['ARAMChaosNexus'] = 65, ['TT_NWraith'] = 50.0, ['TT_OrderTurret4'] = 88.4, ['Odin_SOG_Chaos_Crystal'] = 65, ['TT_SpiderLayer_Web'] = 65, ['OdinQuestIndicator'] = 1.0, ['JarvanIVStandard'] = 65, ['TT_DummyPusher'] = 65, ['OdinClaw'] = 65, ['EliseSpiderling'] = 1.0, ['QuinnValor'] = 65, ['UdyrTigerUlt'] = 65, ['UdyrTurtleUlt'] = 65, ['UdyrUlt'] = 65, ['UdyrPhoenixUlt'] = 65, ['ShacoBox'] = 10, ['HA_AP_Poro'] = 65, ['AnnieTibbers'] = 80.0, ['UdyrPhoenix'] = 65, ['UdyrTurtle'] = 65, ['UdyrTiger'] = 65, ['HA_AP_OrderShrineTurret'] = 88.4, ['HA_AP_OrderTurretRubble'] = 65, ['HA_AP_Chains_Long'] = 65, ['HA_AP_OrderCloth'] = 65, ['HA_AP_PeriphBridge'] = 65, ['HA_AP_BridgeLaneStatue'] = 65, ['HA_AP_ChaosTurretRubble'] = 88.4, ['HA_AP_BannerMidBridge'] = 65, ['HA_AP_PoroSpawner'] = 50.0, ['HA_AP_Cutaway'] = 65, ['HA_AP_Chains'] = 65, ['HA_AP_ShpSouth'] = 65, ['HA_AP_HeroTower'] = 65, ['HA_AP_ShpNorth'] = 65, ['ChaosInhibitor_D'] = 65, ['ZacRebirthBloblet'] = 65, ['OrderInhibitor_D'] = 65, ['Nidalee_Spear'] = 65, ['Nidalee_Cougar'] = 65, ['TT_Buffplat_Chain'] = 65, ['WriggleLantern'] = 1, ['TwistedLizardElder'] = 65.0, ['RabidWolf'] = 65.0, ['HeimerTGreen'] = 50.0, ['HeimerTRed'] = 50.0, ['ViktorFF'] = 65, ['TwistedGolem'] = 80.0, ['TwistedSmallWolf'] = 50.0, ['TwistedGiantWolf'] = 65.0, ['TwistedTinyWraith'] = 50.0, ['TwistedBlueWraith'] = 50.0, ['TwistedYoungLizard'] = 50.0, ['Red_Minion_Melee'] = 48.0, ['Blue_Minion_Melee'] = 48.0, ['Blue_Minion_Healer'] = 48.0, ['Ghast'] = 60.0, ['blueDragon'] = 100.0, ['Red_Minion_MechRange'] = 65.0, ['Test_CubeSphere'] = 65,}
	end
	return (hitboxes[unit.charName] ~= nil and hitboxes[unit.charName] ~= 0) and hitboxes[unit.charName]  or 65
end

function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
	radius = radius or 300
	quality = math.max(8,math.floor(180/math.deg((math.asin((chordlength/(2*radius)))))))
	quality = 2 * math.pi / quality
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
	if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y })  then
		DrawCircleNextLvl(x, y, z, radius, 2, color, 75)
	end
end

function Point:normalized()
	local d = math.sqrt(math.pow(self.x,2) + math.pow(self.y,2))
	return Point(self.x / d, self.y / d)
end

function Point:__mul(p)
	if type(p) == "number" then
		return Point(self.x * p, self.y * p)
	else
		if type(self) == "number" then
			return Point(self * p.x, self * p.y)
		else
			return Point(self.x * p.x, self.y * p.y)
		end
    end
end