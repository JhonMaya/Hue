<?php exit() ?>--by QQQ 84.160.234.153
local Version = "1.00"
local AUTOUPDATE = true
local Author = "QQQ"
if myHero.charName ~= "Evelynn" then return end
local IsLoaded = "The Devil wears Prada"

---------------------------------------------------------------------
--- AutoUpdate for the script ---------------------------------------
---------------------------------------------------------------------
local UPDATE_FILE_PATH = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local UPDATE_NAME = "Evelynn - The Devil wears Prada"
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/bolqqq/BoLScripts/master/Evelynn%20-%20The%20Devil%20wears%20Prada.lua?chunk="..math.random(1, 1000)
local UPDATE_FILE_PATH = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

function AutoupdaterMsg(msg) print("<font color=\"#FF7373\">["..IsLoaded.."]:</font> <font color=\"#FFDFBF\">"..msg..".</font>") end
if AUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, UPDATE_PATH)
    if ServerData then
        local ServerVersion = string.match(ServerData, "local Version = \"%d+.%d+\"")
        ServerVersion = string.match(ServerVersion and ServerVersion or "", "%d+.%d+")
        if ServerVersion then
            ServerVersion = tonumber(ServerVersion)
            if tonumber(Version) < ServerVersion then
                AutoupdaterMsg("A new version is available: ["..ServerVersion.."]")
                AutoupdaterMsg("The script is updating... please don't press [F9]!")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function ()
				AutoupdaterMsg("Successfully updated! ("..Version.." -> "..ServerVersion.."), Please reload (double [F9]) for the updated version!") end) end, 3)
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
		print("<font color=\"#FF7373\">["..IsLoaded.."]:</font><font color=\"#FFDFBF\"> Required libraries downloaded successfully, please reload (double [F9]).</font>")
	end
end

for DOWNLOAD_LIB_NAME, DOWNLOAD_LIB_URL in pairs(REQUIRED_LIBS) do
	if FileExist(LIB_PATH .. DOWNLOAD_LIB_NAME .. ".lua") then
		require(DOWNLOAD_LIB_NAME)
	else
		DOWNLOADING_LIBS = true
		DOWNLOAD_COUNT = DOWNLOAD_COUNT + 1

		print("<font color=\"#FFFF73\">["..IsLoaded.."]:</font><font color=\"#FFDFBF\"> Not all required libraries are installed. Downloading: <b><u><font color=\"#73B9FF\">"..DOWNLOAD_LIB_NAME.."</font></u></b> now! Please don't press [F9]!</font>")
		DownloadFile(DOWNLOAD_LIB_URL, LIB_PATH .. DOWNLOAD_LIB_NAME..".lua", AfterDownload)
	end
end
if DOWNLOADING_LIBS then return end
---------------------------------------------------------------------
--- Vars ------------------------------------------------------------
---------------------------------------------------------------------
-- Vars for Ranges --
	local qRange = 500
	local eRange = 290
	local rRange = 650
	local rWidth = 350
	local rSpeed = 1300
	local rDelay = .250
	local stealthRange = 730
-- Vars for Abilitys --
	local qName = "Hate Spike"
	local wName = "Dark Frenzy"
	local eName = "Ravage"
	local rName = "Agony's Embrace"
	local qColor = ARGB(100,217,0,163)
	local eColor = ARGB(100,210,255,76)
	local rColor = ARGB(100,150,115,255)
	local stealthColor = ARGB(100,179,153,255)
	local TargetColor = ARGB(100,76,255,76)
-- Vars for JungleClear --
	local JungleMobs = {}
	local JungleFocusMobs = {}
-- Vars for LaneClear --
	local enemyMinions = minionManager(MINION_ENEMY, 500, myHero.visionPos, MINION_SORT_HEALTH_ASC)
-- Vars for TargetSelector --
	local ts
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1000, DAMAGE_MAGIC, true)
	ts.name = "Evelynn: Target"
-- Vars for Damage Calculations and KilltextDrawing --
	local ignite = nil
	local iDmg = 0
	local qDmg = 0
	local eDmg = 0
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
							"Kill! - (E)",							-- 05
							"Kill! - (Q)+(E)",						-- 06
						}
-- Vars for Autolevel --
	levelSequence = {
					startQ = { 1,3,2,1,1,4,1,3,1,3,4,3,3,2,2,4,2,2 },
					startE = { 3,1,2,1,1,4,1,3,1,3,4,3,3,2,2,4,2,2 }
					}
-- Misc Vars --
	local enemyHeroes = GetEnemyHeroes()
	local EvelynnMenu
	local VP = nil
---------------------------------------------------------------------
--- OnLoad ----------------------------------------------------------
---------------------------------------------------------------------
function OnLoad()
	IgniteCheck()
	JungleNames()
	VP = VPrediction()
	eSOW = SOW(VP)
	AddMenu()
	-- LFC --
	_G.oldDrawCircle = rawget(_G, 'DrawCircle')
	_G.DrawCircle = DrawCircle2
	PrintChat("<font color=\"#FF7373\">["..IsLoaded.."]:</font><font color=\"#FFDFBF\"> Sucessfully loaded! Version: [<u><b>"..Version.."</b></u>]</font>")
end
---------------------------------------------------------------------
--- Menu ------------------------------------------------------------
---------------------------------------------------------------------
function AddMenu()
	-- Script Menu --
	EvelynnMenu = scriptConfig("Evelynn - The Devil wears Prada", "Evelynn")
	
	-- Target Selector --
	EvelynnMenu:addTS(ts)
	
	-- Create SubMenu --
	EvelynnMenu:addSubMenu(""..myHero.charName..": Key Bindings", "KeyBind")
	EvelynnMenu:addSubMenu(""..myHero.charName..": Extra", "Extra")
	EvelynnMenu:addSubMenu(""..myHero.charName..": Escape", "Escape")
	EvelynnMenu:addSubMenu(""..myHero.charName..": Orbwalk", "Orbwalk")
	EvelynnMenu:addSubMenu(""..myHero.charName..": SBTW-Combo", "SBTW")
	EvelynnMenu:addSubMenu(""..myHero.charName..": Harass", "Harass")
	EvelynnMenu:addSubMenu(""..myHero.charName..": KillSteal", "KS")
	EvelynnMenu:addSubMenu(""..myHero.charName..": LaneClear", "Farm")
	EvelynnMenu:addSubMenu(""..myHero.charName..": JungleClear", "Jungle")
	EvelynnMenu:addSubMenu(""..myHero.charName..": Drawings", "Draw")
	
	-- KeyBindings --
	EvelynnMenu.KeyBind:addParam("SBTWKey", "SBTW-Combo Key: ", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	EvelynnMenu.KeyBind:addParam("HarassKey", "HarassKey: ", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
	EvelynnMenu.KeyBind:addParam("HarassToggleKey", "Toggle Harass: ", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("U"))
	EvelynnMenu.KeyBind:addParam("ClearKey", "Jungle- and LaneClear Key: ", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
	EvelynnMenu.KeyBind:addParam("EscapeKey", "EscapeKey: ", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("G"))
	EvelynnMenu.KeyBind:addParam("UltimateKey", "AutoAim UltimateKey: ", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
	
	-- Extra --
	EvelynnMenu.Extra:addParam("AutoLevelSkills", "Auto Level Skills (Reload Script!)", SCRIPT_PARAM_LIST, 1, {"No Autolevel", "QEWQ - R>Q>E>W", "EQWQ - R>Q>E>W"})
	EvelynnMenu.Extra:addParam("extraInfo", "--- Packet Settings ---", SCRIPT_PARAM_INFO, "")
	EvelynnMenu.Extra:addParam("packetUsage", "Use packets to cast spells: ", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.Extra:addParam("blockR", "Block Ultimate if no target gets hit: ", SCRIPT_PARAM_ONOFF, true)
	
	-- Escape --
	EvelynnMenu.Escape:addParam("escapeQ", "Use (Q) to enemys in Range while escaping: ", SCRIPT_PARAM_ONOFF, true)
	
	-- SOW-Orbwalking --
	eSOW:LoadToMenu(EvelynnMenu.Orbwalk)

	-- SBTW-Combo --
	EvelynnMenu.SBTW:addParam("sbtwItems", "Use Items in Combo: ", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.SBTW:addParam("sbtwInfo", "", SCRIPT_PARAM_INFO, "")
	EvelynnMenu.SBTW:addParam("sbtwInfo", "--- Choose your abilitys for SBTW ---", SCRIPT_PARAM_INFO, "")
	EvelynnMenu.SBTW:addParam("sbtwQ", "Use "..qName.." (Q) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.SBTW:addParam("sbtwW", "Use "..wName.." (W) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.SBTW:addParam("sbtwE", "Use "..eName.." (E) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.SBTW:addParam("sbtwR", "Use "..rName.." (R) in Combo: ", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.SBTW:addParam("sbtwRSlider", "Use (R) if more then X enemys: ", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	
	-- Harass --
	EvelynnMenu.Harass:addParam("harassMode", "Choose your HarassMode: ", SCRIPT_PARAM_LIST, 1, {"Q", "Q-E"})
	EvelynnMenu.Harass:addParam("harassMana", "Don't Harass if below % Mana: ", SCRIPT_PARAM_SLICE, 20, 0, 100, -1)
	EvelynnMenu.Harass:addParam("harassInfo", "", SCRIPT_PARAM_INFO, "")
	EvelynnMenu.Harass:addParam("harassInfo", "--- Choose your abilitys for Harass ---", SCRIPT_PARAM_INFO, "")
	EvelynnMenu.Harass:addParam("harassQ","Use "..qName.." (Q) in Harass:", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.Harass:addParam("harassE","Use "..eName.." (E) in Harass:", SCRIPT_PARAM_ONOFF, true)
	
	-- KillSteal --
	EvelynnMenu.KS:addParam("Ignite", "Use Auto Ignite: ", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.KS:addParam("smartKS", "Enable smart KS: ", SCRIPT_PARAM_ONOFF, true)
	
	-- Lane Clear --
	EvelynnMenu.Farm:addParam("farmMana", "Don't LaneClear if below % Mana: ", SCRIPT_PARAM_SLICE, 20, 0, 100, -1)
	EvelynnMenu.Farm:addParam("farmInfo", "--- Choose your abilitys for LaneClear ---", SCRIPT_PARAM_INFO, "")
	EvelynnMenu.Farm:addParam("farmQ", "Farm with "..qName.." (Q): ", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.Farm:addParam("farmE", "Farm with "..eName.." (E): ", SCRIPT_PARAM_ONOFF, true)
	
	-- Jungle Clear --
	EvelynnMenu.Jungle:addParam("jungleMana", "Don't JungleClear if below % Mana: ", SCRIPT_PARAM_SLICE, 10, 0, 100, -1)
	EvelynnMenu.Jungle:addParam("jungleInfo", "--- Choose your abilitys for JungleClear ---", SCRIPT_PARAM_INFO, "")
	EvelynnMenu.Jungle:addParam("jungleQ", "Clear with "..qName.." (Q):", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.Jungle:addParam("jungleE", "Clear with "..eName.." (E):", SCRIPT_PARAM_ONOFF, true)
	
	-- Drawings --
	EvelynnMenu.Draw:addParam("drawQ", "Draw (Q) Range:", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.Draw:addParam("drawE", "Draw (E) Range:", SCRIPT_PARAM_ONOFF, false)
	EvelynnMenu.Draw:addParam("drawR", "Draw (R) Range:", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.Draw:addParam("drawStealth", "Draw Stealth-Range:", SCRIPT_PARAM_ONOFF, false)
	EvelynnMenu.Draw:addParam("drawKillText", "Draw killtext on enemy: ", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.Draw:addParam("drawTarget", "Draw current target: ", SCRIPT_PARAM_ONOFF, false)
		-- LFC --
	EvelynnMenu.Draw:addSubMenu("LagFreeCircles: ", "LFC")
	EvelynnMenu.Draw.LFC:addParam("LagFree", "Activate Lag Free Circles", SCRIPT_PARAM_ONOFF, false)
	EvelynnMenu.Draw.LFC:addParam("CL", "Length before Snapping", SCRIPT_PARAM_SLICE, 350, 75, 2000, 0)
	EvelynnMenu.Draw.LFC:addParam("CLinfo", "Higher length = Lower FPS Drops", SCRIPT_PARAM_INFO, "")
		-- Permashow --
	EvelynnMenu.Draw:addSubMenu("PermaShow: ", "PermaShow")
	EvelynnMenu.Draw.PermaShow:addParam("info", "--- Reload (Double F9) if you change the settings ---", SCRIPT_PARAM_INFO, "")
	EvelynnMenu.Draw.PermaShow:addParam("HarassMode", "Show HarassMode: ", SCRIPT_PARAM_ONOFF, true)
	EvelynnMenu.Draw.PermaShow:addParam("HarassToggleKey", "Show HarassToggleKey: ", SCRIPT_PARAM_ONOFF, true)
	
	if EvelynnMenu.Draw.PermaShow.HarassMode
		then EvelynnMenu.Harass:permaShow("harassMode") 
	end
	if EvelynnMenu.Draw.PermaShow.HarassToggleKey
		then EvelynnMenu.KeyBind:permaShow("HarassToggleKey") 
	end
	
	-- Other --
	EvelynnMenu:addParam("Version", "Version", SCRIPT_PARAM_INFO, Version)
	EvelynnMenu:addParam("Author", "Author", SCRIPT_PARAM_INFO, Author)
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
	if Target
		then
			if EvelynnMenu.KS.Ignite then AutoIgnite(Target) end
			if UltimateKey then AimTheR(Target) end
	end

	if SBTWKey then SBTW() end
	if HarassKey then Harass() end
	if HarassToggleKey then Harass() end
	if ClearKey then LaneClear() JungleClear() end
	if EvelynnMenu.KS.smartKS then smartKS() end
	if EscapeKey then Escape() end
end
---------------------------------------------------------------------
--- Function KeyBindings for easier KeyManagement -------------------
---------------------------------------------------------------------
function KeyBindings()
	SBTWKey = EvelynnMenu.KeyBind.SBTWKey
	HarassKey = EvelynnMenu.KeyBind.HarassKey
	HarassToggleKey = EvelynnMenu.KeyBind.HarassToggleKey
	ClearKey = EvelynnMenu.KeyBind.ClearKey
	EscapeKey = EvelynnMenu.KeyBind.EscapeKey
	UltimateKey = EvelynnMenu.KeyBind.UltimateKey
end
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
		if EvelynnMenu.Draw.drawQ then DrawCircle(myHero.x, myHero.y, myHero.z, qRange, qColor) end
		if EREADY and EvelynnMenu.Draw.drawE then DrawCircle(myHero.x, myHero.y, myHero.z, eRange, eColor) end
		if RREADY and EvelynnMenu.Draw.drawR then DrawCircle(myHero.x, myHero.y, myHero.z, rRange, rColor) end
	-- Stealth Range --
		if EvelynnMenu.Draw.drawStealth then DrawCircle(myHero.x, myHero.y, myHero.z, stealthRange, stealthColor) end
	-- Draw Target --
	if Target ~= nil and EvelynnMenu.Draw.drawTarget
		then DrawCircle(Target.x, Target.y, Target.z, (GetDistance(Target.minBBox, Target.maxBBox)/2), TargetColor)
	end
	-- Draw KillText --
	if EvelynnMenu.Draw.drawKillText then
			for i = 1, heroManager.iCount do
				local enemy = heroManager:GetHero(i)
				if ValidTarget(enemy) and enemy ~= nil then
					local barPos = WorldToScreen(D3DXVECTOR3(enemy.x, enemy.y, enemy.z))
					local PosX = barPos.x - 60
					local PosY = barPos.y - 10
					DrawText(KillTextList[KillText[i]], 14, PosX, PosY, KillTextColor)
				end
			end
		end
end
---------------------------------------------------------------------
--- Cast Functions for Spells ---------------------------------------
---------------------------------------------------------------------
-- Evelynn Q --
function CastTheQ(enemy)
		if not enemy then enemy = Target end
		if (not QREADY or (GetDistance(enemy) > qRange))
			then return false
		end
		if ValidTarget(enemy) and not EvelynnMenu.Extra.packetUsage
			then CastSpell(_Q)
			return true
		elseif ValidTarget(enemy) and EvelynnMenu.Extra.packetUsage
			then Packet("S_CAST", { spellId = _Q, targetNetworkId = enemy.networkID }):send()
			return true
		end
		return false
end
-- Evelynn E --
function CastTheE(enemy)
		if not enemy then enemy = Target end
		if (not EREADY or (GetDistance(enemy) > eRange))
			then return false
		end
		if ValidTarget(enemy) and not EvelynnMenu.Extra.packetUsage
			then CastSpell(_E, enemy)
			return true
		elseif ValidTarget(enemy) and EvelynnMenu.Extra.packetUsage
			then Packet("S_CAST", { spellId = _E, targetNetworkId = enemy.networkID }):send()
			return true
		end
		return false
end
-- Evelynn R --
function AimTheR(enemy)
	local CastPosition, HitChance, Position = VP:GetCircularAOECastPosition(enemy, rDelay, rWidth, rRange, rSpeed, myHero)
	if HitChance >= 2 and GetDistance(enemy) <= rRange and RREADY
		then
			if EvelynnMenu.Extra.packetUsage
				then Packet('S_CAST', {spellId = _R, fromX = myHero.x, fromY = myHero.z,toX = CastPosition.x, toY = CastPosition.z}):send()
			end
			if not EvelynnMenu.Extra.packetUsage
				then CastSpell(_R, CastPosition.x, CastPosition.z)
			end
	end
end
-- Evelynn Auto R --
function AimTheRonX(enemy)
	if RREADY then
		local Mintargets = EvelynnMenu.SBTW.sbtwRSlider
		local AoeCastPosition, MainTargetHitChance, nTargets = VP:GetCircularAOECastPosition(enemy, rDelay, rWidth, rRange, rSpeed, myHero)
		local hitnumber, hit = CheckHitEnemys()
		if MainTargetHitChance >= 2 and GetDistance(enemy) <= rRange and hitnumber >= Mintargets
			then
				if EvelynnMenu.Extra.packetUsage
					then Packet('S_CAST', {spellId = _R, fromX = myHero.x, fromY = myHero.z,toX = AoeCastPosition.x, toY = AoeCastPosition.z}):send()
				end
				if not EvelynnMenu.Extra.packetUsage
					then CastSpell(_R, AoeCastPosition.x, AoeCastPosition.z)
				end
		end
	end
end
-- Block the cast of R if no enemy gets hit --
function OnSendPacket(p)
	if EvelynnMenu.Extra.blockR and RREADY and p.header == Packet.headers.S_CAST
		then
			local packet = Packet(p)
			if packet:get('spellId') == _R
				then
					local hitnumber, hit = CheckHitEnemys()
					if hitnumber == 0 then p:Block() end
			end
	end
end
-- Check how many enemys get hit by R --
function CheckHitEnemys()
	local enemieshit = {}
	for i, enemy in ipairs(GetEnemyHeroes()) do
		local position = VP:GetPredictedPos(enemy, rDelay)
		if ValidTarget(enemy) and GetDistance(position) <= rWidth and GetDistance(enemy.visionPos, myHero) <= 1.25 * rWidth  then
			table.insert(enemieshit, enemy)
		end
	end
	return #enemieshit, enemieshit
end
---------------------------------------------------------------------
--- SBTW Functions --------------------------------------------------
---------------------------------------------------------------------
function SBTW()
	if ValidTarget(Target)
		then 
			if EvelynnMenu.SBTW.sbtwQ then CastTheQ(Target) end
			if EvelynnMenu.SBTW.sbtwE then CastTheE(Target) end
			if EvelynnMenu.SBTW.sbtwR then AimTheRonX(Target) end
			if EvelynnMenu.SBTW.sbtwItems then UseItems() end
	end
end
---------------------------------------------------------------------
--- Harass Functions ------------------------------------------------
---------------------------------------------------------------------
function Harass()
	if Target and ManaCheck(EvelynnMenu.Harass.harassMana)
			then
				if EvelynnMenu.Harass.harassMode == 1
					then 
						if EvelynnMenu.Harass.harassQ then CastTheQ(Target) end
				end
				if EvelynnMenu.Harass.harassMode == 2
					then
						if EvelynnMenu.Harass.harassQ then CastTheQ(Target) end
						if EvelynnMenu.Harass.harassE then CastTheE(Target) end
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
			elseif hp <= eDmg and EREADY and (distance <= eRange) 
				then CastTheE(enemy)
			elseif hp <= (qDmg + eDmg) and QREADY and EREADY and (distance <= qRange)
				then CastTheQ(enemy)
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
--- Jungle Clear with different forms -------------------------------
---------------------------------------------------------------------
function JungleClear()
	JungleMob = GetJungleMob()
		if JungleMob ~= nil and ManaCheck(EvelynnMenu.Jungle.jungleMana) then
			if EvelynnMenu.Jungle.jungleQ then CastTheQ(JungleMob) end
			if EvelynnMenu.Jungle.jungleE then CastTheE(JungleMob) end
		end
end
-- Get Jungle Mob --
function GetJungleMob()
        for _, Mob in pairs(JungleFocusMobs) do
                if ValidTarget(Mob, qRange) then return Mob end
        end
        for _, Mob in pairs(JungleMobs) do
                if ValidTarget(Mob, qRange) then return Mob end
        end
end
---------------------------------------------------------------------
--- Lane Clear ------------------------------------------------------
---------------------------------------------------------------------
function LaneClear()
	enemyMinions:update()
	for _, minion in pairs(enemyMinions.objects) do
		if ValidTarget(minion) and minion ~= nil and not eSOW:CanAttack() and ManaCheck(EvelynnMenu.Farm.farmMana)
			then 
				if EvelynnMenu.Farm.farmQ then CastTheQ(minion) end
				if EvelynnMenu.Farm.farmE then CastTheE(minion) end
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
	if not EvelynnMenu.Draw.LFC.LagFree then _G.DrawCircle = _G.oldDrawCircle end
	if EvelynnMenu.Draw.LFC.LagFree then _G.DrawCircle = DrawCircle2 end
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
        DrawCircleNextLvl(x, y, z, radius, 1, color, EvelynnMenu.Draw.LFC.CL) 
    end
end
---------------------------------------------------------------------
--- Autolevel Skills ------------------------------------------------
---------------------------------------------------------------------
function AutoLevelMySkills()
		if EvelynnMenu.Extra.AutoLevelSkills == 2 then
			autoLevelSetSequence(levelSequence.startQ)
		elseif EvelynnMenu.Extra.AutoLevelSkills == 3 then
			autoLevelSetSequence(levelSequence.startE)
		end
end
---------------------------------------------------------------------
--- Function Damage Calculations for Skills/Items/Enemys --- 
---------------------------------------------------------------------
function DamageCalculation()
	for i=1, heroManager.iCount do
		local enemy = heroManager:GetHero(i)
			if ValidTarget(enemy) and enemy ~= nil
				then
				aaDmg 		= ((getDmg("AD", enemy, myHero)))
				qDmg 		= ((getDmg("Q", enemy, myHero)) or 0)	
				eDmg		= ((getDmg("E", enemy, myHero)) or 0)	
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
				local abilityDmg = qDmg + eDmg
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
					-- "Kill! - (E)" --
					elseif enemy.health <= eDmg
						then
							if EREADY then KillText[i] = 5
							else KillText[i] = 2
							end
					-- "Kill! - (Q)+(E)" --
					elseif enemy.health <= qDmg+eDmg
						then
							if QREADY and EREADY then KillText[i] = 6
							else KillText[i] = 2
							end
					-- "Harass your enemy!" -- 
					else KillText[i] = 1				
					end
			end
		end
end
---------------------------------------------------------------------
-- Function for Manacheck -------------------------------------------
-- Use like this: ManaCheck(Value or Slider in menu)
---------------------------------------------------------------------
function ManaCheck(ManaValue)
	if myHero.mana > (myHero.maxMana * (ManaValue/100))
		then return true
	else
		return false
	end
end
---------------------------------------------------------------------
--- Function for Misc Movement --------------------------------------
---------------------------------------------------------------------
function Escape()
moveToCursor()
if not enemy then enemy = Target end
if EvelynnMenu.Escape.escapeQ then CastTheQ(enemy) end
if WREADY then CastSpell(_W) end
end
function moveToCursor()
	if GetDistance(mousePos) then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
    end        
end