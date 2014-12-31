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
	local wDelay = .700
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
	local UltForm = false
-- Vars for JungleClear --
	local JungleMobs = {}
	local JungleFocusMobs = {}
-- Vars for LaneClear --
	local enemyMinions = minionManager(MINION_ENEMY, 650, myHero.visionPos, MINION_SORT_HEALTH_ASC)
-- Vars for TargetSelector --
	local ts
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 900, DAMAGE_MAGIC, true)
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
--	SwainMenu:addSubMenu(""..myHero.charName..": Ultimate", "Ultimate")
	SwainMenu:addSubMenu(""..myHero.charName..": SBTW-Combo", "SBTW")
	SwainMenu:addSubMenu(""..myHero.charName..": Harass", "Harass")
--	SwainMenu:addSubMenu(""..myHero.charName..": KillSteal", "KS")
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
--	SwainMenu.Ultimate:addParam("autoUltimate", "Use AutoUltimate to heal: ", SCRIPT_PARAM_ONOFF, true)
--	SwainMenu.Ultimate:addParam("autoUltimateHealth", "Use AutoUltimate if below % HP: ", SCRIPT_PARAM_SLICE, 50, 0, 100, -1)
--	SwainMenu.Ultimate:addParam("autoUltimateMana", "Use AutoUltimate if over % Mana: ", SCRIPT_PARAM_SLICE, 40, 0, 100, -1)
	
	-- SBTW-Combo --
	SwainMenu.SBTW:addParam("sbtwItems", "Use Items in Combo: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.SBTW:addParam("sbtwInfo", "", SCRIPT_PARAM_INFO, "")
	SwainMenu.SBTW:addParam("sbtwInfo", "--- Choose your abilitys for SBTW ---", SCRIPT_PARAM_INFO, "")
	SwainMenu.SBTW:addParam("sbtwQ", "Use "..qName.." (Q) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.SBTW:addParam("sbtwW", "Use "..wName.." (W) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.SBTW:addParam("sbtwE", "Use "..eName.." (E) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.SBTW:addParam("sbtwR", "Use "..rName.." (R) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.SBTW:addParam("sbtwRSlider", "Use (R) if more then X enemys: ", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
	SwainMenu.SBTW:addParam("sbtwMana", "Don't Ult if below % Mana: ", SCRIPT_PARAM_SLICE, 10, 0, 100, -1)

	-- Harass --
	SwainMenu.Harass:addParam("harassMode", "Choose your HarassMode: ", SCRIPT_PARAM_LIST, 2, {"Q", "Q-E", "Q-W-E"})
	SwainMenu.Harass:addParam("harassMana", "Don't Harass if below % Mana: ", SCRIPT_PARAM_SLICE, 20, 0, 100, -1)
	SwainMenu.Harass:addParam("harassInfo", "", SCRIPT_PARAM_INFO, "")
	SwainMenu.Harass:addParam("harassInfo", "--- Choose your abilitys for Harass ---", SCRIPT_PARAM_INFO, "")
	SwainMenu.Harass:addParam("harassQ","Use "..qName.." (Q) in Harass:", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Harass:addParam("harassW","Use "..wName.." (W) in Harass:", SCRIPT_PARAM_ONOFF, true)
	SwainMenu.Harass:addParam("harassE","Use "..eName.." (E) in Harass:", SCRIPT_PARAM_ONOFF, true)
	
	-- KillSteal --
--	SwainMenu.KS:addParam("Ignite", "Use Auto Ignite: ", SCRIPT_PARAM_ONOFF, true)
	
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
--	SwainsUltimate()

	if SBTWKey then SBTW() end
	if HarassKey then Harass() end
	if HarassToggleKey then Harass() end
	if ClearKey then LaneClear() JungleClear() end
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
function AimTheW(enemy)
	local CastPosition, HitChance, Position = VP:GetCircularCastPosition(enemy, wDelay, wWidth, wRange, wSpeed, myHero, false)
	if HitChance >= 2 and GetDistance(enemy) <= wRange and WREADY
		then CastSpell(_W, CastPosition.x, CastPosition.z)
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
	if RREADY and not UltForm and ManaCheck(SwainMenu.SBTW.sbtwMana) and CountEnemyHeroInRange(rRange) >= SwainMenu.SBTW.sbtwRSlider
		then CastSpell(_R)
	end
	-- Transforms us into Human when Bird and no enemys in Range
	if RREADY and UltForm and CountEnemyHeroInRange(900) == 0
		then CastSpell(_R) 
	end
end
---------------------------------------------------------------------
--- Function Swains Ultimate ---------------------------------------- 
---------------------------------------------------------------------
function SwainsUltimate()
	--  If not RREADY or AutoUltimate is disabled or SBTWKey or ClearKey is pressed - don't do anything here for Performanceimprovements
	if not RREADY and not SwainMenu.Ultimate.autoUltimate or SBTWKey or ClearKey then return end
		-- If we have enough Mana but our Health is below the Slider
		if ManaCheck(SwainMenu.Ultimate.autoUltimateMana) and not HealthCheck(SwainMenu.Ultimate.autoUltimateHealth)
			then
				Minion = HealMinion()
				JungleMinion = GetJungleMob()
				-- If Human and we have a Target/Minion or Jungleminion to heal then Cast R
				if not Ultform and (Target ~= nil or Minion ~= nil or JungleMinion ~= nil)
					then CastSpell(_R)
				end
		end
	-- if we have enough health and are in Ultform and don't have a Target transform back
		if ManaCheck(SwainMenu.Ultimate.autoUltimateMana) and HealthCheck(SwainMenu.Ultimate.autoUltimateHealth)
			then
				Minion = HealMinion()
				JungleMinion = GetJungleMob()
				if Ultform and (Target == nil or Minion == nil or JungleMinion == nil)
					then CastSpell(_R)
				end
		end
			-- if we have enough health but our mana gets low transform back to human
		if not ManaCheck(SwainMenu.Ultimate.autoUltimateMana) and HealthCheck(SwainMenu.Ultimate.autoUltimateHealth)
			then 
				Minion = HealMinion()
				JungleMinion = GetJungleMob()
				if Ultform then CastSpell(_R) end
		end
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
--- Jungle Clear with different forms -------------------------------
---------------------------------------------------------------------
function JungleClear()
	JungleMob = GetJungleMob()
		if JungleMob ~= nil and ManaCheck(SwainMenu.Jungle.jungleMana) then
			if SwainMenu.Jungle.jungleQ then CastTheQ(JungleMob) end			
			if SwainMenu.Jungle.jungleW then AimTheW(JungleMob) end			
			if SwainMenu.Jungle.jungleE then CastTheE(JungleMob) end
			if SwainMenu.Jungle.jungleR
				then 
					if not UltForm then CastSpell(_R) end
			end
		end
		-- Transforms back to Human after clearing --
		if JungleMob == nil and UltForm then CastSpell(_R) end
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
						if not UltForm then CastSpell(_R) end
				end
				-- Transforms back to Human after clearing --
				if minion == nil and UltForm then CastSpell(_R) end
		end	
	end
end
-- Returns if minions are in rRange if we need a heal --
function HealMinion()
        for _, healMinion in pairs(enemyMinions.objects) do
			if ValidTarget(healMinion, rRange)
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
		-- Finds the birdform --
		if obj.name:find("swain_demonForm") then
			if GetDistance(obj) <= 70 then
				UltForm = true
			end
		end
	end
end
function OnDeleteObj(obj)
	if obj ~= nil then
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
		-- Find the birdform --
		if obj.name:find("swain_demonForm") then
			if GetDistance(obj) <= 70 then
				UltForm = false
			end
		end
	end
end
---------------------------------------------------------------------
--- OnProcess Spell -------------------------------------------------
---------------------------------------------------------------------
function OnProcessSpell(object,spell)
	if object == myHero and spell.name == "SwainMetamorphism"
		then
			if UltForm then	UltForm = false
		else
			UltForm = true
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