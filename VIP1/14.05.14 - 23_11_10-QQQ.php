<?php exit() ?>--by QQQ 84.160.234.153
-- Encrypt this line and below
---------------------------------------------------------------------
--- AutoUpdate for the script ---------------------------------------
---------------------------------------------------------------------
local UPDATE_FILE_PATH = SCRIPT_PATH.."Swain - Birds and Generals.lua"
local UPDATE_NAME = "Swain - Birds and Generals"
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/bolqqq/BoLScripts/master/Swain%20-%20Birds%20and%20Generals.lua?chunk="..math.random(1, 1000)
local UPDATE_FILE_PATH = SCRIPT_PATH.."Swain - Birds and Generals.lua"
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

function AutoupdaterMsg(msg) print("<font color=\"#9673FF\">[".._G.IsLoaded.."]:</font> <font color=\"#FFDFBF\">"..msg..".</font>") end
if _G.SWAINAUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, UPDATE_PATH)
    if ServerData then
        local ServerVersion = string.match(ServerData, "_G.SwainVersion = \"%d+.%d+\"")
        ServerVersion = string.match(ServerVersion and ServerVersion or "", "%d+.%d+")
        if ServerVersion then
            ServerVersion = tonumber(ServerVersion)
            if tonumber(_G.SwainVersion) < ServerVersion then
                AutoupdaterMsg("A new version is available: ["..ServerVersion.."]")
                AutoupdaterMsg("The script is updating... please don't press [F9]!")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function ()
				AutoupdaterMsg("Successfully updated! (".._G.SwainVersion.." -> "..ServerVersion.."), Please reload (double [F9]) for the updated version!") end) end, 3)
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
		print("<font color=\"#9673FF\">[".._G.IsLoaded.."]:</font><font color=\"#FF7373\"> Required libraries downloaded successfully, please reload (double [F9]).</font>")
	end
end
for DOWNLOAD_LIB_NAME, DOWNLOAD_LIB_URL in pairs(REQUIRED_LIBS) do
	if FileExist(LIB_PATH .. DOWNLOAD_LIB_NAME .. ".lua") then
		require(DOWNLOAD_LIB_NAME)
	else
		DOWNLOADING_LIBS = true
		DOWNLOAD_COUNT = DOWNLOAD_COUNT + 1

		print("<font color=\"#9673FF\">[".._G.IsLoaded.."]:</font><font color=\"#FFDFBF\"> Not all required libraries are installed. Downloading: <b><u><font color=\"#73B9FF\">"..DOWNLOAD_LIB_NAME.."</font></u></b> now! Please don't press [F9]!</font>")
		DownloadFile(DOWNLOAD_LIB_URL, LIB_PATH .. DOWNLOAD_LIB_NAME..".lua", AfterDownload)
	end
end
if DOWNLOADING_LIBS then return end
---------------------------------------------------------------------
--- Vars ------------------------------------------------------------
---------------------------------------------------------------------
-- Vars for Ranges --
	local qRange = 625
	local wRange = 900
	local eRange = 625
	local rRange = 700
	local wWidth = 250
	local wSpeed = 2000
	local wDelay = 1.3 -- 0.7 test .9
-- Vars for Abilitys --
	local qName = "Decrepify"
	local wName = "Nevermove"
	local eName = "Torment"
	local rName = "Ravenous Flock"
	local qColor = ARGB(100,255,76,76)
	local wColor = ARGB(100,255,76,76)
	local eColor = ARGB(100,76,121,255)
	local rColor = ARGB(100,76,255,76)
	local stealthColor = ARGB(100,179,153,255)
	local TargetColor = ARGB(100,76,255,76)
	local UltTimeTextColorGreen = ARGB(255,0,217,0)
	local UltTimeTextColorBlue = ARGB(255,76,210,255)
	local UltTimeTextColorRed = ARGB(255,255,38,38)
	local Bird = false
	local Human = true
	local BirdStartTime = 0
-- Vars for Damage Calculations and KilltextDrawing --
	local ignite = nil
	local iDmg = 0
	local qDmg = 0
	local wDmg = 0
	local eDmg = 0
	local rDmg = 0
	local dfgDmg = 0
	local hxgDmg = 0
	local bwcDmg = 0
	local botrkDmg = 0
	local sheenDmg = 0
	local lichbaneDmg = 0
	local trinityDmg = 0
	local liandrysDmg = 0
	local KillText = {}
	local KillTextColor = ARGB(250, 255, 38, 1)
	local KillTextList = {		
							"Harass your enemy!", 					-- 01
							"Wait for your CD's!",					-- 02
							"Kill! - Ignite",						-- 03
							"Kill! - (Q)",							-- 04 
							"Kill! - (W)",							-- 05
							"Kill! - (E)",							-- 06
							"Kill! - (Q)+(W)",						-- 07
							"Kill! - (Q)+(E)",						-- 08
							"Kill! - (W)+(E)",						-- 09
							"Kill! - (Q)+(W)+(E)",					-- 10
							"Kill! - (Q)+(W)+(E)+(R)"				-- 11
						}
-- Vars for JungleClear --
	local JungleMobs = {}
	local JungleFocusMobs = {}
-- Vars for LaneClear --
	local enemyMinions = minionManager(MINION_ENEMY, 650, myHero.visionPos, MINION_SORT_HEALTH_ASC)
-- Vars for TargetSelector --
	local ts
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1200, DAMAGE_MAGIC, true)
	ts.name = "Swain: Target"
-- Vars for Autolevel --
	levelSequence = {
					startQ = { 1,3,2,3,3,4,3,1,3,1,4,1,1,2,2,4,2,2 },
					startW = { 2,3,1,3,3,4,3,1,3,1,4,1,1,2,2,4,2,2 },
					startE = { 3,1,2,3,3,4,3,1,3,1,4,1,1,2,2,4,2,2 }
					}
-- Misc Vars --
	local enemyHeroes = GetEnemyHeroes()
	local SwainMenu
	local Recalling = false
	local VP = nil
---------------------------------------------------------------------
--- OnLoad ----------------------------------------------------------
---------------------------------------------------------------------
function OnLoad()
	IgniteCheck()
	JungleNames()
	VP = VPrediction()
	sSOW = SOW(VP)
	AddMenu()
	-- LFC --
	_G.oldDrawCircle = rawget(_G, 'DrawCircle')
	_G.DrawCircle = DrawCircle2
	PrintChat("<font color=\"#9673FF\">[".._G.IsLoaded.."]:</font><font color=\"#FFDFBF\"> Sucessfully loaded! Version: [<u><b>".._G.SwainVersion.."</b></u>]</font>")
end
---------------------------------------------------------------------
--- Menu ------------------------------------------------------------
---------------------------------------------------------------------
function AddMenu()
	-- Script Menu --
	SwainMenu = scriptConfig("Swain - Birds and Generals", "Swain")
	
	-- Target Selector --
	SwainMenu:addTS(ts)
	
	-- Create SubMenu --
	SwainMenu:addSubMenu(""..myHero.charName..": Key Bindings", "KeyBind")
	SwainMenu:addSubMenu(""..myHero.charName..": Extra", "Extra")
	SwainMenu:addSubMenu(""..myHero.charName..": Orbwalk", "Orbwalk")
	SwainMenu:addSubMenu(""..myHero.charName..": Ultimate", "Ultimate")
	SwainMenu:addSubMenu(""..myHero.charName..": SBTW-Combo", "SBTW")
	SwainMenu:addSubMenu(""..myHero.charName..": Harass", "Harass")
	SwainMenu:addSubMenu(""..myHero.charName..": KillSteal", "KS")
	SwainMenu:addSubMenu(""..myHero.charName..": LaneClear", "Farm")
	SwainMenu:addSubMenu(""..myHero.charName..": JungleClear", "Jungle")
	SwainMenu:addSubMenu(""..myHero.charName..": Drawings", "Draw")
	
	-- KeyBindings --
	SwainMenu.KeyBind:addParam("SBTWKey", "SBTW-Combo Key: ", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	SwainMenu.KeyBind:addParam("HarassKey", "HarassKey: ", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
	SwainMenu.KeyBind:addParam("HarassToggleKey", "Toggle Harass: ", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("U"))
	SwainMenu.KeyBind:addParam("ClearKey", "Jungle- and LaneClear Key: ", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))

	-- Extra --
	SwainMenu.Extra:addParam("AutoLevelSkills", "Auto Level Skills (Reload Script!)", SCRIPT_PARAM_LIST, 1, {"No Autolevel", "QEWE - R>E>Q>W", "WEQE - R>E>Q>W", "EQWE - R>E>Q>W"})
	SwainMenu.Extra:addParam("extraInfo", "--- Packet Settings ---", SCRIPT_PARAM_INFO, "")
	SwainMenu.Extra:addParam("packetUsage", "Use packets to cast spells: ", SCRIPT_PARAM_ONOFF, true)
	
	-- SOW-Orbwalking --
	sSOW:LoadToMenu(SwainMenu.Orbwalk)
	
	-- Ultimate --
	SwainMenu.Ultimate:addParam("autoUltimate", "Use AutoUltimate to heal: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Ultimate:addParam("autoUltimateRangeSlider", "Transform if no Champions in range: ", SCRIPT_PARAM_SLICE, 700, 750, 1100, 1)
	SwainMenu.Ultimate:addParam("autoUltimateHealth", "Use AutoUltimate if below % HP: ", SCRIPT_PARAM_SLICE, 40, 0, 100, -1)
	SwainMenu.Ultimate:addParam("autoUltimateMana", "Use AutoUltimate if over % Mana: ", SCRIPT_PARAM_SLICE, 20, 0, 100, -1)
	SwainMenu.Ultimate:addParam("autoUltimateInfo", "", SCRIPT_PARAM_INFO, "")
	SwainMenu.Ultimate:addParam("autoUltimateInfo", "Humanform transform to Bird if: ", SCRIPT_PARAM_INFO, "")
	SwainMenu.Ultimate:addParam("autoUltimateHumanHealthlow", "Mana > Slider, Health < Slider: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Ultimate:addParam("autoUltimateInfo", "", SCRIPT_PARAM_INFO, "")
	SwainMenu.Ultimate:addParam("autoUltimateInfo", "Birdform transform to Human if: ", SCRIPT_PARAM_INFO, "")
	SwainMenu.Ultimate:addParam("autoUltimateBirdManalow", "Mana < Slider: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Ultimate:addParam("autoUltimateBirdManaHighHealthHigh", "Mana < Slider, Health > Menu, no Target: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Ultimate:addParam("autoUltimateBirdManaHighHealthLow", "Mana < Slider, Health < Menu, no Target: ", SCRIPT_PARAM_ONOFF, true)
	
	-- SBTW-Combo --
	SwainMenu.SBTW:addParam("sbtwItems", "Use Items in Combo: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.SBTW:addParam("sbtwInfo", "", SCRIPT_PARAM_INFO, "")
	SwainMenu.SBTW:addParam("sbtwInfo", "--- Choose your abilitys for SBTW ---", SCRIPT_PARAM_INFO, "")
	SwainMenu.SBTW:addParam("sbtwQ", "Use "..qName.." (Q) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.SBTW:addParam("sbtwW", "Use "..wName.." (W) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.SBTW:addParam("sbtwE", "Use "..eName.." (E) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.SBTW:addParam("sbtwR", "Use "..rName.." (R) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.SBTW:addParam("sbtwRSlider", "Use (R) if more then X enemys: ", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
	SwainMenu.SBTW:addParam("sbtwRRangeSlider", "Transform if no enemys in range: ", SCRIPT_PARAM_SLICE, 900, 700, 1100, 1)
	SwainMenu.SBTW:addParam("sbtwMana", "Don't Ult if below % Mana: ", SCRIPT_PARAM_SLICE, 10, 0, 100, -1)

	-- Harass --
	SwainMenu.Harass:addParam("harassMode", "Choose your HarassMode: ", SCRIPT_PARAM_LIST, 2, {"Q", "Q-E", "Q-W-E"})
	SwainMenu.Harass:addParam("harassMana", "Don't Harass if below % Mana: ", SCRIPT_PARAM_SLICE, 20, 0, 100, -1)
	SwainMenu.Harass:addParam("harassForceAll", "Force all spells available for max dmg: ", SCRIPT_PARAM_ONOFF, false)
	SwainMenu.Harass:addParam("harassInfo", "", SCRIPT_PARAM_INFO, "")
	SwainMenu.Harass:addParam("harassInfo", "--- Choose your abilitys for Harass ---", SCRIPT_PARAM_INFO, "")
	SwainMenu.Harass:addParam("harassQ","Use "..qName.." (Q) in Harass:", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Harass:addParam("harassW","Use "..wName.." (W) in Harass:", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Harass:addParam("harassE","Use "..eName.." (E) in Harass:", SCRIPT_PARAM_ONOFF, true)
	
	-- KillSteal --
	SwainMenu.KS:addParam("Ignite", "Use Auto Ignite: ", SCRIPT_PARAM_ONOFF, false)
	SwainMenu.KS:addParam("smartKS", "Enable smart KS: ", SCRIPT_PARAM_ONOFF, true)
	
	-- Lane Clear --
	SwainMenu.Farm:addParam("farmMana", "Don't LaneClear if below % Mana: ", SCRIPT_PARAM_SLICE, 20, 0, 100, -1)
	SwainMenu.Farm:addParam("farmInfo", "--- Choose your abilitys for LaneClear ---", SCRIPT_PARAM_INFO, "")
	SwainMenu.Farm:addParam("farmQ", "Farm with "..qName.." (Q): ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Farm:addParam("farmW", "Farm with "..wName.." (W): ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Farm:addParam("farmE", "Farm with "..eName.." (E): ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Farm:addParam("farmR", "Farm with "..rName.." (R): ", SCRIPT_PARAM_ONOFF, false)
	
	-- Jungle Clear --
	SwainMenu.Jungle:addParam("jungleMana", "Don't JungleClear if below % Mana: ", SCRIPT_PARAM_SLICE, 10, 0, 100, -1)
	SwainMenu.Jungle:addParam("jungleInfo", "--- Choose your abilitys for JungleClear ---", SCRIPT_PARAM_INFO, "")
	SwainMenu.Jungle:addParam("jungleQ", "Clear with "..qName.." (Q):", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Jungle:addParam("jungleW", "Clear with "..wName.." (W):", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Jungle:addParam("jungleE", "Clear with "..eName.." (E):", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Jungle:addParam("jungleR", "Clear with "..rName.." (R):", SCRIPT_PARAM_ONOFF, false)

	-- Drawings --
	SwainMenu.Draw:addParam("drawQ", "Draw (Q) Range:", SCRIPT_PARAM_ONOFF, false)
	SwainMenu.Draw:addParam("drawW", "Draw (W) Range:", SCRIPT_PARAM_ONOFF, false)
	SwainMenu.Draw:addParam("drawE", "Draw (E) Range:", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Draw:addParam("drawR", "Draw (R) Range:", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Draw:addParam("drawKillText", "Draw killtext on enemy: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Draw:addParam("drawTarget", "Draw current target: ", SCRIPT_PARAM_ONOFF, false)
	SwainMenu.Draw:addParam("ultTime", "Draw time you can stay in Ultimate: ", SCRIPT_PARAM_ONOFF, false)

		-- LFC --
	SwainMenu.Draw:addSubMenu("LagFreeCircles: ", "LFC")
	SwainMenu.Draw.LFC:addParam("LagFree", "Activate Lag Free Circles", SCRIPT_PARAM_ONOFF, false)
	SwainMenu.Draw.LFC:addParam("CL", "Length before Snapping", SCRIPT_PARAM_SLICE, 350, 75, 2000, 0)
	SwainMenu.Draw.LFC:addParam("CLinfo", "Higher length = Lower FPS Drops", SCRIPT_PARAM_INFO, "")
		-- Permashow --
	SwainMenu.Draw:addSubMenu("PermaShow: ", "PermaShow")
	SwainMenu.Draw.PermaShow:addParam("info", "--- Reload (Double F9) if you change the settings ---", SCRIPT_PARAM_INFO, "")
	SwainMenu.Draw.PermaShow:addParam("HarassMode", "Show HarassMode: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Draw.PermaShow:addParam("HarassToggleKey", "Show HarassToggleKey: ", SCRIPT_PARAM_ONOFF, true)
	
	if SwainMenu.Draw.PermaShow.HarassMode
		then SwainMenu.Harass:permaShow("harassMode") 
	end
	if SwainMenu.Draw.PermaShow.HarassToggleKey
		then SwainMenu.KeyBind:permaShow("HarassToggleKey") 
	end
	
	-- Other --
	SwainMenu:addParam("Version", "Version", SCRIPT_PARAM_INFO, _G.SwainVersion)
	SwainMenu:addParam("Author", "Author", SCRIPT_PARAM_INFO, _G.SwainAuthor)
end
---------------------------------------------------------------------
--- On Tick ---------------------------------------------------------
---------------------------------------------------------------------
function OnTick()
	if myHero.dead then return end
	ts:update()
	Target = ts.target 
	Check()
	LFCfunc()
	AutoLevelMySkills()
	KeyBindings()
	DamageCalculation()
	SwainsUltimate()

	if Target
		then
			if SwainMenu.KS.Ignite then AutoIgnite(Target) end
	end

	if SBTWKey then SBTW() end
	if HarassKey then Harass() end
	if HarassToggleKey then Harass() end
	if ClearKey then LaneClear() JungleClear() end
	if SwainMenu.KS.smartKS then smartKS() end
end
---------------------------------------------------------------------
--- Function KeyBindings for easier KeyManagement -------------------
---------------------------------------------------------------------
function KeyBindings()
	SBTWKey = SwainMenu.KeyBind.SBTWKey
	HarassKey = SwainMenu.KeyBind.HarassKey
	HarassToggleKey = SwainMenu.KeyBind.HarassToggleKey
	ClearKey = SwainMenu.KeyBind.ClearKey
end
---------------------------------------------------------------------
--- Function Checks -------------------------------------------------
---------------------------------------------------------------------
function Check()
	-- Cooldownchecks for Abilitys and Summoners -- 
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	WREADY = (myHero:CanUseSpell(_W) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)
	IREADY = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
	
	-- Check if items are ready -- 
		dfgReady		= (dfgSlot		~= nil and myHero:CanUseSpell(dfgSlot)		== READY) -- Deathfire Grasp
		hxgReady		= (hxgSlot		~= nil and myHero:CanUseSpell(hxgSlot)		== READY) -- Hextech Gunblade
		bwcReady		= (bwcSlot		~= nil and myHero:CanUseSpell(bwcSlot)		== READY) -- Bilgewater Cutlass
		botrkReady		= (botrkSlot	~= nil and myHero:CanUseSpell(botrkSlot)	== READY) -- Blade of the Ruined King
		sheenReady		= (sheenSlot 	~= nil and myHero:CanUseSpell(sheenSlot) 	== READY) -- Sheen
		lichbaneReady	= (lichbaneSlot ~= nil and myHero:CanUseSpell(lichbaneSlot) == READY) -- Lichbane
		trinityReady	= (trinitySlot 	~= nil and myHero:CanUseSpell(trinitySlot) 	== READY) -- Trinity Force
		lyandrisReady	= (liandrysSlot	~= nil and myHero:CanUseSpell(liandrysSlot) == READY) -- Liandrys 
		tmtReady		= (tmtSlot 		~= nil and myHero:CanUseSpell(tmtSlot)		== READY) -- Tiamat
		hdrReady		= (hdrSlot		~= nil and myHero:CanUseSpell(hdrSlot) 		== READY) -- Hydra
		youReady		= (youSlot		~= nil and myHero:CanUseSpell(youSlot)		== READY) -- Youmuus Ghostblade
	
	-- Set the slots for item --
		dfgSlot 		= GetInventorySlotItem(3128)
		hxgSlot 		= GetInventorySlotItem(3146)
		bwcSlot 		= GetInventorySlotItem(3144)
		botrkSlot		= GetInventorySlotItem(3153)							
		sheenSlot		= GetInventorySlotItem(3057)
		lichbaneSlot	= GetInventorySlotItem(3100)
		trinitySlot		= GetInventorySlotItem(3078)
		liandrysSlot	= GetInventorySlotItem(3151)
		tmtSlot			= GetInventorySlotItem(3077)
		hdrSlot			= GetInventorySlotItem(3074)	
		youSlot			= GetInventorySlotItem(3142)
end
---------------------------------------------------------------------
--- ItemUsage -------------------------------------------------------
---------------------------------------------------------------------
function UseItems()
	if not enemy then enemy = Target end
	if ValidTarget(enemy) then
		if dfgReady		and GetDistance(enemy) <= 750 then CastSpell(dfgSlot, enemy) end
		if hxgReady		and GetDistance(enemy) <= 700 then CastSpell(hxgSlot, enemy) end
		if bwcReady		and GetDistance(enemy) <= 450 then CastSpell(bwcSlot, enemy) end
		if botrkReady	and GetDistance(enemy) <= 450 then CastSpell(botrkSlot, enemy) end
		if tmtReady		and GetDistance(enemy) <= 185 then CastSpell(tmtSlot) end
		if hdrReady 	and GetDistance(enemy) <= 185 then CastSpell(hdrSlot) end
		if youReady		and GetDistance(enemy) <= 185 then CastSpell(youSlot) end
	end
end
---------------------------------------------------------------------
--- Draw Function ---------------------------------------------------
---------------------------------------------------------------------	
function OnDraw()
	if myHero.dead then return end 
-- Draw SpellRanges only when our champ is alive and the spell is ready --
	-- Draw Q + E + R --
		if QREADY and SwainMenu.Draw.drawQ then DrawCircle(myHero.x, myHero.y, myHero.z, qRange, qColor) end
		if WREADY and SwainMenu.Draw.drawW then DrawCircle(myHero.x, myHero.y, myHero.z, wRange, wColor) end
		if EREADY and SwainMenu.Draw.drawE then DrawCircle(myHero.x, myHero.y, myHero.z, eRange, eColor) end
		if RREADY and SwainMenu.Draw.drawR then DrawCircle(myHero.x, myHero.y, myHero.z, rRange, rColor) end
	-- Draw Target --
	if Target ~= nil and SwainMenu.Draw.drawTarget
		then DrawCircle(Target.x, Target.y, Target.z, (GetDistance(Target.minBBox, Target.maxBBox)/2), TargetColor)
	end
	if SwainMenu.Draw.ultTime and Bird
		then	local barPos = WorldToScreen(D3DXVECTOR3(myHero.x, myHero.y, myHero.z))
				local PosX = barPos.x - 70
				local PosY = barPos.y - 80
				local rManaValue = 0
				if myHero.level >= 6 and myHero.level <= 10 then rManaValue = 5
				elseif myHero.level >= 11 and myHero.level <= 15 then rManaValue = 6
				elseif myHero.level >= 16 then rManaValue = 7
				end
				local CurrentTimeInUlt = os.clock()
				local TimeInUltform = CurrentTimeInUlt - BirdStartTime
				local CurrentRManaCost = TimeInUltform * rManaValue + 25
				local TransformBackTime = myHero.mana/CurrentRManaCost
				if TransformBackTime >= 20
					then DrawText("Time you can stay in ult:"..string.format("%4.0f", ((TransformBackTime))), 16, PosX, PosY, UltTimeTextColorGreen)
				elseif TransformBackTime <= 19 and TransformBackTime >= 6
					then DrawText("Time you can stay in ult:"..string.format("%4.0f", ((TransformBackTime))), 16, PosX, PosY, UltTimeTextColorBlue)
				elseif TransformBackTime <= 5
					then DrawText("Time you can stay in ult:"..string.format("%4.0f", ((TransformBackTime))), 16, PosX, PosY, UltTimeTextColorRed)
				end
	end
	-- Draw KillText --
	if SwainMenu.Draw.drawKillText then
			for i = 1, heroManager.iCount do
				local enemy = heroManager:GetHero(i)
				if ValidTarget(enemy) and enemy ~= nil then
					local barPos = WorldToScreen(D3DXVECTOR3(enemy.x, enemy.y, enemy.z))
					local PosX = barPos.x - 20
					local PosY = barPos.y - 80
					DrawText(KillTextList[KillText[i]], 14, PosX, PosY, KillTextColor)
				end
			end
	end
end
---------------------------------------------------------------------
--- Cast Functions for Spells ---------------------------------------
---------------------------------------------------------------------
-- Swain Q --
function CastTheQ(enemy)
		if not enemy then enemy = Target end
		if (not QREADY or (GetDistance(enemy) > qRange))
			then return false
		end
		if ValidTarget(enemy) and not SwainMenu.Extra.packetUsage
			then CastSpell(_Q, enemy)
			return true
		elseif ValidTarget(enemy) and SwainMenu.Extra.packetUsage
			then Packet("S_CAST", { spellId = _Q, targetNetworkId = enemy.networkID }):send()
			return true
		end
		return false
end
-- Swain W --
function AimTheX(enemy)
	local CastPosition, HitChance, Position = VP:GetCircularCastPosition(enemy, wDelay, wWidth, wRange, wSpeed, myHero, false)
	if HitChance >= 2 and GetDistance(enemy) <= wRange and WREADY
		then CastSpell(_W, CastPosition.x, CastPosition.z)
	end
end
function AimTheW(enemy)
	local AoeCastPosition, MainTargetHitChance = VP:GetCircularAOECastPosition(enemy, wDelay, wWidth, wRange, wSpeed, myHero)
	if MainTargetHitChance >= 2 and GetDistance(enemy) <= wRange and WREADY
		then CastSpell(_W, AoeCastPosition.x, AoeCastPosition.z)
	end
end
-- Swain E --
function CastTheE(enemy)
		if not enemy then enemy = Target end
		if (not EREADY or (GetDistance(enemy) > eRange))
			then return false
		end
		if ValidTarget(enemy) and not SwainMenu.Extra.packetUsage
			then CastSpell(_E, enemy)
			return true
		elseif ValidTarget(enemy) and SwainMenu.Extra.packetUsage
			then Packet("S_CAST", { spellId = _E, targetNetworkId = enemy.networkID }):send()
			return true
		end
		return false
end
-- Swain R --
function CastTheR()
	-- Transforms us into Bird when Human and enough Mana and enough enemys in rRange
	if RREADY and Human and ManaCheck(SwainMenu.SBTW.sbtwMana) and CountEnemyHeroInRange(rRange) >= SwainMenu.SBTW.sbtwRSlider
		then CastSpell(_R)
	end
	-- Transforms us into Human when Bird and no enemys in Range
	if RREADY and Bird and CountEnemyHeroInRange(SwainMenu.SBTW.sbtwRRangeSlider) == 0
		then CastSpell(_R) 
	end
end
---------------------------------------------------------------------
--- Functions for Formdetection ------------------------------------- 
---------------------------------------------------------------------
function OnGainBuff(unit, buff)
	if unit.isMe and buff.name == 'SwainMetamorphism'
		then Bird = true Human = false 
		BirdStartTime = os.clock()
    end 
end
function OnLoseBuff(unit, buff)
	if unit.isMe and buff.name == 'SwainMetamorphism'
		then Bird = false Human = true
	end 
end
function OnProcessSpell(object,spell)
	if object == myHero and spell.name == "SwainMetamorphism"
		then
			if Human then Bird = true Human = false
			else Bird = false Human = true
			end
	end
end
---------------------------------------------------------------------
--- Function Swains Ultimate ---------------------------------------- 
---------------------------------------------------------------------
function SwainsUltimate()
	if not RREADY then return end
	if SwainMenu.Ultimate.autoUltimate and not Recalling and not SBTWKey
		then
			healMinion = HealMinion()
			JungleMinion = GetJungleMob()
				-- if we are ulting --
				if Bird
					then
						-- If mana < Menu 
						if myHero.mana < (myHero.maxMana * (SwainMenu.Ultimate.autoUltimateMana/100)) and SwainMenu.Ultimate.autoUltimateBirdManalow
							then CastSpell(_R)
						end
					    -- if Mana > Menu and Health > Menu and no Target around
						if myHero.mana > (myHero.maxMana * (SwainMenu.Ultimate.autoUltimateMana/100)) and myHero.health > (myHero.maxHealth * (SwainMenu.Ultimate.autoUltimateHealth/100)) and SwainMenu.Ultimate.autoUltimateBirdManaHighHealthHigh
							then 
								if EnemysInRange(SwainMenu.Ultimate.autoUltimateRangeSlider) == 0 and healMinion == nil and JungleMinion == nil
									then CastSpell(_R)
								end
						end
						-- if Mana > Menu and Health < Menu and no Target around
						if myHero.mana > (myHero.maxMana * (SwainMenu.Ultimate.autoUltimateMana/100)) and myHero.health < (myHero.maxHealth * (SwainMenu.Ultimate.autoUltimateHealth/100)) and SwainMenu.Ultimate.autoUltimateBirdManaHighHealthLow
							then 
								if EnemysInRange(SwainMenu.Ultimate.autoUltimateRangeSlider) == 0 and healMinion == nil and JungleMinion == nil
									then CastSpell(_R)
								end
						end
				end
				-- if we are not ulting --
				if Human
					then
						-- if mana > Menu and Health < Menu and Target around
						if myHero.mana > (myHero.maxMana * (SwainMenu.Ultimate.autoUltimateMana/100)) and myHero.health < (myHero.maxHealth * (SwainMenu.Ultimate.autoUltimateHealth/100)) and (Target ~= nil or Minion ~= nil or JungleMinion ~= nil) and SwainMenu.Ultimate.autoUltimateHumanHealthlow
							then CastSpell(_R)
						end
				end
	end
end
---------------------------------------------------------------------
-- Function EnemysInRange ------------------------------------------- 
-- Counts the enemys in a specific range (RangeToEnemys)
-- and returns the value as a number e.g. 2 if two are in Range
---------------------------------------------------------------------
function EnemysInRange(RangeToEnemys)
	local enemysInRange = 0
	for i = 1, heroManager.iCount, 1 do
	local enemyHeroes = heroManager:getHero(i)
		if enemyHeroes.valid and enemyHeroes.visible and enemyHeroes.dead == false and enemyHeroes.team ~= myHero.team and GetDistance(enemyHeroes) <= RangeToEnemys
			then enemysInRange = enemysInRange + 1 end
		end
	return enemysInRange
end
---------------------------------------------------------------------
--- SBTW Functions --------------------------------------------------
---------------------------------------------------------------------
function SBTW()
	if ValidTarget(Target)
		then 
			if SwainMenu.SBTW.sbtwQ then CastTheQ(Target) end
			if SwainMenu.SBTW.sbtwW then AimTheW(Target) end
			if SwainMenu.SBTW.sbtwE then CastTheE(Target) end
			if SwainMenu.SBTW.sbtwR then CastTheR() end
			if SwainMenu.SBTW.sbtwItems then UseItems() end
	end
end
---------------------------------------------------------------------
--- Harass Functions ------------------------------------------------
---------------------------------------------------------------------
function Harass()
	if Target and ManaCheck(SwainMenu.Harass.harassMana)
			then
				if not SwainMenu.Harass.harassForceAll
					then
						-- Q --
						if SwainMenu.Harass.harassMode == 1
							then 
								if SwainMenu.Harass.harassQ then CastTheQ(Target) end
						end
						-- Q + E --
						if SwainMenu.Harass.harassMode == 2
							then
								if SwainMenu.Harass.harassQ then CastTheQ(Target) end
								if SwainMenu.Harass.harassE then CastTheE(Target) end
						end
						-- Q + W + E --
						if SwainMenu.Harass.harassMode == 3
							then
								if SwainMenu.Harass.harassQ then CastTheQ(Target) end
								if SwainMenu.Harass.harassW then AimTheW(Target) end
								if SwainMenu.Harass.harassE then CastTheE(Target) end
						end
				end
				if SwainMenu.Harass.harassForceAll
					then
						-- Q --
						if SwainMenu.Harass.harassMode == 1
							then 
								if SwainMenu.Harass.harassQ then CastTheQ(Target) end
						end
						-- Q + E and Force all spells --
						if SwainMenu.Harass.harassMode == 2 and SwainMenu.Harass.harassForceAll
							then
								if QREADY and EREADY
									then
										if SwainMenu.Harass.harassE then CastTheE(Target) end
										if SwainMenu.Harass.harassQ then CastTheQ(Target) end
								end
						end
						-- Q + W + E and Force all spells --
						if SwainMenu.Harass.harassMode == 3
							then
								if QREADY and EREADY
									then
										if SwainMenu.Harass.harassE then CastTheE(Target) end
										if SwainMenu.Harass.harassQ then CastTheQ(Target) end
										if SwainMenu.Harass.harassW then AimTheW(Target) end
								end
						end	
				end
	end
end
---------------------------------------------------------------------
--- KillSteal Functions ---------------------------------------------
---------------------------------------------------------------------
function AutoIgnite(enemy)
		if enemy.health <= iDmg and GetDistance(enemy) <= 600 and ignite ~= nil
			then
				if IREADY then CastSpell(ignite, enemy) end
		end
end
-- Checks the Summonerspells for ignite (OnLoad) --
function IgniteCheck()
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
			ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
			ignite = SUMMONER_2
	end
end
function smartKS()
	for _, enemy in pairs(enemyHeroes) do
		if enemy ~= nil and ValidTarget(enemy) then
		local distance = GetDistance(enemy)
		local hp = enemy.health
			if hp <= qDmg and QREADY and (distance <= qRange)
				then CastTheQ(enemy)
			elseif hp <= wDmg and WREADY and (distance <= wRange) 
				then AimTheW(enemy)
			elseif hp <= eDmg and EREADY and (distance <= eRange) 
				then CastTheE(enemy)
			elseif hp <= (qDmg + wDmg) and QREADY and WREADY and (distance <= qRange)
				then CastTheQ(enemy)
			elseif hp <= (qDmg + eDmg) and QREADY and EREADY and (distance <= qRange)
				then CastTheE(enemy)
			elseif hp <= (wDmg + eDmg) and WREADY and EREADY and (distance <= eRange)
				then CastTheE(enemy)
			elseif hp <= (qDmg + wDmg + eDmg) and QREADY and WREADY and EREADY and (distance <= eRange)
				then CastTheE(enemy)
			elseif hp <= (qDmg + wDmg + eDmg+rDmg) and QREADY and WREADY and EREADY and RREADY and (distance <= eRange)
				then CastTheE(enemy)
			end
		end
	end
end
---------------------------------------------------------------------
-- Jungle Mob Names -------------------------------------------------
---------------------------------------------------------------------
function JungleNames()
-- JungleMobNames are the names of the smaller Junglemobs --
	JungleMobNames =
{
	-- Blue Side --
		-- Blue Buff --
		["YoungLizard1.1.2"] = true, ["YoungLizard1.1.3"] = true,
		-- Red Buff --
		["YoungLizard4.1.2"] = true, ["YoungLizard4.1.3"] = true,
		-- Wolf Camp --
		["wolf2.1.2"] = true, ["wolf2.1.3"] = true,
		-- Wraith Camp --
		["LesserWraith3.1.2"] = true, ["LesserWraith3.1.3"] = true, ["LesserWraith3.1.4"] = true,
		-- Golem Camp --
		["SmallGolem5.1.1"] = true,
	-- Purple Side --
		-- Blue Buff --
		["YoungLizard7.1.2"] = true, ["YoungLizard7.1.3"] = true,
		-- Red Buff --
		["YoungLizard10.1.2"] = true, ["YoungLizard10.1.3"] = true,
		-- Wolf Camp --
		["wolf8.1.2"] = true, ["wolf8.1.3"] = true,
		-- Wraith Camp --
		["LesserWraith9.1.2"] = true, ["LesserWraith9.1.3"] = true, ["LesserWraith9.1.4"] = true,
		-- Golem Camp --
		["SmallGolem11.1.1"] = true,
}
-- FocusJungleNames are the names of the important/big Junglemobs --
	FocusJungleNames =
{
	-- Blue Side --
		-- Blue Buff --
		["AncientGolem1.1.1"] = true,
		-- Red Buff --
		["LizardElder4.1.1"] = true,
		-- Wolf Camp --
		["GiantWolf2.1.1"] = true,
		-- Wraith Camp --
		["Wraith3.1.1"] = true,		
		-- Golem Camp --
		["Golem5.1.2"] = true,		
		-- Big Wraith --
		["GreatWraith13.1.1"] = true, 
	-- Purple Side --
		-- Blue Buff --
		["AncientGolem7.1.1"] = true,
		-- Red Buff --
		["LizardElder10.1.1"] = true,
		-- Wolf Camp --
		["GiantWolf8.1.1"] = true,
		-- Wraith Camp --
		["Wraith9.1.1"] = true,
		-- Golem Camp --
		["Golem11.1.2"] = true,
		-- Big Wraith --
		["GreatWraith14.1.1"] = true,
	-- Dragon --
		["Dragon6.1.1"] = true,
	-- Baron --
		["Worm12.1.1"] = true,
}
	for i = 0, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil then
			if FocusJungleNames[object.name] then
				table.insert(JungleFocusMobs, object)
			elseif JungleMobNames[object.name] then
				table.insert(JungleMobs, object)
			end
		end
	end
end
---------------------------------------------------------------------
--- Jungle Clear ----------------------------------------------------
---------------------------------------------------------------------
function JungleClear()
	JungleMob = GetJungleMob()
		if JungleMob ~= nil and ManaCheck(SwainMenu.Jungle.jungleMana) then
			if SwainMenu.Jungle.jungleQ then CastTheQ(JungleMob) end			
			if SwainMenu.Jungle.jungleW then AimTheW(JungleMob) end			
			if SwainMenu.Jungle.jungleE then CastTheE(JungleMob) end
			if SwainMenu.Jungle.jungleR
				then 
					if Human then CastSpell(_R) end
			end
		end
		-- Transforms back to Human after clearing --
		if JungleMob == nil and Bird then CastSpell(_R) end
end
-- Get Jungle Mob --
function GetJungleMob()
        for _, Mob in pairs(JungleFocusMobs) do
                if ValidTarget(Mob, eRange) then return Mob end
        end
        for _, Mob in pairs(JungleMobs) do
                if ValidTarget(Mob, eRange) then return Mob end
        end
end
---------------------------------------------------------------------
--- Lane Clear ------------------------------------------------------
---------------------------------------------------------------------
function LaneClear()
	enemyMinions:update()
	for _, minion in pairs(enemyMinions.objects) do
		if ValidTarget(minion) and minion ~= nil and not sSOW:CanAttack() and ManaCheck(SwainMenu.Farm.farmMana)
			then 
				if SwainMenu.Farm.farmQ then CastTheQ(minion) end
				if SwainMenu.Farm.farmW then AimTheW(minion) end
				if SwainMenu.Farm.farmE then CastTheE(minion) end
				if SwainMenu.Farm.farmR
					then
						if Human then CastSpell(_R) end
				end
				-- Transforms back to Human after clearing --
				if minion == nil and Bird then CastSpell(_R) end
		end	
	end
end
-- Returns if minions are in sliderrange if we need a heal --
function HealMinion()
		enemyMinions:update()
        for _, healMinion in pairs(enemyMinions.objects) do
			if ValidTarget(healMinion, SwainMenu.SBTW.sbtwRRangeSlider) and healMinion ~= nil
				then return healMinion
			end
        end
end
---------------------------------------------------------------------
-- Object Handling Functions ----------------------------------------
-- Checks for objects that are created and deleted
---------------------------------------------------------------------
function OnCreateObj(obj)
	if obj ~= nil then
		-- Finds the birdform --
		if obj.name:find("swain_demonForm") then
			if GetDistance(obj) <= 70
				then Bird = true Human = false
			end
		end
		if obj.name:find("TeleportHome.troy") then
			if GetDistance(obj) <= 70 then
				Recalling = true
			end
		end 
		if FocusJungleNames[obj.name] then
			table.insert(JungleFocusMobs, obj)
		elseif JungleMobNames[obj.name] then
            table.insert(JungleMobs, obj)
		end
	end
end
function OnDeleteObj(obj)
	if obj ~= nil then
		-- Find the birdform --
		if obj.name:find("swain_demonForm") then
			if GetDistance(obj) <= 70
				then Bird = false Human = true
			end
		end
		if obj.name:find("TeleportHome.troy") then
			if GetDistance(obj) <= 70 then
				Recalling = false
			end
		end 
		for i, Mob in pairs(JungleMobs) do
			if obj.name == Mob.name then
				table.remove(JungleMobs, i)
			end
		end
		for i, Mob in pairs(JungleFocusMobs) do
			if obj.name == Mob.name then
				table.remove(JungleFocusMobs, i)
			end
		end
	end
end
---------------------------------------------------------------------
-- Recalling Functions ----------------------------------------------
-- Checks if our champion is recalling or not and sets the var Recalling based on that
-- Other functions can check Recalling to not interrupt it
---------------------------------------------------------------------
function OnRecall(hero, channelTimeInMs)
	if hero.networkID == player.networkID then
		Recalling = true
	end
end
function OnAbortRecall(hero)
	if hero.networkID == player.networkID
		then Recalling = false
	end
end
function OnFinishRecall(hero)
	if hero.networkID == player.networkID
		then Recalling = false
	end
end
---------------------------------------------------------------------
--- Lag Free Circles ------------------------------------------------
---------------------------------------------------------------------
function LFCfunc()
	if not SwainMenu.Draw.LFC.LagFree then _G.DrawCircle = _G.oldDrawCircle end
	if SwainMenu.Draw.LFC.LagFree then _G.DrawCircle = DrawCircle2 end
end
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
    radius = radius or 300
	quality = math.max(8,round(180/math.deg((math.asin((chordlength/(2*radius)))))))
	quality = 2 * math.pi / quality
	radius = radius*.92
    local points = {}
    for theta = 0, 2 * math.pi + quality, quality do
        local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
        points[#points + 1] = D3DXVECTOR2(c.x, c.y)
    end
    DrawLines2(points, width or 1, color or 4294967295)
end
function round(num) 
 if num >= 0 then return math.floor(num+.5) else return math.ceil(num-.5) end
end
function DrawCircle2(x, y, z, radius, color)
    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
    if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
        DrawCircleNextLvl(x, y, z, radius, 1, color, SwainMenu.Draw.LFC.CL) 
    end
end
---------------------------------------------------------------------
--- Autolevel Skills ------------------------------------------------
---------------------------------------------------------------------
function AutoLevelMySkills()
		if SwainMenu.Extra.AutoLevelSkills == 2 then
			autoLevelSetSequence(levelSequence.startQ)
		elseif SwainMenu.Extra.AutoLevelSkills == 3 then
			autoLevelSetSequence(levelSequence.startW)
		elseif SwainMenu.Extra.AutoLevelSkills == 4 then
			autoLevelSetSequence(levelSequence.startE)
		end
end
---------------------------------------------------------------------
--- Function Damage Calculations for Skills/Items/Enemys ------------ 
---------------------------------------------------------------------
function DamageCalculation()
	for i=1, heroManager.iCount do
		local enemy = heroManager:GetHero(i)
			if ValidTarget(enemy) and enemy ~= nil
				then
				aaDmg 		= ((getDmg("AD", enemy, myHero)))
				qDmg 		= ((getDmg("Q", enemy, myHero)) or 0)
				wDmg		= ((getDmg("W", enemy, myHero)) or 0)
				eDmg		= ((getDmg("E", enemy, myHero)) or 0)
				rDmg		= ((getDmg("R", enemy, myHero)) or 0)
				iDmg 		= ((ignite and getDmg("IGNITE", enemy, myHero)) or 0)	-- Ignite
				dfgDmg 		= ((dfgReady and getDmg("DFG", enemy, myHero)) or 0)	-- Deathfire Grasp
				hxgDmg 		= ((hxgReady and getDmg("HXG", enemy, myHero)) or 0)	-- Hextech Gunblade
				bwcDmg 		= ((bwcReady and getDmg("BWC", enemy, myHero)) or 0)	-- Bilgewater Cutlass
				botrkDmg 	= ((botrkReady and getDmg("RUINEDKING", enemy, myHero)) or 0)	-- Blade of the Ruined King
				sheenDmg	= ((sheenReady and getDmg("SHEEN", enemy, myHero)) or 0)	-- Sheen
				lichbaneDmg = ((lichbaneReady and getDmg("LICHBANE", enemy, myHero)) or 0)	-- Lichbane
				trinityDmg 	= ((trinityReady and getDmg("TRINITY", enemy, myHero)) or 0)	-- Trinity Force
				liandrysDmg = ((liandrysReady and getDmg("LIANDRYS", enemy, myHero)) or 0)	-- Liandrys 
				local extraDmg 	= iDmg + dfgDmg + hxgDmg + bwcDmg + botrkDmg + sheenDmg + trinityDmg + liandrysDmg + lichbaneDmg 
				local abilityDmg = qDmg + wDmg + eDmg + rDmg
				local totalDmg = abilityDmg + extraDmg
				-- Set Kill Text --	
					-- "Kill! - Ignite" --
					if enemy.health <= iDmg
						then
							 if IREADY then KillText[i] = 3
							 else KillText[i] = 2
							 end
					-- "Kill! - (Q)" --
					elseif enemy.health <= qDmg
						then
							if QREADY then KillText[i] = 4
							else KillText[i] = 2
							end
					--	"Kill! - (W)" --
					elseif enemy.health <= wDmg
						then
							if WREADY then KillText[i] = 5
							else KillText[i] = 2
							end
					-- "Kill! - (E)" --
					elseif enemy.health <= eDmg
						then
							if EREADY then KillText[i] = 6
							else KillText[i] = 2
							end
					-- "Kill! - (Q)+(W)" --
					elseif enemy.health <= qDmg+wDmg
						then
							if QREADY and WREADY then KillText[i] = 7
							else KillText[i] = 2
							end
					-- "Kill! - (Q)+(E)" --
					elseif enemy.health <= qDmg+eDmg
						then
							if QREADY and EREADY then KillText[i] = 8
							else KillText[i] = 2
							end
					-- "Kill! - (W)+(E)" --
					elseif enemy.health <= wDmg+eDmg
						then
							if WREADY and EREADY then KillText[i] = 9
							else KillText[i] = 2
							end
					-- "Kill! - (Q)+(W)+(E)" --
					elseif enemy.health <= qDmg+wDmg+eDmg
						then
							if QREADY and WREADY and EREADY then KillText[i] = 10
							else KillText[i] = 2
							end
					-- "Kill! - (Q)+(W)+(E)+(R)" --
					elseif enemy.health <= qDmg+wDmg+eDmg+rDmg
						then
							if QREADY and WREADY and EREADY and RREADY then KillText[i] = 11
							else KillText[i] = 2
							end
					-- "Harass your enemy!" -- 
					else KillText[i] = 1				
					end
		end
	end
end
-- AA --
function aaDmg(enemy)
	local aaDmg = ((getDmg("AD", enemy, myHero)) or 0)
	return math.round(aaDmg)
end
-- Q --
function qDmg(enemy)
	local qDmg = ((getDmg("Q", enemy, myHero)) or 0)
    return math.round(qDmg)
end
-- W --
function wDmg(enemy)
	local wDmg = ((getDmg("W", enemy, myHero)) or 0)
    return math.round(wDmg)
end
-- E --
function eDmg(enemy)
	local eDmg = ((getDmg("E", enemy, myHero)) or 0)
    return math.round(eDmg)
end
-- R --
function rDmg(enemy)
	local rDmg = ((getDmg("R", enemy, myHero)) or 0)
    return math.round(rDmg)
end
-- Ignite --
function iDmg(enemy)
	local iDmg = getDmg("IGNITE", enemy, myHero)
	return math.round(iDmg)
end

function drawpreddmg()
	if not SwainMenu.Draw.perdmg then return end	
	local currLine = 1
	for i, enemy in ipairs(GetEnemyHeroes()) do		
		if enemy~=nil and not enemy.dead and enemy.visible and ValidTarget(enemy) then		
				-- Q --
				if QREADY then
					DrawLineHPBar(qDmg(enemy), currLine, "Q: "..qDmg(enemy), enemy)
					currLine = currLine + 1
				end
				-- W --
				if WREADY then
					DrawLineHPBar(wDmg(enemy), currLine, "W: "..wDmg(enemy), enemy)
					currLine = currLine + 1
				end
				-- E --
				if EREADY then
					DrawLineHPBar(eDmg(enemy), currLine, "E: "..eDmg(enemy), enemy)
					currLine = currLine + 1
				end
				-- R --
				if RREADY then
					DrawLineHPBar(rDmg(enemy), currLine, "R: "..rDmg(enemy), enemy)
					currLine = currLine + 1
				end
				--[[
				if WReady and QReady and RReady and igniteReady then 				
					DrawLineHPBar((dmgRQSAVE(enemy)+dmgW(enemy)+dmgIGN(enemy)), currLine, "RQ+W+IGN: "..(dmgRQSAVE(enemy)+dmgW(enemy)+dmgIGN(enemy)), enemy)
					currLine = currLine + 1								
				end	
				]]--
			end
		end		
end
---------------------------------------------------------------------
--- Function Draw Dmg on Hp Bar ------------------------------------- 
---------------------------------------------------------------------
function GetHPBarPos(enemy)
	enemy.barData = {PercentageOffset = {x = -0.05, y = 0}}
	local barPos = GetUnitHPBarPos(enemy)
	local barPosOffset = GetUnitHPBarOffset(enemy)
	local barOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
	local barPosPercentageOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
	local BarPosOffsetX = -50
	local BarPosOffsetY = 46
	local CorrectionY = 39
	local StartHpPos = 31
	barPos.x = math.floor(barPos.x + (barPosOffset.x - 0.5 + barPosPercentageOffset.x) * BarPosOffsetX + StartHpPos)
	barPos.y = math.floor(barPos.y + (barPosOffset.y - 0.5 + barPosPercentageOffset.y) * BarPosOffsetY + CorrectionY)
	local StartPos = Vector(barPos.x , barPos.y, 0)
	local EndPos = Vector(barPos.x + 108 , barPos.y , 0)
	return Vector(StartPos.x, StartPos.y, 0), Vector(EndPos.x, EndPos.y, 0)
end
function DrawLineHPBar(damage, line, text, unit)
	local thedmg = 0
	if damage >= unit.maxHealth then
		thedmg = unit.maxHealth-1
	else
		thedmg=damage
	end
	local StartPos, EndPos = GetHPBarPos(unit)
	local Real_X = StartPos.x+24
	local Offs_X = (Real_X + ((unit.health-thedmg)/unit.maxHealth) * (EndPos.x - StartPos.x - 2))
	if Offs_X < Real_X then Offs_X = Real_X end	
	local mytrans = 350 - math.round(255*((unit.health-thedmg)/unit.maxHealth))
	if mytrans >= 255 then mytrans=254 end
	local my_bluepart = math.round(400*((unit.health-thedmg)/unit.maxHealth))
	if my_bluepart >= 255 then my_bluepart=254 end

	DrawLine(Offs_X-150, StartPos.y-(30+(line*15)), Offs_X-150, StartPos.y-2, 2, ARGB(mytrans, 255,my_bluepart,0))
	DrawText(tostring(text),15,Offs_X-148,StartPos.y-(30+(line*15)),ARGB(mytrans, 255,my_bluepart,0))
end
---------------------------------------------------------------------
-- Function for Manacheck -------------------------------------------
-- Use like this: ManaCheck(Value or Slider in menu)
-- Returns true when mana is over set value
---------------------------------------------------------------------
function ManaCheck(ManaValue)
	if myHero.mana > (myHero.maxMana * (ManaValue/100))
		then return true
	else
		return false
	end
end
---------------------------------------------------------------------
-- Function for Healthcheck -----------------------------------------
-- Use like this: HealthCheck(Value or Slider in menu)
-- Returns true when health is over set value
---------------------------------------------------------------------
function HealthCheck(HealthValue)
	if myHero.health > (myHero.maxHealth * (HealthValue/100))
		then return true
	else
		return false
	end
end