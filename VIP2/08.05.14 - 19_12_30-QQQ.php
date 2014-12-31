<?php exit() ?>--by QQQ 84.160.234.153
--Encrypt this line and below
---------------------------------------------------------------------
--- AutoUpdate for the script ---------------------------------------
---------------------------------------------------------------------
local UPDATE_FILE_PATH = SCRIPT_PATH.."Braum - Poro Barber.lua"
local UPDATE_NAME = "Braum - Poro Barber"
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/bolqqq/BoLScripts/master/Braum%20-%20Poro%20Barber.lua?chunk="..math.random(1, 1000)
local UPDATE_FILE_PATH = SCRIPT_PATH.."Braum - Poro Barber.lua"
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

function AutoupdaterMsg(msg) print("<font color=\"#FF7373\">[".._G.IsLoaded.."]:</font> <font color=\"#FFDFBF\">"..msg..".</font>") end
if _G.BRAUMAUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, UPDATE_PATH)
    if ServerData then
        local ServerVersion = string.match(ServerData, "_G.BraumVersion = \"%d+.%d+\"")
        ServerVersion = string.match(ServerVersion and ServerVersion or "", "%d+.%d+")
        if ServerVersion then
            ServerVersion = tonumber(ServerVersion)
            if tonumber(_G.BraumVersion) < ServerVersion then
                AutoupdaterMsg("A new version is available: ["..ServerVersion.."]")
                AutoupdaterMsg("The script is updating... please don't press [F9]!")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function ()
				AutoupdaterMsg("Successfully updated! (".._G.BraumVersion.." -> "..ServerVersion.."), Please reload (double [F9]) for the updated version!") end) end, 3)
            else
                AutoupdaterMsg("Your script is already the latest version: ["..ServerVersion.."]")
            end
        end
    else
        AutoupdaterMsg("Error downloading version info!")
    end
end
---------------------------------------------------------------------
--- AutoDownload the required libraries -----------------------------
---------------------------------------------------------------------
local REQUIRED_LIBS = 
	{
		["VPrediction"] = "https://raw.github.com/honda7/BoL/master/Common/VPrediction.lua",
		["SOW"] = "https://raw.github.com/honda7/BoL/master/Common/SOW.lua"
	}		
local DOWNLOADING_LIBS = false
local DOWNLOAD_COUNT = 0
local SELF_NAME = GetCurrentEnv() and GetCurrentEnv().FILE_NAME or ""
function AfterDownload()
	DOWNLOAD_COUNT = DOWNLOAD_COUNT - 1
	if DOWNLOAD_COUNT == 0 then
		DOWNLOADING_LIBS = false
		print("<font color=\"#FF7373\">[".._G.IsLoaded.."]:</font><font color=\"#FFDFBF\"> Required libraries downloaded successfully, please reload (double [F9]).</font>")
	end
end
for DOWNLOAD_LIB_NAME, DOWNLOAD_LIB_URL in pairs(REQUIRED_LIBS) do
	if FileExist(LIB_PATH .. DOWNLOAD_LIB_NAME .. ".lua") then
		require(DOWNLOAD_LIB_NAME)
	else
		DOWNLOADING_LIBS = true
		DOWNLOAD_COUNT = DOWNLOAD_COUNT + 1

		print("<font color=\"#FFFF73\">[".._G.IsLoaded.."]:</font><font color=\"#FFDFBF\"> Not all required libraries are installed. Downloading: <b><u><font color=\"#73B9FF\">"..DOWNLOAD_LIB_NAME.."</font></u></b> now! Please don't press [F9]!</font>")
		DownloadFile(DOWNLOAD_LIB_URL, LIB_PATH .. DOWNLOAD_LIB_NAME..".lua", AfterDownload)
	end
end
if DOWNLOADING_LIBS then return end
---------------------------------------------------------------------
--- Vars ------------------------------------------------------------
---------------------------------------------------------------------
-- Vars for JungleClear --
	local JungleMobs = {}
	local JungleFocusMobs = {}
-- Vars for LaneClear --
	local enemyMinions = minionManager(MINION_ENEMY, 500, myHero.visionPos, MINION_SORT_HEALTH_ASC)
-- Vars for TargetSelector --
	local ts
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1000, DAMAGE_MAGIC, true)
	ts.name = "Braum: Target"
-- Misc Vars --
	local enemyHeroes = GetEnemyHeroes()
	local BraumMenu
	local VP = nil
---------------------------------------------------------------------
--- OnLoad ----------------------------------------------------------
---------------------------------------------------------------------
function OnLoad()
--	IgniteCheck()
--	JungleNames()
	--VP = VPrediction()
	bSOW = SOW(VP)
--	AddMenu()
	-- LFC --
--	_G.oldDrawCircle = rawget(_G, 'DrawCircle')
--	_G.DrawCircle = DrawCircle2
	PrintChat("<font color=\"#eFF99CC\">[".._G.IsLoaded.."]:</font><font color=\"#FFDFBF\"> Sucessfully loaded! Version: [<u><b>".._G.BraumVersion.."</b></u>]</font>")
end
---------------------------------------------------------------------
--- Menu ------------------------------------------------------------
---------------------------------------------------------------------
--[[function AddMenu()
	-- Script Menu --
	BraumMenu = scriptConfig("Braum - Poro Barber", "Braum")
	
	-- Target Selector --
	BraumMenu:addTS(ts)
	
	-- Create SubMenu --
	BraumMenu:addSubMenu(""..myHero.charName..": Key Bindings", "KeyBind")
	BraumMenu:addSubMenu(""..myHero.charName..": Extra", "Extra")
	BraumMenu:addSubMenu(""..myHero.charName..": Orbwalk", "Orbwalk")
	BraumMenu:addSubMenu(""..myHero.charName..": SBTW-Combo", "SBTW")
	BraumMenu:addSubMenu(""..myHero.charName..": Harass", "Harass")
	BraumMenu:addSubMenu(""..myHero.charName..": KillSteal", "KS")
	BraumMenu:addSubMenu(""..myHero.charName..": LaneClear", "Farm")
	BraumMenu:addSubMenu(""..myHero.charName..": JungleClear", "Jungle")
	BraumMenu:addSubMenu(""..myHero.charName..": Drawings", "Draw")
	
	
	-- Lane Clear --
	BraumMenu.Farm:addParam("farmInfo", "--- Choose your abilitys for LaneClear ---", SCRIPT_PARAM_INFO, "")
	BraumMenu.Farm:addParam("farmQ", "Farm with "..qName.." (Q): ", SCRIPT_PARAM_ONOFF, true)
	BraumMenu.Farm:addParam("farmW", "Farm with "..wName.." (W): ", SCRIPT_PARAM_ONOFF, true)
	BraumMenu.Farm:addParam("farmE1", "Farm with Slice (E1): ", SCRIPT_PARAM_ONOFF, true)
	BraumMenu.Farm:addParam("farmE2", "Farm with Dice (E2): ", SCRIPT_PARAM_ONOFF, true)
	-- Jungle Clear --
	BraumMenu.Jungle:addParam("jungleInfo", "--- Choose your abilitys for JungleClear ---", SCRIPT_PARAM_INFO, "")
	BraumMenu.Jungle:addParam("jungleQ", "Clear with "..qName.." (Q):", SCRIPT_PARAM_ONOFF, true)
	BraumMenu.Jungle:addParam("jungleW", "Clear with "..wName.." (W):", SCRIPT_PARAM_ONOFF, true)

	-- Drawings --
	BraumMenu.Draw:addParam("drawQ", "Draw (Q) Range:", SCRIPT_PARAM_ONOFF, true)
	BraumMenu.Draw:addParam("drawW", "Draw (W) Range:", SCRIPT_PARAM_ONOFF, false)
	BraumMenu.Draw:addParam("drawE", "Draw (E) Range:", SCRIPT_PARAM_ONOFF, false)
	BraumMenu.Draw:addParam("drawEmax", "Draw (E) max Range:", SCRIPT_PARAM_ONOFF, false)
	BraumMenu.Draw:addParam("drawR", "Draw (R) Range:", SCRIPT_PARAM_ONOFF, false)
	BraumMenu.Draw:addParam("drawKillText", "Draw killtext on enemy: ", SCRIPT_PARAM_ONOFF, true)
	BraumMenu.Draw:addParam("drawTarget", "Draw current target: ", SCRIPT_PARAM_ONOFF, false)
		-- LFC --
	BraumMenu.Draw:addSubMenu("LagFreeCircles: ", "LFC")
	BraumMenu.Draw.LFC:addParam("LagFree", "Activate Lag Free Circles", SCRIPT_PARAM_ONOFF, false)
	BraumMenu.Draw.LFC:addParam("CL", "Length before Snapping", SCRIPT_PARAM_SLICE, 350, 75, 2000, 0)
	BraumMenu.Draw.LFC:addParam("CLinfo", "Higher length = Lower FPS Drops", SCRIPT_PARAM_INFO, "")
end]]--