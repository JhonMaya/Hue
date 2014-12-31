<?php exit() ?>--by dekaron2 86.161.51.182
if myHero.charName ~= "Elise" then return end
require "Collision"
require "Prodiction"
-- Spider Woman by DEKLAND in association with Team Freelo test

----------------------------------END HEADER FILE--------------------------------------------------------------
_G.SPIDERWOMAN = true
_G.SPIDERWOMANTarget = nil
_G.SpiderWomanLoaded = true
_G.SpiderWomanDodging = false
_G.SpiderCanDodge = false
----------------------------------Global Checks Here ----------------------------------------------------------
--[[if _G.MMALoaded == false or _G.MMALoaded == nil then
	print("MMA not found, using built-in Orbwalking")
elseif _G.MMALoaded == true then
	print("MMA found, using MMA Orbwalking")
end]]

if not _G.Activator then
	print("Activator not found, please use Activator for more item support")
else
	print("Activator found, using Spider Woman offensive items")
	_G.OffensiveItems = false
end
---------------------------------- START UPDATE ---------------------------------------------------------------
collectgarbage() 

local versionSPIDER = 0.006 -- current version
local SCRIPT_NAME_SPIDER = "SpiderWoman"
---------------------------------------------------------------------------------------------------------------
local needUpdate_SPIDER = false
local needRun_SPIDER = true
local URL_SPIDER = "http://dekland.com/bol/"..SCRIPT_NAME_SPIDER..".lua"
local PATH_SPIDER = BOL_PATH.."Scripts\\"..SCRIPT_NAME_SPIDER..".lua"
function CheckVersionSPIDER(data)
	local onlineVerSPIDER = tonumber(data)
	if type(onlineVerSPIDER) ~= "number" then return end
	if onlineVerSPIDER and onlineVerSPIDER > versionSPIDER then
		print("<font color='#e0f900'>"..SCRIPT_NAME_SPIDER..": </font>".."<font color='#e0f900'> There is a new version to get. Auto Update. Don't F9 till done...</font>") 
		needUpdate_SPIDER = true
	else
		print("<font color='#00FF00'>"..SCRIPT_NAME_SPIDER.." : "..versionSPIDER.." </font><font color='#00FF00'> loaded. You have the most recent version loaded.</font>")  
	end
end
function UpdateScriptSPIDER()
	if needRun_SPIDER then
		needRun_SPIDER = false
		if _G.SpiderWomanUpdater == nil or _G.SpiderWomanUpdater == true then 
			GetAsyncWebResult("dekland.com", "bol/"..SCRIPT_NAME_SPIDER.."Ver.lua", CheckVersionSPIDER) 
		else
			print("<font color='#ff0000'> Spider Woman: Auto-updating off. You are using version : "..versionSPIDER.."</font>")
		end
	end

	if needUpdate_SPIDER then
		needUpdate_SPIDER = false
		DownloadFile(URL_SPIDER, PATH_SPIDER, function()
                if FileExist(PATH_SPIDER) then
                    print("<font color='#00FF00'>"..SCRIPT_NAME_SPIDER..": </font>".."<font color='#00FF00'> Updated, Reload script for new version.</font>")
                end
		end)
	end
end
AddTickCallback(UpdateScriptSPIDER)
---------------------------------- END UPDATE -----------------------------------------------------------------

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
if _G.GetAsyncWebResult == nil or _G.GetUser == nil or _G.CastSpell == nil then print("Spider Woman: Unauthorized User") return end
local a1 = tonumber(string.sub(tostring(_G.GetAsyncWebResult), 11), 16)
local a2 = tonumber(string.sub(tostring(_G.GetUser), 11), 16)
local a3 = tonumber(string.sub(tostring(_G.CastSpell), 11), 16)
if abs(a2-a1) > 500000 and abs(a3-a2) > 500000 then print("Spider Woman: Unauthorized User") return end
if abs(a2-a1) < 500 and abs(a3-a2) < 500 then print("Spider Woman: Unauthorized User") return end
_G.rawset = rawset3
namez = protect {
	["dekaron2"] = true, ["sniperbro"] = true, ["aiszu"] = true, ["3rasus"] = true, ["zikkah"] = true, ["klokje"] = true,
	["kainisbest"] = true, ["ires"] = true, ["149kg"] = true, ["sida"] = true, ["dansa"] = true, ["arorawish"] = true,
	["catcatchoi"] = true, ["gigalord"] = true, ["rainydays"] = true, ["bigbudda87"] = true, ["wgmiskel"] = true, ["sabaku"] = true,
	["sirkouki"] = true, ["yokokoyo"] = true, ["lassiemeow"] = true, ["orgamarius"] = true, ["eunn"] = true, ["chriss"] = true,
	["brokencyde"] = true, ["acerzocker"] = true, ["markapril"] = true, ["malotesumur"] = true, ["xero666"] = true, ["darkood"] = true,
	["ajporter93"] = true, ["smsflat"] = true, ["aaronxing"] = true, ["alveron"] = true, ["chrissetzer"] = true, ["dosentmatter123"] = true,
	["turtlebot"] = true, ["ijuno"] = true, ["wildflower1"] = true, ["andythesk8r"] = true, ["waffle"] = true, ["mewkyy"] = true,
	["abc8902"] = true, ["l4a"] = true, ["seamlessly"] = true, ["lukeyboy89"] = true, ["mcblaber"] = true, ["kingkidd"] = true,
	["methodxb"] = true, ["markcato95"] = true, ["lumi"] = true, ["aressandoro"] = true, ["qwe"] = true, ["pbp1221990"] = true,
	["xtony211"] = true, ["adz85"] = true, ["nicholasrowan"] = true, ["floresrikko"] = true, ["ezenemy"] = true, ["lienniar"] = true,
	["kihan112"] = true, ["itzneil"] = true, ["shakure"] = true, ["solenrus"] = true, ["kriksi"] = true, ["guest599"] = true,
	["paradoxel"] = true, ["blueworls"] = true, ["ejdernefesi"] = true, ["badger31428"] = true, ["rafaelinux"] = true, ["nightmare"] = true,
	["zzxxcvcv"] = true, ["frinshy"] = true, ["johan95"] = true, ["agn11059555"] = true, ["eriszen"] = true, ["truxranger2"] = true,
	["immortalhz"] = true, ["nsrp"] = true, ["iodas1"] = true, ["pyrophenix"] = true, ["morza"] = true, ["eway86"] = true,
	["cabana"] = true, ["ljk3322"] = true, ["missiles93"] = true, ["hunter35193"] = true, ["ijustwannaleech"] = true, ["odunsevici"] = true,
	["tortelles"] = true, ["tromatic"] = true, ["almightythor101"] = true, ["tipanaya"] = true, ["bugmenot1337"] = true, ["future901"] = true,
	["milchstrudel"] = true, ["herpnderp"] = true, ["pappsen"] = true, ["ywest"] = true, ["web38"] = true, ["midi12"] = true,
	["jasons"] = true, ["errtu"] = true, ["abortion"] = true, ["tjtjsqh"] = true, ["minimoney1"] = true, ["quixor329"] = true,
	["merark"] = true, ["straf"] = true, ["jsteez123"] = true, ["d3dm4n"] = true, ["makelovein"] = true, ["iluvlamastaf"] = true,
	["heartlemon"] = true, ["xruinfx"] = true, ["cbkixo"] = true, ["phbn93"] = true, ["cupidsrage"] = true, ["uruu"] = true,
	["exile"] = true, ["rxemi"] = true, ["yeezus"] = true, ["shk1263"] = true, ["ilikebacon"] = true, ["derpthesauce"] = true,
	["khicon"] = true, ["pbp1221990"] = true, ["grrrt"] = true, ["xtony211"] = true, ["ddsq1226"] = true, ["jta87k"] = true,
	["kobe2324"] = true, ["xcxooxl"] = true, ["omnipot3nt"] = true, ["neyu"] = true, ["sharps"] = true, ["feez"] = true,
	["rafaelj"] = true, ["jty0102"] = true, ["donleon"] = true, ["opaque42"] = true, ["mootme1"] = true, ["kirewade"] = true,
	["dontstephere"] = true, ["aqualake"] = true, ["hahahax"] = true, ["qqq"] = true, ["lunatix"] = true, ["norrin"] = true, 
	["eanzos"] = true, ["eliteis"] = true, ["rewind"] = true, ["phexon"] = true, ["lezbro"] = true, ["herpaderpa123"] = true, 
	["pok"] = true, ["cryo"] = true, ["gaship"] = true, ["lancai"] = true, ["carnegie"] = true, ["kainv2"] = true, 
	["q179339065"] = true, ["andysmalll"] = true, ["cyberlol"] = true, ["ojay"] = true, ["thespecialone"] = true, ["boluser1337"] = true,
	["verajicus"] = true, ["sovietrussiasam"] = true, ["l00b"] = true, ["nekomimibadik"] = true, ["ghostrider9310"] = true, ["walking hell"] = true,
	["mrwong"] = true, ["teino"] = true, ["gianmaranon"] = true, ["mkwarrior"] = true, ["lolnodawg"] = true, ["reodor9"] = true,
	["igotcslol"] = true, ["kyku"] = true, ["errinqq"] = true,
}
_G.rawset = rawset3
if namez[_G.GetUser():lower()] == nil or namez[_G.GetUser():lower()] == false then print("Spider Woman: Unauthorized User") return end
---------------------------------- END CHECK --------------------------------------------------------------------

local priorityTable = {
	AP = {
		"Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
		"Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
		"Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
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

if myHero.charName ~= "Elise" then return end
--require "Collision"
function OnLoad()
	LoadConfigHeader()
	LoadVariables()
	LoadSmiteMinion()
	LoadMenus()
	AdvancedCallbacks()
	PrintFloatText(myHero,12,"Spider Woman Loaded!")
end
function OnUnload()
	PrintFloatText(myHero,12,"Spider Woman UnLoaded!")
end
function LoadConfigHeader()
	Config = scriptConfig("SpiderWoman", "Spider Woman")
end
function LoadMenus()
	LoadEvadeeeMenu()
	LoadSkillsMenu()
	LoadPredictionMenu()
	LoadKSMenu()
	LoadRappelMenu()
	LoadFarmMenu()
	LoadOrbWalkingMenu()
	LoadOffensiveItemsMenu()
	LoadOnDrawMenu()
	LoadShowInGame()
	if SummonerSpells ~= nil then
       LoadIgniteMenu()
       LoadSmiteMenu()
	end
	LoadShowGameConfig()
end
function LoadPredictionMenu()
	Config:addSubMenu("Prediction Choice", "predictionChoice")
	Config.predictionChoice:addParam("useProdiction", "Use Prodiction", SCRIPT_PARAM_ONOFF, true)
	Config.predictionChoice:addParam("useVpred", "Use VPred", SCRIPT_PARAM_ONOFF, false)
end
function LoadSkillsMenu()
	Config:addParam("SpiderInfo", "Spider Woman Version: ", SCRIPT_PARAM_INFO, versionSPIDER)
  	Config:permaShow("SpiderInfo")

	Config:addSubMenu("Combat Keys", "combatKeys")
	Config.combatKeys:addParam("harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, 84)
	Config.combatKeys:addParam("harassToggle", "Harass Toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, 88)
	Config.combatKeys:addParam("teamFight", "TeamFight", SCRIPT_PARAM_ONKEYDOWN, false, 32)

	Config:addSubMenu("Skill Activation", "skillActivation")
	Config.skillActivation:addParam("humanQ", "Human Q", SCRIPT_PARAM_ONOFF, true)
	Config.skillActivation:addParam("humanW", "Human W", SCRIPT_PARAM_ONOFF, true)
	Config.skillActivation:addParam("humanE", "Human E", SCRIPT_PARAM_ONOFF, true)

	Config.skillActivation:addParam("spiderQ", "Spider Q", SCRIPT_PARAM_ONOFF, true)
	Config.skillActivation:addParam("spiderW", "Spider W", SCRIPT_PARAM_ONOFF, true)
	Config.skillActivation:addParam("spiderE", "Spider E", SCRIPT_PARAM_ONOFF, true)

	Config.skillActivation:addParam("UseR", "Use R", SCRIPT_PARAM_ONOFF, true)
	
end
function LoadKSMenu()
	Config:addSubMenu("KS Settings", "KS")
	Config.KS:addParam("KsQ", "Q KS", SCRIPT_PARAM_ONOFF, true)
	Config.KS:addParam("KsW", "W KS", SCRIPT_PARAM_ONOFF, true)
	Config.KS:addParam("KsE", "E KS", SCRIPT_PARAM_ONOFF, true)

end
function LoadRappelMenu()
	Config:addSubMenu("Rappel Settings", "rappel")
	Config.rappel:addParam("rappelKill", "Rappel Killing Spells", SCRIPT_PARAM_ONOFF, true)
	Config.rappel:addParam("rappelDangerous", "Rappel Dangerous Spells", SCRIPT_PARAM_ONOFF, true)
	Config.rappel:addParam("rappelExtreme", "Rappel Extreme Spells", SCRIPT_PARAM_ONOFF, true)	
	Config.rappel:addParam("rappelTower", "Rappel Tower Shots", SCRIPT_PARAM_ONOFF, true)
	Config.rappel:addParam("rappelSmite", "Rappel Smite Steal", SCRIPT_PARAM_ONKEYDOWN, false, 75)
	Config.rappel:addParam("rappelSteal", "Auto Rappel Steal", SCRIPT_PARAM_ONOFF, true)
end
function LoadIgniteMenu()
	Config:addSubMenu("Summoner Spells", "SummonerSpells")
    Config.SummonerSpells:addSubMenu("Ignite Settings", "Ignite")
    Config.SummonerSpells.Ignite:addParam("useIgnite", "Use Ignite", SCRIPT_PARAM_ONOFF, true)
    Config.SummonerSpells.Ignite:addParam("KSMode", "KS Mode", SCRIPT_PARAM_ONOFF, false)
    Config.SummonerSpells.Ignite:addParam("ComboMode", "Combo Mode", SCRIPT_PARAM_ONOFF, true)
    if Config.SummonerSpells.Ignite.useIgnite then
    	Config.SummonerSpells.Ignite:addParam("IgniteMode", "Ignite Mode : ", SCRIPT_PARAM_INFO, "")
    end

    Config.ShowGame:addSubMenu("Show Ignite", "ShowIgnite")
    Config.ShowGame.ShowIgnite:addParam("useIgnite", "Show Use Ignite", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.ShowIgnite:addParam("IgniteMode", "Show Ignite Mode", SCRIPT_PARAM_ONOFF, true)
    
end
function LoadSmiteMenu()
    
    Config.SummonerSpells:addSubMenu("Smite Settings", "Smite")
    Config.SummonerSpells.Smite:addParam("useSmite", "Use Smite", SCRIPT_PARAM_ONKEYTOGGLE, true, 78)
    Config.SummonerSpells.Smite:addParam("smiteStun", "Use Smite to land Stun", SCRIPT_PARAM_ONOFF, true)
    Config.SummonerSpells.Smite:addParam("ChampSmite", "Smite + Champ ability", SCRIPT_PARAM_ONOFF, true)
    Config.SummonerSpells.Smite:addParam("smiteSmall", "Smite Small Camps", SCRIPT_PARAM_ONOFF, true)
    Config.SummonerSpells.Smite:addParam("smiteLarge", "Smite Large Camps", SCRIPT_PARAM_ONOFF, true)
    Config.SummonerSpells.Smite:addParam("smiteEpic", "Smite Epic Camps", SCRIPT_PARAM_ONOFF, true)
    Config.SummonerSpells.Smite:addParam("LifeSaving", "Life Saving Smite", SCRIPT_PARAM_ONOFF, true)
    Config.SummonerSpells.Smite:addParam("LifeSavingHealth", "Life Saving Percent", SCRIPT_PARAM_SLICE, 5, 1, 99, 0)

    Config.ShowGame:addSubMenu("Show Smite", "ShowSmite")
    Config.ShowGame.ShowSmite:addParam("useSmite", "Show Use Smite", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.ShowSmite:addParam("ChampSmite", "Show Use Champ Smite", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowSmite:addParam("smiteSmall", "Show Smite Small Camps", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowSmite:addParam("smiteLarge", "Show Smite Large Camps", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowSmite:addParam("smiteEpic", "Show Smite Epic Camps", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowSmite:addParam("LifeSaving", "Show Life Save Smite", SCRIPT_PARAM_ONOFF, false)
    
end
function LoadOffensiveItemsMenu()
	Config:addSubMenu("Offensive Items Settings", "Items")
	
	Config.Items:addSubMenu("AP Items", "APItems")
	Config.Items.APItems:addParam("useAPItems", "Use AP Items", SCRIPT_PARAM_ONOFF, true)
	Config.Items.APItems:addParam("useBilgewaterCutlass", "Use Bilgewater Cutlass", SCRIPT_PARAM_ONOFF, true)
	Config.Items.APItems:addParam("useBlackfireTorch", "Use Blackfire Torch", SCRIPT_PARAM_ONOFF, true)
	Config.Items.APItems:addParam("useDFG", "Use Deathfire Grasp", SCRIPT_PARAM_ONOFF, true)
	Config.Items.APItems:addParam("useHextechGunblade", "Use Hextech Gunblade", SCRIPT_PARAM_ONOFF, true)
	Config.Items.APItems:addParam("useTwinShadows", "Use Twin Shadows", SCRIPT_PARAM_ONOFF, true)
	Config.Items.APItems:addParam("apKSMode", "AP Item KS Mode", SCRIPT_PARAM_ONOFF, false)
	Config.Items.APItems:addParam("apComboMode", "AP Item Combo Mode", SCRIPT_PARAM_ONOFF, true)
	Config.Items.APItems:addParam("apBurstMode", "AP Item Burst Mode", SCRIPT_PARAM_ONOFF, false)
	if Config.Items.APItems.useAPItems then
		Config.Items.APItems:addParam("APItemMode", "AP Item Mode", SCRIPT_PARAM_INFO, "")
	end

	Config.Items:addSubMenu("AD Items", "ADItems")
	Config.Items.ADItems:addParam("useADItems", "Use AD Items", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useBOTRK", "Use Blade of the Ruined King", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useEntropy", "Use Entropy", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useRavenousHydra", "Use Ravenous Hydra", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useSwordOfTheDevine", "Use Sword of the Divine", SCRIPT_PARAM_ONOFF, true)	
	Config.Items.ADItems:addParam("useTiamat", "Use Tiamat", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("useYoumuusGhostblade", "Use Youmuu's Ghostblade", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("adKSMode", "AD Items KS Mode", SCRIPT_PARAM_ONOFF, false)
	Config.Items.ADItems:addParam("adComboMode", "AD Items Combo Mode", SCRIPT_PARAM_ONOFF, true)
	Config.Items.ADItems:addParam("adBurstMode", "AD Items Burst Mode", SCRIPT_PARAM_ONOFF, false)
	if Config.Items.ADItems.useADItems then
		Config.Items.ADItems:addParam("ADItemMode", "AD Item Mode: ", SCRIPT_PARAM_INFO, "")
	end
	

end
function LoadFarmMenu()
	Config:addSubMenu("Farm Settings", "Farm")
	Config.Farm:addSubMenu("Lane Farm", "laneFarm")
	Config.Farm.laneFarm:addParam("farm", "Farm Regular Press", SCRIPT_PARAM_ONKEYDOWN, false, 85)
	Config.Farm.laneFarm:addParam("farmToggle", "Farm Regular Toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, 90)
	Config.Farm.laneFarm:addParam("laneClear", "Lane Clear Button", SCRIPT_PARAM_ONKEYDOWN, false, 73)
	Config.Farm.laneFarm:addParam("laneClearToggle", "Lane Clear Toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, 79)
	Config.Farm.laneFarm:addParam("farmOrb", "Orbwalk inFarm", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.laneFarm:addParam("farmAA", "AA Farm", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.laneFarm:addParam("farmQ", "Q Farm", SCRIPT_PARAM_ONOFF, true)
	Config.Farm.laneFarm:addParam("farmW", "W Farm", SCRIPT_PARAM_ONOFF, true)
	Config.Farm.laneFarm:addParam("farmFormMode", "Farm Form Mode", SCRIPT_PARAM_INFO, " ")
	Config.Farm.laneFarm:addParam("farmSpider", "Farm in Spider Form", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.laneFarm:addParam("farmHuman", "Farm in Human Form", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.laneFarm:addParam("farmMix", "Farm in Mix Form", SCRIPT_PARAM_ONOFF, true)
	

	Config.Farm:addSubMenu("Jungle Farm", "jungleFarm")
	Config.Farm.jungleFarm:addParam("jungleFarming", "Jungle Farm", SCRIPT_PARAM_ONKEYDOWN, false, 74)
	Config.Farm.jungleFarm:addParam("jungleOrb", "Orbwalk in Jungle Farm", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.jungleFarm:addParam("jungleFarmAA", "AA Jungle Farm", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.jungleFarm:addParam("jungleFarmQ", "Q Jungle Farm", SCRIPT_PARAM_ONOFF, true)
	Config.Farm.jungleFarm:addParam("jungleFarmW", "W Jungle Farm", SCRIPT_PARAM_ONOFF, true)
	Config.Farm.jungleFarm:addParam("jungleFarmAggro", "Aggro with Spiderlings", SCRIPT_PARAM_ONOFF, true)
	Config.Farm.jungleFarm:addParam("jungleFormMode", "Jungle Form Mode", SCRIPT_PARAM_INFO, " ")
	Config.Farm.jungleFarm:addParam("jungleSpider", "Jungle in Spider Form", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.jungleFarm:addParam("jungleHuman", "Jungle in Human Form", SCRIPT_PARAM_ONOFF, false)
	Config.Farm.jungleFarm:addParam("jungleMix", "Jungle in Mix Form", SCRIPT_PARAM_ONOFF, true)
	
	Config.Farm:addParam("manaPercent", "Farm Mana Management", SCRIPT_PARAM_SLICE, 20,1,100,0)
end
function LoadOrbWalkingMenu()
	Config:addSubMenu("OrbWalk Settings", "OrbWalk")
	Config.OrbWalk:addSubMenu("Teamfight OrbWalking", "champOrbWalk")
	Config.OrbWalk.champOrbWalk:addParam("moveToMouse", "Move To Mouse", SCRIPT_PARAM_ONOFF, true)
	Config.OrbWalk.champOrbWalk:addParam("AA", "Auto Attacks", SCRIPT_PARAM_ONOFF, true)

end
function LoadEvadeeeMenu()
	if _G.Evadeee_Loaded == true and not evadeeLoaded then
		Config:addSubMenu("Evadeee Settings", "Evadeee")
		Config.Evadeee:addParam("UseEvadeee", "Use Evadeee Integration", SCRIPT_PARAM_ONOFF, true)
		evadeeLoaded = true
		print("Evadeee found, You can use Evadeee integration")	
	end
end
function LoadOnDrawMenu()
	Config:addSubMenu("Draw Settings", "Draw")
	Config.Draw:addParam("LagFree", "Lag free draw", SCRIPT_PARAM_ONOFF, true)

	Config.Draw:addSubMenu("Draw Skill Ranges", "drawSkillKillRanges")
	Config.Draw.drawSkillKillRanges:addParam("drawQRange", "Draw Q Range", SCRIPT_PARAM_ONOFF, false)
	Config.Draw.drawSkillKillRanges:addParam("drawQRangeColour", "Choose Q Range Colour", SCRIPT_PARAM_COLOR, {87,183,60,244})
	Config.Draw.drawSkillKillRanges:addParam("drawWRange", "Draw W Range", SCRIPT_PARAM_ONOFF, false)
	Config.Draw.drawSkillKillRanges:addParam("drawWRangeColour", "Choose W Range Colour", SCRIPT_PARAM_COLOR, {87,183,60,244})
	Config.Draw.drawSkillKillRanges:addParam("drawERange", "Draw E Range", SCRIPT_PARAM_ONOFF, false)
	Config.Draw.drawSkillKillRanges:addParam("drawERangeColour", "Choose E Range Colour", SCRIPT_PARAM_COLOR, {87,183,60,244})

	Config.Draw:addSubMenu("Draw Kill Range", "drawKillRange")
	Config.Draw.drawKillRange:addParam("KillRange", "Kill Range", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.drawKillRange:addParam("killRangeColour", "Choose Kill Range Colour", SCRIPT_PARAM_COLOR, {87,183,60,244})

	Config.Draw:addSubMenu("Draw Focused Target", "drawFocusedTarget")
	Config.Draw.drawFocusedTarget:addParam("DrawFocusedTarget", "Focused Target", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.drawFocusedTarget:addParam("focusedTargetColour", "Choose Focused Target Colour", SCRIPT_PARAM_COLOR, {244,66,155,255})

	--[[Config.Draw:addSubMenu("Draw My Hero OOM Text", "drawOOMText")
	Config.Draw.drawOOMText:addParam("OOMText", "Out Of Mana Text", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.drawOOMText:addParam("OOMTextColour", "Out Of Mana Text Colour", SCRIPT_PARAM_COLOR, {244,66,155,255})]]

	Config.Draw:addSubMenu("Draw Enemy Killable Text", "drawEnemyKillableText")
	Config.Draw.drawEnemyKillableText:addParam("enemyKillableText", "Enemy Killable Text", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.drawEnemyKillableText:addParam("harassColourText", "Choose Harass Text Colour", SCRIPT_PARAM_COLOR, {255,10,255,20})
	Config.Draw.drawEnemyKillableText:addParam("comboColourText", "Choose Combo Text Colour", SCRIPT_PARAM_COLOR, {255,248,255,20})
	Config.Draw.drawEnemyKillableText:addParam("killHimColourText", "Choose Kill Him Text Colour", SCRIPT_PARAM_COLOR, {255,255,143,20})
	Config.Draw.drawEnemyKillableText:addParam("ksColourText", "Choose KS Text Colour", SCRIPT_PARAM_COLOR, {255,255,10,20})

	--[[Config.Draw:addSubMenu("Draw Enemy Death Timer Text", "drawDeathTimerText")
	Config.Draw.drawDeathTimerText:addParam("enemyDeathTimer", "Death Timer", SCRIPT_PARAM_ONOFF, true)]]

	Config.Draw:addSubMenu("Draw Tracker", "drawTracker")
	Config.Draw.drawTracker:addParam("drawEnemyTracker", "Draw Tracker", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.drawTracker:addParam("drawKillHim", "Draw Kill Tracker", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.drawTracker:addParam("drawComboKiller", "Draw Combo Tracker", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.drawTracker:addParam("drawHarass", "Draw Harass Tracker", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.drawTracker:addParam("drawDamage", "Draw Damage Tracker", SCRIPT_PARAM_ONOFF, true)

	Config.Draw:addSubMenu("Draw Smite", "SmiteSettings")
	Config.Draw.SmiteSettings:addParam("DrawSmite", "Draw Smite Range", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.SmiteSettings:addParam("DrawSmiteTarget", "Draw Smite Target", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.SmiteSettings:addParam("DrawSmiteDamage", "Draw Smite Damage", SCRIPT_PARAM_ONOFF, true)
	Config.Draw.SmiteSettings:addParam("DrawSmiteRappel", "Draw Smite Rappel Range", SCRIPT_PARAM_ONOFF, true)
end
function LoadShowInGame() 
    Config:addSubMenu("Show In Game", "ShowGame")
    
    Config.ShowGame:addSubMenu("Show Skills", "Skills")
    Config.ShowGame.Skills:addParam("Harass", "Show Harass Key Press", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.Skills:addParam("harassToggle", "Show Harass Toggle", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.Skills:addParam("Teamfight", "Show Teamfight", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.Skills:addParam("humanQ", "Show Use Human Q", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.Skills:addParam("humanW", "Show Use Human W", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.Skills:addParam("humanE", "Show Use Human E", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.Skills:addParam("spiderQ", "Show Use Spider Q", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.Skills:addParam("spiderW", "Show Use Spider W", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.Skills:addParam("spiderE", "Show Use Spider E", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.Skills:addParam("TeamfightR", "Show Use R", SCRIPT_PARAM_ONOFF, false)
    
    Config.ShowGame:addSubMenu("Show KS", "ShowKS")
    Config.ShowGame.ShowKS:addParam("ksQ", "Show KS Q", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowKS:addParam("ksW", "Show KS W", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowKS:addParam("ksE", "Show KS E", SCRIPT_PARAM_ONOFF, false)

    Config.ShowGame:addSubMenu("Show Rappel", "ShowRappel")
    Config.ShowGame.ShowRappel:addParam("rappelKill", "Show Rappel Kill Spells", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowRappel:addParam("rappelDangerous", "Show Rappel Dangerous", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowRappel:addParam("rappelExtreme", "Show Rappel Extreme", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowRappel:addParam("rappelSmite", "Show Rappel Smite", SCRIPT_PARAM_ONOFF, false)
    
    Config.ShowGame:addSubMenu("Show Offensive Items", "ShowItems")
    Config.ShowGame.ShowItems:addParam("apItems", "Show Use AP Items", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.ShowItems:addParam("APMode", "Show AP Item Mode", SCRIPT_PARAM_ONOFF, true)
	Config.ShowGame.ShowItems:addParam("adItems", "Show Use AD Items", SCRIPT_PARAM_ONOFF, true)
	Config.ShowGame.ShowItems:addParam("ADMode", "Show AD Item Mode", SCRIPT_PARAM_ONOFF, true)

    Config.ShowGame:addSubMenu("Show Farm", "ShowFarm")
    Config.ShowGame.ShowFarm:addParam("farm", "Show Lane Farm", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.ShowFarm:addParam("farmToggle", "Show Lane Farm Toggle", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.ShowFarm:addParam("laneClear", "Show Lane Clear Button", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("laneClearToggle", "Show Lane Clear Toggle", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("manaPercent", "Show Farm Mana Percen", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("farmOrb", "Show Orbwalking in Farm", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("farmAA", "Show AA in Farm", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("farmQ", "Show Lane Farm Q", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("farmW", "Show Lane Farm W", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("farmFormMode", "Show Farm Form Mode", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("Jungle", "Show Jungle Farm", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.ShowFarm:addParam("jungleOrb", "Show Orbwalking in Jungle", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("jungleAA", "Show Jungle Farm AA", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("JungleQ", "Show Jungle Farm Q", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("JungleW", "Show Jungle Farm W", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowFarm:addParam("JungleFormMode", "Show Jungle Form Mode", SCRIPT_PARAM_ONOFF, false)
       
    
    Config.ShowGame:addSubMenu("Show Orbwalking", "ShowOrb")
    Config.ShowGame.ShowOrb:addParam("MoveToMouse", "Show Move to Mouse Teamfight", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.ShowOrb:addParam("AA", "Show AA in Orbwalking Teamfight", SCRIPT_PARAM_ONOFF, false)
    
    Config.ShowGame:addSubMenu("Show Draw", "ShowDraw")
    Config.ShowGame.ShowDraw:addParam("Circles", "Show Skill Range Circles", SCRIPT_PARAM_ONOFF, true)
    Config.ShowGame.ShowDraw:addParam("LagFree", "Show Lag Free", SCRIPT_PARAM_ONOFF, false)

    Config.ShowGame.ShowDraw:addParam("Target", "Show Focused Target", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowDraw:addParam("OOM", "Show Out Of Mana", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowDraw:addParam("Killable", "Show Enemy Killable", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowDraw:addParam("DrawTimer", "Show Draw Timer", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowDraw:addParam("DrawTracker", "Show Draw Tracker", SCRIPT_PARAM_ONOFF, false)

    Config.ShowGame:addSubMenu("Show Draw Settings", "ShowDraw")
    Config.ShowGame.ShowDraw:addParam("DrawSmite", "Show Draw Smite Range", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowDraw:addParam("DrawSmiteTarget", "Show Draw Smite Target", SCRIPT_PARAM_ONOFF, false)
    Config.ShowGame.ShowDraw:addParam("DrawSmiteDamage", "Show Draw Smite Damage", SCRIPT_PARAM_ONOFF, false)
     
end
function LoadVariables()
	predictionCheck = false
	towerShotTimer = 0
	towerShooting = false
	haveAggro = false
	aggroUnit = nil
	spiderlings = false
	eStunTimer = 0
	------Evadeee Vairbales------
	evadeeLoaded = false
	evadeeNotLoaded = false
	------Map Vairbales------
	LoadMapVariables()
	------Target Variables---
	newTarget = nil
	newTargetPriority = 0
	LoadSkillRanges()
	--LoadVIPPrediction()
	-------Ks---------------
	ksTimer = 0
	ksDamages = {}
	----Summoner Spells-----
	LoadSummonerSpells()
	------Enemies---------
	enemyHeros = {}
	enemyHerosCount = 0
	LoadEnemies()
	---------Allies---------
	alliesHeros = {}
	alliesHerosCount = 0
	LoadAllies()
	-----Orbwalking---------
	NextShot = 0
	aaTime = 0
	-------Minions----------
	--enemyMinions = {}
	--allieMinions = {}
	jungleMinions = {}
	--allJungleMinions = {}
	LoadMinions()
	--farmTimer = 0
	-------Smite Variables---------
	smiteRange = 750
	SmiteDmg = 0
	noSmiteDmg = 0
	smiteRappel = false
	SMITEQHREADY = false
	SMITEQSREADY = false
	-------Prediction Variables----------
	pProdictionMode = false
	pVpredictionMode = false
	-------Farm Variables----------
	pFRegularMode = false
	pFLaneClearMode = false
	---------Ap Items Variables ----
	pAPKSMode = false
	pAPComboMode = false
	pAPBurstMode = false
	---------Lane Form Variables ----
	pHumanMode = false
	pSpiderMode = false
	pMixMode = false
	---------Jungle FormVariables ----
	pJHumanMode = false
	pJSpiderMode = false
	pJMixMode = false
	---------Ad Item Variables ------
	pADKSMode = false
	pADComboMode = false
	pADBurstMode = false

	recalling = false
	----Load Spells Learned Variables-----
	LoadSpellsLearned()
	----Load Cooldown Variables-----
	LoadCoolDownVariables()
	----Load Mana Variables--------
	LoadManaVariables()
	-------Rappel ------------------------
	rappel = false
	wBuffOn = false
	LoadKillSpells()
	LoadDangerousSpells()
	LoadExtremeSpells()
	LoadDoomBall()
	LoadViktorStorm()
	LoadCrowStorm()
	LoadWildFire()
	LoadUltTimers()
end
function LoadDoomBall()
	doomBall = {}
	for i = 1, objManager.maxObjects do
        local obj = objManager:getObject(i)
        if obj ~= nil and obj.valid and obj.name == "yomu_ring_red.troy" then
        	table.insert(doomBall, obj)
        end
    end
end
function LoadViktorStorm()
	viktorStorm = {}
	for i = 1, objManager.maxObjects do
        local obj = objManager:getObject(i)
        if obj ~= nil and obj.valid and obj.name == "Viktor_ChaosStorm_red.troy" then
        	table.insert(viktorStorm, obj)
        end
    end
end
function LoadCrowStorm()
	crowStorm = {}
	for i = 1, objManager.maxObjects do
        local obj = objManager:getObject(i)
        if obj ~= nil and obj.valid and obj.name == "Crowstorm_red_cas" then
        	table.insert(crowStorm, obj)
        end
    end
end
function LoadWildFire()
	wildFire = {}
	for i = 1, objManager.maxObjects do
        local obj = objManager:getObject(i)
        if obj ~= nil and obj.valid and obj.name == "BrandWildfire_mis.troy" then
        	table.insert(wildFire, obj)
        end
    end
end
function LoadUltTimers()
	CaitlynUltTimer = 0
	caitlynUlting = false
	ziggsUltTimer = 0
	ziggsUlting = false
	karthusUltTimer = 0
	morganaUltTimer = 0
	zileanBombTimer = 0
	viUltTimer = 0
	viUlting = false
	fioraTimer = 0
	fioraUlting = false
	nautilusTimer = 0
	nautilusUlting = false
	namiTimer = 0
	namiUlting = false
	brandUltTimer = 0
	brandUlting = false	
	veigarUltTimer = 0
	veigarUlting = false
end
function LoadMapVariables()
	gameState = GetGame()
	if gameState.map.shortName == "summonerRift" then 
		summonersRiftMap = true
	else
		summonersRiftMap = false
	end
	if gameState.map.shortName == "crystalScar" then 
		crystalScarMap = true
	else
		crystalScarMap = false
	end
	if gameState.map.shortName == "howlingAbyss" then
		howlingAbyssMap = true
	else
		howlingAbyssMap = false
	end
	if gameState.map.shortName == "twistedTreeline" then
		twistedTreeLineMap = true
	else
		twistedTreeLineMap = false
	end
end
function LoadSkillRanges()
	rangeQ = 625
	rangeW = 1000
	rangeE = 1100
	rangeQS = 475
	rangeWS = 475
	rangeES = 925
	killRange = 1100
end
function LoadSpellsLearned()
	HUMANQLEARNED = false
	HUMANWLEARNED = false
	HUMANELEARNED = false
	SPIDERQLEARNED = false
	SPIDERWLEARNED = false
	SPIDERELEARNED = false
end
function LoadCoolDownVariables()
	QCD = 0
	QCDTimer = 0
	WCD = 0
	WCDTimer = 0
	ECD = 0
	ECDTimer = 0
	RCD = 0
	RCDTime = 0
	QSCD = 0
	QSCDTimer = 0
	WSCD = 0
	WSCDTimer = 0
	ESCD = 0
	ESCDTimer = 0
end
function LoadManaVariables()
	qMana = 0
	wMana = 0
	eMana = 0
end
function LoadVIPPrediction()
	if Config.predictionChoice.useVpred then
		VP = VPrediction()
	elseif Config.predictionChoice.useProdiction then
		wp = ProdictManager.GetInstance()
		tpW = wp:AddProdictionObject(_W, rangeW, 1440, 0.235)
		tpE = wp:AddProdictionObject(_E, rangeE, 1440, 0.235) --1300, 0.235(grey detector)
	end
	LoadCollisionValues()
end
function LoadCollisionValues()
	wcol = Collision(rangeW, 1440, 0.235, 200)
	ecol = Collision(rangeE, 1440, 0.235, 90)
end
function LoadMinions()
	enemyMinions = minionManager(MINION_ENEMY, rangeE, player, MINION_SORT_HEALTH_ASC)
	jungleMinion = minionManager(MINION_JUNGLE, rangeE, player, MINION_SORT_MAXHEALTH_DEC)
	allyMinion = minionManager(MINION_ALL, rangeE, player, MINION_SORT_MAXHEALTH_DEC)
	--[[for i = 1, objManager.maxObjects do
	    local obj = objManager:getObject(i)
	    if obj ~= nil and obj.type == "obj_AI_Minion" and obj.valid then
	    	--print(obj.team.." "..obj.name)
	    	if obj.team == TEAM_ENEMY then 
				table.insert(enemyMinions, obj)
			elseif obj.team == TEAM_NEUTRAL then
				table.insert(allJungleMinions, obj)
			end
	    end
	end]]
end
function LoadSmiteMinion()
	baronPos = {x = 4600, y = -63, z = 10250}
	--vilemawPos = {x = 7711, y = -62, z = 10080}
	if smite ~= nil then
		for i = 1, objManager.maxObjects do
	        local obj = objManager:getObject(i)
	        if obj ~= nil and obj.type == "obj_AI_Minion" and obj.valid then
				if twistedTreeLineMap then
					if obj.name == "TT_Spiderboss8.1.1" then
						table.insert(jungleMinions, obj)
					elseif obj.name == "TT_NWolf3.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "TT_NWraith1.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "TT_NGolem2.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "TT_NWolf6.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "TT_NWraith4.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "TT_NGolem5.1.1" then 
						table.insert(jungleMinions, obj)
					end
				elseif summonersRiftMap then
					if obj.name == "Worm12.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "Dragon6.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "AncientGolem1.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "GiantWolf2.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "Wraith3.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "LizardElder4.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "Golem5.1.2" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "AncientGolem7.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "GiantWolf8.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "Wraith9.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "LizardElder10.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "Golem11.1.2" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "GreatWraith13.1.1" then 
						table.insert(jungleMinions, obj)
					elseif obj.name == "GreatWraith14.1.1" then 
						table.insert(jungleMinions, obj)
					end
				end
			end
	    end
	end
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
  	----------------------Smite--------------------------------
  	if myHero:GetSpellData(SUMMONER_1).name:find("Smite") then
		smite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("Smite") then
		smite = SUMMONER_2
	else 
		smite = nil
  	end
  	if smite ~= nil then
  		SummonerSpells = true
  	end
end
function LoadEnemies()
	for i = 1, heroManager.iCount do
		local hero = heroManager:GetHero(i)
		if hero.team ~= player.team then
			local enemyCount = enemyHerosCount + 1
			enemyHeros[enemyCount] = {object = hero, priority = 1, killable = 0, ignore = false, hitDamage = 0, comboKillableDmg = 0}
			enemyHerosCount = enemyCount
			--ConfigTarget.setignoreTargets:addParam("ignoreTargets"..i, "Ignore "..hero.charName, SCRIPT_PARAM_ONOFF, false)
		end
	end
	for i = 1, enemyHerosCount do
		local Enemy = enemyHeros[i].object
		Config:addParam("setPriority"..i, "Set Priority "..Enemy.charName, SCRIPT_PARAM_SLICE, 1, 0, 5, 0)
	end
	LoadPriority()
end
function LoadAllies()
	for i = 1, heroManager.iCount do
		local hero = heroManager:GetHero(i)
		if hero.team == player.team then
			local alliesCount = alliesHerosCount + 1
			alliesHeros[alliesCount] = {object = hero}
			alliesHerosCount = alliesCount
		end
	end
end
function LoadPriority()
	for i = 1, enemyHerosCount do
		local Enemy = enemyHeros[i].object
		if Enemy.valid then
			for p, AD in pairs(priorityTable.AD_Carry) do
				if Enemy.charName == AD then
					enemyHeros[i].priority = 1
					Config["setPriority"..i] = 1
				end
			end
			for p, AP in pairs(priorityTable.AP) do
				if Enemy.charName == AP then
					enemyHeros[i].priority = 2
					Config["setPriority"..i] = 2
				end
			end
			for p, Support in pairs(priorityTable.Support) do
				if Enemy.charName == Support then
					enemyHeros[i].priority = 3
					Config["setPriority"..i] = 3
				end
			end
			for p, Bruiser in pairs(priorityTable.Bruiser) do
				if Enemy.charName == Bruiser then
					enemyHeros[i].priority = 4
					Config["setPriority"..i] = 4
				end
			end
			for p, Tank in pairs(priorityTable.Tank) do
				if Enemy.charName == Tank then
					enemyHeros[i].priority = 5
					Config["setPriority"..i] = 5
				end
			end
		end
	end
end
function AdvancedCallbacks()
	AdvancedCallback:bind('OnGainBuff', function(unit, buff) 
		if unit and unit.valid and unit.isMe and buff.name == 'elisespiderling' then spiderlings = true end
		if unit and unit.valid and unit.isMe and buff.name == 'elisespidere' then rappel = true end
		if unit and unit.valid and unit.isMe and buff.name == 'EliseSpiderW' then wBuffOn = true end
		if unit and unit.valid and unit.isMe and buff.name == 'fallenonetarget' then karthusUlt = true end 
		if unit and unit.valid and unit.isMe and buff.name == 'fizzmarinerdoombomb' then fizzUlt = true end 
		if unit and unit.valid and unit.isMe and buff.name == 'zedulttargetmark' then zedUlt = true end 
		if unit and unit.valid and unit.isMe and buff.name == 'SoulShackles' then morganaUlt = true end
		if unit and unit.valid and unit.isMe and buff.name == 'TimeBomb' then zileanBomb = true end
		if unit and unit.team ~= myHero.team and unit.valid and not unit.isMe and buff.name == 'MonkeyKingSpinToWin' then monkeyKingUlt = true monkeyObj = unit end
		if unit and unit.valid and unit.isMe and buff.name == 'varusrsecondary' then varusUlt = true end
		if unit and unit.valid and not unit.isMe and unit.team ~= myHero.team and buff.name == 'fioraduelisthot' then fioraUlt = true fioraUltTarget = unit end
	end)
	AdvancedCallback:bind('OnLoseBuff', function(unit, buff) 
		if unit and unit.valid and unit.isMe and buff.name == 'elisespiderling' then spiderlings = false end
		if unit and unit.valid and unit.isMe and buff.name == 'elisespidere' then rappel = false end
		if unit and unit.valid and unit.isMe and buff.name == 'EliseSpiderW' then wBuffOn = false end
		if unit and unit.valid and unit.isMe and buff.name == 'fallenonetarget' then karthusUlt = false end 
		if unit and unit.valid and unit.isMe and buff.name == 'fizzmarinerdoombomb' then fizzUlt = false end
		if unit and unit.valid and unit.isMe and buff.name == 'zedulttargetmark' then zedUlt = false end  
		if unit and unit.valid and unit.isMe and buff.name == 'SoulShackles' then morganaUlt = false end
		if unit and unit.valid and unit.isMe and buff.name == 'TimeBomb' then zileanBomb = false end
		if unit and unit.team ~= myHero.team and unit.valid and not unit.isMe and buff.name == 'MonkeyKingSpinToWin' then monkeyKingUlt = false monkeyObj = nil  end
		if unit and unit.valid and unit.isMe and buff.name == 'varusrsecondary' then varusUlt = false end
		if unit and unit.valid and not unit.isMe and unit.team == myHero.team and buff.name == 'fioraduelisthot' then fioraUlt = false fioraUltTarget = nil end
	end)
	--[[AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) 
		if unit and unit.valid and unit.isMe and buff.name == 'elisespidere' then qOn = false end
	end)]]
	AdvancedCallback:bind('OnGainAggro', function(unit)
		if ValidTarget(unit) and unit.type == "obj_AI_Minion" then haveAggro = true aggroUnit = unit end
	end)
	AdvancedCallback:bind('OnLoseAggro', function(unit)
		if ValidTarget(unit) and unit.type == "obj_AI_Minion" then haveAggro = false aggroUnit = nil end
	end)
end
function LoadKillSpells()
	KillSpells = {
	   	{ charName = "Riven", 			spellName = "rivenizunablade"},
	   	{ charName = "MissFortune", 	spellName = "MissFortuneBulletTime"},
	   	{ charName = "Amumu", 			spellName = "CurseoftheSadMummy"}, -- need to add distance check
	   	{ charName = "Cassiopeia", 		spellName = "CassiopeiaPetrifyingGaze"},
	   	{ charName = "Sona", 			spellName = "SonaCrescendo"}, -- need to add distance check
	   	{ charName = "Zyra", 			spellName = "ZyraBrambleZone"},
	   	{ charName = "Leona", 			spellName = "LeonaSolarFlare"},
	   	{ charName = "Vi", 				spellName = "ViR"},
	   	{ charName = "Malphite", 		spellName = "UFSlash"},
	   	{ charName = "Fiora", 			spellName = "FioraDance"},
	   	{ charName = "Lissandra", 		spellName = "LissandraR"},
	   	{ charName = "Nautilus", 		spellName = "NautilusGrandLine"},
	   	{ charName = "Nami", 			spellName = "NamiR"},
	   	{ charName = "Hecarim", 		spellName = "HecarimUlt"},
	   	{ charName = "Orianna", 		spellName = "OrianaDetonateCommand"},
	   	{ charName = "Sejuani", 		spellName = "SejuaniGlacialPrisonStart"},
	   	{ charName = "Varus", 			spellName = "VarusR"},
	   	{ charName = "Gragas", 			spellName = "GragasExplosiveCask"},
	   	{ charName = "Ziggs", 			spellName = "ZiggsR"},
	   	{ charName = "XinZhao", 		spellName = "XenZhaoParry"},
	   	{ charName = "Lux", 			spellName = "LuxMaliceCannon"},
	   	{ charName = "Brand", 			spellName = "BrandWildfire"},
	   	{ charName = "Katarina",		spellName = "KatarinaR"},
	   	{ charName = "Veigar",			spellName = "VeigarPrimordialBurst"},
	   	{ charName = "Shyvana",			spellName = "ShyvanaTransformCast"},
	   	{ charName = "Caitlyn",			spellName = "CaitlynAceintheHole"},
	}
end
function LoadDangerousSpells()
	DangerousSpells = {
	   	{ charName = "Gragas", 			spellName = "GragasExplosiveCask"},
	   	{ charName = "Ziggs", 			spellName = "ZiggsR"},
	   	{ charName = "XinZhao", 		spellName = "XenZhaoParry"},
	   	{ charName = "Lux", 			spellName = "LuxMaliceCannon"},
	   	{ charName = "Brand", 			spellName = "BrandWildfire"},
	   	{ charName = "Katarina",		spellName = "KatarinaR"},
	   	{ charName = "Veigar",			spellName = "VeigarPrimordialBurst"},
	   	{ charName = "Shyvana",			spellName = "ShyvanaTransformCast"},
	   	{ charName = "Caitlyn",		spellName = "CaitlynAceintheHole"},
	}
end
function LoadExtremeSpells()
	ExtremeSpells = {
	   	{ charName = "MissFortune", 	spellName = "MissFortuneBulletTime"},
	   	{ charName = "Annie", 			spellName = "InfernalGuardian"},
	   	{ charName = "Amumu", 			spellName = "CurseoftheSadMummy"}, -- need to add distance check
	   	{ charName = "Cassiopeia", 		spellName = "CassiopeiaPetrifyingGaze"},
	   	{ charName = "Sona", 			spellName = "SonaCrescendo"}, -- need to add distance check
	   	{ charName = "Zyra", 			spellName = "ZyraBrambleZone"},
	   	{ charName = "Leona", 			spellName = "LeonaSolarFlare"},
	   	{ charName = "Vi", 				spellName = "ViR"},
	   	{ charName = "Malphite", 		spellName = "UFSlash"},
	   	{ charName = "Fiora", 			spellName = "FioraDance"},
	   	{ charName = "Lissandra", 		spellName = "LissandraR"},
	   	{ charName = "Nautilus", 		spellName = "NautilusGrandLine"},
	   	{ charName = "Nami", 			spellName = "NamiR"},
	   	{ charName = "Hecarim", 		spellName = "HecarimUlt"},
	   	{ charName = "Orianna", 		spellName = "OrianaDetonateCommand"},
	   	{ charName = "Sejuani", 		spellName = "SejuaniGlacialPrisonStart"},
	   	{ charName = "Varus", 			spellName = "VarusR"},
	}
end
function OnTick()
	if not myHero.dead then
		if SPIDEREREADY and Config.rappel.rappelKill or Config.rappel.rappelDangerous or Config.rappel.rappelExtreme then  
			_G.SpiderCanDodge = true 
		else
			_G.SpiderCanDodge = false
		end
		if not evadeeLoaded then
			checkEvadeeLoaded()
		end
		if not rappel and not HUMANEREADY then _G.SpiderWomanDodging = false end
		RREADY = (myHero:CanUseSpell(_R) == READY)
		cooldownTracker()
		rappelCheck()
		if SummonerSpells ~= nil then
			if ignite ~= nil and Config.SummonerSpells.Ignite.useIgnite then
				IREADY = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
			end
			if smite ~= nil and Config.SummonerSpells.Smite.useSmite then
				SMITEREADY = (smite ~= nil and myHero:CanUseSpell(smite) == READY)
				smiteCheck()
			end
		end
		removeObjects()
		checkObjects()
		checkMana()
		checkKillRange()
		checkParamInfo()
		getPriority()
		getTarget()
		checkTowerShot()
		if Config.combatKeys.harass or Config.combatKeys.harassToggle and not Config.combatKeys.teamFight then
			harassKey()
		end
		if Config.OrbWalk.champOrbWalk.moveToMouse or Config.OrbWalk.champOrbWalk.AA then
			orbWalk()
		end
		if (Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) and not Config.combatKeys.teamFight and not Config.combatKeys.harass and not Config.combatKeys.harassToggle then
			farmKey()
		end
		if Config.Farm.jungleFarm.jungleFarming then
			jungleFarm()
		end
		if Config.Items.ADItems.useADItems and Config.Items.ADItems.useMuramana and (_G.Activator == false or _G.Activator == nil) then
		    Muramana()
		end
	end
end
function checkTowerShot()
	if towerShooting and GetTickCount()>towerShotTimer then
		CastSpiderEDefensive()
	end
end
function checkEvadeeLoaded()
	if _G.Evadeee_Loaded then
		LoadEvadeeeMenu()
	elseif _G.Evadeee_Loaded == nil and not evadeeNotLoaded then
		print("Evadee not found, please use Evadeee for better support")
		evadeeNotLoaded = true
	end	
end
function checkObjects( )
	checkViktorStorm()
	checkCrowStorm()
	checkWildFire()
end
function removeObjects()
	removeMinions()
	removeDoomBall()
	removeViktorStorm()
	removeCrowStorm()
	removeWildFire()
end
function checkViktorStorm()
	if next(viktorStorm) ~= nil and SPIDEREREADY then
		for i, storm in pairs(viktorStorm) do
			if storm.valid and storm and GetDistance(storm)<=450 then
				CastSpiderEDefensive()
			end
		end
	end
end
function checkCrowStorm()
	if next(crowStorm) ~= nil and SPIDEREREADY then
		for i, storm in pairs(crowStorm) do
			if storm.valid and storm and GetDistance(storm)<=600 then
				CastSpiderEDefensive()
			end
		end
	end
end
function checkWildFire()
	if next(wildFire) ~= nil and SPIDEREREADY then
		for i, brandUlt in pairs(wildFire) do
			if brandUlt.valid and brandUlt and GetDistance(brandUlt)<=350 then
				CastSpiderEDefensive()
			end
		end
	end
end
function cooldownTracker()
	RCD = myHero:GetSpellData(_R).totalCooldown
	-----OnGainBuff & OnLoseBuff Callback ------
	if rappel then
		ESCDTimer = GetTickCount() + ESCD*1000
	end
	---------------------------------------------
	if isHuman() then
		-------------Human Cooldown tracker--------
		QCD = myHero:GetSpellData(_Q).totalCooldown
		WCD = myHero:GetSpellData(_W).totalCooldown
		ECD = myHero:GetSpellData(_E).totalCooldown
		-------------Human Skill Ready-------------
		HUMANQREADY = myHero:CanUseSpell(_Q) == READY
		HUMANWREADY = myHero:CanUseSpell(_W) == READY
		HUMANEREADY = myHero:CanUseSpell(_E) == READY
		if not HUMANQLEARNED and HUMANQREADY then
			HUMANQLEARNED = true
			SPIDERQLEARNED = true
		elseif not HUMANWLEARNED and HUMANWREADY then
			HUMANWLEARNED = true
			SPIDERWLEARNED = true
		elseif not HUMANELEARNED and HUMANEREADY then
			HUMANELEARNED = true
			SPIDERELEARNED = true
		end
		-------------Spider Skill Ready-------------
		SPIDERQREADY = GetTickCount() > QSCDTimer and RREADY and SPIDERQLEARNED
		SPIDERWREADY = GetTickCount() > WSCDTimer and RREADY and SPIDERWLEARNED
		SPIDEREREADY = GetTickCount() > ESCDTimer and RREADY and SPIDERELEARNED
	else
		-------------Spider Cooldown tracker--------
		QSCD = myHero:GetSpellData(_Q).totalCooldown
		WSCD = myHero:GetSpellData(_W).totalCooldown
		ESCD = myHero:GetSpellData(_E).totalCooldown
		-------------Human Skill Ready-------------
		HUMANQREADY = GetTickCount() > QCDTimer and RREADY and HUMANQLEARNED
		HUMANWREADY = GetTickCount() > WCDTimer and RREADY and HUMANWLEARNED
		HUMANEREADY = GetTickCount() > ECDTimer and RREADY and HUMANELEARNED
		-------------Spider Skill Ready-------------
		SPIDERQREADY = myHero:CanUseSpell(_Q) == READY
		SPIDERWREADY = myHero:CanUseSpell(_W) == READY
		SPIDEREREADY = myHero:CanUseSpell(_E) == READY
		if not SPIDERQLEARNED and SPIDERQREADY then
			SPIDERQLEARNED = true
			HUMANQLEARNED = true
		elseif not SPIDERWLEARNED and SPIDERWREADY then
			SPIDERWLEARNED = true
			HUMANWLEARNED = true
		elseif not SPIDERELEARNED and SPIDEREREADY then
			SPIDERELEARNED = true
			HUMANELEARNED = true
		end
	end
end
function checkMana()
	if isHuman() then
		qMana = myHero:GetSpellData(_Q).mana
		wMana = myHero:GetSpellData(_W).mana
		eMana = myHero:GetSpellData(_E).mana
	end
	--print(myHero:GetSpellData(_Q).mana)
end
function checkKillRange()
	if HUMANEREADY then
		killRange = rangeE
	elseif HUMANWREADY then
		killRange = rangeW
	elseif SPIDEREREADY then
		killRange = rangeES
	elseif HUMANQREADY then
		killRange = rangeQ
	elseif SPIDERQREADY then
		killRange = rangeQS
	elseif SPIDERWREADY then
		killRange = rangeWS
	end
end
function isHuman()
	if myHero:GetSpellData(_Q).name == "EliseHumanQ" then
		return true
	else
		return false
	end
end
function CastQUniversal(target)
	if ValidTarget(target) and Config.Farm.jungleFarm.jungleFarmQ and ((not spiderlings and Config.Farm.jungleFarm.jungleFarmAggro) or not Config.Farm.jungleFarm.jungleFarmAggro) then
		if isHuman() and (Config.Farm.jungleFarm.jungleHuman or Config.Farm.jungleFarm.jungleMix) then
			if (SMITEQHREADY and GetDistance(target)<=rangeQ and qMana<=myHero.mana) and (SMITEQSREADY and GetDistance(target)<=rangeQS) then
				CastQ(target)
				CastR(target)
				CastQ(target)
			elseif SMITEQHREADY and GetDistance(target)<=rangeQ then
				CastQ(target)
			elseif SMITEQSREADY and GetDistance(target)<=rangeQS then
				CastR(target)
				CastQ(target)
			end
		elseif not isHuman() and (Config.Farm.jungleFarm.jungleSpider or Config.Farm.jungleFarm.jungleMix) then
			if (SMITEQSREADY and GetDistance(target)<=rangeQS) and (SMITEQHREADY and GetDistance(target)<=rangeQ and qMana<=myHero.mana) then
				CastQ(target)
				CastR(target)
				CastQ(target)
			elseif SMITEQSREADY and GetDistance(target)<=rangeQS then
				CastQ(target)
			elseif SMITEQHREADY and GetDistance(target)<=rangeQ then
				CastR(target)
				CastQ(target)
			end
		end
	end
end
function QLvl()
	return myHero:GetSpellData(_Q).level
end
function AP()
	return math.ceil(myHero.ap)
end
function smiteCheck()
	SMITEQSREADY = false
	SMITEQHREADY = false
	if SMITEREADY or smiteRappel then
		if myHero.level < 5 then
			SmiteDmg = math.max(20*myHero.level+370)
		elseif myHero.level < 10 then
			SmiteDmg = math.max(30*myHero.level+330)
		elseif myHero.level < 15 then
			SmiteDmg = math.max(40*myHero.level+240)
		else
			SmiteDmg = math.max(50*myHero.level+100)
		end
	else
		SmiteDmg = 0
	end
	if SPIDERQREADY then
		SMITEQSREADY = true
	end
	if HUMANQREADY and qMana<=myHero.mana then
		SMITEQHREADY = true
	end
	----------------------------------------------------------Rappel Smite------------------------------------------------------
	if Config.rappel.rappelSmite then
		if SMITEREADY then
			smiteRappel = true	
		end
		if not rappel then
			if SPIDEREREADY then
				CastSpiderEDefensive()
			end
			if next(jungleMinions)~=nil then
				for i, obj in pairs(jungleMinions) do
					if obj and obj.valid then
						if SmiteDmg>=obj.health and SMITEREADY then 
							CastSpell(smite, obj)
							smiteRappel = false
						else
							if GetDistance(obj)<= smiteRange and smiteRappel then
								if SMITEQSREADY and GetDistance(obj)<=rangeQS then
									local mobHealth = ((obj.maxHealth-obj.health)/100)
									local dmg = myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(8+.03*AP())*mobHealth)
									if dmg>50*QLvl()+10+(60*QLvl()) then
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(60*QLvl()))
									else
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(8+.03*AP())*mobHealth)
									end
								end
								if SMITEQHREADY and GetDistance(obj)<=rangeQ and qMana<=myHero.mana then
									local mobHealth = (obj.health/100)
									local dmg = myHero:CalcMagicDamage(obj, (40*QLvl())+(8+.03*AP())*mobHealth)
									if dmg>40*QLvl()+60*QLvl() then
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (40*QLvl())+(60*QLvl()))
									else
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (40*QLvl())+(8+.03*AP())*mobHealth)
									end
								end
								---------------------------------------------------Epic Mobs & Large Mobs---------------------------------------------------
								if EpicCreep(obj) or LargeCreep(obj) then
									if SmiteDmg>=obj.health and smiteRappel then
										CastQUniversal(obj)
										if not SMITEQSREADY and not SMITEQHREADY and SMITEREADY then
											CastSpell(smite, obj)
											smiteRappel = false
										end
									elseif SmiteDmg>=obj.health and not SMITEREADY then
										CastQUniversal(obj)
									end
								end
							end
						end
					end
				end
			end
			-------------------------------------------------Rappel Form--------------------------------------- 
		else
			if next(jungleMinions)~=nil then
				for i, obj in pairs(jungleMinions) do
					if obj and obj.valid then
						if GetDistance(obj)<= rangeES then
							local spiderQAvailable = GetTickCount() > QSCDTimer and SPIDERQLEARNED
							local humanQAvailable = GetTickCount() > QCDTimer and HUMANQLEARNED
							if spiderQAvailable then
								local mobHealth = ((obj.maxHealth-obj.health)/100)
								local dmg = myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(8+.03*AP())*mobHealth)
								if dmg>50*QLvl()+10+(60*QLvl()) then
									SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(60*QLvl()))
								else
									SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(8+.03*AP())*mobHealth)
								end
							end
							if humanQAvailable and qMana<=myHero.mana then
								local mobHealth = (obj.health/100)
								local dmg = myHero:CalcMagicDamage(obj, (40*QLvl())+(8+.03*AP())*mobHealth)
								if dmg>40*QLvl()+60*QLvl() then
									SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (40*QLvl())+(60*QLvl()))
								else
									SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (40*QLvl())+(8+.03*AP())*mobHealth)
								end
							end
							if SmiteDmg>=obj.health then
								if EpicCreep(obj) then
									CastSpell(_E, obj)
								elseif LargeCreep(obj) then
									CastSpell(_E, obj)
								end
							end
						end
					end
				end
			end
		end
		-----------------------------------------------------------------------Use Smite (basic smite) --------------------------------------------------------------------
	elseif Config.SummonerSpells.Smite.useSmite then
		if next(jungleMinions)~=nil then
			for i, obj in pairs(jungleMinions) do
				if obj and obj.valid then
					if GetDistance(obj)<=smiteRange then
						-------------------------------Champ + Smite -----------------------------------
						if Config.SummonerSpells.Smite.ChampSmite then
							if SmiteDmg>=obj.health and SMITEREADY then 
								if SmallCreep(obj) and Config.SummonerSpells.Smite.smiteSmall then
									CastSpell(smite, obj)
									smiteRappel = false
								elseif LargeCreep(obj) and Config.SummonerSpells.Smite.smiteLarge then
									CastSpell(smite, obj)
									smiteRappel = false
								elseif EpicCreep(obj) and Config.SummonerSpells.Smite.smiteEpic then
									CastSpell(smite, obj)
									smiteRappel = false
								end
							else
								if SMITEQSREADY and GetDistance(obj)<=rangeQS then
									local mobHealth = ((obj.maxHealth-obj.health)/100)
									local dmg = myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(8+.03*AP())*mobHealth)
									if dmg>50*QLvl()+10+(60*QLvl()) then
										--print(myHero:CalcMagicDamage(minion, (40*QLvl())+(60*QLvl())).." Capped")
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(60*QLvl()))
									else
										--print(myHero:CalcMagicDamage(minion, (40*QLvl())+(8+.03*AP())*mobHealth).." not capped")
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(8+.03*AP())*mobHealth)
									end
									--SmiteDmg = SmiteDmg + getDmg("QM", obj, myHero, 3)
								end
								if SMITEQHREADY and GetDistance(obj)<=rangeQ and qMana<=myHero.mana then
									local mobHealth = (obj.health/100)
									local dmg = myHero:CalcMagicDamage(obj, (40*QLvl())+(8+.03*AP())*mobHealth)
									if dmg>40*QLvl()+60*QLvl() then
										--print(myHero:CalcMagicDamage(minion, (40*QLvl())+(60*QLvl())).." Capped")
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (40*QLvl())+(60*QLvl()))
									else
										--print(myHero:CalcMagicDamage(minion, (40*QLvl())+(8+.03*AP())*mobHealth).." not capped")
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (40*QLvl())+(8+.03*AP())*mobHealth)
									end
								end
								---------------------------------------------------Epic Mobs---------------------------------------------------------
								if EpicCreep(obj) and Config.SummonerSpells.Smite.smiteEpic then
									if SmiteDmg>=obj.health and SMITEREADY then
										CastQUniversal(obj)
										if not SMITEQSREADY and not SMITEQHREADY and SMITEREADY then
											CastSpell(smite, obj)
											smiteRappel = false
										end
									elseif SmiteDmg>=obj.health and not SMITEREADY then
										CastQUniversal(obj)
									end	
								-------------------------------------------------Large Mobs---------------------------------------------------------
								elseif LargeCreep(obj) and Config.SummonerSpells.Smite.smiteLarge then
									if SmiteDmg>=obj.health and SMITEREADY then
										CastQUniversal(obj)
										if not SMITEQSREADY and not SMITEQHREADY and SMITEREADY then
											CastSpell(smite, obj)
											smiteRappel = false
										end
									elseif SmiteDmg>=obj.health and not SMITEREADY then
										CastQUniversal(obj)
									end	
								---------------------------------------------------Small Mobs---------------------------------------------------------
								elseif SmallCreep(obj) and Config.SummonerSpells.Smite.smiteSmall then
									if SmiteDmg>=obj.health and SMITEREADY then
										CastQUniversal(obj)
										if not SMITEQSREADY and not SMITEQHREADY and SMITEREADY then
											CastSpell(smite, obj)
											smiteRappel = false
										end
									elseif SmiteDmg>=obj.health and not SMITEREADY then
										CastQUniversal(obj)
									end		
								end
							end 
						-----------------------------regular smite (no champ abilities) ----------------
						else
							if SmiteDmg>=obj.health and SMITEREADY then
								if EpicCreep(obj) and Config.SummonerSpells.Smite.smiteEpic then
									CastSpell(smite, obj)
									smiteRappel = false
								elseif LargeCreep(obj) and Config.SummonerSpells.Smite.smiteLarge then
									CastSpell(smite, obj)
									smiteRappel = false
								elseif SmallCreep(obj) and Config.SummonerSpells.Smite.smiteSmall then
									CastSpell(smite, obj)
									smiteRappel = false
								end
							end 
						end
					end
				end
			end
		end
	end
	------------------------------------------Smite Rappel Steal (auto rappel steal)-----------------------
	if Config.rappel.rappelSteal and SPIDEREREADY then
		if SMITEREADY then
			smiteRappel = true	
		end
		if not rappel then
			if next(jungleMinions)~=nil then
				for i, obj in pairs(jungleMinions) do
					if obj and obj.valid and EpicCreep(obj) then
						if SmiteDmg>=obj.health and SMITEREADY and EpicCreep(obj) then 
							CastSpell(smite, obj)
							smiteRappel = false
						else
							if GetDistance(obj)<= rangeES and smiteRappel and EpicCreep(obj) then
								if SMITEQSREADY then
									local mobHealth = ((obj.maxHealth-obj.health)/100)
									local dmg = myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(8+.03*AP())*mobHealth)
									if dmg>50*QLvl()+10+(60*QLvl()) then
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(60*QLvl()))
									else
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(8+.03*AP())*mobHealth)
									end
								end
								if SMITEQHREADY then
									local mobHealth = (obj.health/100)
									local dmg = myHero:CalcMagicDamage(obj, (40*QLvl())+(8+.03*AP())*mobHealth)
									if dmg>40*QLvl()+60*QLvl() then
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (40*QLvl())+(60*QLvl()))
									else
										SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (40*QLvl())+(8+.03*AP())*mobHealth)
									end
								end
								---------------------------------------------------Epic Mobs & Large Mobs---------------------------------------------------
								if EpicCreep(obj) then
									if SmiteDmg>=obj.health and smiteRappel and SMITEREADY then
										if SPIDEREREADY and GetDistance(obj)<=rangeES and GetDistance(obj)>=smiteRange then
											CastSpiderEDefensive()
										end
										CastQUniversal(obj)
										if not SMITEQSREADY and not SMITEQHREADY then
											CastSpell(smite, obj)
											smiteRappel = false
										end
									elseif SmiteDmg>=obj.health and not SMITEREADY then
										CastQUniversal(obj)
									end
								end
							end
						end
					end
				end
			end
			-------------------------------------------------Rappel Form--------------------------------------- 
		else
			if next(jungleMinions)~=nil then
				for i, obj in pairs(jungleMinions) do
					if obj and obj.valid then
						if GetDistance(obj)<= rangeES and smiteRappel then
							local spiderQAvailable = GetTickCount() > QSCDTimer and SPIDERQLEARNED
							local humanQAvailable = GetTickCount() > QCDTimer and HUMANQLEARNED
							if spiderQAvailable then
								local mobHealth = ((obj.maxHealth-obj.health)/100)
								local dmg = myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(8+.03*AP())*mobHealth)
								if dmg>50*QLvl()+10+(60*QLvl()) then
									SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(60*QLvl()))
								else
									SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (50*QLvl()+10)+(8+.03*AP())*mobHealth)
								end
							end
							if humanQAvailable and qMana<=myHero.mana then
								local mobHealth = (obj.health/100)
								local dmg = myHero:CalcMagicDamage(obj, (40*QLvl())+(8+.03*AP())*mobHealth)
								if dmg>40*QLvl()+60*QLvl() then
									SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (40*QLvl())+(60*QLvl()))
								else
									SmiteDmg = SmiteDmg + myHero:CalcMagicDamage(obj, (40*QLvl())+(8+.03*AP())*mobHealth)
								end
							end
							if SmiteDmg>=obj.health then
								if EpicCreep(obj) then
									CastSpell(_E, obj)
								end
							end
						end
					end
				end
			end
		end
	end
	---------------------------------Smite Stun---------------------------------------------
	if Config.SummonerSpells.Smite.smiteStun and SMITEREADY and Config.combatKeys.teamFight then --Add config teamfight and check target is further then the minion
		local smiteMinion = minionSmiteStun()
		if ValidTarget(smiteMinion) and ValidTarget(newTarget) then
			if isHuman() then
				CastHumanE(newTarget)
			else
				CastR(newTarget)
				CastHumanE(newTarget)
			end
			CastSpell(smite, smiteMinion)
			smiteRappel = false
		end
	end
end
function EpicCreep(minion)
	if ValidTarget(minion) then
		if summonersRiftMap then
			if minion.name == "Worm12.1.1" or minion.name == "Dragon6.1.1" then
				return true
			else
				return false
			end
		elseif twistedTreeLineMap then
			if minion.name == "TT_Spiderboss8.1.1" then
				return true
			else
				return false
			end
		end
	else
		return
	end
end
function LargeCreep(minion)
	if ValidTarget(minion) then
		if summonersRiftMap then
			if minion.name == "AncientGolem1.1.1" or minion.name == "LizardElder4.1.1" or minion.name == "AncientGolem7.1.1" or minion.name == "LizardElder10.1.1" then
				return true
			else
				return false
			end
		elseif twistedTreeLineMap then
			return false
		end
	else
		return
	end
end
function SmallCreep(minion)
	if ValidTarget(minion) then
		if summonersRiftMap then
			if minion.name == "GiantWolf2.1.1" or minion.name == "Wraith3.1.1" or minion.name == "Golem5.1.2" or minion.name == "GiantWolf8.1.1" or minion.name == "Wraith9.1.1" or minion.name == "Golem11.1.2" or minion.name == "GreatWraith13.1.1" or minion.name == "GreatWraith14.1.1" then
				return true
			else
				return false
			end
		elseif twistedTreeLineMap then
			if minion.name == "TT_NWolf3.1.1" or minion.name == "TT_NWraith1.1.1" or minion.name == "TT_NGolem2.1.1" or minion.name == "TT_NWolf6.1.1" or minion.name == "TT_NWraith4.1.1" or minion.name == "TT_NGolem5.1.1" then
				return true
			else
				return false
			end
		end
	else
		return
	end
end
function minionSmiteStun()
	enemyMinions:update()
	local  smiteMinion = nil
	local Count = 0
	for i, minion in pairs(enemyMinions.objects) do
		if ValidTarget(minion) and GetDistance(minion)<=rangeE and HUMANEREADY and ValidTarget(newTarget) and (GetDistance(minion)<=GetDistance(newTarget)) and newTarget.type ~= "obj_AI_Minion" and SMITEREADY then
				-------------------------------------------------Prodict-----------------------------------------
			if Config.predictionChoice.useProdiction then
				local pred = tpE:GetPrediction(newTarget)
				if pred and GetDistance(pred)<=rangeE then
					local hitMinion = checkhitlinepass(myHero, pred, 100, rangeE, minion, 50)
					if hitMinion and GetDistance(minion)<=smiteRange and SmiteDmg>=minion.health then
						Count = Count + 1
						smiteMinion = minion
					end
				end
				-------------------------------------------------VPRED-------------------------------------------
			elseif Config.predictionChoice.useVpred then
				local pred = VP:GetCircularCastPosition(target, math.huge, 90, rangeE, 1400)
				if pred and GetDistance(pred)<=rangeE then
					local hitMinion = checkhitlinepass(myHero, pred, 100, rangeE, minion, 50)
					if hitMinion and GetDistance(minion)<=smiteRange and SmiteDmg>=minion.health then
						Count = Count + 1
						smiteMinion = minion
					end
				end
			end
		end
	end
	if Count > 1 then
		return nil
	else
		return smiteMinion
	end
end
function checkParamInfo()
	if ignite ~= nil then
		if Config.SummonerSpells.Ignite.KSMode then
			Config.SummonerSpells.Ignite.IgniteMode = "KS"
		elseif Config.SummonerSpells.Ignite.ComboMode then
			Config.SummonerSpells.Ignite.IgniteMode = "Combo"
		end
		if pIgniteKSMode then
			if Config.SummonerSpells.Ignite.ComboMode then
				pIgniteKSMode = false
				Config.SummonerSpells.Ignite.KSMode = false
			elseif not Config.SummonerSpells.Ignite.ComboMode and not Config.SummonerSpells.Ignite.KSMode then
				pIgniteKSMode = true
				Config.SummonerSpells.Ignite.KSMode = true
			end
		elseif pIgniteComboMode then
			if Config.SummonerSpells.Ignite.KSMode then
				pIgniteComboMode = false
				Config.SummonerSpells.Ignite.ComboMode = false
			elseif not Config.SummonerSpells.Ignite.ComboMode and not Config.SummonerSpells.Ignite.KSMode then
				pIgniteComboMode = true
				Config.SummonerSpells.Ignite.ComboMode = true
			end
		elseif Config.SummonerSpells.Ignite.KSMode then
			pIgniteKSMode = Config.SummonerSpells.Ignite.KSMode
			pIgniteComboMode = false
		elseif Config.SummonerSpells.Ignite.ComboMode then
			pIgniteKSMode = false
			pIgniteComboMode = Config.SummonerSpells.Ignite.ComboMode
		end
	end
	if Config.Items.APItems.apKSMode then
		Config.Items.APItems.APItemMode = "KS"
	elseif Config.Items.APItems.apComboMode then
		Config.Items.APItems.APItemMode = "Combo"
	elseif Config.Items.APItems.apBurstMode then
		Config.Items.APItems.APItemMode = "Burst"
	end
	if pAPKSMode then
		if Config.Items.APItems.apComboMode or Config.Items.APItems.apBurstMode then
			pAPKSMode = false
			Config.Items.APItems.apKSMode = false
		elseif not Config.Items.APItems.apKSMode and not Config.Items.APItems.apComboMode and not Config.Items.APItems.apBurstMode then
			pAPKSMode = true
			Config.Items.APItems.apKSMode = true
		end
	elseif pAPComboMode then
		if Config.Items.APItems.apKSMode or Config.Items.APItems.apBurstMode then
			pAPComboMode = false
			Config.Items.APItems.apComboMode = false
		elseif not Config.Items.APItems.apKSMode and not Config.Items.APItems.apComboMode and not Config.Items.APItems.apBurstMode then
			pAPComboMode = true
			Config.Items.APItems.apComboMode = true
		end
	elseif pAPBurstMode then
		if Config.Items.APItems.apKSMode or Config.Items.APItems.apComboMode then
			pAPBurstMode = false
			Config.Items.APItems.apBurstMode = false
		elseif not Config.Items.APItems.apKSMode and not Config.Items.APItems.apComboMode and not Config.Items.APItems.apBurstMode then
			pAPBurstMode = true
			Config.Items.APItems.apBurstMode = true
		end
	elseif Config.Items.APItems.apKSMode then
		pAPKSMode = Config.Items.APItems.apKSMode
		pAPComboMode = false
		pAPBurstMode = false
	elseif Config.Items.APItems.apComboMode then
		pAPComboMode = Config.Items.APItems.apComboMode
		pAPKSMode = false
		pAPBurstMode = false
	elseif Config.Items.APItems.apBurstMode then
		pAPBurstMode = Config.Items.APItems.apBurstMode
		pAPKSMode = false
		pAPComboMode = false
	end
	if Config.Items.ADItems.adKSMode then
		Config.Items.ADItems.ADItemMode = "KS"
	elseif Config.Items.ADItems.adComboMode then
		Config.Items.ADItems.ADItemMode = "Combo"
	elseif Config.Items.ADItems.adBurstMode then
		Config.Items.ADItems.ADItemMode = "Burst"
	end
	if pADKSMode then
		if Config.Items.ADItems.adComboMode or Config.Items.ADItems.adBurstMode then
			pADKSMode = false
			Config.Items.ADItems.adKSMode = false
		elseif not Config.Items.ADItems.adKSMode and not Config.Items.ADItems.adComboMode and not Config.Items.ADItems.adBurstMode then
			pADKSMode = true
			Config.Items.ADItems.adKSMode = true
		end
	elseif pADComboMode then
		if Config.Items.ADItems.adKSMode or Config.Items.ADItems.adBurstMode then
			pADComboMode = false
			Config.Items.ADItems.adComboMode = false
		elseif not Config.Items.ADItems.adKSMode and not Config.Items.ADItems.adComboMode and not Config.Items.ADItems.adBurstMode then
			pADComboMode = true
			Config.Items.ADItems.adComboMode = true
		end
	elseif pADBurstMode then
		if Config.Items.ADItems.adKSMode or Config.Items.ADItems.adComboMode then
			pADBurstMode = false
			Config.Items.ADItems.adBurstMode = false
		elseif not Config.Items.ADItems.adKSMode and not Config.Items.ADItems.adComboMode and not Config.Items.ADItems.adBurstMode then
			pADBurstMode = true
			Config.Items.ADItems.adBurstMode = true
		end
	elseif Config.Items.ADItems.adKSMode then
		pADKSMode = Config.Items.ADItems.adKSMode
		pADComboMode = false
		pADBurstMode = false
	elseif Config.Items.ADItems.adComboMode then
		pADComboMode = Config.Items.ADItems.adComboMode
		pADKSMode = false
		pADBurstMode = false
	elseif Config.Items.ADItems.adBurstMode then
		pADBurstMode = Config.Items.ADItems.adBurstMode
		pADKSMode = false
		pADComboMode = false
	end
	---------------------------------------Farm Form Mode ------------------------
	-- pHumanMode
	-- pSpiderMode
	-- pMixMode

	if Config.Farm.laneFarm.farmHuman then
		Config.Farm.laneFarm.farmFormMode = "Human"
	elseif Config.Farm.laneFarm.farmSpider then
		Config.Farm.laneFarm.farmFormMode = "Spider"
	elseif Config.Farm.laneFarm.farmMix then
		Config.Farm.laneFarm.farmFormMode = "Mixed"
	end
	if pHumanMode then
		if Config.Farm.laneFarm.farmSpider or Config.Farm.laneFarm.farmMix then
			pHumanMode = false
			Config.Farm.laneFarm.farmHuman = false
		elseif not Config.Farm.laneFarm.farmHuman and not Config.Farm.laneFarm.farmSpider and not Config.Farm.laneFarm.farmMix then
			pHumanMode = true
			Config.Farm.laneFarm.farmHuman = true
		end
	elseif pSpiderMode then
		if Config.Farm.laneFarm.farmHuman or Config.Farm.laneFarm.farmMix then
			pSpiderMode = false
			Config.Farm.laneFarm.farmSpider = false
		elseif not Config.Farm.laneFarm.farmHuman and not Config.Farm.laneFarm.farmSpider and not Config.Farm.laneFarm.farmMix then
			pSpiderMode = true
			Config.Farm.laneFarm.farmSpider = true
		end
	elseif pMixMode then
		if Config.Farm.laneFarm.farmHuman or Config.Farm.laneFarm.farmSpider then
			pMixMode = false
			Config.Farm.laneFarm.farmMix = false
		elseif not Config.Farm.laneFarm.farmHuman and not Config.Farm.laneFarm.farmSpider and not Config.Farm.laneFarm.farmMix then
			pMixMode = true
			Config.Farm.laneFarm.farmMix = true
		end
	elseif Config.Farm.laneFarm.farmHuman then
		pHumanMode = Config.Farm.laneFarm.farmHuman
		pSpiderMode = false
		pMixMode = false
	elseif Config.Farm.laneFarm.farmSpider then
		pSpiderMode = Config.Farm.laneFarm.farmSpider
		pHumanMode = false
		pMixMode = false
	elseif Config.Farm.laneFarm.farmMix then
		pMixMode = Config.Farm.laneFarm.farmMix
		pHumanMode = false
		pSpiderMode = false
	end
	--------------------------------------------Jungle Form Mode -----------------------------------------
	-- pJHumanMode
	-- pJSpiderMode
	-- pJMixMode

	if Config.Farm.jungleFarm.jungleHuman then
		Config.Farm.jungleFarm.jungleFormMode = "Human"
	elseif Config.Farm.jungleFarm.jungleSpider then
		Config.Farm.jungleFarm.jungleFormMode = "Spider"
	elseif Config.Farm.jungleFarm.jungleMix then
		Config.Farm.jungleFarm.jungleFormMode = "Mixed"
	end
	if pJHumanMode then
		if Config.Farm.jungleFarm.jungleSpider or Config.Farm.jungleFarm.jungleMix then
			pJHumanMode = false
			Config.Farm.jungleFarm.jungleHuman = false
		elseif not Config.Farm.jungleFarm.jungleHuman and not Config.Farm.jungleFarm.jungleSpider and not Config.Farm.jungleFarm.jungleMix then
			pJHumanMode = true
			Config.Farm.jungleFarm.jungleHuman = true
		end
	elseif pJSpiderMode then
		if Config.Farm.jungleFarm.jungleHuman or Config.Farm.jungleFarm.jungleMix then
			pJSpiderMode = false
			Config.Farm.jungleFarm.jungleSpider = false
		elseif not Config.Farm.jungleFarm.jungleHuman and not Config.Farm.jungleFarm.jungleSpider and not Config.Farm.jungleFarm.jungleMix then
			pJSpiderMode = true
			Config.Farm.jungleFarm.jungleSpider = true
		end
	elseif pJMixMode then
		if Config.Farm.jungleFarm.jungleHuman or Config.Farm.jungleFarm.jungleSpider then
			pJMixMode = false
			Config.Farm.jungleFarm.jungleMix = false
		elseif not Config.Farm.jungleFarm.jungleHuman and not Config.Farm.jungleFarm.jungleSpider and not Config.Farm.jungleFarm.jungleMix then
			pJMixMode = true
			Config.Farm.jungleFarm.jungleMix = true
		end
	elseif Config.Farm.jungleFarm.jungleHuman then
		pJHumanMode = Config.Farm.jungleFarm.jungleHuman
		pJSpiderMode = false
		pJMixMode = false
	elseif Config.Farm.jungleFarm.jungleSpider then
		pJSpiderMode = Config.Farm.jungleFarm.jungleSpider
		pJHumanMode = false
		pJMixMode = false
	elseif Config.Farm.jungleFarm.jungleMix then
		pJMixMode = Config.Farm.jungleFarm.jungleMix
		pJHumanMode = false
		pJSpiderMode = false
	end
	------------------------------Prediction Mode------------------------------------
	if pProdictionMode then
		if Config.predictionChoice.useVpred then
			pProdictionMode = false
			Config.predictionChoice.useProdiction = false
			predictionCheck = false
		elseif not Config.predictionChoice.useVpred and not Config.predictionChoice.useProdiction then
			pProdictionMode = true
			Config.predictionChoice.useProdiction = true
		end
	elseif pVpredictionMode then
		if Config.predictionChoice.useProdiction then
			pVpredictionMode = false
			Config.predictionChoice.useVpred = false
			predictionCheck = false
		elseif not Config.predictionChoice.useVpred and not Config.predictionChoice.useProdiction then
			pVpredictionMode = true
			Config.predictionChoice.useVpred = true
		end
	elseif Config.predictionChoice.useProdiction then
		pProdictionMode = Config.predictionChoice.useProdiction
		pVpredictionMode = false
	elseif Config.predictionChoice.useVpred then
		pVpredictionMode = Config.predictionChoice.useVpred
		pProdictionMode = false
	end
	if not predictionCheck then
		if Config.predictionChoice.useVpred then
			require "VPrediction"
			predictionCheck = true
		elseif Config.predictionChoice.useProdiction then
			predictionCheck = true
		end
		LoadVIPPrediction()
	end
end
function rappelCheck()
	if not SPIDEREREADY then return end
	--------------------------Ult Timer Checks----------------------------
	if Config.rappel.rappelDangerous or Config.rappel.rappelKill then
		if GetTickCount() > CaitlynUltTimer and caitlynUlting then -- dangerous
			CastSpiderEDefensive()
		elseif GetTickCount() > ziggsUltTimer and ziggsUlting then -- dangerous
			CastSpiderEDefensive()
		elseif GetTickCount() > brandUltTimer and brandUlting then -- dangerous
			CastSpiderEDefensive()
		elseif GetTickCount() > veigarUltTimer and veigarUlting then -- dangerous
			CastSpiderEDefensive()
		end
		if karthusUlt then -- dangerous
			if GetTickCount() > karthusUltTimer then
				CastSpiderEDefensive()
			end
		else
			karthusUltTimer = GetTickCount() + 2100
		end
		if zedUlt then -- dangerous
			CastSpiderEDefensive()
		end
	end
	if Config.rappel.rappelExtreme or Config.rappel.rappelKill then
		if GetTickCount() > viUltTimer and viUlting then -- extreme
			CastSpiderEDefensive()
		elseif GetTickCount() > fioraTimer and fioraUlting then --extreme
			CastSpiderEDefensive()
		elseif GetTickCount() > nautilusTimer and nautilusUlting then-- extreme
			CastSpiderEDefensive()
		elseif GetTickCount() > namiTimer and namiUlting then -- extreme
			CastSpiderEDefensive()
		end
		if zileanBomb then -- kill
			if GetTickCount() > zileanBombTimer then
				for i = 1, enemyHerosCount do
					local Enemy = enemyHeros[i].object
					if ValidTarget(Enemy) and Enemy.charName == "Zilean" then
						local dmg = getDmg("Q", Enemy, myHero, 3) + 150
						if dmg >= myHero.health then
							CastSpiderEDefensive()
						end
					end
				end
			end
		else
			zileanBombTimer = GetTickCount() + 2100
		end
		if morganaUlt then -- extreme
			if GetTickCount() > morganaUltTimer then
				CastSpiderEDefensive()
			end
		else
			morganaUltTimer = GetTickCount() + 2800
		end
		if fizzUlt then -- extreme
			CastSpiderEDefensive()
		elseif monkeyKingUlt and monkeyObj.valid and monkeyObj and GetDistance(monkeyObj)<=300 then --extreme
			CastSpiderEDefensive()
		elseif varusUlt then
			CastSpiderEDefensive()
		elseif fioraUlt and fioraUltTarget and GetDistance(fioraUltTarget)<=700 then -- extreme
			CastSpiderEDefensive()
		end
	end	
end
function getCooldown(spell)
    return myHero:GetSpellData(spell).currentCd
end
function getPriority()
	for i = 1, enemyHerosCount do
		local Priority = enemyHeros[i].priority
		Priority = Config["setPriority"..i]
	end
end
function getTarget()
	local currentTarget = nil
	local targetSelected = SelectedTarget()
	for i = 1, enemyHerosCount do
		local Enemy = enemyHeros[i].object
		local killMana = 0
		local pendingMana = 0
		local totalCooldownTime = 0
		if ValidTarget(Enemy) then
			local pdmg = getDmg("P", Enemy, myHero, 3)
			local qdmg = getDmg("Q", Enemy, myHero, 3)
			local wdmg = getDmg("W", Enemy, myHero, 3)
			local edmg = getDmg("E", Enemy, myHero, 3)
			local rdmg = getDmg("R", Enemy, myHero, 3)
			local qmdmg = getDmg("QM", Enemy, myHero, 3)
			local wmdmg = getDmg("WM", Enemy, myHero, 3)
			local emdmg = getDmg("EM", Enemy, myHero, 3)
			local ADdmg = getDmg("AD", Enemy, myHero, 3)
			local dfgdamage = (GetInventoryItemIsCastable(3128) and getDmg("DFG",Enemy,myHero) or 0) -- Deathfire Grasp
			local hxgdamage = (GetInventoryItemIsCastable(3146) and getDmg("HXG",Enemy,myHero) or 0) -- Hextech Gunblade
			local bwcdamage = (GetInventoryItemIsCastable(3144) and getDmg("BWC",Enemy,myHero) or 0) -- Bilgewater Cutlass
			local botrkdamage = (GetInventoryItemIsCastable(3153) and getDmg("RUINEDKING", Enemy, myHero) or 0) --Blade of the Ruined King
			local onhitdmg = (GetInventoryHaveItem(3057) and getDmg("SHEEN",Enemy,myHero) or 0) + (GetInventoryHaveItem(3078) and getDmg("TRINITY",Enemy,myHero) or 0) + (GetInventoryHaveItem(3100) and getDmg("LICHBANE",Enemy,myHero) or 0) + (GetInventoryHaveItem(3025) and getDmg("ICEBORN",Enemy,myHero) or 0) + (GetInventoryHaveItem(3087) and getDmg("STATIKK",Enemy,myHero) or 0) + (GetInventoryHaveItem(3209) and getDmg("SPIRITLIZARD",Enemy,myHero) or 0)
			local onspelldamage = (GetInventoryHaveItem(3151) and getDmg("LIANDRYS",Enemy,myHero) or 0) + (GetInventoryHaveItem(3188) and getDmg("BLACKFIRE",Enemy,myHero) or 0)
			local sunfiredamage = (GetInventoryHaveItem(3068) and getDmg("SUNFIRE",Enemy,myHero) or 0)
			local comboKiller = pdmg + qdmg + wdmg + edmg + rdmg + onhitdmg + onspelldamage + sunfiredamage + hxgdamage + bwcdamage + botrkdamage
			local killHim = pdmg + onhitdmg + onspelldamage + sunfiredamage + hxgdamage + bwcdamage + botrkdamage
			local currentDmg = pdmg + onhitdmg + onspelldamage + sunfiredamage + hxgdamage + bwcdamage + botrkdamage
			if IREADY and Config.SummonerSpells.Ignite.useIgnite then
				local idmg = getDmg("IGNITE",Enemy,myHero, 3)
				comboKiller = comboKiller + idmg
				killHim = killHim + idmg
				currentDmg = currentDmg + idmg
				if GetDistance(Enemy)< 600 then
					if idmg+qdmg+wdmg+edmg>=Enemy.health and Config.SummonerSpells.Ignite.ComboMode then
						CastSpell(ignite, Enemy)
					elseif idmg>=Enemy.health and Config.SummonerSpells.Ignite.KSMode then
						CastSpell(ignite, Enemy)
					end
				end
			end
			if HUMANQREADY then
				currentDmg = currentDmg + qdmg
				killMana = killMana + qMana
				if GetDistance(Enemy)<=rangeQ then
					killHim = killHim + qdmg
					if qdmg >=Enemy.health and not IsIgnited() and Config.KS.KsQ then
						table.insert(ksDamages, qdmg)
					end
				end
			else
            	pendingMana = pendingMana + qMana
           		totalCooldownTime = totalCooldownTime + (QCDTimer - GetTickCount())
			end
			if HUMANWREADY then
				currentDmg = currentDmg + wdmg
				killMana = killMana + wMana
				if GetDistance(Enemy)<=rangeW then
					killHim = killHim + wdmg
					if wdmg >=Enemy.health and not IsIgnited() and Config.KS.KsW then
						table.insert(ksDamages, wdmg)
					end
				end
			else
            	pendingMana = pendingMana + wMana
           		totalCooldownTime = totalCooldownTime + (WCDTimer - GetTickCount())
			end
			if HUMANEREADY then
				killMana = killMana + eMana
			else
            	pendingMana = pendingMana + eMana
           		totalCooldownTime = totalCooldownTime + (ECDTimer - GetTickCount())
			end
			if SPIDERQREADY then
				currentDmg = currentDmg + qmdmg
				if GetDistance(Enemy)<=rangeQS then
					killHim = killHim + qmdmg
					if qmdmg >=Enemy.health and not IsIgnited() and Config.KS.KsQSpider then
						table.insert(ksDamages, qmdmg)
					end
				end
			else
           		totalCooldownTime = totalCooldownTime + (QSCDTimer - GetTickCount())
			end
			if SPIDERWREADY then
				currentDmg = currentDmg + wmdmg
				if GetDistance(Enemy)<=rangeWS then
					killHim = killHim + wmdmg
				end
			else
           		totalCooldownTime = totalCooldownTime + (WSCDTimer - GetTickCount())
			end
			if SPIDEREREADY then
				currentDmg = currentDmg + emdmg
				if GetDistance(Enemy)<=rangeES then
					killHim = killHim + emdmg
				end
			else
           		totalCooldownTime = totalCooldownTime + (ESCDTimer - GetTickCount())
			end
			if next(ksDamages)~=nil then
				table.sort(ksDamages, function (a, b) return a<b end)
				local lowestKSDmg = ksDamages[1]
				if ksTimer == nil or GetTickCount()-ksTimer>=1000 then
					if qdmg == lowestKSDmg then
						CastQ(Enemy)
						ksTimer = GetTickCount()
					elseif wdmg == lowestKSDmg then
						CastHumanW(Enemy)
						ksTimer = GetTickCount()
					elseif qmdmg == lowestKSDmg then
						if isHuman() then
							CastSpell(_R)
						else
							CastQ(Enemy)
							ksTimer = GetTickCount()
						end
					end
				end
				table.clear(ksDamages)
				--[[if GetTickCount()-ksTimer<1000 then
					ignoreEnemy = true
				else
					ignoreEnemy = false
				end]]
			end
			if GetInventoryItemIsCastable(3128) then  -- DFG      
				comboKiller = comboKiller + dfgdamage + (comboKiller*0.2)
				killHim = killHim + dfgdamage + (killHim*0.2) 
				if GetInventoryItemIsCastable(3146) then -- Hxg
					comboKiller = comboKiller + (hxgdamage*0.2)
					killHim = killHim + (hxgdamage*0.2)
				end
				if GetInventoryItemIsCastable(3144) then -- bwc
					comboKiller = comboKiller + (bwcdamage*0.2)
					killHim = killHim + (bwcdamage*0.2)
				end
				if GetInventoryItemIsCastable(3153) then -- botrk
					comboKiller = comboKiller + (botrkdamage*0.2)
					killHim = killHim + (botrkdamage*0.2)
				end
		    end
		    enemyHeros[i].comboKillableDmg = comboKiller
			enemyHeros[i].hitDamage = currentDmg
			currentTarget = Enemy
			if currentTarget then
				local mpRegen = myHero.mpRegen
			    local manaNeeded = 0
			    local manaToSeconds = 0
			    if pendingMana>myHero.mana then
			        manaNeeded = math.floor(pendingMana-myHero.mana)
			        manaToSeconds = math.floor(manaNeeded/mpRegen) 
			    end
				if killHim >= currentTarget.health and (killMana<= myHero.mana or (manaToSeconds<=5)) then
					enemyHeros[i].killable = 3
					if GetDistance(currentTarget) <= killRange then
						if newTarget == nil then
							newTarget = currentTarget
							newTargetPriority = enemyHeros[i].priority
						elseif newTarget.health > killHim then
							newTarget = currentTarget
							newTargetPriority = enemyHeros[i].priority
						else
							local currentTargetDmg = currentTarget.health - killHim
							local newTargetDmg = newTarget.health - killHim
							if currentTargetDmg < newTargetDmg then
								newTarget = currentTarget
								newTargetPriority = enemyHeros[i].priority
							end
						end
					end
				elseif comboKiller >= currentTarget.health then
					enemyHeros[i].killable = 2
					if GetDistance(currentTarget) <= killRange and not targetSelected then
						if newTarget == nil then
							newTarget = currentTarget
							newTargetPriority = enemyHeros[i].priority
						elseif newTarget.health > comboKiller then
							newTarget = currentTarget
							newTargetPriority = enemyHeros[i].priority
						elseif newTarget.health> killHim then
							local currentTargetDmg = comboKiller/Config["setPriority"..i]

							local newTargetDmg = comboKiller/newTargetPriority
							if currentTargetDmg > newTargetDmg then
								newTarget = currentTarget
								newTargetPriority = enemyHeros[i].priority
							end
						end
					end
				else
					enemyHeros[i].killable = 1
					if GetDistance(currentTarget) <= killRange and not targetSelected then
						if newTarget == nil then
							newTarget = currentTarget
							newTargetPriority = enemyHeros[i].priority
						elseif newTarget.health > comboKiller then
							local currentTargetDmg = comboKiller/Config["setPriority"..i]
							local newTargetDmg = comboKiller/newTargetPriority
							if currentTargetDmg > newTargetDmg then
								newTarget = currentTarget
								newTargetPriority = enemyHeros[i].priority
							end
						end
					end	
				end
			end
		else
			killable = 0
		end
	end
	if ValidTarget(targetSelected) then
		newTarget = targetSelected
		local champSelected = false
		for i = 1, enemyHerosCount do
			local Enemy = enemyHeros[i].object
			if ValidTarget(Enemy) and Enemy == newTarget then
				champSelected = true
			else
				champSelected = false
			end
		end
		if Config.combatKeys.teamFight and ValidTarget(newTarget) then
			CastItems(newTarget)
			if GetDistance(newTarget)<=killRange then
		     	if isHuman() then
		       		if HUMANEREADY and eWillHit() and GetDistance(newTarget)<=rangeE and champSelected then
		       			CastHumanE(newTarget)
		       		elseif HUMANQREADY and GetDistance(newTarget)<=rangeQ then
		       			CastQ(newTarget)
		       		elseif HUMANWREADY and GetDistance(newTarget)<=rangeW and wWillHit() then
		       			CastHumanW(newTarget)
		       		elseif HUMANWREADY and SPIDERQREADY and GetDistance(newTarget)<=rangeQS then
		       			CastHumanWInverted(newTarget)
		       			CastR(newTarget)
		       			CastQ(newTarget)
		       		elseif SPIDERQREADY and GetDistance(newTarget)<=rangeQS then
		       			CastR(newTarget)
		       			CastQ(newTarget)
		       		elseif SPIDEREREADY and GetDistance(newTarget)<=rangeES and GetDistance(newTarget)>rangeQS and champSelected then
		       			CastR(newTarget)
		       			CastSpiderE(newTarget)
		       		end
		       	else
		       		if not rappel then
			       		if (HUMANEREADY and GetDistance(newTarget)<=rangeE and eMana+qMana<=myHero.mana) and eWillHit() and HUMANQREADY and (not wBuffOn or GetDistance(newTarget)>rangeQS) and champSelected then
			       			CastR(newTarget)
			       			CastHumanE(newTarget)
			       			CastQ(newTarget)
			       		elseif SPIDEREREADY and GetDistance(newTarget)<=rangeES and GetDistance(newTarget)>rangeQS and champSelected then
			       			CastSpiderE(newTarget)
			       		elseif SPIDERQREADY and GetDistance(newTarget)<=rangeQS then
			       			CastQ(newTarget)
			       		elseif SPIDERWREADY and GetDistance(newTarget)<=(myHero.range + GetDistance(myHero.minBBox)) then
			       			CastSpiderW(newTarget)
			       		elseif HUMANQREADY and GetDistance(newTarget)<=rangeQ and qMana<=myHero.mana and not wBuffOn then
			       			CastR(newTarget)
			       			CastQ(newTarget)
			       		end
			       	else
			       		CastSpiderE(newTarget)
			       	end
		       	end
			end
		end
    end
	CastSkills()
end
function CastSkills()
	if ValidTarget(newTarget) then
		if GetDistance(newTarget)>killRange then
			newTarget = nil
			newTargetPriority = 0
		else
			for i = 1, enemyHerosCount do
				local Enemy = enemyHeros[i].object
				local killable = enemyHeros[i].killable
				if ValidTarget(Enemy) then
					if newTarget == Enemy then
						if killable == 3 then
							KillHimTarget(newTarget)
						elseif killable == 2 then
							ComboKillerTarget(newTarget)
						elseif killable == 1 then
							HarassTarget(newTarget)
						end
					end
				end
			end
		end
	else
		newTarget = nil
		newTargetPriority = 0
	end
end
function SelectedTarget()
	local selectedPlayer = GetTarget()
	if ValidTarget(selectedPlayer) and (selectedPlayer.type =="obj_AI_Minion" or selectedPlayer.type == "obj_AI_Hero") and GetDistance(selectedPlayer)<=killRange then
		return selectedPlayer
	else
		return nil
	end
end
function OnCreateObj(obj)	
	if obj and obj.type == "obj_AI_Minion" and obj.valid then
		if twistedTreeLineMap then
			if obj.name == "TT_Spiderboss8.1.1" or obj.name == "TT_NWolf3.1.1" or obj.name == "TT_NWraith1.1.1" or  obj.name == "TT_NGolem2.1.1" or obj.name == "TT_NWolf6.1.1"
			or obj.name == "TT_NWraith4.1.1" or obj.name == "TT_NGolem5.1.1" then
				table.insert(jungleMinions, obj)
			end
		elseif summonersRiftMap then
			if obj.name == "Worm12.1.1" or obj.name == "Dragon6.1.1" or obj.name == "AncientGolem1.1.1" or obj.name == "GiantWolf2.1.1" or obj.name == "Wraith3.1.1"
			or obj.name == "LizardElder4.1.1" or obj.name == "Golem5.1.2" or obj.name == "AncientGolem7.1.1" or obj.name == "GiantWolf8.1.1" or obj.name == "Wraith9.1.1"
			or obj.name == "LizardElder10.1.1" or obj.name == "Golem11.1.2" or obj.name == "GreatWraith13.1.1" or obj.name == "GreatWraith14.1.1" then 
				table.insert(jungleMinions, obj)
			end
		end
		--if GetDistance(obj)<=500 then
			--print(obj.team)
		--end
		--[[if obj.team == myHero.team then 
			table.insert(allieMinions, obj)
		end
		if obj.team == TEAM_ENEMY then 
			table.insert(enemyMinions, obj)
		end	
		if obj.team == TEAM_NEUTRAL then
			table.insert(allJungleMinions, obj)
		end]]
	elseif obj.valid and obj and obj.name == "yomu_ring_red.troy" then
		table.insert(doomBall, obj)
	elseif obj and obj.valid and obj.name == "Viktor_ChaosStorm_red.troy" then
		table.insert(viktorStorm, obj)
	elseif obj and obj.valid and obj.name == "Crowstorm_red_cas.troy" then
		table.insert(crowStorm, obj)
	elseif obj and obj.valid and obj.name == "BrandWildfire_mis.troy" then
		table.insert(wildFire, obj)
	end
end
function removeMinions()
	--[[if next(allieMinions)~=nil then
		for i, obj in pairs(allieMinions) do
			if not obj.valid or obj.dead then
				table.remove(allieMinions, i)
			end
		end
	end
	if next(enemyMinions)~=nil then
		for i, obj in pairs(enemyMinions) do
			if not obj.valid or obj.dead then
				table.remove(enemyMinions, i)
			end
		end
	end]]
	if next(jungleMinions)~=nil then
		for i, obj in pairs(jungleMinions) do
			if not obj.valid or obj.dead then
				table.remove(jungleMinions, i)
			end
		end
	end
	--[[if next(allJungleMinions)~=nil then
		for i, obj in pairs(allJungleMinions) do
			if not obj.valid or obj.dead then
				table.remove(allJungleMinions, i)
			end
		end
	end]]
end
function removeDoomBall()
	if next(doomBall)~= nil then
		for i, obj in pairs(doomBall) do
			if not obj.valid then
				table.remove(doomBall, i)
			end
		end
	end
end
function removeViktorStorm()
	if next(viktorStorm)~= nil then
		for i, obj in pairs(viktorStorm) do
			if not obj.valid then
				table.remove(viktorStorm, i)
			end
		end
	end
end
function removeCrowStorm()
	if next(crowStorm)~= nil then
		for i, obj in pairs(crowStorm) do
			if not obj.valid then
				table.remove(crowStorm, i)
			end
		end
	end
end
function removeWildFire()
	if next(wildFire)~= nil then
		for i, obj in pairs(wildFire) do
			if not obj.valid then
				table.remove(wildFire, i)
			end
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
function harassKey()
	if ValidTarget(newTarget) then
		if isHuman() then
			if HUMANWREADY and GetDistance(newTarget)<=rangeW and wWillHit() then
				CastHumanW(newTarget)
			elseif HUMANQREADY and GetDistance(newTarget)<=rangeQ then
			 	CastQ(newTarget)
			end 
		else
			if SPIDERQREADY and GetDistance(newTarget)<=rangeQS then
	       			CastQ(newTarget)
       		elseif SPIDERWREADY and GetDistance(newTarget)<=(myHero.range + GetDistance(myHero.minBBox)) then
       			CastSpiderW(newTarget)
       		end 
		end
	end
end
function KillHimTarget(target) -- change this function
   if ValidTarget(target) and Config.combatKeys.teamFight then
    	CastItems(target)
     	if isHuman() then
       		if HUMANEREADY and eWillHit() and GetDistance(target)<=rangeE then
       			CastHumanE(target)
       		elseif HUMANQREADY and GetDistance(target)<=rangeQ then
       			CastQ(target)
       		elseif HUMANWREADY and GetDistance(target)<=rangeW and wWillHit() then
       			CastHumanW(target)
       		elseif HUMANWREADY and SPIDERQREADY and GetDistance(target)<=rangeQS then
       			CastHumanWInverted(target)
       			CastR(target)
       			CastQ(target)
       		elseif SPIDERQREADY and GetDistance(target)<=rangeQS then
       			CastR(target)
       			CastQ(target)
       		elseif SPIDEREREADY and GetDistance(target)<=rangeES and GetDistance(target)>rangeQS then
       			CastR(target)
       			CastSpiderE(target)
       		end
       	else
       		if not rappel then
	       		if (HUMANEREADY and GetDistance(target)<=rangeE and eMana+qMana<=myHero.mana) and eWillHit() and HUMANQREADY and (not wBuffOn or GetDistance(target)>rangeQS) then
	       			CastR(target)
	       			CastHumanE(target)
	       			CastQ(target)
	       		elseif SPIDEREREADY and GetDistance(target)<=rangeES and GetDistance(target)>rangeQS then
	       			CastSpiderE(target)
	       		elseif SPIDERQREADY and GetDistance(target)<=rangeQS then
	       			CastQ(target)
	       		elseif SPIDERWREADY and GetDistance(target)<=(myHero.range + GetDistance(myHero.minBBox)) then
	       			CastSpiderW(target)
	       		elseif HUMANQREADY and GetDistance(target)<=rangeQ and qMana<=myHero.mana and (not wBuffOn or GetDistance(target)>rangeQS) then
	       			CastR(target)
	       			CastQ(target)
	       		end
	       	else
	       		CastSpiderE(target)
	       	end
       	end
    end
end
function ComboKillerTarget(target)
	if ValidTarget(target) and Config.combatKeys.teamFight then
    	CastItems(target)
    	if isHuman() then
       		if HUMANEREADY and eWillHit() and GetDistance(target)<=rangeE then
       			CastHumanE(target)
       		elseif HUMANQREADY and GetDistance(target)<=rangeQ then
       			CastQ(target)
       		elseif HUMANWREADY and GetDistance(target)<=rangeW and wWillHit() then
       			CastHumanW(target)
       		elseif HUMANWREADY and SPIDERQREADY and GetDistance(target)<=rangeQS then
       			CastHumanWInverted(target)
       			CastR(target)
       			CastQ(target)
       		elseif SPIDERQREADY and GetDistance(target)<=rangeQS then
       			CastR(target)
       			CastQ(target)
       		elseif SPIDEREREADY and GetDistance(target)<=rangeES and GetDistance(target)>rangeQS and ((GetTickCount()>eStunTimer and not HUMANEREADY and hasLessDamage(target)) or not target.canMove or HUMANEREADY) then
       			CastR(target)
       			CastSpiderE(target)
       		end
       	else
       		if not rappel then
	       		if (HUMANEREADY and GetDistance(target)<=rangeE and eMana+qMana<=myHero.mana) and eWillHit() and HUMANQREADY and (not wBuffOn or GetDistance(target)>rangeQS) then
	       			CastR(target)
	       			CastHumanE(target)
	       			CastQ(target)
	       		elseif SPIDEREREADY and GetDistance(target)<=rangeES and GetDistance(target)>rangeQS and ((GetTickCount()>eStunTimer and not HUMANEREADY and hasLessDamage(target)) or not target.canMove or HUMANEREADY) then
	       			CastSpiderE(target)
	       		elseif SPIDERQREADY and GetDistance(target)<=rangeQS then
	       			CastQ(target)
	       		elseif SPIDERWREADY and GetDistance(target)<=(myHero.range + GetDistance(myHero.minBBox)) then
	       			CastSpiderW(target)
	       		elseif HUMANQREADY and GetDistance(target)<=rangeQ and qMana<=myHero.mana and (not wBuffOn and GetDistance(target)>rangeQS) then
	       			CastR(target)
	       			CastQ(target)
	       		end
	       	else
	       		CastSpiderE(target)	
	       	end
       	end
   end
end
function HarassTarget(target) --fixed rappel with dangerous spell(distance check), fix left click, human W to rappel?
	if ValidTarget(target) and Config.combatKeys.teamFight then
    	CastItems(target)
    	 if isHuman() then
       		if HUMANEREADY and eWillHit() and GetDistance(target)<=rangeE then
       			CastHumanE(target)
       		elseif HUMANQREADY and GetDistance(target)<=rangeQ then
       			CastQ(target)
       		elseif HUMANWREADY and GetDistance(target)<=rangeW and wWillHit() then
       			CastHumanW(target)
       		elseif HUMANWREADY and SPIDERQREADY and GetDistance(target)<=rangeQS then
       			CastHumanWInverted(target)
       			CastR(target)
       			CastQ(target)
       		elseif SPIDERQREADY and GetDistance(target)<=rangeQS then
       			CastR(target)
       			CastQ(target)
       		elseif SPIDEREREADY and GetDistance(target)<=rangeES and GetDistance(target)>rangeQS and ((GetTickCount()>eStunTimer and not HUMANEREADY and hasLessDamage(target)) or not target.canMove or HUMANEREADY) then
       			CastR(target)
       			CastSpiderE(target)
       		end
       	else
       		if not rappel then
	       		if (HUMANEREADY and GetDistance(target)<=rangeE and eMana+qMana<=myHero.mana) and eWillHit() and HUMANQREADY and (not wBuffOn or GetDistance(target)>rangeQS) then
	       			CastR(target)
	       			CastHumanE(target)
	       			CastQ(target)
	       		elseif SPIDEREREADY and GetDistance(target)<=rangeES and GetDistance(target)>rangeQS and ((GetTickCount()>eStunTimer and not HUMANEREADY and hasLessDamage(target)) or not target.canMove or HUMANEREADY) then
	       			CastSpiderE(target)
	       		elseif SPIDERQREADY and GetDistance(target)<=rangeQS then
	       			CastQ(target)
	       		elseif SPIDERWREADY and GetDistance(target)<=(myHero.range + GetDistance(myHero.minBBox)) then
	       			CastSpiderW(target)
	       		elseif HUMANQREADY and GetDistance(target)<=rangeQ and qMana<=myHero.mana and (not wBuffOn or GetDistance(target)>rangeQS) then
	       			CastR(target)
	       			CastQ(target)
	       		end
	       	else
	       		CastSpiderE(target)
	       	end
       	end
   end
end
function isWeaker(target)
	if ValidTarget(target) and myHero.health>target.health and not isSafe(target) and hasLessDamage(target) then
		return true
	else
		return false
	end
end
function isSafe(target)
	--not under tower
	-- only one enemy - Done
	local Count = 0
	for i =1, enemyHerosCount do
		local Enemy = enemyHeros[i].object
		if ValidTarget(Enemy) and Enemy ~= target and GetDistance(target, Enemy)<=1000 then
			Count = Count + 1
		end
	end
	if Count == 0 then
		return false

	else
		return true
	end
end
function hasLessDamage(target)	
	if ValidTarget(target) then
		-----------------MyHero Damages------------------------
		local pdmg = getDmg("P", target, myHero, 3)
		local qdmg = getDmg("Q", target, myHero, 3)
		local wdmg = getDmg("W", target, myHero, 3)
		local edmg = getDmg("E", target, myHero, 3)
		local rdmg = getDmg("R", target, myHero, 3)
		local qmdmg = getDmg("QM", target, myHero, 3)
		local wmdmg = getDmg("WM", target, myHero, 3)
		local emdmg = getDmg("EM", target, myHero, 3)
		local ADdmg = getDmg("AD", target, myHero, 3)
		local myHeroTotalDmg = ADdmg
		if HUMANQREADY then
			myHeroTotalDmg = myHeroTotalDmg + qdmg
		end
		if HUMANWREADY then
			myHeroTotalDmg = myHeroTotalDmg + wdmg
		end
		if HUMANEREADY then
			myHeroTotalDmg = myHeroTotalDmg + edmg
		end
		if SPIDERQREADY then
			myHeroTotalDmg = myHeroTotalDmg + qmdmg
		end
		if SPIDERWREADY then
			myHeroTotalDmg = myHeroTotalDmg + wmdmg
		end
		if SPIDEREREADY then
			myHeroTotalDmg = myHeroTotalDmg + emdmg
		end
		-----------------Target Damages-----------------------
		local targetQREADY = target:CanUseSpell(_Q) == READY
		local targetWREADY = target:CanUseSpell(_W) == READY
		local targetEREADY = target:CanUseSpell(_E) == READY
		local targetRREADY = target:CanUseSpell(_R) == READY
		local targetQdmg = getDmg("Q", myHero, target, 3)
		local targetWdmg = getDmg("W", myHero, target, 3)
		local targetEdmg = getDmg("E", myHero, target, 3)
		local targetRdmg = getDmg("R", myHero, target, 3)
		local targetADdmg = getDmg("AD", myHero, target, 3)
		local targetTotalDmg = targetADdmg
		if targetQREADY then
			targetTotalDmg = targetTotalDmg + targetQdmg
		end
		if targetWREADY then
			targetTotalDmg = targetTotalDmg + targetWdmg
		end
		if targetEREADY then
			targetTotalDmg = targetTotalDmg + targetEdmg
		end
		if targetRREADY then
			targetTotalDmg = targetTotalDmg + targetRdmg
		end
		if targetTotalDmg<myHero.health then --myHeroTotalDmg (replace)
			return true
		else
			return false
		end
	else
		return false
	end
end
function CastQ(target) -- both human and spider
	if not HUMANQREADY and not SPIDERQREADY then return end
	if isHuman() and not Config.skillActivation.humanQ then 
		return 
	elseif not isHuman() and not Config.skillActivation.spiderQ then
		return
	end
	if ValidTarget(target) then
		if isHuman() then
			if GetDistance(target) <= rangeQ and HUMANQREADY then
				CastSpell(_Q, target)
			end
		else
			if GetDistance(target) <= rangeQS and SPIDERQREADY then
				CastSpell(_Q, target)
			end
		end
	end
end
function CastHumanW(target)
	if not HUMANWREADY or not Config.skillActivation.humanW then return end
	if ValidTarget(target) then	
		-------------------------------------------------Prodict-----------------------------------------
		if Config.predictionChoice.useProdiction then
			local pred = tpW:GetPrediction(target)
			if pred and not wcol:GetMinionCollision(myHero, pred) then
				CastSpell(_W, pred.x, pred.z)
			end
		-------------------------------------------------VPRED-------------------------------------------
		elseif Config.predictionChoice.useVpred then
			local WPred = VP:GetCircularCastPosition(target, math.huge, 150, rangeW, 1400)
			if WPred and GetDistance(WPred)<=rangeW and not wcol:GetMinionCollision(myHero, WPred) then
				CastSpell(_W, WPred.x, WPred.z)
			end
		end
	end
end
function wWillHit()
	if not HUMANWREADY or not Config.skillActivation.humanW then return false end
	if ValidTarget(newTarget) then
		

		-------------------------------------------------Prodict-----------------------------------------
		if Config.predictionChoice.useProdiction then
			local pred = tpW:GetPrediction(newTarget)
			if pred and not wcol:GetMinionCollision(myHero, pred) then
				return true
			else 
				return false
			end
		-------------------------------------------------VPRED-------------------------------------------
		elseif Config.predictionChoice.useVpred then
			local WPred = VP:GetCircularCastPosition(newTarget, math.huge, 150, rangeW, 1400)
			if WPred and GetDistance(WPred)<=rangeW and not ecol:GetMinionCollision(myHero, WPred) then
				return true
			else 
				return false
			end
		end 
	else
		return false
	end
end
function CastHumanWInverted(target)
	if not HUMANWREADY or not Config.skillActivation.humanW then return end
	if ValidTarget(target) then
		local pos = Vector(myHero.visionPos.x, myHero.visionPos.y, myHero.visionPos.z)
		local HeroPos = Vector(myHero.x, myHero.y, myHero.z)		
		local targetPos = HeroPos +(HeroPos-pos)*(400/GetDistance(pos))
		if targetPos then
			CastSpell(_W, targetPos.x, targetPos.z)	
		end
	end
end
function CastSpiderW(target)
	if not SPIDERWREADY or not Config.skillActivation.spiderW then return end
	if ValidTarget(target) then
		if GetDistance(target) <= rangeWS and SPIDERWREADY then
			CastSpell(_W)
		end
	end
end
function CastHumanE(target)
	if not HUMANEREADY or not Config.skillActivation.humanE then return end
	if ValidTarget(target) then
		-------------------------------------------------Prodict-----------------------------------------
		if Config.predictionChoice.useProdiction then
			local pred = tpE:GetPrediction(target)
			if pred and not ecol:GetMinionCollision(myHero, pred) then
				CastSpell(_E, pred.x, pred.z)
			end
		-------------------------------------------------VPRED-------------------------------------------
		elseif Config.predictionChoice.useVpred then
			local EPred = VP:GetCircularCastPosition(target, math.huge, 90, rangeE, 1400)
			if EPred and GetDistance(EPred)<=rangeE and not ecol:GetMinionCollision(myHero, EPred) then
				CastSpell(_E, EPred.x, EPred.z)
			end
		end
	end
end
function eWillHit()
	if not HUMANEREADY or not Config.skillActivation.humanE then return false end
	if ValidTarget(newTarget) then	
		-------------------------------------------------Prodict-----------------------------------------
		if Config.predictionChoice.useProdiction then
			local pred = tpE:GetPrediction(newTarget)
			if pred and not ecol:GetMinionCollision(myHero, pred) then
				return true
			else
				return false
			end
		-------------------------------------------------VPRED-------------------------------------------
		elseif Config.predictionChoice.useVpred then
			local EPred = VP:GetCircularCastPosition(newTarget, math.huge, 90, rangeE, 1400)
			if EPred and GetDistance(EPred)<=rangeE and not ecol:GetMinionCollision(myHero, EPred) then
				return true
			else
				return false
			end
		end
	else
		return false
	end
end
function CastSpiderE(target)
	if (not SPIDEREREADY and not rappel) or not Config.skillActivation.spiderE then return end
	if ValidTarget(target) then
		if GetDistance(target) <= rangeES and GetDistance(target)>rangeQS then
			if not rappel then
				CastSpell(_E, target)
			else
				CastSpell(_E, target)
			end
		end
	end
end
function CastSpiderEDefensive()
	if not SPIDEREREADY or not Config.skillActivation.spiderE then return end
	_G.SpiderWomanDodging = true
	if isHuman() then
		CastSpell(_R)
		CastSpell(_E)
	else
		CastSpell(_E)
		caitlynUlting = false
		ziggsUlting = false
		viUlting = false
		fioraUlting = false
		nautilusUlting = false
		namiUlting = false
		brandUlting = false
		veigarUlting = false
		towerShooting = false
	end
end
function CastR(target)
	if not RREADY or not Config.skillActivation.UseR then return end
	if ValidTarget(target) then
		if GetDistance(target) <= killRange and RREADY then
			CastSpell(_R)
		end
	end
end
function CastItems(target)
	if not ValidTarget(target) then
		return
	else
		----------------------------------------------Ap Items---------------------------------------------------
		if Config.Items.APItems.useAPItems then
			----------------------------------------Bilgewater Cutlass-----------------------------------
			if Config.Items.APItems.useBilgewaterCutlass and GetDistance(target)<=450 then
				if Config.Items.APItems.apComboMode or Config.Items.APItems.apKSMode then
					local qdmg = getDmg("Q", target, myHero, 3)
					local wdmg = getDmg("W", target, myHero, 3)
					local edmg = getDmg("E", target, myHero, 3)
					local bwcdamage = (GetInventoryItemIsCastable(3144) and getDmg("BWC",target,myHero) or 0) 
					if Config.Items.APItems.apKSMode and bwcdamage>target.health then
						CastItem(3144, target)
					elseif Config.Items.APItems.apComboMode and bwcdamage + qdmg + wdmg + edmg > target.health then
						CastItem(3144, target)
					end
				elseif Config.Items.APItems.apBurstMode then
					CastItem(3144, target)
				end
			end
			 ------------------------------------------Hextech Gunblade-----------------------------------
		    if Config.Items.APItems.useHextechGunblade and GetDistance(target)<=700 then
		    	if Config.Items.APItems.apComboMode or Config.Items.APItems.apKSMode then
					local qdmg = getDmg("Q", target, myHero, 3)
					local wdmg = getDmg("W", target, myHero, 3)
					local edmg = getDmg("E", target, myHero, 3)
					local hxgdamage = (GetInventoryItemIsCastable(3146) and getDmg("HXG",target,myHero) or 0) 
					if Config.Items.APItems.apKSMode and hxgdamage>target.health then
						CastItem(3146, target)
					elseif Config.Items.APItems.apComboMode and hxgdamage + qdmg + wdmg + edmg > target.health then
						CastItem(3146, target)
					end
				elseif Config.Items.APItems.apBurstMode then
					CastItem(3146, target)
				end
		    end
			---------------------------------------------------BlackfireTorch-----------------------------------
			if Config.Items.APItems.useBlackfireTorch and GetDistance(target)<=750 then
				if Config.Items.APItems.apComboMode or Config.Items.APItems.apKSMode then
					local qdmg = getDmg("Q", target, myHero, 3)
					local wdmg = getDmg("W", target, myHero, 3)
					local edmg = getDmg("E", target, myHero, 3)
					local blackfireTorchdmg = (GetInventoryItemIsCastable(3188) and getDmg("BLACKFIRE",target,myHero) or 0) 
					if Config.Items.APItems.apKSMode and blackfireTorchdmg>target.health then
						CastItem(3188, target)
					elseif Config.Items.APItems.apComboMode and blackfireTorchdmg + qdmg + wdmg + edmg > target.health then
						CastItem(3188, target)
					end
				elseif Config.Items.APItems.apBurstMode then
					CastItem(3188, target)
				end
			end
			------------------------------------Deathfire Grasp-----------------------------------
			if Config.Items.APItems.useDFG and GetDistance(target)<=750 then
				if Config.Items.APItems.apComboMode or Config.Items.APItems.apKSMode then
					local qdmg = getDmg("Q", target, myHero, 3)
					local wdmg = getDmg("W", target, myHero, 3)
					local edmg = getDmg("E", target, myHero, 3)
					local dfgdamage = (GetInventoryItemIsCastable(3128) and getDmg("DFG",target,myHero) or 0)  
					if Config.Items.APItems.apKSMode and dfgdamage>target.health then
						CastItem(3128, target)
					elseif Config.Items.APItems.apComboMode and dfgdamage + qdmg + wdmg + edmg > target.health then
						CastItem(3128, target)
					end
				elseif Config.Items.APItems.apBurstMode then
					CastItem(3128, target)
				end
			end
			----------------------------------------------------Twin Shadows ----------------------------------
    		if Config.Items.APItems.useTwinShadows and GetDistance(target)<=1000 then
    			local itemCode = 0
    			if crystalScarMap then
    				itemCode = 3290
    			else
    				itemCode = 3023
    			end
    			if Config.Items.APItems.apComboMode or Config.Items.APItems.apKSMode then
					local qdmg = getDmg("Q", target, myHero, 3)
					local wdmg = getDmg("W", target, myHero, 3)
					local edmg = getDmg("E", target, myHero, 3)
					local twinShadowsDmg = (GetInventoryItemIsCastable(itemCode) and 1 or 0)  
					if Config.Items.APItems.apKSMode and twinShadowsDmg + qdmg + wdmg + edmg>target.health then
						CastItem(itemCode, target)
					elseif Config.Items.APItems.apComboMode and twinShadowsDmg + qdmg + wdmg + edmg > target.health then
						CastItem(itemCode, target)
					end
				elseif Config.Items.APItems.apBurstMode then
					CastItem(itemCode, target)
				end
    		end
		end
		---------------------------------------------------AD Items------------------------------------------------------------
		if Config.Items.ADItems.useADItems then
			--------------------------------------------Entropy--------------------------------------------------
			if Config.Items.ADItems.useEntropy and GetDistance(target)<=400 then
				if Config.Items.ADItems.adComboMode or Config.Items.ADItems.adKSMode then
					local ADdmg = getDmg("AD", target, myHero, 3)
					local Entropydmg = (GetInventoryItemIsCastable(3184) and 80 or 0)
					if Config.Items.ADItems.adKSMode and Entropydmg > target.health then
						CastItem(3184, target)
					elseif Config.Items.ADItems.adComboMode and Entropydmg + ADdmg*3 > target.health then
						CastItem(3184, target)
					end
				elseif Config.Items.ADItems.adBurstMode then
					CastItem(3184, target)
				end
			end
			-----------------------------------------------Ravenous Hydra------------------------------------------
			if Config.Items.ADItems.useRavenousHydra and GetDistance(target)<=400  then
				if Config.Items.ADItems.adComboMode or Config.Items.ADItems.adKSMode then
					local ADdmg = getDmg("AD", target, myHero, 3)
					local RHydadmg = (GetInventoryItemIsCastable(3074) and getDmg("HYDRA",target,myHero) or 0) 
					if Config.Items.ADItems.adKSMode and RHydadmg > target.health then
						CastItem(3074, target)
					elseif Config.Items.ADItems.adComboMode and RHydadmg + ADdmg*3 > target.health then
						CastItem(3074, target)
					end
				elseif Config.Items.ADItems.adBurstMode then
					CastItem(3074, target)
				end
			end
			-----------------------------------------Blade of the Ruin King---------------------------------
			if Config.Items.ADItems.useBOTRK and GetDistance(target)<=450 then
				if Config.Items.ADItems.adComboMode or Config.Items.ADItems.adKSMode then
					local ADdmg = getDmg("AD", target, myHero, 3)
					local BOTRKdmg = (GetInventoryItemIsCastable(3153) and getDmg("RUINEDKING",target, myHero) or 0)
					if Config.Items.ADItems.adKSMode and BOTRKdmg > target.health then
						CastItem(3153, target)
					elseif Config.Items.ADItems.adComboMode and BOTRKdmg + ADdmg*3 > target.health then
						CastItem(3153, target)
					end
				elseif Config.Items.ADItems.adBurstMode then
					CastItem(3153, target)
				end
			end
			----------------------------------------------Tiamat--------------------------------------------------------
			if Config.Items.ADItems.useTiamat and GetDistance(target)<=450  then
				if Config.Items.ADItems.adComboMode or Config.Items.ADItems.adKSMode then
					local ADdmg = getDmg("AD", target, myHero, 3)
					local TIAMATdmg = (GetInventoryItemIsCastable(3077) and getDmg("TIAMAT",target,myHero) or 0)
					if Config.Items.ADItems.adKSMode and TIAMATdmg > target.health then
						CastItem(3077, target)
					elseif Config.Items.ADItems.adComboMode and TIAMATdmg + ADdmg*3 > target.health then
						CastItem(3077, target)
					end
				elseif Config.Items.ADItems.adBurstMode then
					CastItem(3077, target)
				end
			end
			-----------------------------------------Sword of the Devine--------------------------------------------------
			if Config.Items.ADItems.useSwordOfTheDevine and GetDistance(target)<=900 then
				if Config.Items.ADItems.adComboMode or Config.Items.ADItems.adKSMode then
					local ADdmg = getDmg("AD", target, myHero, 3)
					local critDmg = ADdmg*2
					local SOTDdmg = (GetInventoryItemIsCastable(3131) and myHero:CalcDamage(target, critDmg*3) or 0)
					if Config.Items.ADItems.adKSMode and SOTDdmg > target.health then
						CastItem(3131, target)
					elseif Config.Items.ADItems.adComboMode and SOTDdmg + ADdmg*3 > target.health then
						CastItem(3131, target)
					end
				elseif Config.Items.ADItems.adBurstMode then
					CastItem(3131, target)
				end
			end
			-------------------------------------------------Youmuu's Ghostblade----------------------------------------
			if Config.Items.ADItems.useYoumuusGhostblade and GetDistance(target)<=900 then
				if Config.Items.ADItems.adComboMode or Config.Items.ADItems.adKSMode then
					local ADdmg = getDmg("AD", target, myHero, 3)
					local YGhostbladedmg = (GetInventoryItemIsCastable(3142) and myHero:CalcDamage(target, ADdmg*3) or 0) 
					if Config.Items.ADItems.adKSMode and YGhostbladedmg > target.health then
						CastItem(3142, target)
					elseif Config.Items.ADItems.adComboMode and YGhostbladedmg + ADdmg*3 > target.health then
						CastItem(3142, target)
					end
				elseif Config.Items.ADItems.adBurstMode then
					CastItem(3142, target)
				end
			end
		end
	end
end
function Muramana()
-------------------------------------------------Muramana----------------------------------------
	if GetInventoryItemIsCastable(3042) then
		local count = 0
		for i = 1, enemyHerosCount do
			local Enemy = enemyHeros[i].object
			if ValidTarget(Enemy) then
				if GetDistance(Enemy)<=3000 then
					count = count + 1
				end
			end
		end
		if count > 0 then
			MuramanaToggle(1000, ((player.mana / player.maxMana) > (Config.Items.ADItems.minManaMura / 100)))
		else
			MuramanaOff()
		end
	end
end
function farmKey()
	--Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle
	local minion = lowestHealthMinion()
	if ValidTarget(minion) then
		local range = myHero.range + GetDistance(minion, minion.minBBox)/2
		if GetTickCount() > NextShot and GetDistance(minion)<=range and (Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) and Config.Farm.laneFarm.farmAA then
			myHero:Attack(minion)
		elseif GetTickCount() > aaTime then
			if ValidTarget(minion) then
				if isHuman() then
					if HUMANWREADY and GetDistance(minion)<=rangeW and Config.Farm.laneFarm.farmW and (Config.Farm.laneFarm.farmHuman or Config.Farm.laneFarm.farmMix) then
						local wDmg = getDmg("W", minion, myHero, 3)
						if wDmg>=minion.health and (Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) then
							CastHumanW(minion)
						elseif (Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) and (Config.Farm.laneFarm.farmHuman or Config.Farm.laneFarm.farmMix) then
							CastHumanW(minion)
						end
					elseif HUMANQREADY and qMana<=myHero.mana and GetDistance(minion)<=rangeQ and Config.Farm.laneFarm.farmQ and (Config.Farm.laneFarm.farmHuman or Config.Farm.laneFarm.farmMix) then
						local qDmg = getDmg("Q", minion, myHero, 3)
						if qDmg>=minion.health and (Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) then
							CastQ(minion)
						elseif (Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) and (Config.Farm.laneFarm.farmHuman or Config.Farm.laneFarm.farmMix) then
							CastQ(minion)
						end
					elseif SPIDERQREADY and GetDistance(minion)<=rangeQS and Config.Farm.laneFarm.farmQ and (Config.Farm.laneFarm.farmSpider or Config.Farm.laneFarm.farmMix) then
						local qmDmg = getDmg("QM", minion, myHero, 3)
						if qmDmg>=minion.health and (Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle)  then
							CastR(minion)
							CastQ(minion)
						elseif (Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) and (Config.Farm.laneFarm.farmSpider or Config.Farm.laneFarm.farmMix) then
							CastR(minion)
							CastQ(minion)
						end
					elseif SPIDERWREADY and GetDistance(minion)<=(myHero.range + GetDistance(minion, minion.minBBox)/2) and Config.Farm.laneFarm.farmW and (Config.Farm.laneFarm.farmSpider or Config.Farm.laneFarm.farmMix) then
						local wDmg = getDmg("W", minion, myHero, 3)
						if wDmg>=minion.health and (Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) then
							CastR(minion)
							CastSpiderW(minion)
						elseif (Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) and (Config.Farm.laneFarm.farmSpider or Config.Farm.laneFarm.farmMix) then
							CastR(minion)
							CastSpiderW(minion)
						end
					end
				else
					if HUMANWREADY and GetDistance(minion)<=rangeW and Config.Farm.laneFarm.farmW and not wBuffOn and (Config.Farm.laneFarm.farmHuman or Config.Farm.laneFarm.farmMix) then
						local wDmg = getDmg("W", minion, myHero, 3)
						if wDmg>=minion.health and (Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) then
							CastR(minion)
							CastHumanW(minion)
						elseif (Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) and (Config.Farm.laneFarm.farmHuman or Config.Farm.laneFarm.farmMix) then
							CastR(minion)
							CastHumanW(minion)
						end
					elseif HUMANQREADY and qMana<=myHero.mana and GetDistance(minion)<=rangeQ and Config.Farm.laneFarm.farmQ and not wBuffOn and (Config.Farm.laneFarm.farmHuman or Config.Farm.laneFarm.farmMix) then
						local qDmg = getDmg("Q", minion, myHero, 3)
						if qDmg>=minion.health and (Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) then
							CastR(minion)
							CastQ(minion)
						elseif (Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) and (Config.Farm.laneFarm.farmHuman or Config.Farm.laneFarm.farmMix) then
							CastR(minion)
							CastQ(minion)
						end
					elseif SPIDERQREADY and GetDistance(minion)<=rangeQS and Config.Farm.laneFarm.farmQ and (Config.Farm.laneFarm.farmSpider or Config.Farm.laneFarm.farmMix) then
						local qmDmg = getDmg("QM", minion, myHero, 3)
						if qmDmg>=minion.health and (Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) then
							CastQ(minion)
						elseif (Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) and (Config.Farm.laneFarm.farmSpider or Config.Farm.laneFarm.farmMix) then
							CastQ(minion)
						end
					elseif SPIDERWREADY and GetDistance(minion)<=(myHero.range + GetDistance(minion, minion.minBBox)/2) and Config.Farm.laneFarm.farmW and (Config.Farm.laneFarm.farmSpider or Config.Farm.laneFarm.farmMix) then
						local wDmg = getDmg("W", minion, myHero, 3)
						if wDmg>=minion.health and (Config.Farm.laneFarm.farm or Config.Farm.laneFarm.farmToggle or Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) then
							CastSpiderW(minion)
						elseif (Config.Farm.laneFarm.laneClear or Config.Farm.laneFarm.laneClearToggle) and (Config.Farm.laneFarm.farmSpider or Config.Farm.laneFarm.farmMix) then
							CastSpiderW(minion)
						end
					end
				end
			end
			if Config.Farm.laneFarm.farmOrb then
				MoveToMouse()
			end
		end
	else
		if Config.Farm.laneFarm.farmOrb then
			MoveToMouse()
		end
	end
end
function lowestHealthMinion()
	enemyMinions:update()
	local lessHealthMinion = nil
	for i, minion in pairs(enemyMinions.objects) do
		if minion and minion.valid and GetDistance(minion)<=killRange then
			if lessHealthMinion == nil then
				lessHealthMinion = minion
			elseif minion.health<lessHealthMinion.health then
				lessHealthMinion = minion
			end
		end
	end
	return lessHealthMinion
end
function jungleFarm()
	if Config.Farm.jungleFarm.jungleFarming then
		local minion = highestMaxHealthhMinion()
		if ValidTarget(minion) then
			if rappel then
				CastSpiderE(minion)
			end
			local aaRange = myHero.range + GetDistance(minion, minion.minBBox)/2
			if GetTickCount() > NextShot and GetDistance(minion)<=aaRange and Config.Farm.jungleFarm.jungleFarmAA then
				myHero:Attack(minion)
			elseif GetTickCount() > aaTime then
				if haveAggro and spiderlings and ValidTarget(aggroUnit) and aggroUnit == minion and not isHuman() and Config.Farm.jungleFarm.jungleFarmAggro then
					local mobPos = Vector(aggroUnit.x, aggroUnit.y, aggroUnit.z)
					local HeroPos = Vector(myHero.x, myHero.y, myHero.z)
					local Pos = mobPos +(mobPos-HeroPos)*(-400/GetDistance(aggroUnit))
					myHero:MoveTo(Pos.x, Pos.z)
				else
					if ValidTarget(minion) then
						if not isHuman() then
							if GetDistance(minion)>=100 then
								myHero:MoveTo(minion.x, minion.z)
							end
						end
						if isHuman() then
							if HUMANWREADY and GetDistance(minion)<=rangeW and Config.Farm.jungleFarm.jungleFarmW and (Config.Farm.jungleFarm.jungleHuman or Config.Farm.jungleFarm.jungleMix) then
								CastHumanW(minion)
							elseif HUMANQREADY and qMana<=myHero.mana and GetDistance(minion)<=rangeQ and Config.Farm.jungleFarm.jungleFarmQ and (Config.Farm.jungleFarm.jungleHuman or Config.Farm.jungleFarm.jungleMix) then
								CastQ(minion)
							elseif SPIDERQREADY and GetDistance(minion)<=rangeQS and Config.Farm.jungleFarm.jungleFarmQ and (Config.Farm.jungleFarm.jungleSpider or Config.Farm.jungleFarm.jungleMix) then
								CastR(minion)
								CastQ(minion)
							elseif SPIDERWREADY and GetDistance(minion)<=rangeWS and Config.Farm.jungleFarm.jungleFarmW and (Config.Farm.jungleFarm.jungleSpider or Config.Farm.jungleFarm.jungleMix) then
								CastR(minion)
								CastSpiderW(minion)
							end
						else
							if HUMANWREADY and GetDistance(minion)<=rangeW and not wBuffOn and Config.Farm.jungleFarm.jungleFarmW and (Config.Farm.jungleFarm.jungleHuman or Config.Farm.jungleFarm.jungleMix) and ((not spiderlings and Config.Farm.jungleFarm.jungleFarmAggro) or not Config.Farm.jungleFarm.jungleFarmAggro) then
								CastR(minion)
								CastHumanW(minion)
							elseif HUMANQREADY and qMana<=myHero.mana and GetDistance(minion)<=rangeQ and not wBuffOn and Config.Farm.jungleFarm.jungleFarmQ and (Config.Farm.jungleFarm.jungleHuman or Config.Farm.jungleFarm.jungleMix) and ((not spiderlings and Config.Farm.jungleFarm.jungleFarmAggro) or not Config.Farm.jungleFarm.jungleFarmAggro) then
								CastR(minion)
								CastQ(minion)
							elseif SPIDERQREADY and GetDistance(minion)<=rangeQS and Config.Farm.jungleFarm.jungleFarmQ and (Config.Farm.jungleFarm.jungleSpider or Config.Farm.jungleFarm.jungleMix) then
								CastQ(minion)
							elseif SPIDERWREADY and GetDistance(minion)<=rangeWS and Config.Farm.jungleFarm.jungleFarmW and (Config.Farm.jungleFarm.jungleSpider or Config.Farm.jungleFarm.jungleMix) then
								CastSpiderW(minion)
							end
						end
					end
					if Config.Farm.jungleFarm.jungleOrb then
						MoveToMouse()
					end
				end
			end
		else
			if Config.Farm.jungleFarm.jungleOrb then
				MoveToMouse()
			end
		end
	end
end
function highestMaxHealthhMinion()
	jungleMinion:update()
	local maxHealthMinion = nil
	for i, minion in pairs(jungleMinion.objects) do
		if minion and minion.valid and GetDistance(minion)<=killRange then
			if maxHealthMinion == nil then
				maxHealthMinion = minion
			elseif minion.maxHealth>maxHealthMinion.maxHealth then
				maxHealthMinion = minion
			end
		end
	end
	return maxHealthMinion
end
function orbWalk()	
	local range = myHero.range + GetDistance(myHero.minBBox)
	if GetTickCount() > NextShot and ValidTarget(newTarget) and GetDistance(newTarget)<=range and (Config.combatKeys.teamFight or Config.combatKeys.harass or Config.combatKeys.harassToggle) and Config.OrbWalk.champOrbWalk.AA then
			myHero:Attack(newTarget)
	elseif GetTickCount() > aaTime and Config.combatKeys.teamFight and Config.OrbWalk.champOrbWalk.moveToMouse then
		MoveToMouse()
	end
end
function MoveToMouse()
	local pos = {x = mousePos.x, y = mousePos.y, z = mousePos.z}
	local HeroPos = Vector(myHero.x, myHero.y, myHero.z)
	if GetDistance(mousePos)>175 then
		local movePos = HeroPos +(HeroPos -pos)*(-175/GetDistance(mousePos))
		Packet("S_MOVE", {moveToX = movePos.x, moveToZ = movePos.y}):send()
	elseif GetDistance(mousePos)>125 then
		Packet("S_MOVE", {moveToX = mousePos.x, moveToZ = mousePos.y}):send()
	end
end
function OnProcessSpell(unit, spell)
	if unit.isMe and unit.valid and spell.name:lower():find("attack") and spell.animationTime and spell.windUpTime then
		aaTime = GetTickCount() + spell.windUpTime * 1000+(GetLatency()*2)
		NextShot = GetTickCount() + spell.animationTime * 1000
	end
	if unit.isMe and unit and unit.valid and spell then
		----------Spider Skills ------------------
		if spell.name == "EliseSpiderQCast" then
			QSCDTimer = GetTickCount() + QSCD*1000
		elseif spell.name == "EliseSpiderW" then
			WSCDTimer = GetTickCount() + WSCD*1000
		elseif spell.name == "EliseSpiderEInitial" then
			ESCDTimer = GetTickCount() + ESCD*1000
		-----------Human Skills ------------------
		elseif spell.name == "EliseHumanQ" then
			QCDTimer = GetTickCount() + QCD*1000
		elseif spell.name == "EliseHumanW" then
			WCDTimer = GetTickCount() + WCD*1000
		elseif spell.name == "EliseHumanE" then
			ECDTimer = GetTickCount() + ECD*1000
			eStunTimer = GetTickCount() + 1500
		end
	end
	if unit and unit.type == "obj_AI_Turret" and unit.team ~= myHero.team and spell.target == myHero then
		local turretDmg = 300
		if Config.rappel.rappelTower and SPIDEREREADY and turretDmg>=myHero.health then
			towerShotTimer = GetTickCount() + 300 - GetLatency()
			towerShooting = true
		end
	end
	---------------------------------------------------------------Evadee Configs Loaded--------------------------------------------------------------------------
	if evadeeLoaded and (Config.Evadeee.UseEvadeee and Config.Evadeee.UseEvadeee~=nil) and _G.Evadeee_impossibleToEvade then 
		if SPIDEREREADY and not rappel then
			if unit.valid and spell and unit and unit.team ~= myHero.team then
				--------------------------------------Extreme Spells-----------------------------------
				if Config.rappel.rappelExtreme then
					for i, extremeSpell in pairs(ExtremeSpells) do
						if extremeSpell.spellName == spell.name then
							-------------------------------------AOE Spells------------------------------------
							if spell.name == "ViR" and spell.target == myHero then
								viUltTimer = GetTickCount() + 230 - GetLatency()
								viUlting = true
							elseif spell.name == "FioraDance" and spell.target == myHero then --check distance as she can target someone else and still hit you
								fioraTimer = GetTickCount() + 280 - GetLatency()
								fioraUlting = true
							elseif spell.name == "NautilusGrandLine" and spell.target == myHero then
								nautilusTimer = GetTickCount() + 450 - GetLatency()
								nautilusUlting = true
							-------------------------------Target Spell - No Timers------------------------------- 
					
							elseif spell.name == "HecarimUlt" then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 200, 1000, myHero, 150)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "SejuaniGlacialPrisonStart" then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 150, 1175, myHero, 50)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "VarusR" then --maybe extra logic
								local hitChampion = checkhitlinepass(unit, spell.endPos, 150, 1075, myHero, 50)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "MissFortuneBulletTime" then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 225, 1400, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "SonaCrescendo" then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 200, 1000, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							end
						end
					end
				end
				----------------------------------Dangerous Spells--------------------------------------------------
				if Config.rappel.rappelDangerous then
					for d, dangerousSpell in pairs(DangerousSpells) do
						if dangerousSpell.spellName == spell.name then
							-----------------------------------AOE Spells - No Timers---------------------------------------
							if spell.name == "GragasExplosiveCask" and GetDistance(spell.endPos)<=425 then
								CastSpiderEDefensive()
							elseif spell.name == "XenZhaoParry" and GetDistance(spell.endPos)<=500 then
								CastSpiderEDefensive()
							elseif spell.name == "KatarinaR" and GetDistance(spell.endPos)<=550 then
								CastSpiderEDefensive()
							----------------------------------AOE Spells - With Timers--------------------------------------
							elseif spell.name == "ZiggsR" and GetDistance(spell.endPos)<=600 then
								ziggsUltTimer = GetTickCount() + 950 - GetLatency()
								ziggsUlting = true
							--------------------------------------Target - With Timers ----------------------------------------
							elseif spell.name == "BrandWildfire" and spell.target == myHero then
								brandUltTimer = GetTickCount() + 230 - GetLatency()
								brandUlting = true
							elseif spell.name == "VeigarPrimordialBurst" and spell.target == myHero then
								veigarUltTimer = GetTickCount() + 230 - GetLatency()
								veigarUlting = true
							elseif spell.name == "CaitlynAceintheHole" and spell.target == myHero then
								CaitlynUltTimer = GetTickCount() + 1390 - GetLatency()
								caitlynUlting = true
							-----------------------------------CheckHitLinePass Spells---------------------------------------
							elseif spell.name == "LuxMaliceCannon" then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 250, 3250, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "ShyvanaTransformCast" then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 200, 1100, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							end
						end
					end
				end
				----------------------------------Spells That Will Kill You----------------------------------------
				if Config.rappel.rappelKill then
					for k, killSpell in pairs(KillSpells) do
						if killSpell.spellName == spell.name then
							local Dmg = getDmg("R", myHero, unit, 3)
							-----------------------------------AOE Spells - No Timers---------------------------------------
							if spell.name == "GragasExplosiveCask" and Dmg>=myHero.health and GetDistance(spell.endPos)<=425 then
								CastSpiderEDefensive()
							elseif spell.name == "XenZhaoParry" and Dmg>=myHero.health and GetDistance(spell.endPos)<=500 then
								CastSpiderEDefensive()
							elseif spell.name == "KatarinaR" and Dmg>=myHero.health and GetDistance(spell.endPos)<=550 then
								CastSpiderEDefensive()
							elseif spell.name == "ZyraBrambleZone" and Dmg>=myHero.health and GetDistance(spell.endPos)<=540 then
								CastSpiderEDefensive()
							elseif spell.name == "LeonaSolarFlare" and Dmg>=myHero.health and GetDistance(spell.endPos)<=375 then
								CastSpiderEDefensive()
							elseif spell.name == "UFSlash" and Dmg>=myHero.health and GetDistance(spell.endPos)<=375 then
								CastSpiderEDefensive()
							elseif spell.name == "CurseoftheSadMummy" and Dmg>=myHero.health and GetDistance(spell.endPos)<=550 then
								CastSpiderEDefensive()
							----------------------------------AOE Spells - With Timers--------------------------------------
							elseif spell.name == "ZiggsR" and Dmg>=myHero.health and GetDistance(spell.endPos)<=600 then	
								ziggsUltTimer = GetTickCount() + 950 - GetLatency()
								ziggsUlting = true
							--------------------------------------Object AOE Spells-----------------------------
							elseif spell.name == "OrianaDetonateCommand" and Dmg>=myHero.health then
								if next(doomBall)~= nil then
									for i, obj in pairs(doomBall) do
										if obj.valid and obj and GetDistance(obj)<=440 then
											CastSpiderEDefensive()
										end
									end
								end
							--------------------------------------Target Spells - With Timers ----------------------------------------
							elseif spell.name == "BrandWildfire" and spell.target == myHero and Dmg>=myHero.health then
								brandUltTimer = GetTickCount() + 230 - GetLatency()
								brandUlting = true
							elseif spell.name == "VeigarPrimordialBurst" and spell.target == myHero and Dmg>=myHero.health then
								veigarUltTimer = GetTickCount() + 230 - GetLatency()
								veigarUlting = true
							elseif spell.name == "CaitlynAceintheHole" and spell.target == myHero and Dmg>=myHero.health then
								CaitlynUltTimer = GetTickCount() + 1390 - GetLatency()
								caitlynUlting = true
							elseif spell.name == "ViR" and spell.target == myHero and Dmg>=myHero.health then
								viUltTimer = GetTickCount() + 230 - GetLatency()
								viUlting = true
							elseif spell.name == "FioraDance" and spell.target == myHero and Dmg>=myHero.health then --check distance as she can target someone else and still hit you
								fioraTimer = GetTickCount() + 280 - GetLatency()
								fioraUlting = true
							elseif spell.name == "NautilusGrandLine" and spell.target == myHero and Dmg>=myHero.health then
								nautilusTimer = GetTickCount() + 450 - GetLatency()
								nautilusUlting = true
							-------------------------------Target Spell - No Timers------------------------------- 
							elseif spell.name == "LissandraR" and spell.target == myHero and Dmg>=myHero.health then
								CastSpiderEDefensive()
							-------------------------------CheckHitLinePass Spells---------------------------------
							elseif spell.name == "NamiR" and Dmg>=myHero.health then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 500, 2750, myHero, 0)
								if hitChampion then
									if GetDistance(unit)<1500 then
										CastSpiderEDefensive()
									else
										namiTimer = GetTickCount() + 1130 - GetLatency()
										namiUlting = true
									end
								end
							elseif spell.name == "LuxMaliceCannon" and Dmg>=myHero.health then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 250, 3250, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "ShyvanaTransformCast" and Dmg>=myHero.health then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 200, 1100, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "rivenizunablade" then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 300, 1050, myHero, 50)
								local dmg = unit.totalDamage+480
								if hitChampion and dmg>myHero.health then
									CastSpiderEDefensive()
								end
							elseif spell.name == "HecarimUlt" and Dmg>=myHero.health then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 200, 1000, myHero, 150)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "SejuaniGlacialPrisonStart" and Dmg>=myHero.health then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 150, 1175, myHero, 50)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "VarusR" and Dmg>=myHero.health then --maybe extra logic
								local hitChampion = checkhitlinepass(unit, spell.endPos, 150, 1075, myHero, 50)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "MissFortuneBulletTime" and Dmg>=myHero.health then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 225, 1400, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "SonaCrescendo" and Dmg>=myHero.health then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 200, 1000, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							end
						end
					end
				end
			end
		end
	---------------------------------------------------------------Evadee Configs Loaded but able to dodge-----------------------------------------------------------
	elseif evadeeLoaded and (Config.Evadeee.UseEvadeee and Config.Evadeee.UseEvadeee~=nil) and _G.Evadeee_impossibleToEvade==false then
		if SPIDEREREADY and not rappel then
			if unit.valid and spell and unit and unit.team ~= myHero.team then
				--------------------------------------Extreme Spells-----------------------------------
				if Config.rappel.rappelExtreme then
					for i, extremeSpell in pairs(ExtremeSpells) do
						if extremeSpell.spellName == spell.name then
							--------------------------------------Object AOE Spells-----------------------------
							if spell.name == "OrianaDetonateCommand" then
								if next(doomBall)~= nil then
									for i, obj in pairs(doomBall) do
										if obj.valid and obj and GetDistance(obj)<=440 then
											CastSpiderEDefensive()
										end
									end
								end
							---------------------------------Target Spells - With Timers------------------------
							elseif spell.name == "ViR" and spell.target == myHero then
								viUltTimer = GetTickCount() + 230 - GetLatency()
								viUlting = true
							elseif spell.name == "FioraDance" and spell.target == myHero then
								fioraTimer = GetTickCount() + 280 - GetLatency()
								fioraUlting = true
							elseif spell.name == "NautilusGrandLine" and spell.target == myHero then
								nautilusTimer = GetTickCount() + 450 - GetLatency()
								nautilusUlting = true
							-------------------------------Target Spell - No Timers------------------------------- 
							elseif spell.name == "HecarimUlt" then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 200, 1000, myHero, 150)
								if hitChampion then
									CastSpiderEDefensive()
								end
							end
						end
					end
				end
				----------------------------------Dangerous Spells--------------------------------------------------
				if Config.rappel.rappelDangerous then
					for d, dangerousSpell in pairs(DangerousSpells) do
						if dangerousSpell.spellName == spell.name then
							-----------------------------------AOE Spells - No Timers---------------------------------------
							if spell.name == "GragasExplosiveCask" and GetDistance(spell.endPos)<=425 then
								CastSpiderEDefensive()
							elseif spell.name == "XenZhaoParry" and GetDistance(spell.endPos)<=500 then
								CastSpiderEDefensive()
							elseif spell.name == "KatarinaR" and GetDistance(spell.endPos)<=550 then
								CastSpiderEDefensive()
							----------------------------------AOE Spells - With Timers--------------------------------------
							elseif spell.name == "ZiggsR" and GetDistance(spell.endPos)<=600 then
								ziggsUltTimer = GetTickCount() + 950 - GetLatency()
								ziggsUlting = true
							--------------------------------------Target - With Timers ----------------------------------------
							elseif spell.name == "BrandWildfire" and spell.target == myHero then
								brandUltTimer = GetTickCount() + 230 - GetLatency()
								brandUlting = true
							elseif spell.name == "VeigarPrimordialBurst" and spell.target == myHero then
								veigarUltTimer = GetTickCount() + 230 - GetLatency()
								veigarUlting = true
							elseif spell.name == "CaitlynAceintheHole" and spell.target == myHero then
								CaitlynUltTimer = GetTickCount() + 1390 - GetLatency()
								caitlynUlting = true
							elseif spell.name == "ShyvanaTransformCast" then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 200, 1100, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							end
						end
					end
				end
				----------------------------------Spells That Will Kill You----------------------------------------
				if Config.rappel.rappelKill then
					for k, killSpell in pairs(KillSpells) do
						if killSpell.spellName == spell.name then
							local Dmg = getDmg("R", myHero, unit, 3)
							-----------------------------------AOE Spells - No Timers---------------------------------------
							if spell.name == "GragasExplosiveCask" and Dmg>=myHero.health and GetDistance(spell.endPos)<=425 then
								CastSpiderEDefensive()
							elseif spell.name == "XenZhaoParry" and Dmg>=myHero.health and GetDistance(spell.endPos)<=500 then
								CastSpiderEDefensive()
							elseif spell.name == "KatarinaR" and Dmg>=myHero.health and GetDistance(spell.endPos)<=550 then
								CastSpiderEDefensive()
							----------------------------------AOE Spells - With Timers--------------------------------------
							--------------------------------------Object AOE Spells-----------------------------
							elseif spell.name == "OrianaDetonateCommand" and Dmg>=myHero.health then
								if next(doomBall)~= nil then
									for i, obj in pairs(doomBall) do
										if obj.valid and obj and GetDistance(obj)<=440 then
											CastSpiderEDefensive()
										end
									end
								end
							--------------------------------------Target Spells - With Timers ----------------------------------------
							elseif spell.name == "BrandWildfire" and spell.target == myHero and Dmg>=myHero.health then
								brandUltTimer = GetTickCount() + 230 - GetLatency()
								brandUlting = true
							elseif spell.name == "VeigarPrimordialBurst" and spell.target == myHero and Dmg>=myHero.health then
								veigarUltTimer = GetTickCount() + 230 - GetLatency()
								veigarUlting = true
							elseif spell.name == "CaitlynAceintheHole" and spell.target == myHero and Dmg>=myHero.health then
								CaitlynUltTimer = GetTickCount() + 1390 - GetLatency()
								caitlynUlting = true
							elseif spell.name == "ViR" and spell.target == myHero and Dmg>=myHero.health then
								viUltTimer = GetTickCount() + 230 - GetLatency()
								viUlting = true
							elseif spell.name == "FioraDance" and spell.target == myHero and Dmg>=myHero.health then --check distance as she can target someone else and still hit you
								fioraTimer = GetTickCount() + 280 - GetLatency()
								fioraUlting = true
							elseif spell.name == "NautilusGrandLine" and spell.target == myHero and Dmg>=myHero.health then
								nautilusTimer = GetTickCount() + 450 - GetLatency()
								nautilusUlting = true
							-------------------------------Target Spell - No Timers------------------------------- 
							-------------------------------CheckHitLinePass Spells---------------------------------
							elseif spell.name == "NamiR" and Dmg>=myHero.health then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 500, 2750, myHero, 0)
								if hitChampion then
									if GetDistance(unit)<1500 then
										CastSpiderEDefensive()
									else
										namiTimer = GetTickCount() + 1130 - GetLatency()
										namiUlting = true
									end
								end
							elseif spell.name == "ShyvanaTransformCast" and Dmg>=myHero.health then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 200, 1100, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "rivenizunablade" then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 300, 1050, myHero, 50)
								local dmg = unit.totalDamage+480
								if hitChampion and dmg>myHero.health then
									CastSpiderEDefensive()
								end
							elseif spell.name == "SejuaniGlacialPrisonStart" and Dmg>=myHero.health then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 150, 1175, myHero, 50)
								if hitChampion then
									CastSpiderEDefensive()
								end
							end
						end
					end
				end
			end
		end
	---------------------------------------------------------------NO Evadee Configs Loaded--------------------------------------------------------------------------
	else
		if SPIDEREREADY and not rappel then
			if unit.valid and spell and unit and unit.team ~= myHero.team then
				--------------------------------------Extreme Spells-----------------------------------
				if Config.rappel.rappelExtreme then
					for i, extremeSpell in pairs(ExtremeSpells) do
						if extremeSpell.spellName == spell.name then
							-------------------------------------AOE Spells------------------------------------
							if spell.name == "InfernalGuardian" and GetDistance(spell.endPos)<=200 then
								CastSpiderEDefensive()
							elseif spell.name == "ZyraBrambleZone" and GetDistance(spell.endPos)<=540 then
								CastSpiderEDefensive()
							elseif spell.name == "LeonaSolarFlare" and GetDistance(spell.endPos)<=375 then
								CastSpiderEDefensive()
							elseif spell.name == "UFSlash" and GetDistance(spell.endPos)<=375 then
								CastSpiderEDefensive()
							elseif spell.name == "CurseoftheSadMummy" and GetDistance(spell.endPos)<=550 then
								CastSpiderEDefensive()
							--------------------------------------Object AOE Spells-----------------------------
							elseif spell.name == "OrianaDetonateCommand" then
								if next(doomBall)~= nil then
									for i, obj in pairs(doomBall) do
										if obj.valid and obj and GetDistance(obj)<=440 then
											CastSpiderEDefensive()
										end
									end
								end
							---------------------------------Target Spells - With Timers------------------------
							elseif spell.name == "ViR" and spell.target == myHero then
								viUltTimer = GetTickCount() + 230 - GetLatency()
								viUlting = true
							elseif spell.name == "FioraDance" and spell.target == myHero then --check distance as she can target someone else and still hit you
								fioraTimer = GetTickCount() + 280 - GetLatency()
								fioraUlting = true
							elseif spell.name == "NautilusGrandLine" and spell.target == myHero then
								nautilusTimer = GetTickCount() + 450 - GetLatency()
								nautilusUlting = true
							-------------------------------Target Spell - No Timers------------------------------- 
							elseif spell.name == "LissandraR" and spell.target == myHero then
								CastSpiderEDefensive()
							-------------------------------CheckHitLinePass Spells---------------------------------
							elseif spell.name == "NamiR" then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 500, 2750, myHero, 0)
								if hitChampion then
									if GetDistance(unit)<1500 then
										CastSpiderEDefensive()
									else
										namiTimer = GetTickCount() + 1130 - GetLatency()
										namiUlting = true
									end
								end
							elseif spell.name == "HecarimUlt" then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 200, 1000, myHero, 150)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "SejuaniGlacialPrisonStart" then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 150, 1175, myHero, 50)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "VarusR" then --maybe extra logic
								local hitChampion = checkhitlinepass(unit, spell.endPos, 150, 1075, myHero, 50)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "MissFortuneBulletTime" then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 225, 1400, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "SonaCrescendo" then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 200, 1000, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							end
						end
					end
				end
				----------------------------------Dangerous Spells--------------------------------------------------
				if Config.rappel.rappelDangerous then
					for d, dangerousSpell in pairs(DangerousSpells) do
						if dangerousSpell.spellName == spell.name then
							-----------------------------------AOE Spells - No Timers---------------------------------------
							if spell.name == "GragasExplosiveCask" and GetDistance(spell.endPos)<=425 then
								CastSpiderEDefensive()
							elseif spell.name == "XenZhaoParry" and GetDistance(spell.endPos)<=500 then
								CastSpiderEDefensive()
							elseif spell.name == "KatarinaR" and GetDistance(spell.endPos)<=550 then
								CastSpiderEDefensive()
							----------------------------------AOE Spells - With Timers--------------------------------------
							elseif spell.name == "ZiggsR" and GetDistance(spell.endPos)<=600 then
								ziggsUltTimer = GetTickCount() + 950 - GetLatency()
								ziggsUlting = true
							--------------------------------------Target - With Timers ----------------------------------------
							elseif spell.name == "BrandWildfire" and spell.target == myHero then
								brandUltTimer = GetTickCount() + 230 - GetLatency()
								brandUlting = true
							elseif spell.name == "VeigarPrimordialBurst" and spell.target == myHero then
								veigarUltTimer = GetTickCount() + 230 - GetLatency()
								veigarUlting = true
							elseif spell.name == "CaitlynAceintheHole" and spell.target == myHero then
								CaitlynUltTimer = GetTickCount() + 1390 - GetLatency()
								caitlynUlting = true
							-----------------------------------CheckHitLinePass Spells---------------------------------------
							elseif spell.name == "LuxMaliceCannon" then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 250, 3250, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "ShyvanaTransformCast" then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 200, 1100, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							end
						end
					end
				end
				----------------------------------Spells That Will Kill You----------------------------------------
				if Config.rappel.rappelKill then
					for k, killSpell in pairs(KillSpells) do
						if killSpell.spellName == spell.name then
							local Dmg = getDmg("R", myHero, unit, 3)
							-----------------------------------AOE Spells - No Timers---------------------------------------
							if spell.name == "GragasExplosiveCask" and Dmg>=myHero.health and GetDistance(spell.endPos)<=425 then
								CastSpiderEDefensive()
							elseif spell.name == "XenZhaoParry" and Dmg>=myHero.health and GetDistance(spell.endPos)<=500 then
								CastSpiderEDefensive()
							elseif spell.name == "KatarinaR" and Dmg>=myHero.health and GetDistance(spell.endPos)<=550 then
								CastSpiderEDefensive()
							elseif spell.name == "ZyraBrambleZone" and Dmg>=myHero.health and GetDistance(spell.endPos)<=540 then
								CastSpiderEDefensive()
							elseif spell.name == "LeonaSolarFlare" and Dmg>=myHero.health and GetDistance(spell.endPos)<=375 then
								CastSpiderEDefensive()
							elseif spell.name == "UFSlash" and Dmg>=myHero.health and GetDistance(spell.endPos)<=375 then
								CastSpiderEDefensive()
							elseif spell.name == "CurseoftheSadMummy" and Dmg>=myHero.health and GetDistance(spell.endPos)<=550 then
								CastSpiderEDefensive()
							----------------------------------AOE Spells - With Timers--------------------------------------
							elseif spell.name == "ZiggsR" and Dmg>=myHero.health and GetDistance(spell.endPos)<=600 then	
								ziggsUltTimer = GetTickCount() + 950 - GetLatency()
								ziggsUlting = true
							--------------------------------------Object AOE Spells-----------------------------
							elseif spell.name == "OrianaDetonateCommand" and Dmg>=myHero.health then
								if next(doomBall)~= nil then
									for i, obj in pairs(doomBall) do
										if obj.valid and obj and GetDistance(obj)<=440 then
											CastSpiderEDefensive()
										end
									end
								end
							--------------------------------------Target Spells - With Timers ----------------------------------------
							elseif spell.name == "BrandWildfire" and spell.target == myHero and Dmg>=myHero.health then
								brandUltTimer = GetTickCount() + 230 - GetLatency()
								brandUlting = true
							elseif spell.name == "VeigarPrimordialBurst" and spell.target == myHero and Dmg>=myHero.health then
								veigarUltTimer = GetTickCount() + 230 - GetLatency()
								veigarUlting = true
							elseif spell.name == "CaitlynAceintheHole" and spell.target == myHero and Dmg>=myHero.health then
								CaitlynUltTimer = GetTickCount() + 1390 - GetLatency()
								caitlynUlting = true
							elseif spell.name == "ViR" and spell.target == myHero and Dmg>=myHero.health then
								viUltTimer = GetTickCount() + 230 - GetLatency()
								viUlting = true
							elseif spell.name == "FioraDance" and spell.target == myHero and Dmg>=myHero.health then --check distance as she can target someone else and still hit you
								fioraTimer = GetTickCount() + 280 - GetLatency()
								fioraUlting = true
							elseif spell.name == "NautilusGrandLine" and spell.target == myHero and Dmg>=myHero.health then
								nautilusTimer = GetTickCount() + 450 - GetLatency()
								nautilusUlting = true
							-------------------------------Target Spell - No Timers------------------------------- 
							elseif spell.name == "LissandraR" and spell.target == myHero and Dmg>=myHero.health then
								CastSpiderEDefensive()
							-------------------------------CheckHitLinePass Spells---------------------------------
							elseif spell.name == "NamiR" and Dmg>=myHero.health then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 500, 2750, myHero, 0)
								if hitChampion then
									if GetDistance(unit)<1500 then
										CastSpiderEDefensive()
									else
										namiTimer = GetTickCount() + 1130 - GetLatency()
										namiUlting = true
									end
								end
							elseif spell.name == "LuxMaliceCannon" and Dmg>=myHero.health then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 250, 3250, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "ShyvanaTransformCast" and Dmg>=myHero.health then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 200, 1100, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "rivenizunablade" then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 300, 1050, myHero, 50)
								local dmg = unit.totalDamage+480
								if hitChampion and dmg>myHero.health then
									CastSpiderEDefensive()
								end
							elseif spell.name == "HecarimUlt" and Dmg>=myHero.health then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 200, 1000, myHero, 150)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "SejuaniGlacialPrisonStart" and Dmg>=myHero.health then
								local hitChampion = checkhitlinepass(unit, spell.endPos, 150, 1175, myHero, 50)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "VarusR" and Dmg>=myHero.health then --maybe extra logic
								local hitChampion = checkhitlinepass(unit, spell.endPos, 150, 1075, myHero, 50)
								if hitChampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "MissFortuneBulletTime" and Dmg>=myHero.health then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 225, 1400, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							elseif spell.name == "SonaCrescendo" and Dmg>=myHero.health then
								local hitchampion = checkhitlinepass(unit, spell.endPos, 200, 1000, myHero, 50)
								if hitchampion then
									CastSpiderEDefensive()
								end
							end
						end
					end
				end
			end
		end
	end
end
function OnDraw()
	if not myHero.dead then
		if Config.Draw.SmiteSettings.DrawSmiteRappel  then
			if GetGameTimer() > 900 and summonersRiftMap then
				if Config.Draw.LagFree then
	           		DrawCircle2(baronPos.x, baronPos.y, baronPos.z, rangeES+50, ARGB(244,66,155,255), 3)
	           	else
	           		DrawCircle(baronPos.x, baronPos.y, baronPos.z, rangeES+50, ARGB(244,66,155,255))
	           	end
	        end
	        if summonersRiftMap then
	        	if next(jungleMinions)~=nil then
					for i, obj in pairs(jungleMinions) do
						if obj and obj.valid and obj.visible then
							if obj.name == "Dragon6.1.1" then
								if Config.Draw.LagFree then
					           		DrawCircle2(obj.x, obj.y, obj.z, rangeES, ARGB(244,66,155,255), 3)
					           	else
					           		DrawCircle(obj.x, obj.y, obj.z, rangeES, ARGB(244,66,155,255))
					           	end
					        end
						end
					end
				end
			elseif twistedTreeLineMap then
	        	if next(jungleMinions)~=nil then
					for i, obj in pairs(jungleMinions) do
						if obj and obj.valid and obj.visible then
							if obj.name == "TT_Spiderboss8.1.1" then
								if Config.Draw.LagFree then
					           		DrawCircle2(obj.x, obj.y, obj.z, rangeES, ARGB(244,66,155,255), 3)
					           	else
					           		DrawCircle(obj.x, obj.y, obj.z, rangeES, ARGB(244,66,155,255))
					           	end
					        end
						end
					end
				end
	        end
		end
		if Config.Draw.drawKillRange.KillRange then
			local a = Config.Draw.drawKillRange.killRangeColour[1]
			local r = Config.Draw.drawKillRange.killRangeColour[2]
			local g = Config.Draw.drawKillRange.killRangeColour[3]
			local b = Config.Draw.drawKillRange.killRangeColour[4]
			if Config.Draw.LagFree then
           		DrawCircle2(myHero.x, myHero.y, myHero.z, killRange, ARGB(a,r,g,b), 3)
           	else
           		DrawCircle(myHero.x, myHero.y, myHero.z, killRange, ARGB(a,r,g,b))
           	end
		end
		if Config.Draw.drawSkillKillRanges.drawQRange then
			local a = Config.Draw.drawSkillKillRanges.drawQRangeColour[1]
			local r = Config.Draw.drawSkillKillRanges.drawQRangeColour[2]
			local g = Config.Draw.drawSkillKillRanges.drawQRangeColour[3]
			local b = Config.Draw.drawSkillKillRanges.drawQRangeColour[4]
			if Config.Draw.LagFree then
           		DrawCircle2(myHero.x, myHero.y, myHero.z, rangeQ, ARGB(a,r,g,b), 3)
           	else
           		DrawCircle(myHero.x, myHero.y, myHero.z, rangeQ, ARGB(a,r,g,b))
           	end
		end
		if Config.Draw.drawSkillKillRanges.drawWRange then
			local a = Config.Draw.drawSkillKillRanges.drawWRangeColour[1]
			local r = Config.Draw.drawSkillKillRanges.drawWRangeColour[2]
			local g = Config.Draw.drawSkillKillRanges.drawWRangeColour[3]
			local b = Config.Draw.drawSkillKillRanges.drawWRangeColour[4]
			if Config.Draw.LagFree then
           		DrawCircle2(myHero.x, myHero.y, myHero.z, rangeW, ARGB(a,r,g,b), 3)
           	else
           		DrawCircle(myHero.x, myHero.y, myHero.z, rangeW, ARGB(a,r,g,b))
           	end
		end
		if Config.Draw.drawSkillKillRanges.drawERange then
			local a = Config.Draw.drawSkillKillRanges.drawERangeColour[1]
			local r = Config.Draw.drawSkillKillRanges.drawERangeColour[2]
			local g = Config.Draw.drawSkillKillRanges.drawERangeColour[3]
			local b = Config.Draw.drawSkillKillRanges.drawERangeColour[4]
			if Config.Draw.LagFree then
           		DrawCircle2(myHero.x, myHero.y, myHero.z, rangeE, ARGB(a,r,g,b), 3)
           	else
           		DrawCircle(myHero.x, myHero.y, myHero.z, rangeE, ARGB(a,r,g,b))
           	end
		end
		if Config.Draw.drawFocusedTarget.DrawFocusedTarget then
			if ValidTarget(newTarget) then
				local a = Config.Draw.drawFocusedTarget.focusedTargetColour[1]
				local r = Config.Draw.drawFocusedTarget.focusedTargetColour[2]
				local g = Config.Draw.drawFocusedTarget.focusedTargetColour[3]
				local b = Config.Draw.drawFocusedTarget.focusedTargetColour[4]
		if Config.Draw.LagFree then
	           		DrawCircle2(newTarget.x, newTarget.y, newTarget.z, 125, ARGB(a,r,g,b), 3)
	           	else
	           		DrawCircle(newTarget.x, newTarget.y, newTarget.z, 125, ARGB(a,r,g,b))
	           	end
			end
		end
		if Config.Draw.drawEnemyKillableText.enemyKillableText then
			for i = 1, enemyHerosCount do
				local Enemy = enemyHeros[i].object
				local killable = enemyHeros[i].killable
				if ValidTarget(Enemy) then
					if killable == 4 then
						local a = Config.Draw.drawEnemyKillableText.ksColourText[1]
						local r = Config.Draw.drawEnemyKillableText.ksColourText[2]
						local g = Config.Draw.drawEnemyKillableText.ksColourText[3]
						local b = Config.Draw.drawEnemyKillableText.ksColourText[4]
						DrawText3D(tostring("Ks him"),Enemy.x,Enemy.y, Enemy.z,16,ARGB(a,r,g,b), true)
					elseif killable == 3 then
						local a = Config.Draw.drawEnemyKillableText.killHimColourText[1]
						local r = Config.Draw.drawEnemyKillableText.killHimColourText[2]
						local g = Config.Draw.drawEnemyKillableText.killHimColourText[3]
						local b = Config.Draw.drawEnemyKillableText.killHimColourText[4]
						DrawText3D(tostring("killable"),Enemy.x,Enemy.y, Enemy.z,16,ARGB(a,r,g,b), true)
					elseif killable == 2 then
						local a = Config.Draw.drawEnemyKillableText.comboColourText[1]
						local r = Config.Draw.drawEnemyKillableText.comboColourText[2]
						local g = Config.Draw.drawEnemyKillableText.comboColourText[3]
						local b = Config.Draw.drawEnemyKillableText.comboColourText[4]
						DrawText3D(tostring("Combo killable"),Enemy.x,Enemy.y, Enemy.z,16,ARGB(a,r,g,b), true) 
					elseif killable == 1 then
						local a = Config.Draw.drawEnemyKillableText.harassColourText[1]
						local r = Config.Draw.drawEnemyKillableText.harassColourText[2]
						local g = Config.Draw.drawEnemyKillableText.harassColourText[3]
						local b = Config.Draw.drawEnemyKillableText.harassColourText[4]
						DrawText3D(tostring("Harass Him"),Enemy.x,Enemy.y, Enemy.z,16,ARGB(a,r,g,b), true)
					else
						DrawText3D(tostring("Not killable"),Enemy.x,Enemy.y, Enemy.z,16,ARGB(244,66,155,255), true)
					end
				end
			end
		end
		if Config.Draw.drawTracker.drawEnemyTracker then
			for i = 1, enemyHerosCount do
				local Enemy = enemyHeros[i].object
				local killable = enemyHeros[i].killable
				local hitDamage = enemyHeros[i].hitDamage
				local comboDmg = enemyHeros[i].comboKillableDmg
				if ValidTarget(Enemy) then
					if ValidTarget(Enemy) and GetDistance(Enemy, cameraPos) < 3000 then
						Enemy.barData = GetEnemyBarData()
						local barPos = GetUnitHPBarPos(Enemy)
						local predDmgbarPos = GetUnitHPBarPos(Enemy)
						local predDmgBarPosOffset = GetUnitHPBarOffset(Enemy)
						local barPosOffset = GetUnitHPBarOffset(Enemy)
						local barOffset = { x = Enemy.barData.PercentageOffset.x, y = Enemy.barData.PercentageOffset.y }
						local barPosPercentageOffset = { x = Enemy.barData.PercentageOffset.x, y = Enemy.barData.PercentageOffset.y }
						local BarPosOffsetX = 171
						local BarPosOffsetY = 46
						local CorrectionY =  14.5
						local StartHpPos = 31
						local healthLeft = Enemy.health-hitDamage
						local comboDmgHealth = comboDmg/Enemy.maxHealth
						local killHimHealth = hitDamage/Enemy.maxHealth
						local predDmgIndicatorPos = 0
						if hitDamage == 0 then
							predDmgIndicatorPos = Enemy.health/Enemy.maxHealth*102
						elseif hitDamage<Enemy.health then
							predDmgIndicatorPos = healthLeft/Enemy.maxHealth*102 --Enemy.health/Enemy.maxHealth*107 keep track of current health
						elseif hitDamage>Enemy.health then
							predDmgIndicatorPos = 0
						end
						local predDmgText = "Pred Dmg"
						local HarassIndicatorPos = Enemy.health/Enemy.maxHealth*102
						local harassText = "Harass"
						local comboKillableIndicatorPos = 0
						local killHimIndicatorPos = 0
						if comboDmg>=Enemy.health then
							comboKillableIndicatorPos = Enemy.health/Enemy.maxHealth*102 --comboDmgHealth*107
					
						end
						local comboKillableText = "comboKillable"
						if hitDamage>=Enemy.health then
							killHimIndicatorPos = Enemy.health/Enemy.maxHealth*102 --killHimHealth*107
						end
						local killHimText = "Kill Him"
						
						--------------------------------------------------Predicted Dmg --------------------------------------------------------
						if Config.Draw.drawTracker.drawDamage then
							predDmgbarPos.x = barPos.x + (predDmgBarPosOffset.x - 0.5 + barPosPercentageOffset.x) * BarPosOffsetX + StartHpPos 
							predDmgbarPos.y = barPos.y + (predDmgBarPosOffset.y - 0.7 + barPosPercentageOffset.y) * BarPosOffsetY + CorrectionY  -- 0.8 max height

							DrawText(tostring(predDmgText),13,predDmgbarPos.x+predDmgIndicatorPos - 10 ,predDmgbarPos.y-36 ,ARGB(255,90,190,255))		
							DrawText("|",13,predDmgbarPos.x+predDmgIndicatorPos ,predDmgbarPos.y ,ARGB(255,90,190,255))
							DrawText("|",13,predDmgbarPos.x+predDmgIndicatorPos ,predDmgbarPos.y-9 ,ARGB(255,90,190,255))
							DrawText("|",13,predDmgbarPos.x+predDmgIndicatorPos ,predDmgbarPos.y-18 ,ARGB(255,90,190,255))
							DrawText("|",13,predDmgbarPos.x+predDmgIndicatorPos ,predDmgbarPos.y-27 ,ARGB(255,90,190,255))
						end
						barPos.x = barPos.x + (barPosOffset.x - 0.5 + barPosPercentageOffset.x) * BarPosOffsetX + StartHpPos 
						barPos.y = barPos.y + (barPosOffset.y - 0.6 + barPosPercentageOffset.y) * BarPosOffsetY + CorrectionY  -- 0.8 max height
						--------------------------------------------------Harass --------------------------------------------------------
						if Config.Draw.drawTracker.drawHarass and (comboDmg<=Enemy.health) then						
							DrawText(tostring(harassText),13,barPos.x+HarassIndicatorPos - 10 ,barPos.y-27 ,ARGB(255,50,255,20))		
							DrawText("|",13,barPos.x+HarassIndicatorPos ,barPos.y ,ARGB(255,50,255,20))
							DrawText("|",13,barPos.x+HarassIndicatorPos ,barPos.y-9 ,ARGB(255,50,255,20))
							DrawText("|",13,barPos.x+HarassIndicatorPos ,barPos.y-18 ,ARGB(255,50,255,20))
						--------------------------------------------------comboKillable --------------------------------------------------------
						elseif Config.Draw.drawTracker.drawComboKiller and (hitDamage<= Enemy.health and comboDmg>Enemy.health) then
							--or (healthLeft<comboDmg and comboDmg<Enemy.health and hitDamage<= Enemy.health) --added marker when prediction in that section
							DrawText(tostring(comboKillableText),13,barPos.x+comboKillableIndicatorPos - 10 ,barPos.y-27 ,ARGB(255,255,143,20))		
							DrawText("|",13,barPos.x+comboKillableIndicatorPos ,barPos.y ,ARGB(255,255,143,20))
							DrawText("|",13,barPos.x+comboKillableIndicatorPos ,barPos.y-9 ,ARGB(255,255,143,20))
							DrawText("|",13,barPos.x+comboKillableIndicatorPos ,barPos.y-18 ,ARGB(255,255,143,20))
						--------------------------------------------------killHim --------------------------------------------------------
						elseif Config.Draw.drawTracker.drawKillHim and (hitDamage>=Enemy.health) then
							DrawText(tostring(killHimText),13,barPos.x+killHimIndicatorPos - 10 ,barPos.y-27 ,ARGB(255,255,30,20))		
							DrawText("|",13,barPos.x+killHimIndicatorPos ,barPos.y ,ARGB(255,255,30,20))
							DrawText("|",13,barPos.x+killHimIndicatorPos ,barPos.y-9 ,ARGB(255,255,30,20))
							DrawText("|",13,barPos.x+killHimIndicatorPos ,barPos.y-18 ,ARGB(255,255,30,20))
							
						end
					end
				end
			end
		end
		if smite ~= nil and Config.SummonerSpells.Smite.useSmite and Config.Draw.SmiteSettings.DrawSmite and not myHero.dead then
			--DrawCircle(myHero.x, myHero.y, myHero.z, smiteRange, 0x992D3D)
			if Config.Draw.LagFree then
           		DrawCircle2(myHero.x, myHero.y, myHero.z, smiteRange, ARGB(175,153,45,61), 3)
           	else
           		DrawCircle(myHero.x, myHero.y, myHero.z, smiteRange, 0x992D3D)
           	end
		end
		if Config.Draw.SmiteSettings.DrawSmiteDamage or Config.Draw.SmiteSettings.DrawSmiteTarget and SMITEREADY then
			if next(jungleMinions)~=nil then
				for i, obj in pairs(jungleMinions) do
					if obj and obj.valid and obj.visible and not obj.dead then
						if GetDistance(obj)<=smiteRange then
							if Config.Draw.SmiteSettings.DrawSmiteTarget and SMITEREADY then
								local healthradius = obj.health*100/obj.maxHealth
								if Config.Draw.LagFree then
					           		DrawCircle2(obj.x, obj.y, obj.z, healthradius+100, ARGB(255,0,255,0), 3)
					           	else
					           		DrawCircle(obj.x, obj.y, obj.z, healthradius+100, 0x00FF00)
					           	end
								if SMITEREADY then
									local smitehealthradius = SmiteDmg*100/obj.maxHealth
									if Config.Draw.LagFree then
						           		DrawCircle2(obj.x, obj.y, obj.z, smitehealthradius+100, ARGB(255,0,255,255), 3)
						           	else
						           		DrawCircle(obj.x, obj.y, obj.z, smitehealthradius+100, 0x00FFFF)
						           	end
								end
							end
						end
						if Config.Draw.SmiteSettings.DrawSmiteDamage and GetDistance(obj)<= smiteRange*2 then
							local wtsobject = WorldToScreen(D3DXVECTOR3(obj.x,obj.y,obj.z))
							local objectX, objectY = wtsobject.x, wtsobject.y
							local onScreen = OnScreen(wtsobject.x, wtsobject.y)
							if onScreen then
								local statusdmgS = SmiteDmg*100/obj.health
								local statuscolorS = (SMITEREADY and 0xFF00FF00 or 0xFFFF0000)
								local textsizeS = statusdmgS < 100 and math.floor((statusdmgS/100)^2*20+8) or 28
								textsizeS = textsizeS > 16 and textsizeS or 16
								DrawText(string.format("%.1f", statusdmgS).."% - Smite", textsizeS, objectX-40, objectY+38, statuscolorS)
								if myHero.charName == "Nunu" and myHero:GetSpellData(_Q).level>0 then
									local statusdmgQ = qDmg*100/obj.health
									local statuscolorQ = (CanUseQ and 0xFF00FF00 or 0xFFFF0000)
									local textsizeQ = statusdmgQ < 100 and math.floor((statusdmgQ/100)^2*20+8) or 28
									textsizeQ = textsizeQ > 16 and textsizeQ or 16
									DrawText(string.format("%.1f", statusdmgQ).."% - Q", textsizeQ, objectX-40, objectY+56, statuscolorQ)
									if SmiteSlot ~= nil then
										local statusdmgSQ = MixDmg*100/obj.health
										local statuscolorSQ = ((SMITEREADY and CanUseQ) and 0xFF00FF00 or 0xFFFF0000)
										local textsizeSQ = statusdmgSQ < 100 and math.floor((statusdmgSQ/100)^2*20+8) or 28
										textsizeSQ = textsizeSQ > 16 and textsizeSQ or 16
										DrawText(string.format("%.1f", statusdmgSQ).."% - Smite+Q", textsizeSQ, objectX-40, objectY+74, statuscolorSQ)
									end
								end
								if myHero.charName == "Chogath" and myHero:GetSpellData(_R).level>0 then
									local statusdmgR = rDmg*100/obj.health
									local statuscolorR = (CanUseR and 0xFF00FF00 or 0xFFFF0000)
									local textsizeR = statusdmgR < 100 and math.floor((statusdmgR/100)^2*20+8) or 28
									textsizeR = textsizeR > 16 and textsizeR or 16
									DrawText(string.format("%.1f", statusdmgR).."% - R", textsizeR, objectX-40, objectY+56, statuscolorR)
									if SmiteSlot ~= nil then
										local statusdmgSR = MixRDmg*100/obj.health
										local statuscolorSR = ((SMITEREADY and CanUseR) and 0xFF00FF00 or 0xFFFF0000)
										local textsizeSR = statusdmgSR < 100 and math.floor((statusdmgSR/100)^2*20+8) or 28
										textsizeSR = textsizeSR > 16 and textsizeSR or 16
										DrawText(string.format("%.1f", statusdmgSR).."% - Smite+R", textsizeSR, objectX-40, objectY+74, statuscolorSR)
									end
								end	
							end
						end
					end
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
function LoadShowGameConfig()
	if Config.ShowGame.Skills.Harass then
		Config.combatKeys:permaShow("harass")
	end
	if Config.ShowGame.Skills.harassToggle then
		Config.combatKeys:permaShow("harassToggle")
	end
    if Config.ShowGame.Skills.Teamfight then
    	Config.combatKeys:permaShow("teamFight")
    end
    if Config.ShowGame.Skills.humanQ then
    	Config.skillActivation:permaShow("humanQ")
    end    
    if Config.ShowGame.Skills.humanW then
    	Config.skillActivation:permaShow("humanW")
    end    
    if Config.ShowGame.Skills.humanE then
    	Config.skillActivation:permaShow("humanE")
    end  
    if Config.ShowGame.Skills.spiderQ then
    	Config.skillActivation:permaShow("spiderQ")
    end    
    if Config.ShowGame.Skills.spiderW then
    	Config.skillActivation:permaShow("spiderW")
    end    
    if Config.ShowGame.Skills.spiderE then
    	Config.skillActivation:permaShow("spiderE")
    end 
    if Config.ShowGame.Skills.TeamfightR then
    	Config.skillActivation:permaShow("UseR")
    end            
    if Config.ShowGame.ShowKS.ksQ then
    	Config.KS:permaShow("KsQ")
    end    
    if Config.ShowGame.ShowKS.ksW then
    	Config.KS:permaShow("KsW")
    end    
    if Config.ShowGame.ShowKS.ksE then
    	Config.KS:permaShow("KsE")
    end      
    if Config.ShowGame.ShowRappel.rappelKill then
    	Config.rappel:permaShow("rappelKill")
    end
    if Config.ShowGame.ShowRappel.rappelDangerous then
    	Config.rappel:permaShow("rappelDangerous")
    end
    if Config.ShowGame.ShowRappel.rappelExtreme then
    	Config.rappel:permaShow("rappelExtreme")
    end
    if Config.ShowGame.ShowRappel.rappelSmite then
    	Config.rappel:permaShow("rappelSmite")
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
   	if ignite ~= nil then
		if Config.ShowGame.ShowIgnite.useIgnite then
	    	Config.SummonerSpells.Ignite:permaShow("useIgnite")
	    end  
		if Config.ShowGame.ShowIgnite.IgniteMode then
	    	Config.SummonerSpells.Ignite:permaShow("IgniteMode")
	    end  
	end
	if smite ~= nil then
		if Config.ShowGame.ShowSmite.useSmite then
	    	Config.SummonerSpells.Smite:permaShow("useSmite")
	    end 
	    if Config.ShowGame.ShowSmite.ChampSmite then
	    	Config.SummonerSpells.Smite:permaShow("ChampSmite")
	    end 
	    if Config.ShowGame.ShowSmite.smiteSmall then
	    	Config.SummonerSpells.Smite:permaShow("smiteSmall")
	    end 
	    if Config.ShowGame.ShowSmite.smiteLarge then
	    	Config.SummonerSpells.Smite:permaShow("smiteLarge")
	    end
	    if Config.ShowGame.ShowSmite.smiteEpic then
	    	Config.SummonerSpells.Smite:permaShow("smiteEpic")
	    end
	    if Config.ShowGame.ShowSmite.LifeSaving then
	    	Config.SummonerSpells.Smite:permaShow("LifeSaving")
	    end
	    if Config.ShowGame.ShowDraw.DrawSmite then
			Config.Draw.SmiteSettings:permaShow("DrawSmite")
		end
		if Config.ShowGame.ShowDraw.DrawSmiteTarget then
			Config.Draw.SmiteSettings:permaShow("DrawSmiteTarget")
		end
		if Config.ShowGame.ShowDraw.DrawSmiteDamage then
			Config.Draw.SmiteSettings:permaShow("DrawSmiteDamage")
		end
	end
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
    if Config.ShowGame.ShowFarm.manaPercent then
    	Config.Farm:permaShow("manaPercent")
    end 
    if Config.ShowGame.ShowFarm.farmAA then
    	Config.Farm.laneFarm:permaShow("farmAA")
    end 
    if Config.ShowGame.ShowFarm.farmOrb then
    	Config.Farm.laneFarm:permaShow("farmOrb")
    end  
    if Config.ShowGame.ShowFarm.farmQ then
    	Config.Farm.laneFarm:permaShow("farmQ")
    end    
    if Config.ShowGame.ShowFarm.farmW then
    	Config.Farm.laneFarm:permaShow("farmW")
    end     
    if Config.ShowGame.ShowFarm.farmFormMode then
    	Config.Farm.laneFarm:permaShow("farmFormMode")
    end          
    if Config.ShowGame.ShowFarm.jungleOrb then
    	Config.Farm.jungleFarm:permaShow("jungleOrb")
    end
    if Config.ShowGame.ShowFarm.jungleAA then
    	Config.Farm.jungleFarm:permaShow("jungleFarmAA")
    end 
    if Config.ShowGame.ShowFarm.Jungle then
    	Config.Farm.jungleFarm:permaShow("jungleFarming")
    end   
    if Config.ShowGame.ShowFarm.JungleQ then
    	Config.Farm.jungleFarm:permaShow("jungleFarmQ")
    end    
    if Config.ShowGame.ShowFarm.JungleW then
    	Config.Farm.jungleFarm:permaShow("jungleFarmW")
    end  
    if Config.ShowGame.ShowFarm.JungleFormMode then
    	Config.Farm.jungleFarm:permaShow("jungleFormMode")
    end  
    if Config.ShowGame.ShowOrb.MoveToMouse then
    	Config.OrbWalk.champOrbWalk:permaShow("moveToMouse")
    end    
    if Config.ShowGame.ShowOrb.AA then
    	Config.OrbWalk.champOrbWalk:permaShow("AA")
	end
    if Config.ShowGame.ShowDraw.LagFree then
		Config.Draw:permaShow("LagFree")
	end    
    if Config.ShowGame.ShowDraw.Target then
    	Config.Draw.drawFocusedTarget:permaShow("DrawFocusedTarget")
    end    
    --[[if Config.ShowGame.ShowDraw.OOM then
    	Config.Draw.drawOOMText:permaShow("OOMText")
    end   ]] 
    if Config.ShowGame.ShowDraw.Killable then
    	Config.Draw.drawEnemyKillableText:permaShow("enemyKillableText")
    end
    if Config.ShowGame.ShowDraw.DrawTracker then
    	Config.Draw.drawTracker:permaShow("drawEnemyTracker")
    end
end