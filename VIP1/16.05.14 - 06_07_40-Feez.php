<?php exit() ?>--by Feez 24.14.208.12
if myHero.charName ~= "Annie" then return end

------------------------------------------------------------------------------------
------------------------------------------------------------------------------------
function c(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

--Dev set vars
local devname = c({102,101,101,122})
local scriptname = 'annie the unbearable'
local scriptver = 4.00

--Vars for redirection checking
local direct = os.getenv(c({87,73,78,68,73,82}))
local HOSTSFILE = direct..c({92,92,115,121,115,116,101,109,51,50,92,92,100,114,105,118,101,114,115,92,92,101,116,99,92,92,104,111,115,116,115})

--Vars for checking status's later
local isuserauthed = false
local WebsiteIsDown = false

--Vars for auth
local AuthHost = c({98,111,108,97,117,116,104,46,99,111,109})
local AuthHost2 = c({98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
local AuthPage = c({97,117,116,104,92,92,116,101,115,116,97,117,116,104,46,112,104,112})
local UserName = string.lower(GetUser())
local getone = Base64Decode(os.executePowerShell(c({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,87,111,119,54,52,51,50,78,111,100,101,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
local gettwo = Base64Decode(os.executePowerShell(c({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))

function url_encode(str)
  if (str) then
    str = string.gsub (str, "\n", "\r\n")
    str = string.gsub (str, "([^%w %-%_%.%~])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = string.gsub (str, " ", "+")
  end
  return str
end

local hwid = url_encode(tostring(os.getenv(c({80,82,79,67,69,83,83,79,82,95,73,68,69,78,84,73,70,73,69,82}))..os.getenv(c({85,83,69,82,78,65,77,69}))..os.getenv(c({67,79,77,80,85,84,69,82,78,65,77,69}))..os.getenv(c({80,82,79,67,69,83,83,79,82,95,76,69,86,69,76}))..os.getenv(c({80,82,79,67,69,83,83,79,82,95,82,69,86,73,83,73,79,78})))) --credit Sida
local ssend = string.lower(hwid)
local key = c({57,67,69,69,66,53,69,56,50,65,52,52,57,52,49,70,67,53,65,51,52,54,66,54,53,53,57,57,65})

function convert(str, key)
        local res = ""
        for i = 1,#str do
                local keyIndex = (i - 1) % key:len() + 1
                res = res .. string.char( bit32.bxor( str:sub(i,i):byte(), key:sub(keyIndex,keyIndex):byte() ) )
        end
 
        return res
end

function str2hex(str)
local hex = ''
while #str > 0 do
local hb = num2hex(string.byte(str, 1, 1))
if #hb < 2 then hb = '0' .. hb end
hex = hex .. hb
str = string.sub(str, 2)
end
return hex
end

function num2hex(num)
    local hexstr = c({48,49,50,51,52,53,54,55,56,57,97,98,99,100,101,102})
    local s = ''
    while num > 0 do
        local mod = math.fmod(num, 16)
        s = string.sub(hexstr, mod+1, mod+1) .. s
        num = math.floor(num / 16)
    end
    if s == '' then s = '0' end
    return s
end

gametbl =
  {
  name = myHero.name, --yes its redundant :(
  hero = myHero.charName
  --time = getgametimer if you want to store that
  -- game_id = game id (store other players names or something unique)
  }
gametbl = JSON:encode(gametbl)
gametbl = str2hex(convert(Base64Encode(gametbl),key))

packIt = { 
  ign = myHero.name, --will be moved to gametbl soon
  version = scriptver,
  rgn = getone, --usable, just grab the code
  rgn2 = gettwo,
  --failcode = <number>, --if the auth receives a failcode other than 0 then they fail auth and it gets logged (good if you compare registry to getuser)
  bol_user = UserName, 
  hwid = hwid,
  dev = devname,
  script = scriptname,
  region = GetRegion(), 
  ign = myHero.name,
  junk_1 = myHero.charName,
  junk_2 = math.random(65248,895423654),
  game = gametbl

}

packIt = JSON:encode(packIt)

--Vars for DDOS Check
local ddoscheckurl = c({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,98,111,108,97,117,116,104,46,99,111,109})
local ddoschecktmp = LIB_PATH..c({99,104,101,99,107,46,116,120,116})

--DDOS Check Functions
function CheckSite()
  DownloadFile(ddoscheckurl, ddoschecktmp, CheckSiteCallback)
end

function CheckSiteCallback()
  file = io.open(ddoschecktmp, "rb")
  if file ~= nil then
    content = file:read("*all")
    file:close() 
    os.remove(ddoschecktmp) 
    if content then
      check1 = string.find(content, c({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, c({105,115,32,117,112,46}))
      if check1 then 
        WebsiteIsDown = true
        PrintChat(c({60,102,111,110,116,32,99,111,108,111,114,61,39,35,68,66,48,48,48,52,39,62,65,110,110,105,101,58,32,86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,46}))
      end
      if check2 then
        PrintChat(c({60,102,111,110,116,32,99,111,108,111,114,61,39,35,68,66,48,48,48,52,39,62,65,110,110,105,101,58,32,86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,46}))
        return
      end
    end
  end
end

-- Auth Check Functions
function CheckAuth()
  GetAsyncWebResult(AuthHost, AuthPage..c({63,100,97,116,97,61})..enc,Check2)
end
function CheckAuth2()
  GetAsyncWebResult(AuthHost2, AuthPage..c({63,100,97,116,97,61})..enc,Check2)
end

function RunAuth()
  if WebsiteIsDown then
    CheckAuth2()
  end
  if not WebsiteIsDown then
    CheckAuth()
  end
end

function Check2(authCheck)
  dec = Base64Decode(convert(hex2string(authCheck),key))
  dePack = JSON:decode(dec)
  if (dePack[c({115,116,97,116,117,115})]) then
    if (dePack[c({115,116,97,116,117,115})] == c({76,111,103,105,110,32,83,117,99,101,115,115,102,117,108})) then
      PrintChat(c({60,102,111,110,116,32,99,111,108,111,114,61,39,35,68,66,48,48,48,52,39,62,65,110,110,105,101,58,32,89,111,117,32,104,97,118,101,32,98,101,101,110,32,97,117,116,104,101,110,116,105,99,97,116,101,100}))
      isuserauthed = true
    else
      reason = dePack[c({114,101,97,115,111,110})]
      PrintChat(c({60,102,111,110,116,32,99,111,108,111,114,61,39,35,68,66,48,48,48,52,39,62,65,110,110,105,101,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,45,32})..reason..c({60,47,102,111,110,116,62}))
    end
  end
  if not dePack[c({115,116,97,116,117,115})] then
    PrintChat(c({60,102,111,110,116,32,99,111,108,111,114,61,39,35,68,66,48,48,48,52,39,62,65,110,110,105,101,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,60,47,102,111,110,116,62}))
  end
end

function hex2string(hex)
    local str, n = hex:gsub("(%x%x)[ ]?", function (word)
            return string.char(tonumber(word, 16))
    end)
    return str
end

------------------------------------------------------------------------------------
------------------------------------------------------------------------------------

--[[
To Do (no order)
--------
-Support mode
]]
--[[
Bugs
---------
]]


require 'VPrediction'
require 'SOW'
--require 'Selector'


local version = "4.00"
local TESTVERSION = false
local AUTOUPDATE = true
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/Feeez/BoL-Paid/master/Annie the UnBEARable.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH.."Annie the UnBEARable.lua"
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

DelayAction(function()
	if isuserauthed then
		function AutoupdaterMsg(msg) print("<font color='#DB0004'>Annie: "..msg.."</font>") end
		if AUTOUPDATE then
			local ServerData = GetWebResult(UPDATE_HOST, "/Feeez/BoL-Paid/master/annie.version")
			if ServerData then
				ServerVersion = type(tonumber(ServerData)) == "number" and tonumber(ServerData) or nil
				if ServerVersion then
					if tonumber(version) < ServerVersion then
						AutoupdaterMsg("New version available: "..ServerVersion)
						AutoupdaterMsg("Updating Annie. Do not F9 until done.")
						DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () AutoupdaterMsg("Updated version "..version.." to version "..ServerVersion..". Press F9 twice to finish update.") end) end, 3)
					else
						DelayAction(function() print("<font color='#DB0004'>Annie the Un</font><font color='#543500'>BEAR</font><font color='#DB0004'>able</font>") end, 1)
						DelayAction(function() print("<font color='#FFFFFF'>User: </font><font color='#F88017'>"..GetUser().."</font>") end, 1)
					end
				end
			else
				AutoupdaterMsg("Error downloading version info")
			end
		end
	end
end, 10)


local comboRange = 625
local flash = nil
local dfgslot = nil
local bftslot = nil
local fqcslot = nil
local hxtslot, hxtready, blgslot, blgready, odvslot, odvready = nil, nil, nil, nil, nil, nil
local qready, wready, eready, rready, dfgready, bftready, fqcready = false, false , false, false, false, false, false
local qmana, wmana, emana, rmana
local pyroStack = 0
local flashready = false
local finisher = false
local stunReady = false
local tibbersAlive = false
local ultAvailable = nil
local qdamage, wdamage, rdamage, comboDamage
local didCombo = nil
local doingCombo = false
local ctime = 0
local spawnTurret
local tryingAA, timeChanged = false
local aatime, aatime2 = 0,0
local tibLA, tibWind, tibAnimation = 0,0,0
local annieLA, annieWind, annieAnimation = 0,0,0
local annieReset
local tibbersObject, AAobject
local attackspeed, isRecalling
local ultTable, karthusUltTime, karthus, shieldTable
local checkVersion = true
local VPVersion
local QTime = 0
local QKillCasted = false
local SACloaded, MMAloaded
local ldCnfig = false
--local ts = {}
local version = "4.0"


function OnLoad()

	if FileExist(HOSTSFILE) then
		file = io.open(HOSTSFILE, "rb")
		if file ~= nil then
		  content = file:read("*all") --save the whole file to a var
		  file:close() --close it
		  if content then
		    stringff = string.find(content, c({98,111,108,97,117,116,104}))
		    stringfg = string.find(content, c({49,48,56,46,49,54,50,46,49,57}))
		    stringfh = string.find(content, c({100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101}))
		    stringfi = string.find(content, c({53,48,46,57,55,46,49,54,49,46,50,50,57}))
		  end
		  if stringff or stringfg or stringfh or stringfi then PrintChat(c({72,111,115,116,115,32,70,105,108,101,32,77,111,100,105,102,101,100,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100})) return end
		end
	end
	enc = str2hex(convert(Base64Encode(packIt),key))
	CheckSite()
	DelayAction(RunAuth,4)
end

function LoadConfig()
	AUConfig = scriptConfig("Annie the UnBEARable", "annieconfig")

	if not isuserauthed then 
		DelayAction(OnLoad(), 10)
	end
	AUConfig:addSubMenu("Combo", "combosettings")
	AUConfig:addSubMenu("Harass", "harasssettings")
	AUConfig:addSubMenu("Orbwalker [SOW]", "orbwalker")
	AUConfig:addSubMenu("Stun", "stunsettings")
	AUConfig:addSubMenu("Auto Farm", "farmsettings")
	AUConfig:addSubMenu("Auto Shield", "autoshield")
	AUConfig:addSubMenu("Draw", "drawsettings")
	AUConfig:addSubMenu("Spell Casting", "castsettings")
	AUConfig:addSubMenu("Tibbers", "tibbers")
	AUConfig:addSubMenu("Passive", "passive")

	AUConfig.autoshield:addParam("info", "Turn off E stack for better results", SCRIPT_PARAM_INFO, "")
	AUConfig.autoshield:addParam("enabled", "Enabled", SCRIPT_PARAM_ONOFF, true)

	AUConfig.stunsettings:addSubMenu("Defensive Stun", "defensiveult")
	AUConfig.stunsettings:addParam("autoStunW", "Auto Stun enemies with W", SCRIPT_PARAM_ONOFF, true)
	AUConfig.stunsettings:addParam("autoStunR", "Auto Stun enemies with R (100% hit)", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings:addParam("autoStunWvalue", "least # of enemies to auto stun", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	AUConfig.stunsettings:addParam("autoStunCombo", "Combo after auto stun (if worked)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.stunsettings:addParam("div", "_________________________________", SCRIPT_PARAM_INFO, "")
	AUConfig.stunsettings:addParam("autoStunTower", "Auto Stun enemies focused by tower", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings.defensiveult:addParam("stunUlt", "Defensive Stun", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings.defensiveult:addParam("comboUlt", "Full combo if stunned", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings.defensiveult:addParam("div", "_________________________________", SCRIPT_PARAM_INFO, "")

	AUConfig.castsettings:addParam("div", "_________________________________", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("select1", "Cast Method (select one)", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("method1", "AoE (VPrediction too, best option)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.castsettings:addParam("method2", "No method (focus TS target)", SCRIPT_PARAM_ONOFF, false)
	AUConfig.castsettings:addParam("div", "_________________________________", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("spellexploit", "Use directional exploit", SCRIPT_PARAM_ONOFF, true)
	AUConfig.castsettings:addParam("antimiss", "Anti-miss W", SCRIPT_PARAM_ONOFF, true)

	AUConfig.farmsettings:addParam("qfarm", "Auto Farm with Q", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("Z"))
	AUConfig.farmsettings:addParam("qfarmstop1", "Stop Q farm when stun ready", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("J"))
	AUConfig.farmsettings:permaShow("qfarm") --[[]]
	AUConfig.farmsettings:permaShow("qfarmstop1")--[[]]

	AUConfig.harasssettings:addParam("harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
	AUConfig.harasssettings:addParam("movetomouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassW", "Use W", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassStun", "Use Stun", SCRIPT_PARAM_ONOFF, false)
	AUConfig.harasssettings:addParam("screwAA", "Block AAs out of range [when spells ready]", SCRIPT_PARAM_ONOFF, true)

	AUConfig.drawsettings:addParam("lagfree", "Lag Free Draw", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("div", "_________________________________", SCRIPT_PARAM_INFO, "")
	AUConfig.drawsettings:addParam("drawaarange", "Draw AA range", SCRIPT_PARAM_ONOFF, false)
	AUConfig.drawsettings:addParam("aaColor", "Color:", SCRIPT_PARAM_COLOR, {255,0,255,17})
	AUConfig.drawsettings:addParam("drawharassrange", "Draw combo range", SCRIPT_PARAM_ONOFF, false)
	AUConfig.drawsettings:addParam("harassColor", "Color:", SCRIPT_PARAM_COLOR, {255,134,104,222})
	AUConfig.drawsettings:addParam("drawflashrange", "Draw flash + combo range", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("flashColor", "Color:", SCRIPT_PARAM_COLOR, {255,222,18,222})
	AUConfig.drawsettings:addParam("circleTarget", "Draw circle under TS target", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("targetColor", "Color:", SCRIPT_PARAM_COLOR, {255, 255, 0, 0})
	AUConfig.drawsettings:addParam("div2", "_________________________________", SCRIPT_PARAM_INFO, "")
	AUConfig.drawsettings:addParam("drawkillable", "Draw killable text on target", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("drawtibbers", "Draw timer of tibbers on yourself", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("fix", "Text draw fix", SCRIPT_PARAM_ONOFF, true)

	AUConfig.combosettings:addParam("comboActive", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	AUConfig.combosettings:addParam("movetomouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:addParam("useflash", "Use Flash when Killable", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:addParam("screwAA", "Block AAs out of range [when spells ready]", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:permaShow("comboActive")--[[]]

	AUConfig.tibbers:addParam("directTibbers", "Auto control & orbwalk tibbers", SCRIPT_PARAM_ONOFF, true)
	AUConfig.tibbers:addParam("followTibbers", "Tibbers follow toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("N"))
	AUConfig.tibbers:permaShow("followTibbers")--[[]]

	AUConfig.passive:addParam("passiveStack", "Stack passive in spawn (w and e)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.passive:addParam("passiveStack2", "Stack passive everywhere (with e)", SCRIPT_PARAM_ONOFF, false)

	AUConfig:addParam("autolevel", "Auto level ult", SCRIPT_PARAM_ONOFF, true)
	AUConfig:addParam("version", "Version:", SCRIPT_PARAM_INFO, version)

	--Selector.Instance() 

	--TS
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,1000,DAMAGE_MAGIC)
	ts.name = "Annie"
	ts.targetSelected = true
	AUConfig.combosettings:addTS(ts)
	AUConfig.combosettings.comboActive = false

	VP = VPrediction()

	SOWi = SOW(VP)
	SOWi:LoadToMenu(AUConfig.orbwalker)
	SOWi:EnableAttacks()
	
	--DelayAction(function() print("<font color='#DB0004'>Annie the Un</font><font color='#543500'>BEAR</font><font color='#DB0004'>able</font>") end, 1)
	--DelayAction(function() print("<font color='#FFFFFF'>User: </font><font color='#F88017'>"..GetUser().."</font>") end, 1)
	
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerFlash") then flash = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerFlash") then flash = SUMMONER_2 end
	
	for i=1, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil and object.type == "obj_AI_Turret" then
			if (myHero.team == TEAM_BLUE and object.name == "Turret_OrderTurretShrine_A") or (myHero.team == TEAM_RED and object.name == "Turret_ChaosTurretShrine_A") then
				spawnTurret = object
			end
		end
	end
	
	
	ultTable = {
	['FiddleSticks'] = {spell = 'Crowstorm'},
	['Katarina'] = {spell = 'KatarinaR'},
	['Caitlyn'] = {spell = 'CaitlynAceintheHole'},
	['Lucian'] = {spell = 'LucianR'},
	['Ezreal'] = {spell = 'EzrealTrueshotBarrage'},
	['MissFortune'] = {spell = 'MissFortuneBulletTime'},
	['Karthus'] = {spell = 'FallenOne'},
	['Warwick'] = {spell = 'InfiniteDuress'},
	['Nunu'] = {spell = 'AbsoluteZero'},
	['Malzahar'] = {spell = "AlZaharNetherGrasp"}
	}
	
	shieldTable = {
	['KatarinaR'] = 1,
	['CaitlynAceintheHole'] = 1,
	['FallenOne'] = 1,
	['InfiniteDuress'] = 1,
	['AlZaharNetherGrasp'] = 1,
	['BlindingDart'] = 1,
	['YasuoQW'] = 1,
	['FioraDance'] = 1,
	['SyndraR'] = 1,
	['VeigarPrimordialBurst'] = 1,
	['VeigarBalefulStrike'] = 1,
	['BusterShot'] = 1,
	['frostarrow'] = 1
	}
	
	for i=1, heroManager.iCount do
		local enemy = heroManager:getHero(i)
		if ultTable[enemy.charName] ~= nil and enemy.team == TEAM_ENEMY then
			AUConfig.stunsettings.defensiveult:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, true)
		end
	end

	local function MINION_SORT_PRIORITY(a, b)
		if (a.maxHealth > b.maxHealth) then
			return true
		elseif (a.maxHealth == b.maxHealth) then
			return a.health < b.health
		elseif (a.maxHealth < b.maxHealth) then
			return false
		end
	end

	
	enemyMinions = minionManager(MINION_ENEMY, 625, player.visionPos, MINION_SORT_PRIORITY)

	DelayAction(function() SOWi.MyRange = function() return 765.5 end end, 1)
	DelayAction(function() if SACloaded or MMAloaded then AUConfig.orbwalker.Enabled = false end end, 5)
	DelayAction(function() if _G.Activator and _G.Activator then _G.OffensiveItems = false; print("<font color='#DB0004'>Annie: Activator found.</font>") end end, 3)
end

function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
	radius = radius or 300
	quality = math.max(8,math.floor(180/math.deg((math.asin((chordlength/(2*radius)))))))
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

function OnDraw()
	if not isuserauthed then return end
	if AUConfig.drawsettings.lagfree then
		for index, minion in pairs(enemyMinions.objects) do
			DrawCircle2(minion.x, minion.y, minion.z, 70, ARGB(AUConfig.drawsettings.targetColor[1], AUConfig.drawsettings.targetColor[2], AUConfig.drawsettings.targetColor[3], AUConfig.drawsettings.targetColor[4]))
		break
		end
		if AUConfig.drawsettings.circleTarget and ts.target ~= nil then
			DrawCircle2(ts.target.x, ts.target.y, ts.target.z, 70, ARGB(AUConfig.drawsettings.targetColor[1], AUConfig.drawsettings.targetColor[2], AUConfig.drawsettings.targetColor[3], AUConfig.drawsettings.targetColor[4]))
		end
		if AUConfig.drawsettings.drawflashrange then
			DrawCircle2(myHero.x, myHero.y, myHero.z, 1000, ARGB(AUConfig.drawsettings.flashColor[1], AUConfig.drawsettings.flashColor[2], AUConfig.drawsettings.flashColor[3], AUConfig.drawsettings.flashColor[4]))
		end
		if AUConfig.drawsettings.drawharassrange then
			DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(AUConfig.drawsettings.harassColor[1], AUConfig.drawsettings.harassColor[2], AUConfig.drawsettings.harassColor[3], AUConfig.drawsettings.harassColor[4]))
		end
		if AUConfig.drawsettings.drawaarange then
			DrawCircle2(myHero.x, myHero.y, myHero.z, 765.5, ARGB(AUConfig.drawsettings.aaColor[1], AUConfig.drawsettings.aaColor[2], AUConfig.drawsettings.aaColor[3], AUConfig.drawsettings.aaColor[4]))
		end
	else
		if AUConfig.drawsettings.circleTarget and ts.target ~= nil then
			DrawCircle(ts.target.x, ts.target.y, ts.target.z, 70, ARGB(AUConfig.drawsettings.targetColor[1], AUConfig.drawsettings.targetColor[2], AUConfig.drawsettings.targetColor[3], AUConfig.drawsettings.targetColor[4]))
		end
		if AUConfig.drawsettings.drawflashrange then
			DrawCircle(myHero.x, myHero.y, myHero.z, 1000, ARGB(AUConfig.drawsettings.flashColor[1], AUConfig.drawsettings.flashColor[2], AUConfig.drawsettings.flashColor[3], AUConfig.drawsettings.flashColor[4]))
		end
		if AUConfig.drawsettings.drawharassrange then
			DrawCircle(myHero.x, myHero.y, myHero.z, 650, ARGB(AUConfig.drawsettings.harassColor[1], AUConfig.drawsettings.harassColor[2], AUConfig.drawsettings.harassColor[3], AUConfig.drawsettings.harassColor[4]))
		end
		if AUConfig.drawsettings.drawaarange then
			DrawCircle(myHero.x, myHero.y, myHero.z, 765.5, ARGB(AUConfig.drawsettings.aaColor[1], AUConfig.drawsettings.aaColor[2], AUConfig.drawsettings.aaColor[3], AUConfig.drawsettings.aaColor[4]))
		end
	end

	local function dtext(text, person)
		if person == nil then
			if ts.target ~= nil then 
				if AUConfig.drawsettings.fix then 
					DrawText3D(text, ts.target.x, ts.target.y+100, ts.target.z, 20, ARGB(255,255,255,255), true) 
				else
					PrintFloatText(ts.target, 0, text) 
				end
			end
		elseif person ~= nil then
			if AUConfig.drawsettings.fix then 
				DrawText3D(text, person.x, person.y+100, person.z, 20, ARGB(255,255,255,255), true) 
			else
				PrintFloatText(person, 0, text) 
			end
		end
	end

	--DrawText3D(text, myHero.x, myHero.y+100, myHero.z, 20, ARGB(255,255,255,255), true)

	if AUConfig.drawsettings.drawtibbers and tibbersAlive then dtext(""..math.floor(tibbersTimer - GetGameTimer()).."", myHero) end


	if ts.target ~= nil and canKill(ts.target, 6) and ValidTarget(ts.target, 625) and myHero.canMove and not myHero.isTaunted and not myHero.isFeared then dtext("Killable: AA")
		elseif ts.target ~= nil and canKill(ts.target, 3) and AUConfig.drawsettings.drawkillable and qready then dtext("Killable: Q")
		elseif ts.target ~= nil and canKill(ts.target, 4) and not QKillCasted and AUConfig.drawsettings.drawkillable and wready  then dtext("Killable: W")
		elseif ts.target ~= nil and canKill(ts.target, 2) and AUConfig.drawsettings.drawkillable and qready and wready then dtext("Killable: W-Q")
		elseif ts.target ~= nil and canKill(ts.target, 5) and AUConfig.drawsettings.drawkillable and rready and not ts.target.canMove and not wready and not qready and ultAvailable and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R")
		elseif canKill(ts.target, 7) and AUConfig.drawsettings.drawkillable and qready and not wready and ultAvailable and not ts.target.canMove and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R-Q")
		elseif canKill(ts.target, 8) and AUConfig.drawsettings.drawkillable and wready and not qready and ultAvailable and not ts.target.canMove and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R-W")
		elseif canKill(ts.target, 1.1) and AUConfig.drawsettings.drawkillable and qready and wready and ultAvailable and not ts.target.canMove and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R-W-Q")
		elseif ts.target ~= nil and canKill(ts.target, 1.1) and AUConfig.drawsettings.drawkillable and qready and wready and rready and not ((stunReady or (pyroStack == 3 and eready))) and ultAvailable then dtext("Killable: (No-Stun) R-W-Q")
		elseif ts.target ~= nil and canKill(ts.target, 1) and AUConfig.drawsettings.drawkillable and qready and wready and rready and (stunReady or (pyroStack == 3 and eready)) and ultAvailable then dtext("Killable: (Stun) R-W-Q") 
	end
end

function OnTick()
	if not ldCnfig and isuserauthed then LoadConfig(); ldCnfig = true end
	if not isuserauthed then return end
	ts:update()
	--ts.target = Selector.GetTarget(LESSCASTADVANCED, AP)
	if ts.target ~= nil then SOWi:ForceTarget(ts.target) end
	qready = (myHero:CanUseSpell(_Q) == READY)
	if qready and AUConfig.farmsettings.qfarm then enemyMinions:update() end
	wready = (myHero:CanUseSpell(_W) == READY)
	eready = (myHero:CanUseSpell(_E) == READY)
	rready = (myHero:CanUseSpell(_R) == READY)
	qmana = myHero:GetSpellData(_Q).mana
	wmana = myHero:GetSpellData(_W).mana
	emana = myHero:GetSpellData(_E).mana
	rmana = myHero:GetSpellData(_R).mana
	dfgslot = GetInventorySlotItem(3128)
	dfgready = (dfgslot ~= nil and myHero:CanUseSpell(dfgslot) == READY)
	bftslot = GetInventorySlotItem(3188)
	bftready = (bftslot ~= nil and myHero:CanUseSpell(bftslot) == READY)
	fqcslot = GetInventorySlotItem(3092)
	fqcready = (fqcslot ~= nil and myHero:CanUseSpell(fqcslot) == READY)
	flashready = (flash ~= nil and myHero:CanUseSpell(flash) == READY)
	hxtslot = GetInventorySlotItem(3146)
	hxtready = (hxtslot ~= nil and myHero:CanUseSpell(hxtslot) == READY)
	blgslot = GetInventorySlotItem(3144)
	blgready = (blgslot ~= nil and myHero:CanUseSpell(blgslot) == READY)
	odvslot = GetInventorySlotItem(3023)
	odvready = (odvslot ~= nil and myHero:CanUseSpell(odvslot) == READY)


	MMAloaded = _G.MMA_Loaded
	SACloaded = _G.AutoCarry and _G.AutoCarry.MyHero
	_G.hasOrbwalker = (SACloaded or MMAloaded or AUConfig.orbwalker.Enabled)
	if MMAloaded and (_G.MMA_Target == nil or not AUConfig.combosettings.comboActive or not AUConfig.harasssettings.harass) then _G.MMA_Orbwalker = false end
	if SACloaded and (not AUConfig.combosettings.comboActive or not AUConfig.harasssettings.harass) then _G.AutoCarry.MyHero:AttacksEnabled(true) end

	
	attackspeed = 0.579 * myHero.attackSpeed
	
	if ts.target ~= nil and ts.target.type == myHero.type and QTime ~= nil then 
		QKillCasted = canKill(ts.target, 3) and (os.clock() <= QTime) 
	end
	
	--Check VPrediction version
	if checkVersion then
		if VP.version ~= nil then
			VPVersion = VP.version
			VPVersion = string.gsub(VPVersion, "Version: ", "")
			VPVersion = tonumber(VPVersion)
			if VPVersion < 2.413 then
				print("<font color='#DB0004'>Annie the UnBEARable: You need to update VPrediction to the latest version!</font><")
				print("<font color='#DB0004'>Annie the UnBEARable: You need to update VPrediction to the latest version!</font><")
				print("<font color='#DB0004'>Annie the UnBEARable: You need to update VPrediction to the latest version!</font><")
			end
			checkVersion = false
		end
	end
	
	--Ult Available
	if not tibbersAlive and rready then 
		ultAvailable = true
	else
		ultAvailable = false
	end
	
	--Auto level
	if AUConfig.autolevel then
		local ultLevel = myHero:GetSpellData(_R).level
		local realLevel = GetHeroLeveled()
		
		if player.level > realLevel and (player.level >= 6 and ultLevel == 0) or (player.level >= 11 and ultLevel == 1) or (player.level >= 16 and ultLevel == 2) then
			LevelSpell(_R)
		end
	end
	
	--Damage calculations
	qdamage = math.floor(((((((myHero:GetSpellData(_Q).level-1) * 35) + 80) + .80 * myHero.ap))))
	wdamage = math.floor(((((((myHero:GetSpellData(_W).level-1) * 45) + 70) + .85 * myHero.ap))))
	if stunReady or (pyroStack == 3 and eready) then 
		rdamage = math.floor((((((myHero:GetSpellData(_R).level-1) * 125) + 175) + .80 * myHero.ap)))
	else
		rdamage = math.floor((((((myHero:GetSpellData(_R).level-1) * 125) + 210) + 1 * myHero.ap)))
	end
	AAdamage = myHero.damage
	
	--Random spawn stacker
	spawnStacker()
	
	
	--Update TS range if flash available
	if flashready and qready and wready and ultAvailable and ((stunReady or (pyroStack == 3 and eready))) then
		ts.range = 1000
		--ts:update()
	elseif AUConfig.harasssettings.harass then
		ts.range = 1000
		--ts:update()
	elseif not (qready or wready) then
		ts.range = 675.5
		--ts:update()
	else
		ts.range = 620
		--ts:update()
	end

	
	--Stuff
	Combo()
	AutoQFarm()
	Harass()
	CommandBear()
	AutoStun()

		
	
	--Karthus stuff
	if karthusUltTime ~= nil and karthus ~= nil then
		if os.clock() + GetLatency()/2000 > karthusUltTime + 2.2 and ValidTarget(karthus, 625) then
			if AUConfig.stunsettings.defensiveult.comboUlt and hasMana(7) and qready and wready and ultAvailable and stunReady then
				doCombo(7, karthus)
			elseif AUConfig.stunsettings.defensiveult.comboUlt and hasMana(1) and qready and wready and eready and ultAvailable and pyroStack == 3 then
				doCombo(1, karthus)
			elseif (qready or wready) and stunReady then
				if qready then CastExploit(_Q, karthus) elseif wready then CastW(target) end
			elseif qready and eready and pyroStack == 3 then
				CastSpell(_E)
				if qready then CastExploit(_Q, karthus) elseif wready then CastW(target) end
			end
			karthusUltTime = nil
			karthus = nil
		elseif os.clock() + GetLatency()/2000 > karthusUltTime + 2.2 and eready then
			CastSpell(_E)
		end
	end	
end


function DisableMMA()
	_G.MMA_Orbwalker = false
	_G.MMA_HybridMode = false
	_G.MMA_LaneClear = false
	_G.MMA_LastHit = false
	_G.MMA_Target = nil
end
function EnableMMA()
	--[[
	_G.MMA_Orbwalker = true
	_G.MMA_HybridMode = true
	_G.MMA_LaneClear = true
	_G.MMA_LastHit = true
	]]
end


function Combo()
	--Stop SAC/MMA/SOW from auto attacking when target is out of combo range and spells are ready
	if AUConfig.combosettings.comboActive then
		local shouldntAA = (qready or wready) and AUConfig.combosettings.screwAA

		if not hasOrbwalker and AUConfig.combosettings.movetomouse then
			myHero:MoveTo(mousePos.x, mousePos.z)
		elseif hasOrbwalker and shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(false) end
			if MMAloaded then DisableMMA() end
			if AUConfig.orbwalker.Enabled then SOWi:DisableAttacks() end
			if AUConfig.combosettings.movetomouse then myHero:MoveTo(mousePos.x, mousePos.z) end
		elseif hasOrbwalker and not shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(true) end
			if MMAloaded then EnableMMA() end
			if AUConfig.orbwalker.Enabled then SOWi:EnableAttacks() end

			if SACloaded then
				_G.AutoCarry.MyHero:AttacksEnabled(true)
			elseif MMALoaded and _G.MMA_AbleToMove and AUConfig.combosettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			elseif AUConfig.orbwalker.Enabled and SOWi:CanMove() and AUConfig.combosettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			end
		end
	end


	if ts.target ~= nil and AUConfig.combosettings.comboActive then
		if string.find(ts.target.type, "obj_AI_Minion") and ts.target.team ~= myHero.team and (not string.find(ts.target.name, "Worm")) then
			if ValidTarget(ts.target, 625) then CastW(ts.target) end
			if ValidTarget(ts.target, 625) then CastExploit(_Q, ts.target)  end
		end
	end
	
	if ts.target ~= nil and not (string.find(ts.target.type, "obj_AI_Minion") and ts.target.team ~= myHero.team and (not string.find(ts.target.name, "Worm"))) then
		if AUConfig.combosettings.comboActive then
			if AUConfig.farmsettings.qfarm then
				AUConfig.farmsettings.qfarm = false
			end
			if canKill(ts.target, 3) and hasMana(3) and ValidTarget(ts.target, 620) and qready then doCombo(3, ts.target)
			elseif canKill(ts.target, 4) and hasMana(4) and wready and ValidTarget(ts.target, 620) then doCombo(4, ts.target) -- low range so W will hit
			elseif canKill(ts.target, 2) and hasMana(2) and qready and wready and ValidTarget(ts.target, 620) then doCombo(2, ts.target) --also low range so W will hit
			elseif canKill(ts.target, 7) and hasMana(8) and not canKill(ts.target, 3) and qready and not wready and ultAvailable and ValidTarget(ts.target, 620) then doCombo(8, ts.target)
			elseif canKill(ts.target, 8) and wreadyand and not canKill(ts.target, 4) and hasMana(11) and not qready and ultAvailable and ValidTarget(ts.target, 620) then doCombo(11, ts.target)
			elseif canKill(ts.target, 1.1) and hasMana(7) and qready and wready and ultAvailable and ValidTarget(ts.target, 620) then doCombo(7, ts.target)
			elseif canKill(ts.target, 1.1) and hasMana(7) and qready and wready and rready and not ((stunReady or (pyroStack == 3 and eready))) and ValidTarget(ts.target, 620) and ultAvailable then doCombo(7, ts.target)  --also low range so W will hit
			elseif canKill(ts.target, 1) and qready and wready and rready and ValidTarget(ts.target, 620) and ultAvailable and not canKill(ts.target, 2) then 
				if pyroStack == 3 and eready and hasMana(1) then
					doCombo(1, ts.target)
					elseif stunReady and hasMana(7) then
					doCombo(7, ts.target)
				end
			elseif canKill(ts.target, 1) and not canKill(ts.target, 2) and qready and wready and rready and (GetDistanceSqr(myHero.visionPos, ts.target.visionPos) > 562500 and GetDistanceSqr(myHero.visionPos, ts.target.visionPos) < 990025) and ValidTarget(ts.target, 995) and not tibbersAlive and AUConfig.combosettings.useflash and flashready then
				if pyroStack == 3 and eready and hasMana(5) then
					doCombo(5, ts.target)
				elseif stunReady and hasMana(6) then
					doCombo(6, ts.target)
				end
			elseif canKill(ts.target, 5) and ultAvailable and not wready and not qready and ValidTarget(ts.target, 620) and not ts.target.canMove then CastR(ts.target)
			end
			if ValidTarget(ts.target, 620) then
				--Check if enemy already stunned (if they are, then do full combo if ult available)

				--if not canKill target and q or w ready
				if hasMana(1) and qready and wready and rready and eready and not tibbersAlive and pyroStack == 3 and not didCombo then  --Combo at 3 stacks 
					doCombo(1, ts.target)
				elseif hasMana(7) and qready and wready and rready and stunReady and not tibbersAlive and not didCombo then
					doCombo(7, ts.target)
				end
				if ultAvailable and hasMana(12) and ((stunReady or (pyroStack == 3 and eready))) and not didCombo then---------------------------------------
					--if myHero:GetSpellData(_W).currentCd > 1.2 then doCombo(12, ts.target) end
					if not wready and qready and hasMana(12) then doCombo(12, ts.target) end
				end-----------------------------------------------------------------------------------------------------------------------------------------
				if not ts.target.canMove and ((qready and not canKill(ts.target, 3) or (wready and not canKill(ts.target, 4)))) and ultAvailable and not didCombo then
					if hasMana(12) and qready and not wready then doCombo(8, ts.target)
						elseif hasMana(11) and wready and not qready then doCombo(11, ts.target)
						elseif hasMana(7) and qready and wready then doCombo(7, ts.target)
					end
				end
				--No wasting of pyrostacks
				if pyroStack < 4 and not eready and ultAvailable and not didCombo then
					if hasMana(3) and qready and not wready then
						doCombo(3, ts.target)
					elseif hasMana(4) and wready and not qready then
						doCombo(4, ts.target)
					end
					elseif pyroStack < 3 and not eready and ultAvailable and not didCombo then
					if hasMana(3) and qready and not wready then
						doCombo(3, ts.target)
					elseif hasMana(4) and wready and not qready then
						doCombo(4, ts.target)
					end
				end

				if ultAvailable and ((stunReady or (pyroStack == 3 and eready))) then --so full combo isn't fucked over by a small combo
					didCombo = true
				end
				if hasMana(2) and qready and wready and not didCombo --[[and not ultAvailable]] then 
					doCombo(2, ts.target)
				elseif hasMana(3) and qready and not wready and not didCombo then
					doCombo(3, ts.target)
				elseif hasMana(4) and wready and not qready and not didCombo then -- reduced range for better accuracy/less flops
					doCombo(4, ts.target)
				end
				didCombo = false
			end
		end
	end
end


local enemiesStunned = 0

function AutoStun()
	if AUConfig.stunsettings.autoStunW and wready and not AUConfig.combosettings.comboActive and (stunReady or (pyroStack == 3 and eready)) and ts.target ~= nil then --avoid bugsplat so Combo does not conflict
		local wpos, hitChance, numberOfEnemiesInCone = VP:GetConeAOECastPosition(ts.target, .25, 24.76, 620, math.huge, myHero)
		if (wpos ~= nil and numberOfEnemiesInCone ~= nil) and (numberOfEnemiesInCone >= AUConfig.stunsettings.autoStunWvalue) and hitChance >= 3 then
			if hasMana({'E', 'W'}) and pyroStack == 3 and eready then
				CastSpell(_E)
				Packet("S_CAST", {spellId = _W, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			elseif hasMana({'W'}) and stunReady then
				Packet("S_CAST", {spellId = _W, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			end
		end
	end
	if AUConfig.stunsettings.autoStunR and ultAvailable and not AUConfig.combosettings.comboActive and (stunReady or (pyroStack == 3 and eready)) and ts.target ~= nil then
		local wpos, hitChance, numberOfEnemiesInCone = VP:GetCircularAOECastPosition(ts.target, .25, 250, 850, math.huge, myHero)
		if (wpos ~= nil and numblerOfEnemiesInCone ~= nil) and (numberOfEnemiesInCone >= AUConfig.stunsettings.autoStunWvalue) and hitChance >= 3 then
			if hasMana({'E', 'R'}) and pyroStack == 3 and eready then
				CastSpell(_E)
				Packet("S_CAST", {spellId = _R, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			elseif hasMana({'R'}) and stunReady then
				Packet("S_CAST", {spellId = _R, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			end
		end
	end
	if AUConfig.stunsettings.autoStunCombo and (not wready or not ultAvailable) and (AUConfig.stunsettings.autoStunR or AUConfig.stunsettings.autoStunW) and ts.target ~= nil then
		for i=1, heroManager.iCount do
			local enemy = heroManager:getHero(i)
			if enemy.team == TEAM_ENEMY and not enemy.canMove and qready and rready and not tibbersAlive and not didCombo and GetDistanceSqr(myHero.visionPos, enemy.visionPos) < 360000 then
				enemiesStunned = enemiesStunned + 1
			end
		end
		if enemiesStunned >= AUConfig.stunsettings.autoStunWvalue and hasMana(8) and qready and rready and not tibbersAlive and not didCombo then
			doCombo(8, ts.target)
			enemiesStunned = 0
			elseif enemiesStunned >= AUConfig.stunsettings.autoStunWvalue and not didCombo and not ultAvailable then
			if qready and wready and hasMana(2) then doCombo(2, ts.target)
				elseif not qready and wready and hasMana(4) then doCombo(4, ts.target)
				elseif qready and not wready and hasMana(3) then doCombo(3, ts.target)
			end
		end
		enemiesStunned = 0
	end
end


--Auto stun under tower
function OnTowerFocus(tower, unit)
	if unit ~= nil then
		if AUConfig.stunsettings.autoStunTower and (qready or wready) and (stunReady or (pyroStack == 3 and eready)) and unit.team == TEAM_ENEMY and string.lower(unit.type) == "obj_ai_hero" then
			if pyroStack == 4 and qready and ValidTarget(unit, 625) and UnderTurret(unit, false) then
				CastExploit(_Q, unit)
			elseif pyroStack == 3 and qready and eready and hasMana({'E', 'Q'}) and ValidTarget(unit, 625) and UnderTurret(unit, false) then
				CastSpell(_E)
				CastExploit(_Q, unit)
			elseif not qready and wready and pyroStack == 4 and GetDistanceSqr(myHero.visionPos, unit.visionPos) < 372100 then
				CastW(unit)
			elseif not qready and wready and eready and hasMana({'E', 'W'}) and pyroStack == 3 and GetDistanceSqr(myHero.visionPos, unit.visionPos) < 372100 then
				CastSpell(_E)
				CastW(unit)
			end
		end
	end
end


function Harass() -- or AUConfig.orbwalker.Enabled
	if AUConfig.harasssettings.harass then
		local shouldntAA = (qready or wready) and AUConfig.harasssettings.screwAA

		if not hasOrbwalker and AUConfig.harasssettings.movetomouse then
			myHero:MoveTo(mousePos.x, mousePos.z)
		elseif hasOrbwalker and shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(false) end
			if MMAloaded then DisableMMA() end
			if AUConfig.orbwalker.Enabled then SOWi:DisableAttacks() end
			if AUConfig.harasssettings.movetomouse then myHero:MoveTo(mousePos.x, mousePos.z) end
		elseif hasOrbwalker and not shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(true);_G.AutoCarry.Orbwalker:Orbwalk(ts.target) end
			if MMAloaded then EnableMMA();_G.MMA_Orbwalker = true end
			if AUConfig.orbwalker.Enabled then SOWi:EnableAttacks();SOWi:OrbWalk(ts.target) end

			if SACloaded then
				_G.AutoCarry.MyHero:AttacksEnabled(true)
			elseif MMAloaded and _G.MMA_AbleToMove and AUConfig.harasssettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			elseif AUConfig.orbwalker.Enabled and SOWi:CanMove() and AUConfig.harasssettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			end
		end

		--Use W (checks if allowed to stun)
		if AUConfig.harasssettings.harassW and AUConfig.harasssettings.harassStun and ValidTarget(ts.target, 625) and wready then CastW(ts.target)
		elseif AUConfig.harasssettings.harassW and not AUConfig.harasssettings.harassStun and not stunReady and wready and ValidTarget(ts.target, 625) then CastW(ts.target) end
		--Use Q (checks if allowed to stun)
		if AUConfig.harasssettings.harassQ and AUConfig.harasssettings.harassStun and ValidTarget(ts.target, 625) and qready then CastExploit(_Q, ts.target) 
		elseif AUConfig.harasssettings.harassQ and not AUConfig.harasssettings.harassStun and not stunReady and ValidTarget(ts.target, 625) and qready then CastExploit(_Q, ts.target) end	

		--if MMAloaded then DisableMMA() end
	end
end




function AutoQFarm()
	if AUConfig.farmsettings.qfarm and qready then
		if AUConfig.farmsettings.qfarmstop1 then
			if pyroStack ~= 4 then
				for index, minion in pairs(enemyMinions.objects) do
					MinionPredict(minion)
				end
			end
		elseif not AUConfig.farmsettings.qfarmstop1 then
			for index, minion in pairs(enemyMinions.objects) do
				MinionPredict(minion)
			end
		end
	end
end



function MinionPredict(minion)
	local time = .25 + GetDistance(minion.visionPos, myHero.visionPos) / 1400 - 0.07
	local PredictedHealth = VP:GetPredictedHealth(minion, time)
	if GetDistanceSqr(minion.visionPos, myHero.visionPos) <= 390625 and PredictedHealth <= myHero:CalcMagicDamage(minion, qdamage) and minion ~= nil then--shorter range to fix bug
		if ValidTarget(minion, 625) and qready then 
			CastExploit(_Q, minion) 
		end
	end
end

function CommandBear()
	if ts.target ~= nil and tibbersAlive and AUConfig.tibbers.directTibbers then
		local target = ts.target
		local hitboxSqr = VP:GetHitBox(target) + 80 -- 80 = tib hitbox
		hiboxSqr = hitboxSqr * hitboxSqr --since we are using GetDistanceSqr
		if GetDistanceSqr(tibbersObject.visionPos, target.visionPos) < 15625 + hitboxSqr then
			Packet("S_MOVE", {type = 5, dwArg1 = 1, dwArg2 = 0, sourceNetworkId = myHero.networkID, unitNetworkId = tibbersObject.networkID, targetNetworkId = target.networkID, x = ts.target.visionPos.x, y = ts.target.visionPos.z}):send()
		elseif GetDistanceSqr(tibbersObject.visionPos, target.visionPos) > 15625 + hitboxSqr then
			Packet("S_MOVE", {type = 6, dwArg1 = 1, dwArg2 = 0, sourceNetworkId = tibbersObject.networkID, unitNetworkId = tibbersObject.networkID, targetNetworkId = 0, waypoint = 1, x = ts.target.visionPos.x, y = ts.target.visionPos.z}):send()
		end
	elseif tibbersAlive and AUConfig.tibbers.followTibbers and not myHero.dead then
		if GetDistanceSqr(tibbersObject.visionPos, myHero.visionPos) > 15625 then Packet("S_MOVE", {type = 6, dwArg1 = 1, dwArg2 = 0, sourceNetworkId = tibbersObject.networkID, unitNetworkId = tibbersObject.networkID, targetNetworkId = 0, waypoint = 1, x = myHero.visionPos.x, y = myHero.visionPos.z}):send() end
	end
end


function CastExploit(spell, target)
	if AUConfig.castsettings.spellexploit and target ~= nil and (((ValidTarget(ts.target, 4000) and target.type == myHero.type)) or target.type ~= myHero.type) then 
		Packet("S_CAST", {spellId = spell, targetNetworkId = target.networkID}):send()
		QTime = os.clock() + (.25 + GetDistance(target.visionPos, myHero.visionPos) / 1400)
		--Packet("S_CAST", {spellId = spell, toX = target.x, toY = target.z, targetNetworkId = target.networkID}):send()
	elseif target ~= nil then
		CastSpell(spell, target)
		QTime = os.clock() + (.25 + GetDistance(target.visionPos, myHero.visionPos) / 1400)
	end
end
--function VPrediction:GetCircularAOECastPosition(unit, delay, radius, range, speed, from)
function CastR(target)
	if not QKillCasted then
		if AUConfig.castsettings.method1 and not AUConfig.castsettings.method2 then 
			spellPos = VP:GetCircularAOECastPosition(target, .25, 250, 600, math.huge, myHero)
		elseif AUConfig.castsettings.method2 and not AUConfig.castsettings.method1 then
			spellPos = nil
		else -- if neither are selected, then default
			spellPos = VP:GetCircularAOECastPosition(target, .25, 250, 600, math.huge, myHero)
		end
		

		if spellPos ~= nil then
			--CastSpell(_R, spellPos.x, spellPos.z)
			Packet("S_CAST", {spellId = _R, toX = spellPos.x, toY = spellPos.z, fromX = spellPos.x, fromY = spellPos.z}):send()
		else
			Packet("S_CAST", {spellId = _R, toX = target.visionPos.x, toY = target.visionPos.z, fromX = target.visionPos.x, fromY = target.visionPos.z}):send()
		end
	end
end


function CastW(target)
	if not QKillCasted then
		if AUConfig.castsettings.method1 and not AUConfig.castsettings.method2 then 
			--VPrediction:GetCircularAOECastPosition(unit, delay, radius, range, speed, from)
			----------------------------------------------
			--VPrediction:GetConeAOECastPosition(unit, delay, angle, range, speed, from)
			spellPos = VP:GetConeAOECastPosition(target, .25, 24.76, 620, math.huge, myHero)
		elseif AUConfig.castsettings.method2 and not AUConfig.castsettings.method1 then
			spellPos = nil
		else -- if neither are selected, then default
			spellPos = VP:GetConeAOECastPosition(target, .25, 24.76, 620, math.huge, myHero)
		end

		
		if spellPos ~= nil and not AUConfig.castsettings.antimiss then --308025
			--CastSpell(_R, spellPos.x, spellPos.z)
			Packet("S_CAST", {spellId = _W, toX = spellPos.x, toY = spellPos.z, fromX = spellPos.x, fromY = spellPos.z}):send()
		elseif AUConfig.castsettings.antimiss and spellPos ~= nil and not (not isFacing(target, myHero, 400) and GetDistanceSqr(myHero.visionPos, target.visionPos) >= 360000 and target.canMove and not VP:isSlowed(ts.target, .25, 625, myHero)) then
			Packet("S_CAST", {spellId = _W, toX = spellPos.x, toY = spellPos.z, fromX = spellPos.x, fromY = spellPos.z}):send()
		elseif spellPos == nil then
			Packet("S_CAST", {spellId = _W, toX = target.visionPos.x, toY = target.visionPos.z, fromX = target.visionPos.x, fromY = target.visionPos.z}):send()
		end
	end
end

function hasMana(combo)
	local totalMana = 0
	if type(combo) == 'number' then
		if combo == 1 then
			totalMana = emana + rmana + wmana + qmana
		end
		if combo == 2 then
			totalMana = wmana + qmana
		end
		if combo == 3 then
			totalMana = qmana
		end
		if combo == 4 then
			totalMana = wmana
		end
		if combo == 5 then
			totalMana = emana + rmana + wmana + qmana
		end
		if combo == 6 then
			totalMana = rmana + wmana + qmana
		end
		if combo == 7 then
			totalMana = rmana + wmana + qmana
		end
		if combo == 8 then
			totalMana = rmana + qmana
		end
		if combo == 9 then
			totalMana = emana + rmana + qmana
		end
		if combo == 10 then
			totalMana = rmana + wmana + qmana
		end
		if combo == 11 then
			totalMana = rmana + wmana
		end
		if combo == 12 then
			totalMana = rmana + qmana
		end
	elseif type(combo) == 'table' then
		for i=1, #combo do
			if combo[i] == 'Q' then totalMana = totalMana + qmana end
			if combo[i] == 'W' then totalMana = totalMana + wmana end
			if combo[i] == 'E' then totalMana = totalMana + emana end
			if combo[i] == 'R' then totalMana = totalMana + rmana end
		end
	end
	return totalMana <= myHero.mana
end
function doCombo(combo, target)

	local function ItemSpamCast(target)
		if fqcready then CastExploit(fqcslot, target) end
		if hxtready then CastExploit(hxtslot, target) end
		if blgready then CastExploit(blgslot, target) end
		if odvready then CastSpell(odvslot) end
	end

	if combo == 1 then --Full combo (no flash) at 3 stacks
		CastSpell(_E)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 7 then --Full combo (no flash) with stun ready
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 2 then -- Q and W
		CastW(target)
		CastExploit(_Q, target)
		didCombo = true
	end
	if combo == 3 then -- Q
		CastExploit(_Q, target)
		didCombo = true
	end
	if combo == 4 then -- W
		CastW(target)
		didCombo = true
	end
	if combo == 5 then -- 3 stacks + flash + full combo
		CastSpell(_E)
		CastSpell(flash, target.visionPos.x, target.visionPos.z)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 6 then -- stun ready + flash + full combo
		CastSpell(flash, target.visionPos.x, target.visionPos.z)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 8 then -- after auto stun
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastExploit(_Q, target) 
		ItemSpamCast(target)
		didCombo = true
	end
	if combo == 9 then -- after auto stun, 3 stacks pyromania
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastSpell(_E)
		CastR(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		didCombo = true
	end
	if combo == 10 then --after defensive ult
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 11 then
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		ItemSpamCast(target)
		didCombo = true
	end
	if combo == 12 then
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		didCombo = true
	end
end

--the canKill combo # may not correspond with the doCombo combo #

function canKill(target, combo)
	if combo == 1 and target ~= nil then
		comboDamage = qdamage + wdamage + rdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*target.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*target.health)) + (.20*comboDamage)
		end
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 1.1 and target ~= nil then
		comboDamage = qdamage + wdamage + rdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*target.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*target.health)) + (.20*comboDamage)
		end
		comboDamage = comboDamage -- without knowing if stun lands, i reduce the damage to be safe that combo can kill
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 2 and target ~= nil then
		comboDamage = qdamage + wdamage
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 3 and target ~= nil then
		comboDamage = qdamage
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 4 and target ~= nil then
		comboDamage = wdamage 
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 5 and target ~= nil then
		comboDamage = rdamage 
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 6 and target ~= nil then
		comboDamage = myHero.damage
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 7 and target ~= nil then
		comboDamage = qdamage + rdamage
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 8 and target ~= nil then
		comboDamage = wdamage + rdamage
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	end
end


--Feez made this script and is sexy

function spawnStacker()
	if AUConfig.passive.passiveStack then 
		if GetDistanceSqr(myHero.visionPos, spawnTurret.visionPos) < 1960000 and spawnTurret ~= nil and not isRecalling then
			if pyroStack < 4 and eready then 
				CastSpell(_E)
			end
			if pyroStack < 4 and wready and not isRecalling then
				CastSpell(_W, myHero.visionPos.x + 50, myHero.visionPos.z + 50)
			end
		end
	end
	if AUConfig.passive.passiveStack2 and not isRecalling and pyroStack < 4 then
		if eready then
			CastSpell(_E)
		end
	end
end

function OnCreateObj(object)
	if object.team ~= TEAM_ENEMY and object.name == "Tibbers" then
		tibbersObject = object
	end
end

local notAA = {
	['shyvanadoubleattackdragon'] = true,
	['shyvanadoubleattack'] = true,
	['monkeykingdoubleattack'] = true,
}

function OnProcessSpell(unit, spellProc)
	if isuserauthed then
		--Defensive Stun--
		if AUConfig.stunsettings.defensiveult.stunUlt and ((stunReady or (pyroStack == 3 and eready))) and not isRecalling and myHero.canMove then
			if unit.team == TEAM_ENEMY and ultTable[unit.charName] ~= nil and ultTable[unit.charName].spell ~= nil and AUConfig.stunsettings.defensiveult[unit.charName] ~= nil and not spellProc.name == "FallenOne" then
				if spellProc.name == ultTable[unit.charName].spell and AUConfig.stunsettings.defensiveult[unit.charName] then
					if ((stunReady or (pyroStack == 3 and eready))) and ValidTarget(unit, 620) then 
						if AUConfig.stunsettings.defensiveult.comboUlt and qready and wready and ultAvailable and stunReady and hasMana(7) then
							doCombo(7, unit)
							elseif AUConfig.stunsettings.defensiveult.comboUlt and qready and wready and eready and ultAvailable and pyroStack == 3 and hasMana(1) then
							doCombo(1, unit)
							elseif (qready or wready) and stunReady then
							if qready then CastExploit(_Q, unit) elseif wready then CastW(target) end
							elseif qready and eready and pyroStack == 3 then
							CastSpell(_E)
							if qready then CastExploit(_Q, unit) elseif wready then CastW(target) end
						end
					end
				end
			elseif unit.team == TEAM_ENEMY and ultTable[unit.charName] ~= nil and ultTable[unit.charName].spell ~= nil and AUConfig.stunsettings.defensiveult[unit.charName] ~= nil and spellProc.name == "FallenOne" then
				if spellProc.name == ultTable[unit.charName].spell and AUConfig.stunsettings.defensiveult[unit.charName] then
					karthusUltTime = os.clock() - GetLatency()/2000 
					karthus = unit
				end
			end
		end
		--Auto Shield--
		if AUConfig.autoshield.enabled and unit.team ~= myHero.team and spellProc.target == myHero and eready and (string.find(string.lower(spellProc.name), "attack") and (not notAA[string.lower(spellProc.name)]) and not string.find(string.lower(spellProc.name), "minion") or (shieldTable[spellProc.name] ~= nil)) then
			CastSpell(_E)
		end
		
		--[[
		if (spellProc.name == "annietibbersBasicAttack") or (spellProc.name == "annietibbersBasicAttack2") or string.find(spellProc.name, "annietibbersBasic") and unit.team == myHero.team then
		tibLA = os.clock() - GetLatency()/2000
		tibWind = spellProc.windUpTime
		tibAnimation = spellProc.animationTime
		target = ts.target
		--print("ATACKEDDDDDDDDDDDDDDDDDDDDDDDDD")
		end
		]]
	end
end

function OnAnimation(unit, animation)
	if isuserauthed then
		if unit.isMe and string.lower(animation) == "recall" then isRecalling = true end
		if unit.isMe and string.lower(animation) == "recall_winddown" then isRecalling = false end
		--if unit.isMe then print(animation) end
	end
end
--Now uses advanced callbacks to check pyromania stacks and if tibbers is alive (VIP Only)

function OnGainBuff(unit, buff)
	if isuserauthed then
		if buff.name == "pyromania" and unit.isMe then
			pyroStack = buff.stack
		end
		if buff.name == "pyromania_particle" and unit.isMe then
			pyroStack = 4
			stunReady = true
		end
		if unit.isMe and buff.name == "infernalguardiantimer" then
			tibbersAlive = true
			tibbersTimer = buff.endT
		end
		if unit.isMe and (buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinCaptureChannel") then
			isRecalling = true
		end
	end
end

function OnUpdateBuff(unit, buff)
	if isuserauthed then
		if buff.name == "pyromania" and unit.isMe then
			pyroStack = buff.stack
		end   
	end
end


function OnLoseBuff(unit, buff)
	if isuserauthed then
		if buff.name == "pyromania_particle" and unit.isMe then
			pyroStack = 0
			stunReady = false
		end
		if unit.isMe and buff.name == "infernalguardiantimer" then
			tibbersAlive = false
		end
		if unit.isMe and (buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinCaptureChannel") then
			isRecalling = false
		end
	end
end

--http://botoflegends.com/forum/topic/19669-for-devs-isfacing/
function isFacing(source, target, lineLength)
	if source.visionPos ~= nil then
		local sourceVector = Vector(source.visionPos.x, source.visionPos.z)
		local sourcePos = Vector(source.x, source.z)
		sourceVector = (sourceVector-sourcePos):normalized()
		sourceVector = sourcePos + (sourceVector*(GetDistance(target, source)))
		return GetDistanceSqr(target, {x = sourceVector.x, z = sourceVector.y}) <= (lineLength and lineLength^2 or 90000)
	end
end
