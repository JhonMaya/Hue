<?php exit() ?>--by ahmedzarga23 197.2.35.177
if myHero.charName ~= "Irelia" then return end
require 'VPrediction'
require 'SOW'
local version = "1.061"
local Author = "si7ziTV"
local TESTVERSION = false
local AUTOUPDATE = true
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/si7ziTV/BoL/master/Scripts/Better Nerf Irelia.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH.."Better Nerf Irelia.lua"
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH
 
function AutoupdaterMsg(msg) print("<font color=\"#6699ff\"><b>Better Nerf Irelia:</b></font> <font color=\"#FFFFFF\">"..msg..".</font>") end
if AUTOUPDATE then
local ServerData = GetWebResult(UPDATE_HOST, "/si7ziTV/BoL/master/Scripts/versions/Better Nerf Irelia.lua.version")
if ServerData then
ServerVersion = type(tonumber(ServerData)) == "number" and tonumber(ServerData) or nil
if ServerVersion then
if tonumber(version) < ServerVersion then
AutoupdaterMsg("New version available"..ServerVersion)
AutoupdaterMsg("Updating, please don't press F9")
DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version.") end) end, 3)
else
AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
end
end
else
AutoupdaterMsg("Error downloading version info")
end
end

-----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------

local REQUIRED_LIBS = {
		["SOW"] = "https://bitbucket.org/honda7/bol/raw/master/Common/SOW.lua",
		["VPrediction"] = "https://bitbucket.org/honda7/bol/raw/master/Common/VPrediction.lua"
	}

local DOWNLOADING_LIBS, DOWNLOAD_COUNT = false, 0
local SELF_NAME = GetCurrentEnv() and GetCurrentEnv().FILE_NAME or ""

function AfterDownload()
	DOWNLOAD_COUNT = DOWNLOAD_COUNT - 1
	if DOWNLOAD_COUNT == 0 then
		DOWNLOADING_LIBS = false
		print("<b>[Irelia]: Required libraries downloaded successfully, please reload (double F9).</b>")
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

if DOWNLOADING_LIBS then return end

-----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------

--[other Stuff]--
local menu
local ts
local EnemyMinionManager = nil
local JungleMinionManager = nil
local ignite = nil
local IREADY = false

--[[Spell data]]--
local QReady, WReady, EReady, RReady = false, false, false, false

--[[Spell Range]]--
local AArange = 125
local Qrange = 650
local Wrange = 125
local Erange = 325
local Rrange = 925

--[[Spell Damage]]--
local Qdamage = {20, 50, 80, 110, 140}
local Edamage = {80, 130, 180, 230, 280}
local Rdamage = {320, 480, 640}

--[[Spell Mana]]--
local Qmana = {60, 65, 70, 75, 80}
local Wmana = {40, 40, 40, 40, 40}
local Emana = {50, 55, 60, 65, 70}
local Rmana = {100, 100, 100} 

--[[Spell Scaling]]--
local Qscaling = 1
local Rscaling = 0.6

function OnLoad()
	VP = VPrediction()
	iSOW = SOW(VP)
	_Menu()
	_Init()
		PrintChat("<font color=\"#FE642E\"><b>" ..">>  [Better Nerf] Irelia</b> has been loaded")
			Loaded = true
end

function OnLoad()
	VP = VPrediction()
	iSOW = SOW(VP)
	_Menu()
	_Init()
		PrintChat("<font color=\"#FE642E\"><b>" ..">>  [Better Nerf] Irelia</b> has been loaded")
			Loaded = true
end

function _Init()	
	--[Minion Manager]--
	EnemyMinionManager = minionManager(MINION_ENEMY, Qrange, myHero, MINION_SORT_MAXHEALTH_DEC)
	JungleMinionManager = minionManager(MINION_JUNGLE, Qrange, myHero, MINION_SORT_MAXHEALTH_DEC)
	
	--[Target Selector]--
	ts = TargetSelector(TARGET_LESS_CAST,1300,DAMAGE_PHYSICAL) -- (mode, range, damageType)
	ts.name = "Irelia"
	
	--[find Ignite]--
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
    ignite = SUMMONER_1
  elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
    ignite = SUMMONER_2
  end
	
	--[Evadeee Integration]--	
	if _G.Evadeee_Loaded then
		_G.Evadeee_Enabled = true
	end
	
end

--[ingame Menu]--
function _Menu()
	
	if VIP_USER then
		print('>> [Better Nerf] Irelia: VIP Menu loaded.')
	else
		print('>> [Better Nerf] Irelia: FreeUser Menu loaded.')
	end	 

	menu = scriptConfig("[Better Nerf] Irelia: Main Menu", "Better Nerf Irelia")
		
		--------------------------------------[Version Author]-------------------------------------
		menu:addParam("Version", "Version", SCRIPT_PARAM_INFO, version)
		menu:addParam("Author", "Author", SCRIPT_PARAM_INFO, Author)		    
		-------------------------------------------------------------------------------------------
	
		-----------------------------------------[Orbwalk]-----------------------------------------
		menu:addSubMenu("[Better Nerf] Irelia: Orbwalk", "Orbwalk")
		iSOW:LoadToMenu(menu.Orbwalk)
		-------------------------------------------------------------------------------------------
		
		---------------------------------------[Menu: Combo]---------------------------------------
		menu:addSubMenu("[Better Nerf] Irelia: Combo", "combo")
			menu.combo:addParam("combokey","Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
			menu.combo:addParam("useQ","Use (Q)", SCRIPT_PARAM_ONOFF, true)
			menu.combo:addParam("useW","Use (W)", SCRIPT_PARAM_ONOFF, true)
	if VIP_USER then
			menu.combo:addParam("VIPuseE","Use (E)", SCRIPT_PARAM_LIST, 2, { "ALWAYS", "STUN", "NEVER" })
	else
			menu.combo:addParam("FREEuseE", "Use (E)", SCRIPT_PARAM_ONOFF, true)
			menu.combo:addParam("FREEuseEstun", "Use (E) Stun only", SCRIPT_PARAM_ONOFF, true)	
	end
			menu.combo:addParam("useR","Use (R)", SCRIPT_PARAM_ONOFF, false)
			menu.combo:addParam("RRange", "Adjust (R) Range", SCRIPT_PARAM_SLICE, 750, 0, 1000)
			menu.combo:addParam("aimR", "Manuel (R) Press 4x Autocast", SCRIPT_PARAM_ONKEYDOWN, false, 82)
			menu.combo:addParam("useitems","Use Items", SCRIPT_PARAM_ONOFF, true)
			menu.combo:addParam("useQ2", "Use Q to Gapclose", SCRIPT_PARAM_ONOFF, true)	
		-------------------------------------------------------------------------------------------
		
		---------------------------------------[Menu: Harass]--------------------------------------
		menu:addSubMenu("[Better Nerf] Irelia: Harass", "harass")
			menu.harass:addParam("harasskey","Harras Key", SCRIPT_PARAM_ONKEYDOWN, false, 67)
			menu.harass:addParam("useQ","Use (Q)", SCRIPT_PARAM_ONOFF, true)
			menu.harass:addParam("useW","Use (W)", SCRIPT_PARAM_ONOFF, true)
	if VIP_USER then
			menu.harass:addParam("VIPuseE","Use (E)", SCRIPT_PARAM_LIST, 1, { "ALWAYS", "STUN", "NEVER" })
	else
			menu.harass:addParam("FREEuseE", "Use (E)", SCRIPT_PARAM_ONOFF, true)
			menu.harass:addParam("FREEuseEstun", "Use (E) Stun only", SCRIPT_PARAM_ONOFF, true)
	end			
		-------------------------------------------------------------------------------------------
		
		-------------------------------------[Menu: Laneclear]-------------------------------------
		menu:addSubMenu("[Better Nerf] Irelia: Laneclear", "lclear")
			menu.lclear:addParam("clear", "Clear that Wave!", SCRIPT_PARAM_ONKEYDOWN, false, 86)
			menu.lclear:addParam("useQ", "Use (Q)", SCRIPT_PARAM_ONOFF, true)
			menu.lclear:addParam("useW", "Use (W)", SCRIPT_PARAM_ONOFF, false)
			menu.lclear:addParam("useR", "Use (R)", SCRIPT_PARAM_ONOFF, false)
			menu.lclear:addParam("useitems", "Use Items", SCRIPT_PARAM_ONOFF, true)
		-------------------------------------------------------------------------------------------
		
		------------------------------------[Menu: Jungleclear]------------------------------------
		menu:addSubMenu("[Better Nerf] Irelia: Jungleclear", "jclear")
			menu.jclear:addParam("clear", "Clear that Camp!", SCRIPT_PARAM_ONKEYDOWN, false, 86)
			menu.jclear:addParam("useQ", "Use (Q)", SCRIPT_PARAM_ONOFF, true)
			menu.jclear:addParam("useW", "Use (W)", SCRIPT_PARAM_ONOFF, true)
			menu.jclear:addParam("useR", "Use (R)", SCRIPT_PARAM_ONOFF, false)
			menu.jclear:addParam("useitems", "Use Items", SCRIPT_PARAM_ONOFF, true)
		--menu.jclear:addParam("lasthitQ", "Lasthit Junglecreeps using (Q)", SCRIPT_PARAM_ONKEYDOWN, false, 71)
		-------------------------------------------------------------------------------------------

		-------------------------------------[Menu: Killsteal]-------------------------------------
		menu:addSubMenu("[Better Nerf] Irelia: Killsteal", "killsteal")
			menu.killsteal:addParam("killstealQ", "Use (Q) to Killsteal", SCRIPT_PARAM_ONOFF, true)
			--menu.killsteal:addParam("killstealE", "Use (E) to Killsteal", SCRIPT_PARAM_ONOFF, true)
			--menu.killsteal:addParam("killstealR", "Use (R) to Killsteal", SCRIPT_PARAM_ONOFF, true)
			menu.killsteal:addParam("ignite", "Use Auto Ignite", SCRIPT_PARAM_ONOFF, true)
			--menu.killsteal:addParam("items", "Use Items", SCRIPT_PARAM_ONOFF, true)
	
		------------------------------------[Menu: Flee]------------------------------------
		menu:addSubMenu("[Better Nerf] Irelia: Flee", "flee")
			menu.flee:addParam("flee", "Flee", SCRIPT_PARAM_ONKEYDOWN, false, 84)
			menu.flee:addParam("fleeradius", "Adjust your Radius to Flee",SCRIPT_PARAM_SLICE, 175, 0, 500)
		-------------------------------------------------------------------------------------------
	
		----------------------------------------[Menu: Misc]---------------------------------------
		menu:addSubMenu("[Better Nerf] Irelia: Misc", "misc")
			menu.misc:addSubMenu("Mana Management", "manamanage")
			menu.misc.manamanage:addParam("minmanafarm", "Minimum Mana to Farm", SCRIPT_PARAM_SLICE, 35, 0, 100)
			menu.misc.manamanage:addParam("minmanaharass", "Minimum Mana to Harass", SCRIPT_PARAM_SLICE, 25, 0, 100)
			menu.misc:addParam("evadeeeintegration", "Use Evadeee Integration (on Toggle)", SCRIPT_PARAM_ONOFF, true)
		-------------------------------------------------------------------------------------------
	
		-------------------------------------[Menu: Drawings]--------------------------------------
		menu:addSubMenu("[Better Nerf] Irelia: Drawings", "drawings")
			menu.drawings:addSubMenu("Spells", "spells")
				menu.drawings.spells:addParam("drawAA", "Draw AA", SCRIPT_PARAM_ONOFF, true)
				menu.drawings.spells:addParam("drawQ", "Draw (Q)", SCRIPT_PARAM_ONOFF, true)
				menu.drawings.spells:addParam("drawE", "Draw (E)", SCRIPT_PARAM_ONOFF, false)
				menu.drawings.spells:addParam("drawR", "Draw (R)", SCRIPT_PARAM_ONOFF, false)
				menu.drawings.spells:addParam("drawadvanced", "Use Advanced Drawings", SCRIPT_PARAM_ONOFF, true)
			menu.drawings:addSubMenu("Combo", "combo")
				menu.drawings.combo:addParam("drawpredictdmg", "Draw Predicted Damage", SCRIPT_PARAM_ONOFF, true)
			menu.drawings:addSubMenu("Laneclear", "lclear")
				menu.drawings.lclear:addParam("Qdraw", "Draw Minions can be killed using (Q)", SCRIPT_PARAM_ONOFF, true)
				menu.drawings.lclear:addParam("AAdraw", "Draw Minions can be killed using (AA)", SCRIPT_PARAM_ONOFF, true)
			menu.drawings:addSubMenu("Flee", "flee")
				menu.drawings.flee:addParam("flee", "Draw Flee Radius", SCRIPT_PARAM_ONOFF, true)
			menu.drawings:addSubMenu("Killsteal", "killsteal")
				menu.drawings.killsteal:addParam("Qdraw", "Draw Enemys can be killed using (Q)", SCRIPT_PARAM_ONOFF, true)
				--menu.drawings.killsteal:addParam("Edraw", "Draw Enemys can be killed using (E)", SCRIPT_PARAM_ONOFF, true)
				--menu.drawings.killsteal:addParam("Rdraw", "Draw Enemys can be killed using (R)", SCRIPT_PARAM_ONOFF, true)
			menu.drawings:addParam("quality", "Quality", SCRIPT_PARAM_SLICE, 135, 0, 720)
			menu.drawings:addParam("color", "Color", SCRIPT_PARAM_LIST, 1, { "WHITE" , "BLUE" , "PURPLE" , "RED" , "ORANGE" , "YELLOW" , "GREEN" })
				--menu.drawings.combo:addParam("drawQway", "Draw Way Calculation Enemy using (Q)", SCRIPT_PARAM_ONOFF, true)
			--menu.drawings:addSubMenu("Jungleclear", "jclear")
				--menu.drawings.jclear:addParam("Qdraw", "Draw Junglecreeps can be killed using (Q)", SCRIPT_PARAM_ONOFF, true)
		-------------------------------------------------------------------------------------------
		
		----------------------------------------[permaShow]----------------------------------------
		menu.combo:permaShow("combokey")
		menu.harass:permaShow("harasskey")
		menu.lclear:permaShow("clear")
		menu.flee:permaShow("flee")
		-------------------------------------------------------------------------------------------
end

--------------------------------------------[OnTick]-------------------------------------------
function OnTick()
	if myHero.dead then return end
		if Loaded then
			ts:update()
			EnemyMinionManager:update()
			JungleMinionManager:update()
		_itemauto()
		_item_check()
		_spell_check()	
		if menu.combo.combokey then
			_combo()
		end
		if menu.combo.aimR then
			_autoaimR()
		end
		if menu.harass.harasskey then
			_harass()
		end
		if menu.flee.flee then
			_flee()
		end
		if menu.lclear.clear then
			_lclear()
		end
		if menu.jclear.clear then
			_jclear()
		end
		if menu.killsteal.killstealQ then
			_killstealQ()
		end
		if menu.killsteal.killstealE then
			_killstealE()
		end
		if menu.killsteal.killstealR then
			_killstealR()
		end
		if menu.killsteal.ignite then
			_ignite()
		end
		if menu.misc.evadeeeintegration then
			_EvadeeeIntegration()
		end
	end
end
function _item_check()
	ROready = (ROSlot   ~= nil and myHero:CanUseSpell(ROSlot)   == READY)
	TMready = (TMSlot   ~= nil and myHero:CanUseSpell(TMSlot)   == READY)
	HDAready = (HDASlot   ~= nil and myHero:CanUseSpell(HDASlot)   == READY)
	BWready = (BWSlot   ~= nil and myHero:CanUseSpell(BWSlot)   == READY)
	BRKready = (BRKSlot   ~= nil and myHero:CanUseSpell(BRKSlot)   == READY)
end
function _spell_check()
	Qready = (myHero:CanUseSpell(_Q) == READY)
	Wready = (myHero:CanUseSpell(_W) == READY)
	Eready = (myHero:CanUseSpell(_E) == READY)
	Rready = (myHero:CanUseSpell(_R) == READY)
	IREADY = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
end
-----------------------------------------------------------------------------------------------

--------------------------------------------[Combo]--------------------------------------------
function _combo()
	local Enemies = GetEnemyHeroes
	local target = ts.target
	
	if menu.combo.combokey and ts.target then
		_itemslots()
		_itemslots2()
		if Qready and menu.combo.useQ and GetDistance(ts.target) < Qrange then
			CastSpell(_Q, target)
		end
		if Wready and menu.combo.useW and GetDistance(ts.target) < Wrange then
			CastSpell(_W)
		end
		if Rready and menu.combo.useR and (ts.target) and GetDistance(ts.target) < menu.combo.RRange then
			CastSpell(_R, target.x, target.z)
		end
		if menu.combo.useQ2 then
			for _, minion in pairs(EnemyMinionManager.objects) do 
			  if minion and ts.target then 
			    if GetDistance(minion,ts.target) < GetDistance(ts.target,myHero) and (getDmg("Q",minion,myHero)+getDmg("AD",minion,myHero)) > minion.health then
			      CastSpell(_Q,minion)
			    end 
		    end 
	    end  
		end
	end

if VIP_USER then	
		if menu.combo.VIPuseE == 2 then
		  if ts.target then 
			if Eready and	GetDistance(ts.target) < Erange and
			(myHero.health * 100 /myHero.maxHealth) < (ts.target.health * 100 /ts.target.maxHealth) 		 then
				CastSpell(_E, target) 
			end
		end 
	end 
		if menu.combo.VIPuseE == 1 then
		 if ts.target then 
			if Eready and	GetDistance(ts.target) < Erange then
				CastSpell(_E, target) 
			end
		end
	end 
	else
		if menu.combo.FREEuseE and menu.combo.FREEuseEstun then
		  if ts.target then 
			if Eready and	GetDistance(ts.target) < Erange and
			(myHero.health * 100 /myHero.maxHealth) < (ts.target.health * 100 /ts.target.maxHealth) 		 then
				CastSpell(_E, target) 
			end
		end
	end 
		if menu.combo.FREEuseE then
		 if ts.target then 
			if Eready and	GetDistance(ts.target) < Erange then
				CastSpell(_E, target) 
			end
		end
	end
end 
end 
-----------------------------------------------------------------------------------------------

function _autoaimR()
	local Enemies = GetEnemyHeroes()
	
	for i, enemy in pairs(Enemies) do
		if ValidTarget(enemy, Qrange) and not enemy.dead and GetDistance(enemy) < 900 and menu.combo.aimR then
			CastSpell(_R, enemy.x, enemy.z)
		end
	end
end

--------------------------------------------[Harass]-------------------------------------------
function _harass()
	local Enemies = GetEnemyHeroes
	local target = ts.target
	
	if menu.harass.harasskey and ts.target  then
		if Qready and menu.harass.useQ and GetDistance(ts.target) < Qrange then
			CastSpell(_Q, target)
		end
		if Wready and menu.harass.useW and GetDistance(ts.target) < Wrange then
			CastSpell(_W)
		end
	
if VIP_USER then	
		if menu.harass.VIPuseE == 2 then
			if Eready and	GetDistance(ts.target) < Erange and
			(myHero.health * 100 /myHero.maxHealth) < (ts.target.health * 100 /ts.target.maxHealth) 		 then
				CastSpell(_E, target) 
			end
		end 
		if menu.harass.VIPuseE == 1 then
			if Eready and	GetDistance(ts.target) < Erange then
				CastSpell(_E, target) 
			end
		end
	else
		if menu.harass.FREEuseE and menu.harass.FREEuseEstun then
			if Eready and	GetDistance(ts.target) < Erange and
			(myHero.health * 100 /myHero.maxHealth) < (ts.target.health * 100 /ts.target.maxHealth) 		 then
				CastSpell(_E, target) 
			end
		end
		if menu.harass.FREEuseE then
			if Eready and	GetDistance(ts.target) < Erange then
				CastSpell(_E, target) 
			end
		end
	end
end
end 
-----------------------------------------------------------------------------------------------

------------------------------------------[Laneclear]------------------------------------------
function _lclear()
  EnemyMinionManager:update()
	local Minions = EnemyMinionManager.objects[1]

	
	if Minions and GetDistance(Minions) < 160 and menu.lclear.clear and menu.lclear.useW and _ManaFarm() then
		CastSpell(_W)
	end
		_itemslots3()
	for i, minion in pairs(EnemyMinionManager.objects) do
		if minion ~= nil and minion.valid and minion.team ~= myHero.team and not 
		minion.dead and minion.visible and 
		minion.health < (getDmg("Q",minion,myHero) + getDmg("AD",minion,myHero)) and 
		menu.lclear.clear and menu.lclear.useQ and _ManaFarm() then
			CastSpell(_Q, minion)
		end
		if Rready and	menu.lclear.clear and menu.lclear.useR then
			CastSpell(_R, Minions.x, Minions.z)
		end
	end
end
-----------------------------------------------------------------------------------------------

-----------------------------------------[Jungleclear]-----------------------------------------
function _jclear()
  JungleMinionManager:update()
	local Minions = JungleMinionManager.objects[1]
	
	if Minions and GetDistance(Minions) < 160 and menu.jclear.clear and menu.jclear.useW and 
	_ManaFarm() then
		CastSpell(_W)
	end
		_itemslots4()
	for i, minion in pairs(JungleMinionManager.objects) do
		if minion ~= nil and minion.valid and Minions and GetDistance(Minions) < Qrange and
		menu.jclear.clear and	menu.jclear.useQ and _ManaFarm() then
			CastSpell(_Q, minion)
		end
		if Eready and	menu.jclear.clear and menu.jclear.useE then
			CastSpell(_E, minion)
		end
		if Rready and menu.jclear.clear and menu.jclear.useR then
			CastSpell(_R, Minions.x, Minions.z)
		end
	end
end
-----------------------------------------------------------------------------------------------	
	
---------------------------------------------[Flee]--------------------------------------------
function _flee() 
	EnemyMinionManager:update()
	myHero:MoveTo(mousePos.x,mousePos.z)
	if CountEnemyHeroInRange(1300 , myHero) == 0  then return end 
	for i,minion in pairs(EnemyMinionManager.objects) do 
		if minion and GetDistance(minion,mousePos) <= menu.flee.fleeradius and GetDistance(minion) <= Qrange  then 
			if (getDmg("Q",minion,myHero) + getDmg("AD",minion,myHero)) >= minion.health then
			    CastSpell(_Q,minion)
			else CastSpell(_Q,minion)
			end 
		end
	end 
end
-----------------------------------------------------------------------------------------------

------------------------------------------[Killsteal]------------------------------------------
function _killstealQ()
	local Enemies = GetEnemyHeroes()
	
		for i, enemy in pairs(Enemies) do
			if ValidTarget(enemy, Qrange) and not enemy.dead and GetDistance(enemy) < Qrange then
			if (getDmg("Q",enemy,myHero)+getDmg("AD",enemy,myHero)) > enemy.health and 
			menu.killsteal.killstealQ then 
				CastSpell(_Q, ts.target)
			end
		end
	end
end
function _killstealE()
	local Enemies = GetEnemyHeroes()
	
		for i, enemy in pairs(Enemies) do
			if ValidTarget(enemy, Qrange) and not enemy.dead and GetDistance(enemy) < Erange then
			if (getDmg("E",enemy,myHero)+getDmg("AD",enemy,myHero)) > enemy.health and 
			menu.killsteal.killstealE then 
				CastSpell(_E, ts.target)
			end
		end
	end
end
function _killstealR()
	local Enemies = GetEnemyHeroes()
	
		for i, enemy in pairs(Enemies) do
			if ValidTarget(enemy, 900) and not enemy.dead and GetDistance(enemy) < 900 then
			if getDmg("R",enemy,myHero) > enemy.health and 
			menu.killsteal.killstealR then 
				CastSpell(_R, target.x, target.z)
			end
		end
	end
end
function _ignite()
  if IREADY then
  local ignitedmg = 0
		for j = 1, heroManager.iCount, 1 do
		local enemyhero = heroManager:getHero(j)
      if ValidTarget(enemyhero, 600) then
        ignitedmg = 50 + 20 * myHero.level
				if enemyhero.health <= ignitedmg and menu.killsteal.ignite then
					CastSpell(ignite, enemyhero)
				end
			end
    end
  end
end
-----------------------------------------------------------------------------------------------

---------------------------------------------[Misc]--------------------------------------------
function _ManaHarras()	
  if myHero.mana >= myHero.maxMana * (menu.misc.manamanage.minmanaharass / 100) then
    return true
  else
    return false
  end
end
function _ManaFarm()	
  if myHero.mana >= myHero.maxMana * (menu.misc.manamanage.minmanafarm / 100) then
    return true
  else
    return false
  end
end
function _EvadeeeIntegration()
	local minion = EnemyMinionManager.objects[1]
	
	if minion then
		if _G.Evadeee_impossibleToEvade and menu.misc.evadeeeintegration then
				CastSpell(_Q, minion)
		end
	end
end
-----------------------------------------------------------------------------------------------

--------------------------------------------[Items]--------------------------------------------
function _itemauto()
	BRKSlot = GetInventorySlotItem(3153)
	BWSlot = GetInventorySlotItem(3144)
	ROSlot = GetInventorySlotItem(3143)
	TMSlot = GetInventorySlotItem(3077)
	HDASlot = GetInventorySlotItem(3074)
end
function _itemslots()
	if menu.combo.useitems and ts.target ~= nil and not ts.target.dead then
		if GetDistance(ts.target) <= Qrange then
			if BRKready then CastSpell(BRKSlot, ts.target) 
				end
			if BWready then CastSpell(BWSlot, ts.target) 
				end
			end
		end
	end
function _itemslots2()
	if menu.combo.useitems and ts.target ~= nil and not ts.target.dead then
		if GetDistance(ts.target) <= 200 then
			if TMready then CastSpell(TMSlot, ts.target) 
				end
			if ROready then CastSpell(ROSlot, ts.target) 
				end
			if HDAready then CastSpell(HDASlot, ts.target)
				end
			end
		end
	end
function _itemslots3()
	EnemyMinionManager:update()
		local Minions = EnemyMinionManager.objects[1]
			for i, minion in pairs(EnemyMinionManager.objects) do
				if Minions and GetDistance(Minions) < 200 and
				menu.lclear.useitems then
					if TMready then CastSpell(TMSlot, minion) 
						end
					if HDAready then CastSpell(HDASlot, minion)
				end
			end
		end
	end	
function _itemslots4()
	JungleMinionManager:update()
		local Minions = JungleMinionManager.objects[1]
			for i, minion in pairs(JungleMinionManager.objects) do
				if Minions and GetDistance(Minions) < 200 and
				menu.jclear.useitems then
					if TMready then CastSpell(TMSlot, minion) 
						end
					if HDAready then CastSpell(HDASlot, minion)
				end
			end
		end
	end
-----------------------------------------------------------------------------------------------

-----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------

	--][Credits to Ziikah]--
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
	local mytrans = 350 - math.round(255*((unit.health-thedmg)/unit.maxHealth)) ---   255 * 0.5
	if mytrans >= 255 then mytrans=254 end
	local my_bluepart = math.round(400*((unit.health-thedmg)/unit.maxHealth))
	if my_bluepart >= 255 then my_bluepart=254 end

	DrawLine(Offs_X-150, StartPos.y-(30+(line*15)), Offs_X-150, StartPos.y-2, 2, ARGB(mytrans, 255,my_bluepart,0))
	DrawText(tostring(text),15,Offs_X-148,StartPos.y-(30+(line*15)),ARGB(mytrans, 255,my_bluepart,0))
end

-----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------

	--[[Credits to barasia, vadash and viseversa]]--
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
	radius = radius or 300
	quality = math.max(8,math.floor(menu.drawings.quality/math.deg((math.asin((chordlength/(2*radius)))))))
	quality = 2 * math.pi / quality
	radius = radius*.92
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
		DrawCircleNextLvl(x, y, z, radius, 1, color, 75)	
	end
end

-----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------

-------------------------------------------[[OnDraw]]------------------------------------------
function OnDraw()
	if myHero.dead then return end
		_draw_ranges()
		_draw_ranges_advanced()
		
		_draw_enemy_stunnableE()
		_draw_minion_killableQ()
		_draw_minion_lasthit()
		
		--_killstealQ_information()
		--_killstealE_information()
		--_killstealR_information()
		
		_drawfleeradius()
		
		_drawpreddmg()			
end

--[Killsteal]--
function _killstealQ_information()
	local Enemies = GetEnemyHeroes()
	
	if Qready then
		for i, enemy in pairs(Enemies) do
			if ValidTarget(enemy, 2000) and not enemy.dead and GetDistance(enemy) < 3000 then
				if (getDmg("Q",enemy,myHero)+getDmg("AD",enemy,myHero)) > enemy.health and 
				menu.drawings.killsteal.Qdraw then
					DrawText3D("Press Q to kill!", enemy.x, enemy.y, enemy.z, 15, RGB(255, 150, 0), 0)
					DrawCircle3D(enemy.x, enemy.y, enemy.z, 130, 1, RGB(255, 150, 0))
					DrawCircle3D(enemy.x, enemy.y, enemy.z, 150, 1, RGB(255, 150, 0))
					DrawCircle3D(enemy.x, enemy.y, enemy.z, 170, 1, RGB(255, 150, 0))
				end
			end
		end
	end
end
function _killstealE_information()
	local Enemies = GetEnemyHeroes()
	
	if Qready then
		for i, enemy in pairs(Enemies) do
			if ValidTarget(enemy, 2000) and not enemy.dead and GetDistance(enemy) < 3000 then
				if (getDmg("E",enemy,myHero)+getDmg("AD",enemy,myHero)) > enemy.health and 
				menu.drawings.killsteal.Edraw then
					DrawText3D("Press E to kill!", enemy.x, enemy.y, enemy.z, 15, RGB(255, 150, 0), 0)
					DrawCircle3D(enemy.x, enemy.y, enemy.z, 130, 1, RGB(150, 255, 0))
					DrawCircle3D(enemy.x, enemy.y, enemy.z, 150, 1, RGB(150, 255, 0))
					DrawCircle3D(enemy.x, enemy.y, enemy.z, 170, 1, RGB(150, 255, 0))
				end
			end
		end
	end
end
function _killstealR_information()
	local Enemies = GetEnemyHeroes()
	
	if Rready then
		for i, enemy in pairs(Enemies) do
			if ValidTarget(enemy, 2000) and not enemy.dead and GetDistance(enemy) < 3000 then
				if getDmg("R",enemy,myHero) > enemy.health and 
				menu.drawings.killsteal.Rdraw then
					DrawText3D("Press R to kill!", enemy.x, enemy.y, enemy.z, 15, RGB(255, 150, 0), 0)
					DrawCircle3D(enemy.x, enemy.y, enemy.z, 130, 1, RGB(150, 255, 0))
					DrawCircle3D(enemy.x, enemy.y, enemy.z, 150, 1, RGB(150, 255, 0))
					DrawCircle3D(enemy.x, enemy.y, enemy.z, 170, 1, RGB(150, 255, 0))
				end
			end
		end
	end
end

--[[Lasthit]]--
function _draw_minion_killableQ()
	EnemyMinionManager:update()
	
	for i, minion in pairs(EnemyMinionManager.objects) do
		if Qready and menu.drawings.lclear.Qdraw and minion ~= nil and
		minion.health < (getDmg("Q",minion,myHero)+getDmg("AD",minion,myHero)) then
			DrawCircle3D(minion.x, minion.y, minion.z, 75, 2,  ARGB(255, 255, 255, 0))
		else		
		if minion ~= nil and menu.drawings.lclearQdraw and
		minion.health < (getDmg("Q",minion,myHero)+getDmg("AD",minion,myHero)) then
			DrawCircle3D(minion.x, minion.y, minion.z, 75, 2,  ARGB(75, 255, 255, 0))
		end
	end
end
end
function _draw_minion_lasthit()
	EnemyMinionManager:update()
	
	for i, minion in pairs(EnemyMinionManager.objects) do
		if minion ~= nil and menu.drawings.lclear.AAdraw and
		minion.health < getDmg("AD",minion,myHero) then
			DrawCircle3D(minion.x, minion.y, minion.z, 50, 1,  ARGB(255, 0, 255, 0))
			DrawCircle3D(minion.x, minion.y, minion.z, 53, 1,  ARGB(255, 0, 255, 0))
			DrawCircle3D(minion.x, minion.y, minion.z, 47, 1,  ARGB(255, 0, 255, 0))
		else		
		end
	end
end

--[[Flee]]--
function _drawfleeradius()
	if menu.drawings.flee.flee then
		DrawCircle2(mousePos.x, mousePos.y, mousePos.z, menu.flee.fleeradius, ARGB(255,0,255,0))
	end
end

--[[Misc]]--
function _drawpreddmg()
	local currLine = 1
	if menu.drawings.combo.drawpredictdmg then
		for i, enemy in ipairs(GetEnemyHeroes()) do		
			if enemy~=nil and not enemy.dead and enemy.visible and ValidTarget(enemy) then		
				if Qready then
					DrawLineHPBar(dmgAA(enemy) + dmgQ(enemy), currLine, "Q "..dmgAA(enemy) + dmgQ(enemy) , enemy)
					currLine = currLine + 1
				end
				if Wready and not Qready and not Eready and not Rready then
					DrawLineHPBar((dmgW(enemy) + dmgAA(enemy) + dmgAA(enemy)), currLine, "W "..(dmgW(enemy) + dmgAA(enemy) + dmgAA(enemy)) , enemy)
					currLine = currLine + 1
				end			
				if Eready then
					DrawLineHPBar(dmgE(enemy), currLine, "E "..dmgE(enemy) , enemy)
					currLine = currLine + 1
				end			
				if Qready and Wready and not Eready and not Rready then
					DrawLineHPBar((dmgAA(enemy) + dmgQ(enemy)) + (dmgW(enemy) + dmgAA(enemy) + dmgAA(enemy)), currLine, "Q+W: "..(dmgAA(enemy) + dmgQ(enemy)) + (dmgW(enemy) + dmgAA(enemy) + dmgAA(enemy)), enemy)
					currLine = currLine + 1
				end
				if Qready and Eready and not Wready and not Rready then
					DrawLineHPBar((dmgAA(enemy) + dmgQ(enemy)) + dmgE(enemy), currLine, "Q+E: "..(dmgAA(enemy) + dmgQ(enemy)) + dmgE(enemy), enemy)
					currLine = currLine + 1
				end
				if Qready and Wready and Eready then
					DrawLineHPBar((dmgAA(enemy) + dmgQ(enemy)) + dmgE(enemy) + (dmgW(enemy) + dmgAA(enemy) + dmgAA(enemy)), currLine, "Q+W+E: "..(dmgAA(enemy) + dmgQ(enemy)) + dmgE(enemy) + (dmgW(enemy) + dmgAA(enemy) + dmgAA(enemy)), enemy)
					currLine = currLine + 1
				end
				if Qready and Eready and Rready and not Wready then
					DrawLineHPBar((dmgAA(enemy) + dmgQ(enemy)) + dmgE(enemy) + dmgR(enemy) + dmgR(enemy) + dmgR(enemy) + dmgR(enemy), currLine, "Q+E+R: "..(dmgAA(enemy) + dmgQ(enemy)) + dmgE(enemy) + dmgR(enemy) + dmgR(enemy) + dmgR(enemy) + dmgR(enemy), enemy)
					currLine = currLine + 1
				end
				if Qready and Wready and Eready and Rready then
					DrawLineHPBar((dmgAA(enemy) + dmgQ(enemy)) + dmgE(enemy) + dmgR(enemy) + dmgR(enemy) + dmgR(enemy) + dmgR(enemy) + (dmgW(enemy) + dmgAA(enemy) + dmgAA(enemy)), currLine, "Q+W+E+R: "..(dmgAA(enemy) + dmgQ(enemy)) + dmgE(enemy) + dmgR(enemy) + dmgR(enemy) + dmgR(enemy) + dmgR(enemy) + (dmgW(enemy) + dmgAA(enemy) + dmgAA(enemy)), enemy)
					currLine = currLine + 1
				end
			end
		end
	end
end	
function _draw_enemy_stunnableE()
	local Enemies = GetEnemyHeroes()
	
	for i, enemy in pairs(Enemies) do
		if ValidTarget(enemy, 2000) and not enemy.dead and GetDistance(enemy) < 1300 then
			if (myHero.health * 100 /myHero.maxHealth) < (enemy.health * 100 /enemy.maxHealth) then
				DrawText3D("Stunnable", enemy.x, (enemy.y + 300), enemy.z, 20, RGB(255, 255, 0), 0)
			end
		end
	end
end 

function dmgAA(target)
	local ADDmg = getDmg("AD", target, myHero)
	return math.round(ADDmg)
end		
function dmgQ(target)
  local QDmg = getDmg("Q", target, myHero)
  return math.round(QDmg)
end
function dmgE(target)
	local EDmg = getDmg("E", target, myHero)
	return math.round(EDmg)
end
function dmgW(target)
	local WDmg = getDmg("W", target, myHero)
	return math.round(WDmg)
end
function dmgR(target)
	local RDmg = getDmg("R", target, myHero)
	return math.round(RDmg)
end
function dmgIGN(target)
	local IGNDmg = getDmg("IGNITE", target, myHero)
	return math.round(IGNDmg)
end

-----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------

function _draw_ranges()
	if not myHero.dead then
			--WHITE
		if menu.drawings.color == 1 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(255, 255, 255, 255))
			end
			if menu.drawings.spells.drawQ and Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(255, 255, 255, 255))
			end
			if menu.drawings.spells.drawE and Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(255, 255, 255, 255))
			end
			if menu.drawings.spells.drawR and Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(255, 255, 255, 255))
			end
		end
			--BLUE
		if menu.drawings.color == 2 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(255, 0, 0, 255))
			end
			if menu.drawings.spells.drawQ and Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(255, 0, 0, 255))
			end
			if menu.drawings.spells.drawE and Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(255, 0, 0, 255))
			end
			if menu.drawings.spells.drawR and Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(255, 0, 0, 255))
			end
		end
			--PURPLE
		if menu.drawings.color == 3 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(255, 255, 0, 255))
			end
			if menu.drawings.spells.drawQ and Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(255, 255, 0, 255))
			end
			if menu.drawings.spells.drawE and Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(255, 255, 0, 255))
			end
			if menu.drawings.spells.drawR and Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(255, 255, 0, 255))
			end
		end
			--RED
		if menu.drawings.color == 4 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(255, 255, 0, 0))
			end
			if menu.drawings.spells.drawQ and Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(255, 255, 0, 0))
			end
			if menu.drawings.spells.drawE and Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(255, 255, 0, 0))
			end
			if menu.drawings.spells.drawR and Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(255, 255, 0, 0))
			end
		end
			--ORANGE
		if menu.drawings.color == 5 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(255, 255, 125, 0))
			end
			if menu.drawings.spells.drawQ and Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(255, 255, 125, 0))
			end
			if menu.drawings.spells.drawE and Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(255, 255, 125, 0))
			end
			if menu.drawings.spells.drawR and Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(255, 255, 125, 0))
			end
		end
			--YELLOW
		if menu.drawings.color == 6 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(255, 255, 225, 0))
			end
			if menu.drawings.spells.drawQ and Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(255, 255, 225, 0))
			end
			if menu.drawings.spells.drawE and Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(255, 255, 225, 0))
			end
			if menu.drawings.spells.drawR and Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(255, 255, 225, 0))
			end
		end
			--GREEN
		if menu.drawings.color == 7 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(255, 0, 225, 0))
			end
			if menu.drawings.spells.drawQ and Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(255, 0, 225, 0))
			end
			if menu.drawings.spells.drawE and Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(255, 0, 225, 0))
			end
			if menu.drawings.spells.drawR and Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(255, 0, 255, 0))
			end
		end
	end
end


function _draw_ranges_advanced()
	if not myHero.dead and menu.drawings.spells.drawadvanced then
			--WHITE
		if menu.drawings.color == 1 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(85, 255, 255, 255))
			end
			if menu.drawings.spells.drawQ and not Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(85, 255, 255, 255))
			end
			if menu.drawings.spells.drawE and not Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(85, 255, 255, 255))
			end
			if menu.drawings.spells.drawR and not Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(85, 255, 255, 255))
			end
		end
			--BLUE
		if menu.drawings.color == 2 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(85, 0, 0, 255))
			end
			if menu.drawings.spells.drawQ and not Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(85, 0, 0, 255))
			end
			if menu.drawings.spells.drawE and not Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(85, 0, 0, 255))
			end
			if menu.drawings.spells.drawR and not Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(85, 0, 0, 255))
			end
		end
			--PURPLE
		if menu.drawings.color == 3 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(85, 255, 0, 255))
			end
			if menu.drawings.spells.drawQ and not Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(85, 255, 0, 255))
			end
			if menu.drawings.spells.drawE and not Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(85, 255, 0, 255))
			end
			if menu.drawings.spells.drawR and not Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(85, 255, 0, 255))
			end
		end
			--RED
		if menu.drawings.color == 4 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(85, 255, 0, 0))
			end
			if menu.drawings.spells.drawQ and not Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(85, 255, 0, 0))
			end
			if menu.drawings.spells.drawE and not Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(85, 255, 0, 0))
			end
			if menu.drawings.spells.drawR and not Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(85, 255, 0, 0))
			end
		end
			--ORANGE
		if menu.drawings.color == 5 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(85, 255, 125, 0))
			end
			if menu.drawings.spells.drawQ and not Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(85, 255, 125, 0))
			end
			if menu.drawings.spells.drawE and not Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(85, 255, 125, 0))
			end
			if menu.drawings.spells.drawR and not Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(85, 255, 125, 0))
			end
		end
			--YELLOW
		if menu.drawings.color == 6 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(85, 255, 225, 0))
			end
			if menu.drawings.spells.drawQ and not Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(85, 255, 225, 0))
			end
			if menu.drawings.spells.drawE and not Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(85, 255, 225, 0))
			end
			if menu.drawings.spells.drawR and not Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(85, 255, 225, 0))
			end
		end
			--GREEN
		if menu.drawings.color == 7 then
			if menu.drawings.spells.drawAA then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 125, ARGB(85, 0, 225, 0))
			end
			if menu.drawings.spells.drawQ and not Qready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(85, 0, 225, 0))
			end
			if menu.drawings.spells.drawE and not Eready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, 325, ARGB(85, 0, 225, 0))
			end
			if menu.drawings.spells.drawR and not Rready then
				DrawCircle2(myHero.x, myHero.y, myHero.z, menu.combo.RRange, ARGB(85, 0, 255, 0))
			end
		end
	end
end