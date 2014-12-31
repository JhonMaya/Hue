<?php exit() ?>--by Tux 70.160.53.227
--###############################################################Credits to Honda7 for the Lib Downloader###################################################################--
local REQUIRED_LIBS = {
		["VPrediction"] = "https://github.com/honda7/BoL/blob/master/Common/VPrediction.lua",
		["Prodicion"] = "https://bitbucket.org/Klokje/public-klokjes-bol-scripts/raw/master/Common/Prodiction.lua",
		["Collision"] = "https://bitbucket.org/Klokje/public-klokjes-bol-scripts/raw/b891699e739f77f77fd428e74dec00b2a692fdef/Common/Collision.lua",
	}

local DOWNLOADING_LIBS, DOWNLOAD_COUNT = false, 0
local SELF_NAME = GetCurrentEnv() and GetCurrentEnv().FILE_NAME or ""

function AfterDownload()
	DOWNLOAD_COUNT = DOWNLOAD_COUNT - 1
	if DOWNLOAD_COUNT == 0 then
		DOWNLOADING_LIBS = false
		print("<b>[Thresh Prince]: Required libraries downloaded successfully, please reload (double F9).</b>")
	end
end

for DOWNLOAD_LIB_NAME, DOWNLOAD_LIB_URL in pairs(REQUIRED_LIBS) do
	if FileExist(LIB_PATH .. DOWNLOAD_LIB_NAME .. ".lua") then
		require(DOWNLOAD_LIB_NAME)
	else
		DOWNLOADING_LIBS = true
		DOWNLOAD_COUNT = DOWNLOAD_COUNT + 1
		DownloadFile(DOWNLOAD_LIB_URL, LIB_PATH .. DOWNLOAD_LIB_NAME..".lua", AfterDownload)
	end
end

if DOWNLOADING_LIBS then print("Downloading required libraries, please wait...") return end
--##########################################################################################################################################################################--

-----------------------------------End of Header File----------------------------------------------------------
----------------------------------- START UPDATE --------------------------------------------------------------
local versionTHRESH = 0.001 -- current version
local SCRIPT_NAME_THRESH = "Thresh Prince"
---------------------------------------------------------------------------------------------------------------
local needUpdate_THRESH = false
local needRun_THRESH = true
local URL_THRESH = "http://dekland.com/bol/"..SCRIPT_NAME_THRESH..".lua"
local PATH_THRESH = BOL_PATH.."Scripts\\"..SCRIPT_NAME_THRESH..".lua"
function CheckVersionTHRESH(data)
	local onlineVerTHRESH = tonumber(data)
	if type(onlineVerTHRESH) ~= "number" then return end
	if onlineVerTHRESH and onlineVerTHRESH > versionTHRESH then
		print("<font color='#e0f900'>"..SCRIPT_NAME_THRESH..": </font>".."<font color='#e0f900'> There is a new version to get. Auto Update. Don't F9 till done...</font>") 
		needUpdate_THRESH = true
	else
		print("<font color='#00FF00'>"..SCRIPT_NAME_THRESH.." : "..versionTHRESH.." </font><font color='#00FF00'> loaded. You have the most recent version loaded.</font>")  
	end
end
function UpdateScriptTHRESH()
	if needRun_THRESH then
		needRun_THRESH = false
		if _G.ThreshPrinceUpdater == nil or _G.ThreshPrinceUpdater == true then 
			GetAsyncWebResult("dekland.com", "bol/"..SCRIPT_NAME_THRESH.."Ver.lua", CheckVersionTHRESH) 
		else
			print("<font color='#ff0000'> Thresh Prince: Auto-updating off. You are using version : "..versionTHRESH.."</font>")
		end
	end

	if needUpdate_THRESH then
		needUpdate_THRESH = false
		DownloadFile(URL_THRESH, PATH_THRESH, function()
                if FileExist(PATH_THRESH) then
                    print("<font color='#00FF00'>"..SCRIPT_NAME_THRESH..": </font>".."<font color='#00FF00'> Updated, Reload script for new version.</font>")
                end
		end)
	end
end
AddTickCallback(UpdateScriptTHRESH)
---------------------------------- END UPDATE -----------------------------------------------------------------]]
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
	["dekaron2"] = true, ["sniperbro"] = true, ["tux"] = true, ["vex"] = true, ["ires"] = true, ["ryosu"] = true, ["skinalt"] = true, ["adamhacks"] = true, ["qqq"] = true, ["149kg"] = true, ["sida"] = true, ["weeeqt"] = true, ["olorin"] = true, ["klokje"] = true,
	["spudgy"] = true, ["ottohr"] = true, ["xlain"] = true, ["dansa"] = true, ["thezone"] = true, ["deklanddev"] = true, ["ahmedzarga23"] = true,
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
local isChanneling = 
{
    CaitlynAceintheHole = {charName = "Caitlyn", delay = 0.5},
    Crowstorm =	{charName = "FiddleSticks", delay = 0.5},
    Drain = {charName = "FiddleSticks", delay = 0.5},
	GalioIdolOfDurand = {charName = "Galio", delay = 0.5},
	ReapTheWhirlwind = {charName = "Janna", delay = 0.5},
	KatarinaR = {charName = "Katarina", delay = 0.5},
	LucianR = {charName = "Lucian", delay = 0.5},
	AlZaharNetherGrasp = {charName = "Malzahar", delay = 0.5},
	MissFortuneBulletTime = {charName = "MissFortune", delay = 0.5},
	SoulShackles = {charName = "Morgana", delay = 0.5},
	AbsoluteZero = {charName = "Nunu", delay = 0.5},
	UrgotSwap2 = {charName = "Urgot", delay = 0.5},
	VelkozR = {charName = "Velkoz", delay = 0.5},
	InfiniteDuress = {charName = "Warwick", delay = 0.5},
	XerathLocusOfPower2 = {charName = "Xerath", delay = 0.5},
}
local creators = {
	
}
function OnLoad()
    checkCreator()
    if not creatorCheck then
        LoadConfigHeader()
        LoadVariables()
        LoadMenus()
    	PrintFloatText(myHero,12,"Thresh Prince Loaded!")
    else
		print("<font color='#FF0000'> Script is disabled, Team Freelo is present </font>")
	end
end
function OnUnload()
	PrintFloatText(myHero,12,"Thresh Prince UnLoaded!")
end
function checkCreator()
	creatorCheck = false
	count = 0
	for i = 1, heroManager.iCount do
		local hero = heroManager:GetHero(i)
		if hero.team ~= player.team and creators[hero.name:lower()] ~= nil and creators[hero.name:lower()] then
		    count = count + 1
		end
    end
    if count>0 then
        creatorCheck = true
    else
        creatorCheck = false
    end
end
function LoadConfigHeader()
	Config = scriptConfig("ThreshPrince", "Thresh Prince")
	Config:addParam("ThreshPrinceInfo", "Thresh Prince Version: ", SCRIPT_PARAM_INFO, versionTHRESH)
end
function LoadMenus()
	LoadPredictionMenu()
	LoadSkillsMenu()
	LoadFarmMenu()
	LoadUltMenu()
	LoadOrbWalkingMenu()
	LoadLanternMenu()
	LoadOffensiveItemsMenu()
	LoadDrawMenu()
	if ignite ~= nil then
       LoadIgniteMenu()
	end
end
function LoadPredictionMenu()
	Config:addSubMenu("Prediction Choice", "predictionChoice")
	Config.predictionChoice:addParam("predictionChoice", "Prediction ", SCRIPT_PARAM_LIST, 1, {"Vprediction", "Prodiction"})
end
function LoadSkillsMenu()
	Config:addSubMenu("Combat Keys", "combatKeys")
    Config.combatKeys:addParam("teamFight", "Team Fight Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
    Config.combatKeys:addParam("Engage", "Engage them", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
	Config.combatKeys:addParam("Push", "Push with Flay", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("E"))
	Config.combatKeys:addParam("Harass", "Harass with Hook", SCRIPT_PARAM_ONKEYDOWN, false, 84)
	Config.combatKeys:addParam("jungleEscape", "Escape using Jungle", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("J"))

	Config:addSubMenu("Spell Settings", "spellSettings")
	Config.spellSettings:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.spellSettings:addParam("qRangeSlider", "Q Range", SCRIPT_PARAM_SLICE, 955, 1, 1055, 0)
	Config.spellSettings:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)  -- need to check for allies at 1500 range even though max range is 950 so it casts towards allies
	Config.spellSettings:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Flay Settings", "flaySettings")
	Config.flaySettings:addParam("autoFlay", "Auto Flay Gap Closer", SCRIPT_PARAM_ONOFF, false)
end
function LoadFarmMenu()
	Config:addSubMenu("Farm Settings", "Farm")

	Config.Farm:addSubMenu("Lane Farm", "laneFarm")
	Config.Farm.laneFarm:addParam("farm", "Farm Press", SCRIPT_PARAM_ONKEYDOWN, false, 85)
	Config.Farm.laneFarm:addParam("farmToggle", "Farm Toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, 90)
	Config.Farm.laneFarm:addParam("laneClear", "Lane Clear Press", SCRIPT_PARAM_ONKEYDOWN, false, 73)
	Config.Farm.laneFarm:addParam("laneClearToggle", "Lane Clear Toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, 79)
	Config.Farm.laneFarm:addParam("farmOrb", "Orbwalk inFarm", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.laneFarm:addParam("farmAA", "AA Regular Farm", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.laneFarm:addParam("farmQ", "Q Regular Farm", SCRIPT_PARAM_ONOFF, true)
	Config.Farm.laneFarm:addParam("farmW", "W Regular Farm", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.laneFarm:addParam("farmE", "E Regular Farm", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.laneFarm:addParam("break", "------------------", SCRIPT_PARAM_INFO, "")
	Config.Farm.laneFarm:addParam("farmQclear", "Q Clear Farm", SCRIPT_PARAM_ONOFF, true)
	Config.Farm.laneFarm:addParam("farmWclear", "W Clear Farm", SCRIPT_PARAM_ONOFF, true)
	Config.Farm.laneFarm:addParam("farmEclear", "E Clear Farm", SCRIPT_PARAM_ONOFF, true)
	
	Config.Farm:addSubMenu("Jungle Farm", "jungleFarm")
	Config.Farm.jungleFarm:addParam("jungleFarming", "Jungle Farm", SCRIPT_PARAM_ONKEYDOWN, false, 74)
	Config.Farm.jungleFarm:addParam("jungleOrb", "Orbwalk in Jungle Farm", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.jungleFarm:addParam("jungleFarmAA", "AA Jungle Farm", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.jungleFarm:addParam("jungleFarmQ", "Q Jungle Farm", SCRIPT_PARAM_ONOFF, true)
	Config.Farm.jungleFarm:addParam("jungleFarmW", "W Jungle Farm", SCRIPT_PARAM_ONOFF, true)
	Config.Farm.jungleFarm:addParam("jungleFarmE", "E Jungle Farm", SCRIPT_PARAM_ONOFF, true)
end
function LoadUltMenu()
	Config:addSubMenu("Ult Settings", "ultSettings")
	Config.ultSettings:addParam("Box", "Auto-Ult", SCRIPT_PARAM_ONOFF, false)
	Config.ultSettings:addParam("BoxCount", "Enemies in Range", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
end
function LoadOrbWalkingMenu()
	Config:addSubMenu("Orbwalking", "orbWalk")
	Config.orbWalk:addParam("moveToMouseTeamFight", "Move to mouse in Team Fight", SCRIPT_PARAM_ONOFF, true)
	Config.orbWalk:addParam("aaTeamFight", "AA in Teamfight", SCRIPT_PARAM_ONOFF, true)
	Config.orbWalk:addParam("moveToMouseHarass", "Move to mouse in Harass", SCRIPT_PARAM_ONOFF, true)
	Config.orbWalk:addParam("aaHarass", "AA in Harass", SCRIPT_PARAM_ONOFF, true)
end
function LoadLanternMenu() ---- i see this menu becoming a lot bigger
	Config:addSubMenu("Lantern Settings", "lanternSettings")
	Config.lanternSettings:addParam("Lantern", "Auto Lantern Most Armour in Combo", SCRIPT_PARAM_ONOFF, false)
	-- need to add a priority system here. but should be based on health as well. (possibly multiply current health by # of priority so that lower priority people appear to have more health. need to take into account mr/armor/etc)
end
function LoadOffensiveItemsMenu()
	Config:addSubMenu("Offensive Items Settings", "Items")

	Config.Items:addSubMenu("AD Items", "ADItems")
	Config.Items.ADItems:addParam("useADItems", "Use AD Items", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useBOTRK", "Use Blade of the Ruined King", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useEntropy", "Use Entropy", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useRavenousHydra", "Use Ravenous Hydra", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useSwordOfTheDevine", "Use Sword of the Divine", SCRIPT_PARAM_ONOFF, true)	
	Config.Items.ADItems:addParam("useTiamat", "Use Tiamat", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useYoumuusGhostblade", "Use Youmuu's Ghostblade", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useMuramana", "Use Muramana", SCRIPT_PARAM_ONOFF, true) -- enable in activator
	Config.Items.ADItems:addParam("minManaMura", "Min Mana for Muramana", SCRIPT_PARAM_SLICE, 20, 1, 99, 0) -- enable in activator
    Config.Items.ADItems:addParam("ADItemMode", "AD Item Mode: ", SCRIPT_PARAM_LIST, 1, {"Burst Mode", "Combo Mode", "KS Mode"})
end
function LoadIgniteMenu()
	Config:addSubMenu("Summoner Spells", "SummonerSpells")
    Config.SummonerSpells:addSubMenu("Ignite Settings", "Ignite")
    Config.SummonerSpells.Ignite:addParam("useIgnite", "Use Ignite", SCRIPT_PARAM_ONOFF, true)
    Config.SummonerSpells.Ignite:addParam("IgniteMode", "Ignite Mode : ", SCRIPT_PARAM_LIST, 2, {"ComboMode", "KSMode"})

    Config.ShowGame:addSubMenu("Show Ignite", "ShowIgnite")
    Config.ShowGame.ShowIgnite:addParam("useIgnite", "Show Use Ignite", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.ShowIgnite:addParam("IgniteMode", "Show Ignite Mode", SCRIPT_PARAM_ONOFF, true)
end
function LoadDrawMenu()
	Config:addSubMenu("Draw Settings", "drawSettings")
	Config.drawSettings:addParam("drawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, false)
	Config.drawSettings:addParam("drawW", "Draw W Range", SCRIPT_PARAM_ONOFF, false)
	Config.drawSettings:addParam("drawE", "Draw E Range", SCRIPT_PARAM_ONOFF, false)
	Config.drawSettings:addParam("drawR", "Draw R Range", SCRIPT_PARAM_ONOFF, false)
	Config.drawSettings:addParam("drawSoul", "Draw Marker on Souls", SCRIPT_PARAM_ONOFF, false)
end	

function AdvancedCallBacks()
	AdvancedCallback:bind('OnGainBuff', function(unit, buff) 
		if unit and unit.team == TEAM_ENEMY and buff.name == 'threshqfakeknockup' then isHooked = true end
		if unit and unit.team == TEAM_ENEMY and buff.name == 'BlackShield' then morganaShieldedUnit = unit end
		if unit and unit.team == TEAM_ENEMY and buff.name == 'SivirE' then sivrShieldedUnit = unit end
		if unit and unit.team == TEAM_ENEMY and buff.name == 'NocturneShroudofDarkness' then nocturneShieldedUnit = unit end
		if unit and unit.isMe and (buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinRecallImproved") then recalling = true end
	end)
	AdvancedCallback:bind('OnLoseBuff', function(unit, buff) 
		if unit and unit.team == TEAM_ENEMY and buff.name == 'threshqfakeknockup' then isHooked = false end
		if unit and unit.team == TEAM_ENEMY and buff.name == 'BlackShield' then morganaShieldedUnit = nil end
		if unit and unit.team == TEAM_ENEMY and buff.name == 'SivirE' then sivrShieldedUnit = nil end
		if unit and unit.team == TEAM_ENEMY and buff.name == 'NocturneShroudofDarkness' then nocturneShieldedUnit = nil end
		if unit and unit.isMe and (buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinRecallImproved") then recalling = false end
	end)
	--[[AdvancedCallBack:bind('OnGainBuff', function(unit, buff)
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "rengarnewpassivebuff" then
			rengarLeap = true end
	end)]]
	--[[AdvancedCallBack:bind('OnLoseBuff', function(unit, buff)
		if unit and unit.valid and unit.team == TEAM_ENEMY and buff.name == "rengarnewpassivebuff" then
			rengarLeap = false end
	end)]]
end
function LoadVariables()
    autoflayList = {}
    souls = {}
    ------------------------------Skill Variables---------------------------------------------
    qRange = 1175
    qSpeed = 1200
    qDelay = 0.500
    qWidth = 60
    eRange = 570
    eSpeed = 1200
    eDelay = 0.333
    eWidth = 180
    rRadius = 450
    wRange = 950
    qCastAt = 0
    -------------------------Orb Walking ---------------------------------
    aaTime = 0
	NextShot = 0
	moveTimer = 0
    -------------------------------Flay Variables----------------------------------------------
    targetedDistanceBuffer = 75*75
    spellCastTick = 0
    minDelay = 0
    maxDelay = 2000
    particleFound = nil
    spellParticle = {valid = false}
    maxParticleDistance = 250*250
    -----------------------------------Prediction Varables------------------------------------------------
    ProPredictionActivated = false
    VPredictionActivated = false
    ------------------------------------Target Selector Variables----------------------------------------
    ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, qRange, DAMAGE_MAGIC, true)    
    ts.name = "Thresh"
    Config:addTS(ts)
    AdvancedCallBacks()

end
function LoadVpred()
	VP = VPrediction()
	VPredictionActivated = true
	Config.spellSettings:addParam("qHitChance", "Q Hit Chance Buffer", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
end
function LoadProdiction()
	wp = ProdictManager.GetInstance()
	tpQ = wp:AddProdictionObject(_Q, qRange, qSpeed, qDelay, qWidth)
	tpW = wp:AddProdictionObject(_W, wRange, math.huge, 0.5)
	
	--LoadCollisionValues()
	ProPredictionActivated = true
end
function LoadSummonerSpells()
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then 
		ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignite = SUMMONER_2
	else 
		ignite = nil
  	end
  	if (ignite ~= nil) then
  		------- Ignite Variables ------
		pIgniteKSMode = false
		pIgniteComboMode = false
  		igniteTick = 0
  		SummonerSpells = true
  	end
end
function OnTick()
	if creatorCheck then return end
	if not myHero.dead then
		ts:update()
		actualDelay = GetTickCount() - spellCastTick
		shouldCast = (spellParticle.valid and GetDistanceSqr(spellParticle) <= maxParticleDistance)
		----------------------------summoner spells-------------------------------
		if SummonerSpells ~= nil then
			if Config.SummonerSpells.Ignite.useIgnite then
				IREADY = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
			end
		end
		------------------------------Prediction Mode------------------------------------
	    if Config.predictionChoice.predictionChoice == 1 then
			if not VPredictionActivated then
				LoadVpred()
			end
		elseif Config.predictionChoice.predictionChoice == 2 then
			if not ProPredictionActivated then
				LoadProdiction()
			end
		end
		--------------------------------------AutoFlay Checks--------------------------------
		autoFlayChecks()
		if ts.target ~= nil then
			if ValidTarget(ts.target) and Config.combatKeys.Engage then Engage() end
			if ValidTarget(ts.target) and Config.combatKeys.teamFight then TeamFight() end
			if ValidTarget(ts.target) and Config.combatKeys.Harass then HookHarass() end
			if Config.combatKeys.Push then FlayPush() end
		end
		if Config.combatKeys.jungleEscape then JungleEscape() end
		if Config.flaySettings.autoFlay then
			if shouldCast and CanCast(_E) then 
				CastSpell(_E, ts.target.x, ts.target.z)
			end
	    end
	    if Config.orbWalk.moveToMouseTeamFight or Config.orbWalk.aaTeamFight then
			orbWalk()
		end
		if Config.ultSettings.Box then
			AutoBox() 
		end
		if Config.drawSettings.drawSoul then
			soulsUpdate()
		end
	end
end

function Engage()
	local spellQ = myHero:GetSpellData(_Q).name
	if Config.spellSettings.useQ and CanCast(_Q) and morganaShieldedUnit ~= ts.target and sivrShieldedUnit ~= ts.target and nocturneShieldedUnit ~= ts.target and GetDistance(ts.target) <= Config.spellSettings.qRangeSlider and spellQ == "ThreshQ" then
		if Config.predictionChoice.predictionChoice == 1 then
    		local CastPosition, HitChance, Position = VP:GetLineCastPosition(ts.target, qDelay, qWidth, qRange, qSpeed, myHero, true)
    		if HitChance >= Config.spellSettings.qHitChance then
    			CastSpell(_Q, CastPosition.x, CastPosition.z)
    		end
    	elseif 	Config.predictionChoice.predictionChoice == 2 then
	        local pred = tpQ:GetPrediction(ts.target)
	        if pred and GetDistance(pred)<=qRange then
	            CastSpell(_Q, pred.x, pred.z)    
	        end
    	end
	end
	if Config.spellSettings.useE and CanCast(_E) and GetDistance(ts.target) < eRange then
		xPos = myHero.x + (myHero.x - ts.target.x)
		zPos = myHero.z + (myHero.z - ts.target.z)
		CastSpell(_E, xPos, zPos)
	elseif Config.spellSettings.useQ and GetDistance(ts.target) > eRange and os.time() > qCastAt + 1400 and spellQ == "threshqleap" then
		CastSpell(_Q)
	end
	if Config.spellSettings.useW and isHooked == true and morganaShieldedUnit ~= ts.target and sivrShieldedUnit ~= ts.target and nocturneShieldedUnit ~= ts.target then
		LanternMostAr()
	end
end

function TeamFight()
	local spellQ = myHero:GetSpellData(_Q).name
	if Config.spellSettings.useQ and CanCast(_Q) and morganaShieldedUnit ~= ts.target and sivrShieldedUnit ~= ts.target and nocturneShieldedUnit ~= ts.target and GetDistance(ts.target) <= Config.spellSettings.qRangeSlider and spellQ == "ThreshQ" then
	    if Config.predictionChoice.predictionChoice == 1 then
    	    local CastPosition, HitChance, Position = VP:GetLineCastPosition(ts.target, qDelay, qWidth, qRange, qSpeed, myHero, true)
    		if HitChance >= Config.spellSettings.qHitChance then
    			CastSpell(_Q, CastPosition.x, CastPosition.z)
    		end
    	elseif 	Config.predictionChoice.predictionChoice == 2 then
	        local pred = tpQ:GetPrediction(ts.target)
	        if pred and GetDistance(pred)<=qRange then
	            CastSpell(_Q, pred.x, pred.z)    
	        end
    	end
	end
	if Config.spellSettings.useE and CanCast(_E) and GetDistance(ts.target) < eRange then
		xPos = myHero.x + (myHero.x - ts.target.x)
		zPos = myHero.z + (myHero.z - ts.target.z)
		CastSpell(_E, xPos, zPos)
	elseif Config.spellSettings.useQ and GetDistance(ts.target) > eRange and os.time() > qCastAt + 1400 and spellQ == "threshqleap" then
		CastSpell(_Q)
	end
	if Config.spellSettings.useW and Config.lanternSettings.Lantern then 
		LanternMostAr() 
	end
	
end

function CanCast(Spell)
	return (player:CanUseSpell(Spell) == READY)
end

function GetHighestArmorAlly()
	local mostArmor = 1
	local mostArmorChamp = nil
	for _, Ally in pairs(GetAllyHeroes()) do
		if Ally.armor > mostArmor and GetDistance(Ally) <= wRange and Ally.dead == false then
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
function GrabFurthestAlly() --add health checks to this?
    local furthestAlly = nil
    for _, Ally in pairs(GetAllyHeroes()) do
        for e, enemy in pairs(GetEnemyHeroes()) do
            if ValidTarget(enemy) and GetDistance(enemy)<=500 and GetDistance(Ally, enemy)>1000 and GetDistance(Ally)<=1500 and not Ally.dead then 
                if furthestAlly == nil then
                    furthestAlly = Ally
                elseif GetDistance(furthestAlly)<GetDistance(Ally) then
                    furthestAlly = Ally
                end
            end
        end
    end
    if furthestAlly then
        return furthestAlly
    end
end
function PriorityLantern()
    local target = nil
    local lowestEffectiveHealth = 0
    for _, Ally in pairs(GetAllyHeroes()) do
       if not Ally.dead and GetDistance(Ally)<=1500 and CountEnemyHeroInRange(1000, Ally)>0 then
           local armour = Ally.armor
           local magicResist = Ally.magicArmor
           local health = Ally.health
           local currentEffectiveHealth = (armour*magicResist)+health
           if target == nil then
               target = Ally
               lowestEffectiveHealth = currentEffectiveHealth
           elseif currentEffectiveHealth<lowestEffectiveHealth then
               target = Ally
               lowestEffectiveHealth = currentEffectiveHealth
           end
       end
    end
end
function AOELantern()
    local count = 0
    local highestCount = 0
    local chosenAlly = nil
    for _, Ally in pairs(GetAllyHeroes()) do
        if not Ally.isMe and not Ally.dead and GetDistance(Ally)<=1500 and CountEnemyHeroInRange(500, Ally)>0 then
            for i, ally in pairs(GetAllyHeroes()) do
                if Ally ~= ally and GetDistance(ally, Ally)<=400 then
                   count = count + 1
                end
            end
            if chosenAlly == nil then
                chosenAlly = Ally
                highestCount = count
            elseif count>highestCount then
                chosenAlly = Ally
                highestCount = count
            end
        end
    end
    if chosenAlly then
        return chosenAlly
    end
end
function LanternMostAr()
	local furthestAlly = GrabFurthestAlly()
	local aoeAlly = AOELantern()
	if myHero.team ~= nil and furthestAlly and CanCast(_W) then
		local AllyPos = Vector(furthestAlly.visionPos.x, furthestAlly.visionPos.y, furthestAlly.visionPos.z)
		local HeroPos = Vector(myHero.x, myHero.y, myHero.z)
		local Pos = HeroPos + (HeroPos-AllyPos)*(-950/GetDistance(furthestAlly))
		CastSpell(_W, Pos.x, Pos.z)
	elseif myHero.team ~= nil and aoeAlly and CanCast(_W) then
		CastSpell(_W, aoeAlly.visionPos.x, aoeAlly.visionPos.z)
	end
end
function HookHarass()
	local spellQ = myHero:GetSpellData(_Q).name
	if CanCast(_Q) and morganaShieldedUnit ~= ts.target and sivrShieldedUnit ~= ts.target and nocturneShieldedUnit ~= ts.target and GetDistance(ts.target) <= Config.spellSettings.qRangeSlider and spellQ == "ThreshQ" then
		local CastPosition, HitChance, Position = VP:GetLineCastPosition(ts.target, qDelay, qWidth, qRange, qSpeed, myHero, true)
		if HitChance >= Config.spellSettings.qHitChance then
			CastSpell(_Q, CastPosition.x, CastPosition.z)
		end
	end
end
function IsIgnited(target)
	if TargetHaveBuff("SummonerDot", target) then
		igniteTick = GetTickCount()
		return true
	elseif igniteTick == nil or GetTickCount()-igniteTick>500 then
		return false
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
	if CanCast(_R) and CountEnemyHeroInRange(450) >= Config.ultSettings.BoxCount then
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
function autoFlayChecks()
	for champion, buff in pairs(autoflayList) do
		if GetDistance(champion)<=eRange and CanCast(_E) then
			CastSpell(_E, champion.x, champion.z)
		end
	end
end

--[[function checkShowGameConfig()
	if Config.ShowGame.Skills.Harass or Config.ShowGame.Skills.harassToggle or Config.ShowGame.Skills.teamFight or Config.ShowGame.Skills.AutoE or 
	Config.ShowGame.Skills.teamFightQ or Config.ShowGame.Skills.teamFightW or Config.ShowGame.Skills.teamFightE or Config.ShowGame.Skills.HarassQ or 
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
function soulsUpdate()
    for i, soul in pairs(souls) do
		if soul.team == TEAM_ENEMY or not soul.valid then
			table.remove(souls, i)
		end
	end 
end
function OnCreateObj(obj)
	if creatorCheck then return end
	if not spellParticle.valid and obj.team ~= player.team and obj.name == particleFound then -- team doesnt work
		spellParticle = obj
		particleFound = nil
	end
	if Config.drawSettings.drawSoul and obj and obj.name == "Thresh_soul.troy" or obj.name == "Thresh_soul_giant.troy" then
		table.insert(souls, obj)
	end
end
function orbWalk()	
	local range = myHero.range
	if ValidTarget(newTarget) then range = range + GetDistance(newTarget, newTarget.minBBox)/2-40 end
	if GetTickCount() > NextShot and ValidTarget(newTarget) and GetDistance(newTarget)<=range and Config.combatKeys.teamFight and Config.orbWalk.aaTeamFight then
			myHero:Attack(newTarget)
	elseif GetTickCount() > aaTime and Config.combatKeys.teamFight and Config.orbWalk.moveToMouseTeamFight then
		MoveToMouse()
	end
end
function MoveToMouse()
	if GetTickCount()>moveTimer then
		local x = mousePos.x
	    local y = mousePos.z
	    local selectionRadius = 65
	    local destination = Point(x, y)
	    local heroPos = Point(myHero.x, myHero.y)
	    if heroPos:distance(destination) <= selectionRadius then
	        local moveTo = heroPos + (destination - heroPos):normalized() * (selectionRadius + 30)
	        player:MoveTo(moveTo.x, moveTo.y)
	    else
	        player:MoveTo(x, y)
	    end
		moveTimer = GetTickCount() + 150
	end
end
function OnProcessSpell(unit, spell)
	if creatorCheck then return end
	if Config.flaySettings.autoFlay then
		if unit.team ~= player.team and string.find(spell.name, "Basic") == nil then
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
    if unit.isMe and unit.valid and spell.name:lower():find("attack") and spell.animationTime and spell.windUpTime then
		aaTime = GetTickCount() + spell.windUpTime * 1000
		NextShot = GetTickCount() + spell.animationTime * 1000
	end
	if isHooked and not unit.isMe and spell.name == "LanternWAlly" then
	   CastSpell(_Q) 
	end
	if isChanneling[spell.name] and unit.team == TEAM_ENEMY and GetDistance(spell.startPos)<=eRange then
	   CastSpell(_E, spell.startPos.x, spell.startPos.z)
	   autoflayList[unit] = {Name = spell.name}
	elseif unit.team ~= myHero.team and spell.name == "SummonerFlash" and GetDistance(spell.endPos)<=eRange then
		CastSpell(_E, spell.endPos.x, spell.endPos.z)
	end
end
function OnAnimation(unit,animationName)
	if creatorCheck then return end
	if unit.isMe and not animationName:lower():find("recall") then recalling = false end
	if unit.team == TEAM_ENEMY and autoflayList[unit] and not animationName:lower():find("spell") then
		autoflayList[unit] = nil
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
function OnDraw()
	if Config.drawSettings.drawSoul then
	    for i, soul in pairs(souls) do
	        if soul ~= nil then
	        	for i = 1, 3 do
	    			DrawCircle2(soul.x, soul.y, soul.z, 50+i, ARGB(255,255,255,255))
	    		end
	    	end
	    end
	end
end
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
	if Config.ShowGame.Skills.teamFight then
		Config.combatKeys:permaShow("teamFight")
	end
	if Config.ShowGame.Skills.teamFightQ then
		Config.skillActivation:permaShow("UseQ")
	end
	if Config.ShowGame.Skills.teamFightW then
		Config.skillActivation:permaShow("UseW")
	end
	if Config.ShowGame.Skills.teamFightE then
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