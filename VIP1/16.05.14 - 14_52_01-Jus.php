<?php exit() ?>--by Jus 189.69.24.82
if myHero.charName ~= "Riven" or not VIP_USER then return end

require "VPrediction"
---------------------------

function _AutoupdaterMsg(msg)
    print("<font color=\"#6699ff\"><b>Riven, Boost my Elo:</b></font> <font color=\"#FFFFFF\">"..msg..".</font>") 
end

local version         		  = "1.01"
local AUTOUPDATE      		  = true
local UPDATE_HOST           = "raw.github.com"
local UPDATE_PATH           = "/Jusbol/scripts/master/rivenmyelo.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH      = SCRIPT_PATH.."rivenyelo.lua"
local UPDATE_URL            = "https://"..UPDATE_HOST..UPDATE_PATH
if AUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, "/Jusbol/scripts/master/VersionFiles/RivenPaid.version")
    if ServerData then
        ServerVersion = type(tonumber(ServerData)) == "number" and tonumber(ServerData) or nil
        if ServerVersion then
            if tonumber(version) < ServerVersion then
                _AutoupdaterMsg("New version available"..ServerVersion)
                _AutoupdaterMsg("Updating from github.com, please dont press F9")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () _AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version.") end) end, 3)
            else
               _AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
            end
        end
    else
        _AutoupdaterMsg("Error downloading version info! (github.com)")
    end
end

--AUTH SYSTEM----------------------------------------------------------------------------------------------------------
function c(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

--Dev set vars
local devname = c({74,117,115})
local scriptname = 'rivenmyelo'
local scriptver = 1.01

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

local gametbl =
  {
  name = myHero.name, --yes its redundant :(
  hero = myHero.charName
  --time = getgametimer if you want to store that
  -- game_id = game id (store other players names or something unique)
  }
local gametbl = JSON:encode(gametbl)
local gametbl = str2hex(convert(Base64Encode(gametbl),key))

local packIt = { 
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

local packIt = JSON:encode(packIt)

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
        PrintChat(c({86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,46}))
      end
      if check2 then
        PrintChat(c({86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,46}))
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

-------Variables-------
local myPlayer                                  			       = 	GetMyHero()
local Passive, temUltimate, UsandoHP        			           = 	false, false, false
local TickTack, qTick, lTick, rTick, jTick, shieldTick       = 	0, 0 ,0, 0, 0, 0
local Target, selectedTarget                    			       = 	nil, nil
local lastAttack, lastWindUpTime, lastAttackCD, lastAniQ     = 	0, 0, 0, 0
local IgniteSpell                              				       =   {spellSlot = "SummonerDot", slot = nil, range = 600, ready = false}
local BarreiraSpell                             			       =   {spellSlot = "SummonerBarrier", slot = nil, range = 0, ready = false}
local qCount, countTick 									                   = 	0, 0
local lockIgnite											                       =   false
-------BENCHMARK Variables-------
local PassiveIndicator  									                   = 	0
local tock              									                   = 	0
local vp, Jungle, enemyMinions								               = 	nil, nil, nil
--------------------------------
local LOADED                                                 = false
--------------------------------
function LoadFullMenu()
	menu = scriptConfig("Riven by Jus", "JusRivenPaid")
    MenuCombo() --skills and combo key
    MenuSpell() --multi skill / OnProcessSpell settings
    MenuExtraCombo()    --NoAACombo, items in combo, ignite if killable
    MenuExtraComboOthers()
    MenuExtraComboRange()
    MenuExtraComboBuff()
    MenuFarm()  --farm sttuff       
    MenuExtra() --potions/barrier
    MenuDraw()  --draw ranges   
    MenuSystem()--orbwalk/sida/mma
    Others()    --others
    PermaMenu()
    LOADED = true
end

function Check2(authCheck)
  dec = Base64Decode(convert(hex2string(authCheck),key))
  dePack = JSON:decode(dec)
  if (dePack[c({115,116,97,116,117,115})]) then
    if (dePack[c({115,116,97,116,117,115})] == c({76,111,103,105,110,32,83,117,99,101,115,115,102,117,108})) then
      PrintChat(c({32,62,62,32,89,111,117,32,104,97,118,101,32,98,101,101,110,32,97,117,116,104,101,110,116,105,99,97,116,101,100,32,60,60}))
      isuserauthed = true
      LoadFullMenu()
    else
      reason = dePack[c({114,101,97,115,111,110})]
      PrintChat(c({32,62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,45,32})..reason..c({32,60,60}))
    end
  end
  if not dePack[c({115,116,97,116,117,115})] then
    PrintChat(c({32,62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,60,60}))
  end
end

function OnLoad()
  if FileExist(HOSTSFILE) then
    file = io.open(HOSTSFILE, "rb")
    if file ~= nil then
      content = file:read("*all") --save the whole file to a var
      file:close() --close it
      if content then
        local stringff = string.find(content, c({98,111,108,97,117,116,104}))
        local stringfg = string.find(content, c({49,48,56,46,49,54,50,46,49,57}))
        local stringfh = string.find(content, c({100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101}))
        local stringfi = string.find(content, c({53,48,46,57,55,46,49,54,49,46,50,50,57}))
      end
      if stringff or stringfg or stringfh or stringfi then PrintChat(c({72,111,115,116,115,32,70,105,108,101,32,77,111,100,105,102,101,100,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100})) return end
    end
  end
  enc = str2hex(convert(Base64Encode(packIt),key))
  CheckSite()
  DelayAction(RunAuth,4)
end

function hex2string(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end


function Spell()
	local summonerTable	=	{SUMMONER_1, SUMMONER_2}
	local spells_		=	{IgniteSpell, BarreiraSpell}
	for i=1, #summonerTable do
		for a=1, #spells_ do
			if myPlayer:GetSpellData(summonerTable[i]).name:find(spells_[a].spellSlot) then 
				spells_[a].slot = summonerTable[i]
			end
		end
	end
end

function Others()   
    menu:addParam("Version", "Version Info:", SCRIPT_PARAM_INFO, version)
    Spell()
    vp 					= VPrediction()
    menu.combo.key  	     = false
    menu.combo.skills.r    = true
    if menu.system.sida then
      if Integration then
        if _G.MMA_Loaded then
          myOrb = false       
        end
      if _G.AutoCarry then
        myOrb = false       
      end
    else
      myOrb = true
      if _G.MMA_Loaded then             
        myOrb = true
      end
      if _G.AutoCarry then
       myOrb = true
      end
    end 
  end  
    PrintChat("<font color=\"#6699ff\"><b>Riven, Boost My Elo by Jus loaded.</b></font>")
end

function MenuCombo()
    menu:addSubMenu("[Combo System]", "combo")
    menu.combo:addSubMenu("[Skills Settings]", "skills")
    menu.combo.skills:addParam("q", "Use (Q) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.skills:addParam("w", "Use (W) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.skills:addParam("e", "Use (E) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.skills:addParam("r", "Use (R) in Combo", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("S"))   
    menu.combo:addParam("key", "Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
end

function MenuSpell()
    menu.combo:addSubMenu("[Multi-Skill System]", "multi")
    menu.combo.multi:addParam("qe", "Try (Q-E) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.multi:addParam("ew", "Try (E-W) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.multi:addParam("qr", "Try (Q-R) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.multi:addParam("er", "Try (E-R) in Combo", SCRIPT_PARAM_ONOFF, true)
end

function MenuExtraCombo()
	menu:addSubMenu("[Extra Combo System]", "comboextra")
	menu.comboextra:addParam("", "[Triple Q-AA Settings]", SCRIPT_PARAM_INFO, "")
	menu.comboextra:addParam("mode", "Delay Mode:", SCRIPT_PARAM_LIST, 2, { "Manual", "Auto"})
	menu.comboextra:addParam("delay", "Manual Mode Delay:", SCRIPT_PARAM_SLICE, 2, 1, 10, 0)
	menu.comboextra:addParam("speed", "Try Speed up Q-AA", SCRIPT_PARAM_ONOFF, false)
	menu.comboextra:addParam("", "", SCRIPT_PARAM_INFO, "")
	menu.comboextra:addParam("", "[(W) Settings]", SCRIPT_PARAM_INFO, "")
	menu.comboextra:addParam("items", "Use Items after (W)", SCRIPT_PARAM_ONOFF, true)
	menu.comboextra:addParam("autow", "Auto (W) if # enemys >=", SCRIPT_PARAM_SLICE, 2, 1, 4, 0)	
    menu.comboextra:addParam("", "", SCRIPT_PARAM_INFO, "")
    menu.comboextra:addParam("", "[Ultimate Settings]", SCRIPT_PARAM_INFO, "")
    menu.comboextra:addParam("", "-First (R) Settings-", SCRIPT_PARAM_INFO, "")
    --menu.comboextra:addParam("firstRmode", "Settings mode:", SCRIPT_PARAM_LIST, 2, {"Simple", "Complete"})
    menu.comboextra:addParam("autostartRnumber", "(R) if enemys # >=", SCRIPT_PARAM_SLICE, 2, 1, 4, 0)    
    menu.comboextra:addParam("autostartRhealth", "Main Target Health", SCRIPT_PARAM_SLICE, 30, 10, 100, 0)    
    menu.comboextra:addParam("autostartRrange", "Range to Main Target", SCRIPT_PARAM_SLICE, 400, 125, 550, 0)
    menu.comboextra:addParam("", "-Second (R) Settings-", SCRIPT_PARAM_INFO, "")
    menu.comboextra:addParam("ksultimate", "Try KS with Ultimate", SCRIPT_PARAM_ONOFF, true)
    menu.comboextra:addParam("nKsultimate", "Use KS (R) if # enemys >=", SCRIPT_PARAM_SLICE, 2, 1, 4, 0)
    menu.comboextra:addParam("ultlogic", "Use (R) Team fight logic", SCRIPT_PARAM_ONOFF, true)
    menu.comboextra:addParam("", "", SCRIPT_PARAM_INFO, "")    
    menu.comboextra:addParam("", "[Force Passive Settings]", SCRIPT_PARAM_INFO, "")
	menu.comboextra:addParam("q", "Packet Passive with (Q)", SCRIPT_PARAM_ONOFF, false)
    menu.comboextra:addParam("w", "Packet Passive with (W)", SCRIPT_PARAM_ONOFF, false)
    menu.comboextra:addParam("e", "Packet Passive with (E)", SCRIPT_PARAM_ONOFF, false)
end

function MenuExtraComboOthers()
    menu.comboextra:addSubMenu("[Others Settings]", "others")
    menu.comboextra.others:addParam("", "[General Settings]", SCRIPT_PARAM_INFO, "")
    menu.comboextra.others:addParam("smart", "Use Smart Combo", SCRIPT_PARAM_ONOFF, true)    
    menu.comboextra.others:addParam("ignite", "Use Smart Ignite", SCRIPT_PARAM_ONOFF, true)
    menu.comboextra.others:addParam("targetrange", "Range to Auto Target", SCRIPT_PARAM_SLICE, 800, 550, 1000, 0)
    menu.comboextra.others:addParam("aarange", "Advanced Calc to AA", SCRIPT_PARAM_ONOFF, true)
end

function MenuExtraComboRange()
    menu.comboextra:addSubMenu("[Custom Ranges]", "range")
    menu.comboextra.range:addParam("use", "Use Custom Range", SCRIPT_PARAM_ONOFF, false)
    menu.comboextra.range:addParam("", "- (Q) Settings -", SCRIPT_PARAM_INFO, "")
    menu.comboextra.range:addParam("startq", "Range to Engage", SCRIPT_PARAM_SLICE, 550, 275, 825, 0)
    menu.comboextra.range:addParam("", "- (W) Settings -", SCRIPT_PARAM_INFO, "")   
    menu.comboextra.range:addParam("startw", "Range to Stun", SCRIPT_PARAM_SLICE, 280, 150, 285, 0)
    menu.comboextra.range:addParam("", "- (E) Settings -", SCRIPT_PARAM_INFO, "") 
    menu.comboextra.range:addParam('starte', "Range to Engage", SCRIPT_PARAM_SLICE, 425, 250, 500, 0)
end

function MenuExtraComboBuff()
	menu.comboextra:addSubMenu("[Custom Delays to Buff]", "buff")
	menu.comboextra.buff:addParam("use", "Use Custom Delays", SCRIPT_PARAM_ONOFF, false)
	menu.comboextra.buff:addParam("q", "(Q) delay (s) to next cast", SCRIPT_PARAM_SLICE, 3, 1, 4, 0)	
	menu.comboextra.buff:addParam("r", "(R) delay (s) to second cast", SCRIPT_PARAM_SLICE, 13, 1, 14, 0)
end

function MenuFarm()
    menu:addSubMenu("[Farm System]", "farm")
    menu.farm:addParam("lasthit", "Last Hit Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
    menu.farm:addParam("lineclear", "Lane Clear Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
    menu.farm:addParam("clearjungle", "Jungle Clear Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
    menu.farm:addParam("shieldfarm", "Last hit with Shield", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("G"))
    menu.farm:addSubMenu("[Extra Settings]", "extrafarm")
    menu.farm.extrafarm:addParam("delay", "Extra Delay to Hit Minions", SCRIPT_PARAM_SLICE, 360, -300, 2000, 0)
    --menu.farm.extrafarm:addParam("junglemode", "Jungle Mode:", SCRIPT_PARAM_LIST, 1, { "Normal", "Faker", "Boxbox"})
    menu.farm.extrafarm:addParam("jungles", "[Jungle Skills]", SCRIPT_PARAM_INFO, "")
    menu.farm.extrafarm:addParam("q", "Use (Q) in Jungle", SCRIPT_PARAM_ONOFF, true)
    menu.farm.extrafarm:addParam("w", "Use (w) in Jungle", SCRIPT_PARAM_ONOFF, true)
    menu.farm.extrafarm:addParam("e", "Use (E) in Jungle", SCRIPT_PARAM_ONOFF, true)
    menu.farm.extrafarm:addParam("", "", SCRIPT_PARAM_INFO, "")
    menu.farm.extrafarm:addParam("", "[Delay To Cast]", SCRIPT_PARAM_INFO, "")
    menu.farm.extrafarm:addParam("qdelay", "(Q) delay", SCRIPT_PARAM_SLICE, 0.1, 0, 1, 1)
    menu.farm.extrafarm:addParam("wdelay", "(W) delay", SCRIPT_PARAM_SLICE, 0.1, 0, 1, 1)
    menu.farm.extrafarm:addParam("edelay", "(E) delay", SCRIPT_PARAM_SLICE, 0.1, 0, 1, 1)
end

function MenuExtra()
    menu:addSubMenu("[Others System]", "extra")
    menu.extra:addParam("systemextra", "Use Extra System", SCRIPT_PARAM_ONOFF, true)
    menu.extra:addParam("hp", "Auto HP Potion if Health < %", SCRIPT_PARAM_SLICE, 70, 0, 90, 0)
    menu.extra:addParam("barrier", "Auto Barrier if Health < %", SCRIPT_PARAM_SLICE, 40, 0, 90, 0)
    menu.extra:addParam("eTurret", "Auto (E) if get Aggro by Turret", SCRIPT_PARAM_ONOFF, true)
    menu.extra:addParam("jump", "Jump Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
    menu.extra:addParam("run", "Run Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
end

function MenuDraw()
    menu:addSubMenu("[Draw System]", "draw")        
    menu.draw:addParam("Q", "Draw (Q) range", SCRIPT_PARAM_ONOFF, false)
    menu.draw:addParam("W", "Draw (W) range", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("E", "Draw (E) range", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("R", "Draw (R) range", SCRIPT_PARAM_ONOFF, false)    
    menu.draw:addParam("spots", "[Draw Jump Spots]", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("castpos", "Cast Position Circle", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("from", "Destination Circle", SCRIPT_PARAM_ONOFF, false)    
    menu.draw:addParam("", "[Others]", SCRIPT_PARAM_INFO, "")
    menu.draw:addParam("passivecount", "Draw Passive Count", SCRIPT_PARAM_ONOFF, false)
    menu.draw:addParam("target", "Draw Target", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("targettext", "Draw Text in Target", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("refreshtime", "Refresh Time (s) Text", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
end


function MenuSystem()
	menu:addSubMenu("[General System]", "system")
    menu.system:addParam("", "[Orbwalk Settings]", SCRIPT_PARAM_INFO, "")
	menu.system:addParam("orb", "Enable Jus Orbwalk", SCRIPT_PARAM_ONOFF, true)
	menu.system:addParam("orbsens", "Jus's Orb Sensitivity", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
	menu.system:addParam("sida", "Enable Sida/MMA support", SCRIPT_PARAM_ONOFF, true)
  	menu.system:addParam("evadeee", "Use Evadeee support", SCRIPT_PARAM_ONOFF, true)
    menu.system:addParam("", "[Packet Settings]", SCRIPT_PARAM_INFO, "")
	menu.system:addParam("packet", "Use Packet", SCRIPT_PARAM_ONOFF, true)
    menu.system:addParam("pType", "Packet Type", SCRIPT_PARAM_LIST, 1, {"Dance", "Laugh"})
    menu.system:addParam('mov', "Use Movement", SCRIPT_PARAM_ONOFF, true)
    menu.system:addParam("noface", "Try exploit (Q) in Smart Combo", SCRIPT_PARAM_ONOFF, true) -- add packet cast in Q in smart combo.
    menu.system:addSubMenu("[VPrediction Settings]", "vpred")
    menu.system.vpred:addParam("use", "Use VPrediction", SCRIPT_PARAM_ONOFF, true)
    menu.system.vpred:addParam("qpred", "(Q) with VPrediction", SCRIPT_PARAM_ONOFF, true)
    menu.system.vpred:addParam("qhitchance", "(Q) Hit Chance", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
    menu.system.vpred:addParam("rpred", "(R) with VPrediction", SCRIPT_PARAM_ONOFF, true)
    menu.system.vpred:addParam("rhitchance", "(R) Hit Chance", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
    menu.system.vpred:addParam("rlogic", "Use (R) VPred Teamfight Logic", SCRIPT_PARAM_ONOFF, true)    
 end

function PermaMenu()
    menu.combo.skills:permaShow("r")
end

function GetAArange(myTarget)
	local use_ 	=	menu.comboextra.others.aarange
	if use_ then return ThisIsReal(myTarget)
	else return GetDistance(myTarget) end
end

function GetCustomRange(spell)
	local userange	=	menu.comboextra.range.use
	local nQ 		=	550
	local nW 		=	282
	local nE 		=	425
	if userange then
		local startq_	=	menu.comboextra.range.startq
		local startw_	=	menu.comboextra.range.startw
		local starte_	=	menu.comboextra.range.starte
		if spell == _Q then return startq_ end
		if spell == _W then return startw_ end
		if speel == _E then return starte_ end
	else		
		if spell == _Q then return nQ end
		if spell == _W then return nW end
		if spell == _E then return nE end		
	end
end

function GetBuffDelay(spell)
	local use_ 	=	menu.comboextra.buff.use
	if use_ then
		if spell == _Q then	return (menu.comboextra.buff.q*1000) end
		if spell == _R then return (menu.comboextra.buff.r*1000) end
	else
		if spell == _Q then return 1000 end
		if spell == _R then return 13000 end
	end
end

function getTrueRange()
     return myPlayer.range + GetDistance(myPlayer.minBBox)
end

function EnemyInRange(enemy, range)
         if ValidBBoxTarget(enemy, range) then
                return true
        end
    return false
 end

function getHitBoxRadius(hero_)
    return GetDistance(hero_.minBBox, hero_.maxBBox)/2
end

function ThisIsReal(myTarget) -- < myPlayer.range    
    local range = GetDistance(myTarget) - getHitBoxRadius(myTarget) - getHitBoxRadius(myPlayer)
    return range
end

function RangeWithUltimate()
    local myRange = myPlayer.range
    if temUltimate then
        return myRange + 75
    else
        return myRange
    end
    return myRange
end

local function DelayCalc()
	local mode 		=	menu.comboextra.mode
	local mDelay	=	menu.comboextra.delay
	if mode == 1 then
		return mDelay
	end
	if mode == 2 then
		local Total = (1/myPlayer.attackSpeed) - GetLatency()/2000 --seconds
		return Total
	end
end

function OnSendPacket(p)
if not LOADED then return end
    local isJump_   =   menu.extra.jump
    if isJump_ then return end
    local myPacket  =   Packet(p)
    --local delay_ 	=   menu.combo.extracombo.smart
    local qForce	=	menu.comboextra.q
    local wForce	=	menu.comboextra.w
    local eForce	=	menu.comboextra.e 
    local useP_		=	menu.system.packet
    local pType_    =   menu.system.pType    
    local table 	= 	{_Q, _W, _E}    
    local tTarget   =   ValidTarget(Target) 
    local pTick     =   qTick
    if myPacket:get('name') == 'S_CAST' then
        if useP_ and myPacket:get('spellId') == _Q then
            if pType_ == 1 then
                DelayAction(function()                 
                p = CLoLPacket(65)
                p:EncodeF(myPlayer.networkID)
                p:Encode1(0)
                p.dwArg1 = 0
                p.dwArg2 = 0
                SendPacket(p) end,
                0.1)
            end
            if pType_ == 2 then
                DelayAction(function() 
                p = CLoLPacket(0x47)
                p:EncodeF(myHero.networkID)
                p:Encode1(2)
                p.dwArg1 = 1
                p.dwArg2 = 0
                SendPacket(p) end,
                0.1)
            end          
        end
    	for i=1, #table do        
        	if myPacket:get('spellId') == table[i] and tTarget then
        		if table[i] == _Q and qForce then        	               	 
	            	local del 	=	DelayCalc()            	                              
		                if ThisIsReal(Target) <= myPlayer.range and GetTickCount() + GetLatency()/2 < pTick - (del + 1500) then  --700                                               
		                    myPacket:block()
                            myPlayer:Attack(Target)                    
		                end
	            end
	            if table[i] == _W and wForce and Passive then
	            	if ThisIsReal(Target) <= myPlayer.range then                                                 
	                    myPacket:block()                    
	                end
	            end
	            if table[i] == _E and eForce and Passive then
	            	if ThisIsReal(Target) <= myPlayer.range then                                                 
	                    myPacket:block()                    
	                end
	            end
            end
        end
    end
end

function OnGainBuff(unit, buff)
    if unit.isMe then
        if buff.name:lower():find("rivenpassiveaaboost") then Passive       = true PassiveIndicator = buff.stack end
        if buff.name:lower():find("rivenwindslashready") then temUltimate   = true end
        if buff.name:lower():find("regenerationpotion")  then UsandoHP      = true end
    end
end

function OnUpdateBuff(unit, buff)
    if unit.isMe then
        if buff.name:lower():find("rivenpassiveaaboost") then PassiveIndicator = buff.stack end
    end
end

function OnLoseBuff(unit, buff)
    if unit.isMe then
        if buff.name:lower():find("rivenpassiveaaboost") then Passive       = false 
            if buff.stack == nil then PassiveIndicator = 0 end end
        if buff.name:lower():find("rivenwindslashready") then temUltimate   = false end
        if buff.name:lower():find("regenerationpotion")  then UsandoHP      = false end
    end
end

-- items
local Items = {
["Brtk"]        =       {ready = false, range = 450, SlotId = 3153, slot = nil},
["Bc"]          =       {ready = false, range = 450, SlotId = 3144, slot = nil},
["Rh"]          =       {ready = false, range = 200, SlotId = 3074, slot = nil},
["Tiamat"]      =       {ready = false, range = 200, SlotId = 3077, slot = nil},
["Hg"]          =       {ready = false, range = 700, SlotId = 3146, slot = nil},
["Yg"]          =       {ready = false, range = 410, SlotId = 3142, slot = nil},
["RO"]          =       {ready = false, range = 500, SlotId = 3143, slot = nil},
["SD"]          =       {ready = false, range = 200, SlotId = 3131, slot = nil},
["MU"]          =       {ready = false, range = 200, SlotId = 3042, slot = nil} }
local HP_MANA     = { ["Hppotion"] = {SlotId = 2003, ready = false, slot = nil} }
local FoundItems  = {}

-- cast items
local function CheckItems(tabela)
    for ItemIndex, Value in pairs(tabela) do
        Value.slot = GetInventorySlotItem(Value.SlotId)
            if Value.slot ~= nil and (myPlayer:CanUseSpell(Value.slot) == READY) then
            FoundItems[#FoundItems+1] = ItemIndex
        end
    end
end

function CastCommonItem(myTarget)
    local items_suv =   menu.extra.systemextra
    if not items_suv then return end
    CheckItems(Items)
    if #FoundItems ~= 0 then
        for i, Items_ in pairs(FoundItems) do
            if ValidTarget(myTarget) then                            
                if GetDistance(myTarget) <= Items[Items_].range then
                    if Items_ == "Brtk" or Items_ == "Bc" then
                        CastSpell(Items[Items_].slot, myTarget)
                    else
                        CastSpell(Items[Items_].slot)
                    end
                end
            end
            FoundItems[i] = nil
        end    
    end
end
 
function CastSurviveItem()
    local items_suv =   menu.extra.systemextra
    if not items_suv then return end
    CheckItems(HP_MANA)    
    local hp_                       = menu.extra.hp   
    local barrier_                  = menu.extra.barrier
    local myPlayerhp_               = (myPlayer.health / myPlayer.maxHealth *100)
    if #FoundItems ~= 0 then        
        for i, HP_MANA_ in pairs(FoundItems) do
            if HP_MANA_ == "Hppotion" and myPlayerhp_ <= hp_ and not InFountain() and not UsandoHP then
               CastSpell(HP_MANA[HP_MANA_].slot)
            end
            FoundItems[i] = nil
        end
        if BarreiraSpell.slot ~= nil and myPlayerhp_ <= barrier_ and not InFountain() then
            CastSpell(BarreiraSpell.slot)
        end
    end
end

local function PredCastQ(myTarget)
	local useq      =   menu.combo.skills.q
    local mode_     =   menu.comboextra.mode
    local delayType =   nil
    local qpred_	=	menu.system.vpred.qpred
    local qChance_	=	menu.system.vpred.qhitchance
    local range_ 	=	GetCustomRange(_Q)
    local buffdelay = 	GetBuffDelay(_Q)
    if useq and qpred_ then
    	local del   =   DelayCalc()
    	if mode_ == 1 then delayType = del else  delayType = ( (lastWindUpTime/1000) - ((GetLatency())/2000) ) end    	
    	local AOECastPosition, MainTargetHitChance, nTargets = vp:GetCircularAOECastPosition(myTarget, delayType, 120, 825, math.huge, myPlayer)
    	if GetDistance(myTarget) <= range_ and MainTargetHitChance >= qChance_ and not Passive then
    		qTick = GetTickCount() + GetLatency() / 2
    		CastSpell(_Q, AOECastPosition.x, AOECastPosition.z)    		
    	end
    	if GetDistance(myTarget) <= range_ and MainTargetHitChance >= qChance_ and GetTickCount() + GetLatency() / 2 - qTick > del*1000 then
    		if delayType < 1 then delayType = 0.215 end
    		DelayAction(function()
                        	if ValidTarget(myTarget) then
                        		qTick = GetTickCount() + GetLatency() / 2 
                                CastSpell(_Q, AOECastPosition.x, AOECastPosition.z)                                 
                            end 
                        end, delayType)
    	end
    	if GetDistance(myTarget) <= range_ and GetAArange(myTarget) > myPlayer.range or GetTickCount() + GetLatency() / 2 - qTick > buffdelay then
            qTick = GetTickCount() + GetLatency() / 2
            CastSpell(_Q, AOECastPosition.x, AOECastPosition.z)
        end
    end
end

function CastQ(myTarget)   
    local useq      =   menu.combo.skills.q
    local mode_     =   menu.comboextra.mode
    local range_ 	=	GetCustomRange(_Q)
    local buffdelay = 	GetBuffDelay(_Q)
    local delayType =   nil
    local qpred_	=	menu.system.vpred.qpred
    if qpred_ then PredCastQ(myTarget) return end
    if useq then
        local del   =   DelayCalc()
        if mode_ == 1 then delayType = del else  delayType = ( (lastWindUpTime/1000) - ((GetLatency())/2000) ) end 
        if GetDistance(myTarget) <= GetCustomRange(_Q) and not Passive then
            qTick = GetTickCount() + GetLatency() / 2
            CastSpell(_Q, myTarget.x, myTarget.z)
        end
        if GetDistance(myTarget) <= GetCustomRange(_Q) and GetTickCount() + GetLatency() / 2 - qTick > del*1000 then
        	if delayType < 1 then delayType = 0.215 end
            DelayAction(function()
                            if ValidTarget(myTarget) then 
                                CastSpell(_Q, myTarget.x, myTarget.z) qTick = GetTickCount() + GetLatency() / 2 
                            end 
                        end, delayType)
        end
        if GetDistance(myTarget) <= GetCustomRange(_Q) and GetAArange(myTarget) > myPlayer.range or GetTickCount() + GetLatency() / 2 - qTick > buffdelay then
            qTick = GetTickCount() + GetLatency() / 2
            CastSpell(_Q, myTarget.x, myTarget.z)
        end 
    end	
end

local function CastW(myTarget)   
    local usew      =   menu.combo.skills.w  
    local useItems_ =   menu.comboextra.items
    local range_ 	=	GetCustomRange(_W)   
    if usew then       
        if GetDistance(myTarget) <= range_ then            
        	CastSpell(_W)            
        end 
    end
    if useItems_ then CastCommonItem(myTarget) end
end

local function AutoStun()
    local autow_    =   menu.comboextra.autow 
    local range_ 	=	GetCustomRange(_W)    
    if CountEnemyHeroInRange(range_) >= autow_ then
         CastSpell(_W)           
    end
end

local function CastE(myTarget)    
    local usee      =   menu.combo.skills.e
    local range_ 	=	GetCustomRange(_W)   
    if usee then
        if GetDistance(myTarget) <= range_ then
            CastSpell(_E, myTarget.x, myTarget.z)       
        elseif GetDistance(myTarget) > myPlayer.range and myPlayer:CanUseSpell(_Q) ~= READY or temUltimate then
        	CastSpell(_E, myTarget.x, myTarget.z)
        end
    end
end

function TryksR(myTarget)
	if not temUltimate then return end
	local useks_	=	menu.combo.extracombo.ksultimate
	local enemy 	= 	GetEnemyHeroes()
	local rlvl      =   myPlayer:GetSpellData(_R).level	
	if useks_ and ValidTarget(myTarget) then		
		for i, Targets in pairs(enemy) do			
			if ValidTarget(Targets) then
				local DamageR   =   getDmg("R", myTarget, myPlayer, 2)
				if Targets.health < myTarget.health and Targets.health <= DamageR and GetDistance(Targets) <= 900 then
					--if temUltimate and GetTickCount() + GetLatency() / 2 > rTick + 12000 then
					  	CastSpell(_R, Targets.x, Targets.z)
					--end
				end
			end
		end
	end
end
local lockultimate = false
local function UltimateKS()
    local useksR_   	=   menu.comboextra.ksultimate
    local ComboON     =   menu.combo.key
    local nKs_        =   menu.comboextra.nKsultimate  
    local Enemys    	=   nil
    if not useksR_ then return end
    if ComboON then return end
    if CountEnemyHeroInRange(900) < nKs_ then return end   
    for i, Enemys in pairs(GetEnemyHeroes()) do
        if ValidTarget(Enemys) and GetDistance(Enemys) < 900 then 
        local rDmg = getDmg("R", Enemys, myPlayer,2)           
            if Enemys.health <= rDmg then
            	rTick = GetTickCount() + GetLatency() / 2
                CastSpell(_R)                
                if temUltimate and GetDistance(Enemys) <= 900 and Enemys.health <= rDmg or GetTickCount() + GetLatency() / 2 - rTick> 10000 then
                    CastSpell(_R, Enemys.x, Enemys.z)
                end
            end
        end
    end
end

local function CheckfirstRmode(myTarget)
	if not ValidTarget(myTarget) then return end
	local rRange 	=	menu.comboextra.autostartRrange
	local HealthE	=	menu.comboextra.autostartRhealth    
    local rEnemys	=	menu.comboextra.autostartRnumber
    local eMode 	=	menu.comboextra.firstRmode
    return CountEnemyHeroInRange(rRange) >= rEnemys and (myTarget.health / myTarget.maxHealth * 100) <= HealthE and GetDistance(myTarget) <= rRange and not lockultimate
end

function NewCastR(myTarget)
	local user      =   menu.combo.skills.r  
    local eMode 	=	menu.comboextra.firstRmode
    local rPred 	=	menu.system.vpred.rpred
    local rChance 	=	menu.system.vpred.rhitchance
    local HealthE	=	menu.comboextra.autostartRhealth
    if user then
    	if not CheckfirstRmode(myTarget) then return end
    	local DamageR   =   getDmg("R", myTarget, myPlayer, 2)
        local nEnemys	=	CountEnemyHeroInRange(rRange)
        local enemyHea 	=	(myTarget.health / myTarget.maxHealth * 100)        
    	if not temUltimate then
    		rTick = GetTickCount() + GetLatency() / 2
    		CastSpell(_R)
        	lockIgnite = true
    	end
		if rPred and temUltimate then
			local mainCastPosition, mainHitChance = vp:GetConeAOECastPosition(myTarget, 0.25,  45,  900,  1200, myPlayer)
			if mainHitChance >= rChance and GetDistance(myTarget) <= 900 and myTarget.health <= DamageR or GetTickCount() + GetLatency() / 2 - rTick > GetBuffDelay(_R) then
				CastSpell(_R, mainCastPosition.x, mainCastPosition.z)
        		if not myTarget.dead then lockIgnite = false end
			elseif GetDistance(myTarget) > 400 and GetDistance(myTarget) <= 900 and (myTarget.health/myTarget.maxHealth)*100 <= HealthE then
				CastSpell(_R, mainCastPosition.x, mainCastPosition.z)
				if not myTarget.dead then lockIgnite = false end
			end
		else
			if temUltimate and ValidTarget(myTarget) and myTarget.health <= DamageR or GetTickCount() + GetLatency() / 2 - rTick > GetBuffDelay(_R) and GetDistance(myTarget) <= 900 then
				CastSpell(_R, myTarget.x, myTarget.z)
       			if not myTarget.dead then lockIgnite = false end
			elseif GetDistance(myTarget) > 400 and GetDistance(myTarget) <= 900 and (myTarget.health/myTarget.maxHealth)*100 <= HealthE then
				CastSpell(_R, myTarget.x, myTarget.z)
				if not myTarget.dead then lockIgnite = false end
			end
		end	
	end
end

function OnAnimation(unit, animationname)
	if not LOADED then return end
    local ComboON       =   menu.combo.key
    local qForce		=	menu.comboextra.q
    local wForce		=	menu.comboextra.w
    local eForce		=	menu.comboextra.e       
    local animaT        =   { "spell1a", "spell1b", "spell1c" } --, "spell2"}
    if unit.isMe and ComboON and ValidTarget(Target) then       
        -- if animationname:lower():find("attack") and speed_ then
        --     local del   =   DelayCalc()       
        --     if GetDistance(Target) <= 550 then -- (GetTickCount() + GetLatency() / 2) - qTick > del then                      
        --         if mode_ == 1 then delayType = ( (del/1000) - 0.05 ) else  delayType = ( (lastWindUpTime/1000) - ((GetLatency())/2000) - 0.025 ) end           
        --         DelayAction(function()
        --                         if ValidTarget(Target) then 
        --                             CastSpell(_Q, Target.x, Target.z) 
        --                             qTick = GetTickCount() + GetLatency() / 2 
        --                         end 
        --                     end, delayType)
        --     end
        -- end            
	    -- for i=1, #animaT do
	    --     if animationname:lower():find(animaT[i]) and ThisIsReal(Target) <= myPlayer.range then
     --            myPlayer:Attack(Target)
	    --     end
     --    end
    end
    if unit.isMe then -- ad jump key check
        if animationname:lower():find("spell1a") then qCount = 1 end
        if animationname:lower():find("spell1b") then qCount = 2 end
        if animationname:lower():find("spell1c") then qCount = 3 countTick = GetTickCount() + GetLatency()/2 end        
    end
end

function OnProcessSpell(unit, spell)
	if not LOADED then return end
	local jump_	=	menu.extra.jump
	if jump_ then return end   

    if unit.isMe then      
        --------use---------
        local useq          =   menu.combo.skills.q
        local usew          =   menu.combo.skills.w
        local usee          =   menu.combo.skills.e
        local user          =   menu.combo.skills.r
        -------multi skill-------
        local qe_           =   menu.combo.multi.qe
        local ew_           =   menu.combo.multi.ew
        local qr_           =   menu.combo.multi.qr
        local er_           =   menu.combo.multi.er
        local useItems_     =   menu.comboextra.items
        local ComboON       =   menu.combo.key
        local mov_			=	menu.system.mov
        local qPacket 		=	menu.system.noface
        -------new-----------------
        local speed_		=	menu.comboextra.speed       
        if spell.name:lower():find("attack") then           
            --[[orbwalk]]             
            lastAttack      = GetTickCount() - GetLatency() / 2 - 100
            lastWindUpTime  = spell.windUpTime * 1000
            lastAttackCD    = spell.animationTime * 1000
            if speed_ and ValidTarget(Target) and ComboON then CastQ(Target) end       
        end      
        if ComboON and ValidTarget(Target) then
	        if spell.name:lower():find("riventricleave") then
	        	qTick = GetTickCount() + GetLatency() / 2
	        	if speed_ and ValidTarget(Target) and ThisIsReal(Target) <= myPlayer.range then myPlayer:Attack(Target) end          
                if qPacket then
                	Packet('S_MOVE', {x = spell.endPos.x, y = spell.endPos.z}):send()
                end
                if mov_ then
	             	local newPos    =   Target + (Vector(spell.startPos) - Target):normalized()*50
	                Packet('S_MOVE', {x = newPos.x, y = newPos.z}):send() --spell.startPos.x 
	            end     
                if qr_ and user then
                    NewCastR(Target)
                end
	        end

	        if spell.name:lower():find("rivenmartyr") then
	        	if useItems_ then CastCommonItem(Target) end                	
	        end    

	        if spell.name:lower():find("rivenfeint") then
                if er_ and user then
                	NewCastR(Target)
                end
                if usew and ew_ then
                    CastW(Target)
                    --if useItems_ then CastCommonItem(Target) end
                end
	        end
	    end
        if spell.name:lower():find("rivenfengshuiengine") then
            rTick = GetTickCount() + GetLatency() / 2
        end
    end 
    local autoE 		=	menu.extra.eTurret
    if autoE and unit.type == "obj_AI_Turret" and spell.target.networkID == myPlayer.networkID and GetDistance(spell.endPos) <= 1000 then
    	CastSpell(_E, mousePos.x, mousePos.z)
    end
end

function JumpQ()
    local From, To, CastPos = ReturnDrawPoint()
    if qCount == 0 then   
	   CastSpell(_Q, mousePos.x, mousePos.z)
       qTick = GetTickCount() + GetLatency()/2
    elseif (From and To and CastPos ~= nil) and GetDistance(myPlayer, CastPos) <= 100 then
        myPlayer:MoveTo(CastPos.x, CastPos.z)        
        CastSpell(_Q, CastPos.x, CastPos.z)
    end
    if qCount == 2 and GetTickCount() + GetLatency() / 2 - qTick > 4000 then
        CastSpell(_Q, mousePos.x, mousePos.z)
    end
    if GetDistance(mousePos) > 150 then
	 	local moveToPos = myPlayer + (Vector(mousePos) - myPlayer):normalized()* 150
    	myPlayer:MoveTo(moveToPos.x, moveToPos.z)
	end   	
end

function BurnYouShit(myTarget)
	if lockIgnite then return end
    local useI  =   menu.comboextra.others.ignite
    local slot_ =   IgniteSpell.slot
    if useI and slot_ ~= nil and myPlayer:CanUseSpell(slot_) == READY and GetDistance(myTarget, myPlayer) <= IgniteSpell.range and not TargetHaveBuff(IgniteSpell.spellSlot, myTarget) then
        local iDmg  =   getDmg("IGNITE", myTarget, myPlayer, myPlayer.level)
        if myTarget.health <= iDmg then
            CastSpell(slot_, myTarget)
        end
    end
end

function LineFarm()  
    local delay_    =       menu.farm.extrafarm.delay
    local myOrb		=		menu.system.orb    
    local items_0   =      	menu.comboextra.items 
    local enemyMinions      =   minionManager(MINION_ENEMY, 875, myPlayer, MINION_SORT_HEALTH_ASC) 
    enemyMinions:update() 
    -- if GetTickCount() + GetLatency() / 2 > lTick and myOrb == true then
    --     myPlayer:MoveTo(mousePos.x, mousePos.z)
    -- end
    Minion = enemyMinions.objects[1]
    if Minion ~= nil and not Minion.dead then                  
        if GetDistance(Minion, myPlayer) <= 550 and not Passive then
            tock = GetTickCount() + GetLatency()/2
            CastSpell(_Q, Minion.x, Minion.z)
        end
        if GetTickCount() + GetLatency()/2 - tock > 2500 then
            tock = GetTickCount() + GetLatency()/2
            CastSpell(_Q, Minion.x, Minion.z)
        end
        if not UnderTurret(Minion) then
            CastSpell(_E, Minion.x, Minion.z)
        end
        if GetDistance(Minion, myPlayer) <= 380 then CastSpell(_W) end
        if items_0 then CastCommonItem(Minion) end       
        -- if ValidTarget(Minion) and GetTickCount() + GetLatency() / 2 > lTick then
        --     myPlayer:Attack(Minion)
        --     lTick = GetTickCount() + GetLatency() / 2  + 360 + delay_
        -- end     
    end 
    if myOrb then OrbWalk(Minion) end    
end

local function FarmWithShield()	
    local delay_    		=   menu.farm.extrafarm.delay
    local myOrb				=	menu.system.orb
    local enemyMinions    	= 	minionManager(MINION_ENEMY, 550, myPlayer, MINION_SORT_HEALTH_ASC)
    enemyMinions:update()
    if GetTickCount() + GetLatency() / 2 > TickTack and myOrb == true then
        myPlayer:MoveTo(mousePos.x, mousePos.z)
    end
    minion = enemyMinions.objects[1]
    if minion ~= nil and not minion.dead then
        local aDmg = getDmg("AD", minion, myPlayer)
        if ValidTarget(minion) and minion.health <= aDmg  and EnemyInRange(minion, getTrueRange()) and GetTickCount() + GetLatency() / 2 > TickTack then
            CastSpell(_E, minion.x, minion.z)
            myPlayer:Attack(minion)
            TickTack = GetTickCount() + GetLatency() / 2 + 360 + delay_
        end
    end
end

function FarmChicken()
    local delay_    		=   menu.farm.extrafarm.delay
    local myOrb				=	menu.system.orb
    local enemyMinions    	= 	minionManager(MINION_ENEMY, 550, myPlayer, MINION_SORT_HEALTH_ASC)
    enemyMinions:update()
    if GetTickCount() + GetLatency() / 2 > TickTack and myOrb == true then
        myPlayer:MoveTo(mousePos.x, mousePos.z)
    end
    minion = enemyMinions.objects[1]
    if minion ~= nil and not minion.dead then
        local aDmg = getDmg("AD", minion, myPlayer)
        if ValidTarget(minion) and minion.health <= aDmg  and EnemyInRange(minion, getTrueRange()) and GetTickCount() + GetLatency() / 2 > TickTack then
            myPlayer:Attack(minion)
            TickTack = GetTickCount() + GetLatency() / 2 + 360 + delay_
        end
    end
end

function JungleBitch()
	local Jungle        = minionManager(MINION_JUNGLE, 550, myPlayer, MINION_SORT_MAXHEALTH_DEC)
    Jungle:update()
    local useq          =   menu.farm.extrafarm.q
    local usew          =   menu.farm.extrafarm.w
    local usee          =   menu.farm.extrafarm.e  
    local delay_        =   menu.farm.extrafarm.delay
    local KillMobs      =   menu.farm.clearjungle
    local myOrb			=	menu.system.orb
    if not KillMobs then return end
    Jungle:update()
    -- if GetTickCount() + GetLatency() / 2 > jTick and myOrb == true then
    --     myPlayer:MoveTo(mousePos.x, mousePos.z)
    -- end 
    Minion = Jungle.objects[1]
    if Minion ~= nil and not Minion.dead then
        if useq and not Passive then
           CastSpell(_Q, Minion.x, Minion.z)
        end
        if usee then
            CastSpell(_E, Minion.x, Minion.z)
        end
        if usew and GetDistance(Minion, myPlayer) <= 282 and not Passive then
            CastSpell(_W)
        end
        -- if ValidTarget(Minion) and GetTickCount() + GetLatency() / 2 > jTick then
        --     myPlayer:Attack(Minion)
        --     jTick = GetTickCount() + GetLatency() / 2  + 360 + delay_
        -- end                
    end
    if myOrb then OrbWalk(Minion) end  
end

function SidaMMA()
	local ComboON          =   menu.combo.key
	local FarmChicken_     =   menu.farm.lasthit
  local LineFarm_        =   menu.farm.lineclear
	local farmShiels	     =	menu.farm.shieldfarm
	local Integration	     =	menu.system.sida
	local myOrb			       =	menu.system.orb
	--if ComboON or FarmChicken_ or LineFarm_ or farmShiels then
		if Integration then
			if _G.MMA_Loaded then
				myOrb = false				
			end
			if _G.AutoCarry then
				myOrb = false				
			end
		else
			myOrb = true
			if _G.MMA_Loaded then							
			 myOrb = true
			end
			if _G.AutoCarry then
       myOrb = true
			end
		end	
	--end		
end

function TargetIsValid(myTarget)
    return ValidTarget(myTarget) and myTarget.type == "obj_AI_Hero" and myTarget.type ~= "obj_AI_Turret" and myTarget.type ~= "obj_AI_Minion" and not TargetHaveBuff("UndyingRage", myTarget) and not TargetHaveBuff("JudicatorIntervention", myTarget)
end

function F5Target()
  local integration_ = menu.system.sida
  if integration_ then
    if _G.MMA_Target and _G.MMA_Target.type == myPlayer.type then return _G.MMA_Target end
    if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair and _G.AutoCarry.Attack_Crosshair.target and _G.AutoCarry.Attack_Crosshair.target.type == myPlayer.type then return _G.AutoCarry.Attack_Crosshair.target end
  end
    selectedTarget        = GetTarget()
    local found 	        = false
    local range_          = menu.comboextra.others.targetrange
    local inimigos 	      = nil
    local Enemy 	        = nil
    local Compare	        = nil
    local finalTarget	    = nil
    
    if range_ == 0 then range_ = 850 end

    if ValidTarget(selectedTarget) and TargetIsValid(selectedTarget) then
    	return selectedTarget
    end

    if not selectedTarget then
        inimigos  = GetEnemyHeroes()
        for i, Enemy in pairs(inimigos) do
            if Enemy ~= nil and ValidTarget(Enemy) and GetDistance(Enemy) <= range_ and TargetIsValid(Enemy) then
              local basedmg   = 50
              local myDmg     = (myPlayer:CalcDamage(Enemy, 200) or 0)
            	local finalDmg	=	Enemy.health / myDmg
                if finalDmg < basedmg then
                	found = true                	
                    return Enemy
                end		                
            else
            	found = false
            end		  
        end
    end
    if not selectedTarget and not found then
    	local mouseTarget = nil
    	inimigos  = GetEnemyHeroes()
    	for i, Enemy in pairs(inimigos) do
    		local distancMouse = GetDistance(mousePos, Enemy)
	    	if Enemy ~= nil and ValidTarget(Enemy) and distancMouse <= 150 and TargetIsValid(Enemy) then
	    		return Enemy
	    	end
	    end
	end	
	finalTarget = selectedTarget or Enemy
    if ValidTarget(finalTarget) and TargetIsValid(Enemy) then
	return finalTarget
    end
end

local ComboPerDamage 	= {}
local KillText 			  =	nil
local TextTick        = 0
function TextDamage(myTarget)
	if not ValidTarget(myTarget) and not TargetIsValid(myTarget) then return end
  local refres_ = menu.draw.refreshtime
  if GetTickCount() + GetLatency()/2 - TextTick < refres_*1000 then return end
	------------------------------Get Table with ignite or not
	if IgniteSpell.slot ~= nil then
		skillTable	=	{ "Q", "W", "R", "IGNITE"}
		skills 		=	{ _Q, _W, _R, IgniteSpell.slot}
	else
		skillTable	=	{ "Q", "W", "R"}
		skills 		=	{ _Q, _W, _R}
	end
	----------------------------------------------------------

	--------------------------------------Find spell not in CD
	local qDmg, wDmg, rDmg, iDmg 	= 0, 0 ,0 ,0
	local TotalDamage 				= 0
	local possible 					= {}	
	for i=1, #skillTable do
		for a=1, #skills do
			if i==a and myPlayer:CanUseSpell(skills[a]) == READY then
				table.insert(possible, skillTable[i])
			end
		end
	end
	----------------------------------------------------------

	----------------ONLY CALCULATE WHAT IS NOT IN COOLDDOWN
	if #possible >= 1 then
		for b=1, #possible do
			if possible[b] == "Q" then
				qDmg = (getDmg(possible[b], myTarget, myPlayer, 1)*3 or 0)
			end
			if possible[b] == "W" then
				wDmg = (getDmg(possible[b], myTarget, myPlayer, 1)	or 0)		
			end
			if possible[b] == "R" then			
				rDmg = (getDmg(possible[b], myTarget, myPlayer, 2) or 0)				
			end		
			if possible[b] == "IGNITE" then
				iDmg = (getDmg(possible[b], myTarget, myPlayer, myPlayer.level)	or 0)		
			end
			possible[b] = nil --clear table		
		end
	end
	--------------------------------------------------------

  ----------------- DAMAGE TABLE-----------------------------------------------------
  if qDmg or wDmg or rDmg or iDmg ~= 0 then
    if qDmg >= myTarget.health then KillText = "3x(Q) can kill" lockultimate = true
    elseif qDmg + wDmg >= myTarget.health then KillText = "(Q) + (W) can kill"  lockultimate = true
    elseif qDmg + rDmg >= myTarget.health then KillText = "(Q) + (R) can kill"  lockultimate = false
    elseif qDmg + iDmg >= myTarget.health then KillText = "(Q) + (I) can kill"  lockultimate = true
    elseif qDmg + wDmg + rDmg >= myTarget.health then KillText = "(Q) + (W) + (R)"  lockultimate = false
    elseif qDmg + wDmg + iDmg >= myTarget.health then KillText = "(Q) + (W) + (I)"  lockultimate = false
    elseif qDmg + rDmg + iDmg >= myTarget.health then KillText = "(Q) + (R) + (I)"  lockultimate = false
    elseif qDmg + wDmg + rDmg + iDmg >= myTarget.health then KillText = "(All IN)"  lockultimate = false
    elseif wDmg >= myTarget.health then KillText = "(W) can kill" lockultimate = true
    -- elseif wDmg + rDmg >= myTarget.health then KillText = "(W) + (R) can kill" lockultimate = false
    -- elseif wDmg + iDmg >= myTarget.health then KillText = "(W) + (I) can kill" lockultimate = true
    -- elseif wDmg + rDmg + iDmg >= myTarget.health then KillText = "(W) + (R) + (I) can kill" lockultimate = false
    elseif rDmg >= myTarget.health then KillText = "(R) can kill" lockultimate = false
    elseif rDmg + iDmg >= myTarget.health then KillText = "(R) + (I) can kill" lockultimate = false
    elseif iDmg >= myTarget.health then KillText = "(I) can kill" lockultimate = true
    else
      KillText = "Harass"
    end
  end
	------------------------------------------------------------------------------------
	
	if KillText == nil then KillText = "Harass" end
  TextTick = GetTickCount() + GetLatency()/2
end

 -- for i = 1, #dmgTable, 1 do
 --  for j = 1, #dmgTable, 1 do
 --   if i ~= j or j == 1 then
 --    for k = 1, #dmgTable, 1 do
 --     if k ~= j and k ~= i or k == 1 then
 --      if myTarget.health < dmgTable[i].dmg + +dmgTable[j].dmg + dmgTable[k].dmg then
 --       KillText = dmgTable[i].text + +dmgTable[j].text + dmgTable[k].text + "kill"
 --     end
 --    end
 --   end
 --  end
 -- end
 -- dmgTable = {
 --  {["dmg"]=0 , ["text"]=" ",
 --  {["dmg"]=qDmg , ["text"]="Q ",
 --  {["dmg"]=wDmg , ["text"]="W ",
 --  {["dmg"]=eDmg , ["text"]="E ",
 --  {["dmg"]=rDmg , ["text"]="R ",
 --  {["dmg"]=iDmg , ["text"]="I ",
 -- }


 -- function ComboDamage(myTarget, cooldown)
 --    local Skills  = {_Q, _W, _E}
 --    local dmgCalc = {"Q", "W", "E"}
 --    local Combos  = { {_Q,},
 --                      {_Q, _W},
 --                      {_Q, _W, _E}}                      
 --    local Damage  = { qDmg = 0, wDmg = 0 , eDmg = 0}
 --    local Spells  = {}
 --    if cooldown then
 --      for a=1, #Skills do
 --        for b=1, #dmgCalc do
 --          if a==b and myPlayer:CanUseSpell(Skills[b]) then
 --            table.insert(spells, )



-- dmgTable = {
--  {["dmg"]=0 , ["text"]=" ",
--  {["dmg"]=qDmg , ["text"]="Q ",
--  {["dmg"]=wDmg , ["text"]="W ",
--  {["dmg"]=eDmg , ["text"]="E ",
--  {["dmg"]=rDmg , ["text"]="R ",
--  {["dmg"]=iDmg , ["text"]="I ",
-- }
 
-- function xxxxxx()
--  killable = false
--  for i = 1, #dmgTable, 1 do
--   for j = 1, #dmgTable, 1 do
--    if i ~= j or j == 1 then
--     for k = 1, #dmgTable, 1 do
--      if k ~= j and k ~= i or k == 1 then
--       for l = 1, #dmgTable, 1 do
--        if l ~= k and l ~= j amd l ~= i or l == 1 then
--         for m = 1, #dmgTable, 1 do
--          if m ~= i and m ~= j amd m ~= k and m ~= l or m == 1 then        
--           if myTarget.health < dmgTable[i].dmg + +dmgTable[j].dmg + dmgTable[k].dmg + dmgTable[l].dmg + dmgTable[m].dmg then
--            KillText = dmgTable[i].text .. dmgTable[j].text .. dmgTable[k].text .. dmgTable[l].text .. dmgTable[m].text .. "kill"
--            killable = true
--           end
--          end
--         end
--        end
--       end
--      end
--     end
--    end
--   end
--  end
--  if killable == false then
--   KillText = "harass"
--  end
-- end



local function DamageCombo(myTarget)	
	if not ValidTarget(myTarget) and not TargetIsValid(myTarget) then return end
	--local bench = os.clock()
	local qPacket 		=	menu.system.noface
	local skillTable	=	{}
	local skills 		=	{}
	if IgniteSpell.slot ~= nil then
		skillTable	=	{ "Q", "W", "R", "IGNITE"}
		skills 		=	{ _Q, _W, _R, IgniteSpell.slot}
	else
		skillTable	=	{ "Q", "W", "R"}
		skills 		=	{ _Q, _W, _R}
	end
	local possible		=	{}
	local needTable		=	{}
	local TotalDamage	=	0
	local qDmg, rDmg, wDmg, iDmg = 0, 0, 0, 0
	for i=1, #skillTable do
		for a=1, #skills do
			if i==a and myPlayer:CanUseSpell(skills[a]) == READY then
				table.insert(possible, skillTable[i])
			end
		end
	end
	if #possible >= 1 then
		for b=1, #possible do
			if possible[b] == "Q" then
				qDmg = getDmg(possible[b], myTarget, myPlayer, 1)*3
				table.insert(needTable, qDmg)
			end
			if possible[b] == "W" then
				wDmg = getDmg(possible[b], myTarget, myPlayer, 1)
				table.insert(needTable, wDmg)
			end
			if possible[b] == "R" then
				rDmg = getDmg(possible[b], myTarget, myPlayer, 2)
				table.insert(needTable, rDmg)
			end
			if possible[b] == "IGNITE" then
				iDmg = getDmg(possible[b], myTarget, myPlayer, myPlayer.level)
				table.insert(needTable, iDmg)
			end
			possible[b] = nil
		end
	end

	local pDmg = getDmg("P", myTarget, myPlayer)	
	--local needTable = {qDmg, wDmg, rDmg, iDmg}	
	if #needTable > 0 then
		for i=1, #needTable do		
			--if needTable[i] ~= 0 then		
				if needTable[i] == qDmg then
		          	if qDmg >= myTarget.health and GetDistance(myTarget) <= 550 then
		          		lockIgnite = true
		          		if qPacket and GetDistance(myTarget) <= 500 then
		          			qTick = GetTickCount() + GetLatency() / 2
		          			Packet('S_CAST', {spellId = _Q, fromX = myPlayer.x, fromY = myPlayer.z, toX = myTarget.x, toY = myTarget.z}):send() 			
--CastSpell(_Q, {myPlayer.x, myTarget.x}, {myPlayer.x, myTarget.z})          		
		          		else
		          			if GetDistance(myTarget) <= 500 then
		          				qTick = GetTickCount() + GetLatency() / 2
			        			CastSpell(_Q, myTarget.x, myTarget.z)
			        		end
			       		end
		          	else
		          		lockIgnite = false
		          		CastQ(myTarget)
		          	end		          	
		        end        	
			
				if needTable[i] == iDmg then        
					if iDmg < myTarget.health then lockIgnite = true
					elseif iDmg > myTarget.health and not temUltimate then lockIgnite = false end
				end			
			needTable[i] = nil
		end
			CastE(myTarget)
			NewCastR(myTarget)
        	CastW(myTarget)
	else			
        CastQ(myTarget)
		CastE(myTarget)
		NewCastR(myTarget)
        CastW(myTarget)
    end
end

-- local function GenerateCastCombo(myTarget)
-- 	if #ComboPerDamage == 0 then
-- 		print("0")
-- 		CastQ(myTarget)
-- 		CastE(myTarget)
-- 		NewCastR(myTarget)
--         CastW(myTarget)
--     end
-- 	if #ComboPerDamage > 1 then
-- 		print("> 1")
-- 		local qpred_	=	menu.system.qpred		
-- 		for i=1, #ComboPerDamage do
-- 			if ComboPerDamage[i] == _Q then
-- 				if qpred_ then PredCastQ(myTarget) else CastQ(myTarget) CastE(myTarget) end
-- 			end
-- 			if ComboPerDamage[i] == _W then CastE(myTarget) CastW(myTarget) end
-- 			if ComboPerDamage[i] == _R then CastE(myTarget) NewCastR(myTarget) end			
-- 		end
-- 	end
-- 	if #ComboPerDamage == 1 then
-- 		print("=== 1")
-- 		local qpred_		=	menu.system.qpred
-- 		local qPacket 		=	menu.system.noface
-- 		if ComboPerDamage[i] == _Q then
-- 			if qPacket and GetDistance(myTarget) <= 500 then
-- 				CastE(myTarget)
-- 				qTick = GetTickCount() + GetLatency() / 2
--           		Packet('S_CAST', {spellId = _Q, fromX = myPlayer.x, fromY = myPlayer.z, toX = myTarget.x, toY = myTarget.z}):send()
--           	else
--           		CastE(myTarget)
--           		qTick = GetTickCount() + GetLatency() / 2
-- 	        	CastSpell(_Q, myTarget.x, myTarget.z)
-- 	        end
-- 	    end
-- 	    if ComboPerDamage[i] == _R then
-- 	    	CastE(myTarget)
-- 	    	NewCastR(myTarget)
-- 	    end
-- 	end	
-- end

function NormalCombo(myTarget)
	local anti_over	=	menu.comboextra.others.smart
    if ValidTarget(myTarget) then
        if anti_over then
        	DamageCombo(myTarget)        	        	
        else	
	      CastQ(myTarget)
			  CastE(myTarget)
			  NewCastR(myTarget)
	      CastW(myTarget)
        end
    end   
end

function OnWndMsg(Msg, Key)
	if not LOADED then return end
	if myPlayer.dead then return end	
	if Msg == WM_LBUTTONDOWN or WM_RBUTTONDOWN then		
		Target = F5Target()
	end
end

function OrbWalk(myTarget)
    if ValidTarget(myTarget) and ThisIsReal(myTarget) <= myPlayer.range then
        if timeToShoot() then
            myPlayer:Attack(myTarget)
        elseif heroCanMove() then
            moveToCursor()
        end
    else
        moveToCursor()
    end
end

local function RunRun()
    if qCount == 0 then
        CastSpell(_Q, mousePos.x, mousePos.z)
    end
    if qCount == 2 then        
        CastSpell(_E, mousePos.x, mousePos.z)        
        DelayAction(function() CastSpell(_Q, mousePos.x, mousePos.z) end, 0.75)       
    end
    if GetDistance(mousePos) > myPlayer.range then
        myPlayer:MoveTo(mousePos.x, mousePos.z)
    end
end

function heroCanMove()
    return ( GetTickCount() + GetLatency() / 2 > lastAttack + lastWindUpTime + 20)
end 
 
function timeToShoot()
    return (GetTickCount() + GetLatency() / 2 > lastAttack + lastAttackCD or qTick + lastAniQ)
end 

function moveToCursor()
 	local orbsens_	=	(menu.system.orbsens)*100 
	if GetDistance(mousePos) >= myPlayer.range + orbsens_ then
 		local moveToPos = myPlayer + (Vector(mousePos) - myPlayer):normalized()*250
		myPlayer:MoveTo(moveToPos.x, moveToPos.z)
	end   
end

function OnTick()
	if not LOADED then return end
    if myPlayer.dead then return end
    CastSurviveItem()
    local ComboON       =   menu.combo.key
    local FarmChicken_  =   menu.farm.lasthit
    local LineFarm_     =   menu.farm.lineclear
    local myOrb			=	menu.system.orb
    local jump_			=	menu.extra.jump
    local farmShiels	=	menu.farm.shieldfarm
    local targettext_	=	menu.draw.targettext
    local runrun_       =   menu.extra.run
    local ksUl_			=	menu.comboextra.ksultimate
    local evade_    = menu.system.evadeee
    local RealTarget	=	nil    
    --local BeSmart     =   menu.combo.extracombo.smart
    if runrun_ then RunRun() end    
    Target = F5Target()
    if ValidTarget(Target) and TargetIsValid(Target) then
    	RealTarget = Target    
    end
    if ksUl_ then UltimateKS() end
    AutoStun()
    if evade_ and not _G.Evadeee or _G.Evadeee_impossibleToEvade then
      if ComboON then       
        NormalCombo(RealTarget)        
        if myOrb then OrbWalk(RealTarget) end
      end
    else
      if ComboON then       
        NormalCombo(RealTarget)        
        if myOrb then OrbWalk(RealTarget) end
      end
    end
    SidaMMA()
    if FarmChicken_ then FarmChicken() end
    if LineFarm_ then LineFarm() end
    if farmShiels then FarmWithShield() end
    JungleBitch()
    TextDamage(RealTarget) 
    if ValidTarget(RealTarget) then 
        BurnYouShit(RealTarget)         
    end

    if jump_ then JumpQ() end
    --if not targettext_ then KillText = nil end
    if myPlayer:CanUseSpell(_Q) ~= READY and GetTickCount() + GetLatency()/2 > countTick + 2000 then qCount = 0 end
    if temUltimate then lockIgnite = true end
end

--[[Credits to barasia, vadash and viseversa for anti-lag circles]]--
local function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
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

local Points    = nil
local JumpSpots = 
{
['Riven'] = 
    {
        {From = Vector(6478.0454101563, -64.045028686523, 8342.501953125),  To = Vector(6751, 56.019004821777, 8633), CastPos = Vector(6751, 56.019004821777, 8633)},
        {From = Vector(6447, 56.018882751465, 8663),  To = Vector(6413, -62.786361694336, 8289), CastPos = Vector(6413, -62.786361694336, 8289)},
        {From = Vector(6195.8334960938, -65.304061889648, 8559.810546875),  To = Vector(6327, 56.517200469971, 8913), CastPos = Vector(6327, 56.517200469971, 8913)},
        {From = Vector(7095, 56.018997192383, 8763),  To = Vector(7337, 55.616943359375, 9047), CastPos = Vector(7337, 55.616943359375, 9047)},
        {From = Vector(7269, 55.611968994141, 9055),  To = Vector(7027, 56.018997192383, 8767), CastPos = Vector(7027, 56.018997192383, 8767)},
        {From = Vector(5407, 55.045528411865, 10095),  To = Vector(5033, -63.082427978516, 10119), CastPos = Vector(5033, -63.082427978516, 10119)},
        {From = Vector(5047, -63.08129119873, 10113),  To = Vector(5423, 55.007797241211, 10109), CastPos = Vector(5423, 55.007797241211, 10109)},
        {From = Vector(4747, -62.445854187012, 9463),  To = Vector(4743, -63.093593597412, 9837), CastPos = Vector(4743, -63.093593597412, 9837)},
        {From = Vector(4769, -63.086654663086, 9677),  To = Vector(4775, -63.474864959717, 9301), CastPos = Vector(4775, -63.474864959717, 9301)},
        {From = Vector(6731, -64.655540466309, 8089),  To = Vector(7095, 56.051624298096, 8171), CastPos = Vector(7095, 56.051624298096, 8171)},
        {From = Vector(7629.0434570313, 55.042400360107, 9462.6982421875),  To = Vector(8019, 53.530429840088, 9467), CastPos = Vector(8019, 53.530429840088, 9467)},
        {From = Vector(7994.2685546875, 53.530174255371, 9477.142578125),  To = Vector(7601, 55.379856109619, 9441), CastPos = Vector(7601, 55.379856109619, 9441)},
        {From = Vector(6147, 54.117427825928, 11063),  To = Vector(6421, 54.63500213623, 10805), CastPos = Vector(6421, 54.63500213623, 10805)},
        {From = Vector(5952.1977539063, 54.240119934082, 11382.287109375),  To = Vector(5889, 39.546829223633, 11773), CastPos = Vector(5889, 39.546829223633, 11773)},
        {From = Vector(6003.1401367188, 39.562377929688, 11827.516601563),  To = Vector(6239, 54.632926940918, 11479), CastPos = Vector(6239, 54.632926940918, 11479)},
        {From = Vector(3947, 51.929698944092, 8013),  To = Vector(3647, 54.027297973633, 7789), CastPos = Vector(3647, 54.027297973633, 7789)},
        {From = Vector(1597, 54.923656463623, 8463),  To = Vector(1223, 50.640468597412, 8455), CastPos = Vector(1223, 50.640468597412, 8455)},
        {From = Vector(1247, 50.737510681152, 8413),  To = Vector(1623, 54.923782348633, 8387), CastPos = Vector(1623, 54.923782348633, 8387)},
        {From = Vector(2440.49609375, 53.364398956299, 10038.1796875),  To = Vector(2827, -64.97053527832, 10205), CastPos = Vector(2827, -64.97053527832, 10205)},
        {From = Vector(2797, -65.165946960449, 10213),  To = Vector(2457, 53.364398956299, 10055), CastPos = Vector(2457, 53.364398956299, 10055)},
        {From = Vector(2797, 53.640556335449, 9563),  To = Vector(3167, -63.810096740723, 9625), CastPos = Vector(3167, -63.810096740723, 9625)},
        {From = Vector(3121.9699707031, -63.448329925537, 9574.16015625),  To = Vector(2755, 53.722351074219, 9409), CastPos = Vector(2755, 53.722351074219, 9409)},
        {From = Vector(3447, 55.021110534668, 7463),  To = Vector(3581, 54.248985290527, 7113), CastPos = Vector(3581, 54.248985290527, 7113)},
        {From = Vector(3527, 54.452239990234, 7151),  To = Vector(3372.861328125, 55.13143157959, 7507.2211914063), CastPos = Vector(3372.861328125, 55.13143157959, 7507.2211914063)},
        {From = Vector(2789, 55.241321563721, 6085),  To = Vector(2445, 60.189605712891, 5941), CastPos = Vector(2445, 60.189605712891, 5941)},
        {From = Vector(2573, 60.192783355713, 5915),  To = Vector(2911, 55.503971099854, 6081), CastPos = Vector(2911, 55.503971099854, 6081)},
        {From = Vector(3005, 55.631782531738, 5797),  To = Vector(2715, 60.190528869629, 5561), CastPos = Vector(2715, 60.190528869629, 5561)},
        {From = Vector(2697, 60.190807342529, 5615),  To = Vector(2943, 55.629695892334, 5901), CastPos = Vector(2943, 55.629695892334, 5901)},
        {From = Vector(3894.1960449219, 53.4684715271, 7192.3720703125),  To = Vector(3641, 54.714691162109, 7495), CastPos = Vector(3641, 54.714691162109, 7495)},
        {From = Vector(3397, 55.605663299561, 6515),  To = Vector(3363, 53.412925720215, 6889), CastPos = Vector(3363, 53.412925720215, 6889)},
        {From = Vector(3347, 53.312397003174, 6865),  To = Vector(3343, 55.605716705322, 6491), CastPos = Vector(3343, 55.605716705322, 6491)},
        {From = Vector(3705, 53.67945098877, 7829),  To = Vector(4009, 51.996047973633, 8049), CastPos = Vector(4009, 51.996047973633, 8049)},
        {From = Vector(7581, -65.361351013184, 5983),  To = Vector(7417, 54.716590881348, 5647), CastPos = Vector(7417, 54.716590881348, 5647)},
        {From = Vector(7495, 53.744125366211, 5753),  To = Vector(7731, -64.48851776123, 6045), CastPos = Vector(7731, -64.48851776123, 6045)},
        {From = Vector(7345, -52.344753265381, 6165),  To = Vector(7249, 55.641929626465, 5803), CastPos = Vector(7249, 55.641929626465, 5803)},
        {From = Vector(7665.0073242188, 54.999004364014, 5645.7431640625),  To = Vector(7997, -62.778995513916, 5861), CastPos = Vector(7997, -62.778995513916, 5861)},
        {From = Vector(7995, -61.163398742676, 5715),  To = Vector(7709, 56.321662902832, 5473), CastPos = Vector(7709, 56.321662902832, 5473)},
        {From = Vector(8653, 55.073780059814, 4441),  To = Vector(9027, -61.594711303711, 4425), CastPos = Vector(9027, -61.594711303711, 4425)},
        {From = Vector(8931, -62.612571716309, 4375),  To = Vector(8557, 55.506855010986, 4401), CastPos = Vector(8557, 55.506855010986, 4401)},
        {From = Vector(8645, 55.960289001465, 4115),  To = Vector(9005, -63.280235290527, 4215), CastPos = Vector(9005, -63.280235290527, 4215)},
        {From = Vector(8948.08203125, -63.252712249756, 4116.5078125),  To = Vector(8605, 56.22159576416, 3953), CastPos = Vector(8605, 56.22159576416, 3953)},
        {From = Vector(9345, 67.37971496582, 2815),  To = Vector(9375, 67.509948730469, 2443), CastPos = Vector(9375, 67.509948730469, 2443)},
        {From = Vector(9355, 67.649841308594, 2537),  To = Vector(9293, 63.953853607178, 2909), CastPos = Vector(9293, 63.953853607178, 2909)},
        {From = Vector(8027, 56.071315765381, 3029),  To = Vector(8071, 54.276405334473, 2657), CastPos = Vector(8071, 54.276405334473, 2657)},
        {From = Vector(7995.0229492188, 54.276401519775, 2664.0703125),  To = Vector(7985, 55.659393310547, 3041), CastPos = Vector(7985, 55.659393310547, 3041)},
        {From = Vector(5785, 54.918552398682, 5445),  To = Vector(5899, 51.673694610596, 5089), CastPos = Vector(5899, 51.673694610596, 5089)},
        {From = Vector(5847, 51.673683166504, 5065),  To = Vector(5683, 54.923862457275, 5403), CastPos = Vector(5683, 54.923862457275, 5403)},
        {From = Vector(6047, 51.67359161377, 4865),  To = Vector(6409, 51.673400878906, 4765), CastPos = Vector(6409, 51.673400878906, 4765)},
        {From = Vector(6347, 51.673400878906, 4765),  To = Vector(5983, 51.673580169678, 4851), CastPos = Vector(5983, 51.673580169678, 4851)},
        {From = Vector(6995, 55.738128662109, 5615),  To = Vector(6701, 61.461639404297, 5383), CastPos = Vector(6701, 61.461639404297, 5383)},
        {From = Vector(6697, 61.083110809326, 5369),  To = Vector(6889, 55.628131866455, 5693), CastPos = Vector(6889, 55.628131866455, 5693)},
        {From = Vector(11245, -62.793098449707, 4515),  To = Vector(11585, 52.104347229004, 4671), CastPos = Vector(11585, 52.104347229004, 4671)},
        {From = Vector(11491.91015625, 52.506042480469, 4629.763671875),  To = Vector(11143, -63.063579559326, 4493), CastPos = Vector(11143, -63.063579559326, 4493)},
        {From = Vector(11395, -62.597496032715, 4315),  To = Vector(11579, 51.962089538574, 4643), CastPos = Vector(11579, 51.962089538574, 4643)},
        {From = Vector(11245, 53.017200469971, 4915),  To = Vector(10869, -63.132637023926, 4907), CastPos = Vector(10869, -63.132637023926, 4907)},
        {From = Vector(10923.66015625, -63.288948059082, 4853.9931640625),  To = Vector(11295, 53.402942657471, 4913), CastPos = Vector(11295, 53.402942657471, 4913)},
        {From = Vector(10595, 54.870422363281, 6965),  To = Vector(10351, 55.198459625244, 7249), CastPos = Vector(10351, 55.198459625244, 7249)},
        {From = Vector(10415, 55.269580841064, 7277),  To = Vector(10609, 54.870502471924, 6957), CastPos = Vector(10609, 54.870502471924, 6957)},
        {From = Vector(12395, 54.809947967529, 6115),  To = Vector(12759, 57.640727996826, 6201), CastPos = Vector(12759, 57.640727996826, 6201)},
        {From = Vector(12745, 57.225738525391, 6265),  To = Vector(12413, 54.803039550781, 6089), CastPos = Vector(12413, 54.803039550781, 6089)},
        {From = Vector(12645, 53.343021392822, 4615),  To = Vector(12349, 56.222766876221, 4849), CastPos = Vector(12349, 56.222766876221, 4849)},
        {From = Vector(12395, 52.525123596191, 4765),  To = Vector(12681, 53.853294372559, 4525), CastPos = Vector(12681, 53.853294372559, 4525)},
        {From = Vector(11918.497070313, 57.399909973145, 5471),  To = Vector(11535, 54.801097869873, 5471), CastPos = Vector(11535, 54.801097869873, 5471)},
        {From = Vector(11593, 54.610706329346, 5501),  To = Vector(11967, 56.541202545166, 5477), CastPos = Vector(11967, 56.541202545166, 5477)},
        {From = Vector(11140.984375, 65.858421325684, 8432.9384765625),  To = Vector(11487, 53.453464508057, 8625), CastPos = Vector(11487, 53.453464508057, 8625)},
        {From = Vector(11420.7578125, 53.453437805176, 8608.6923828125),  To = Vector(11107, 65.090522766113, 8403), CastPos = Vector(11107, 65.090522766113, 8403)},
        {From = Vector(11352.48046875, 57.916156768799, 8007.10546875),  To = Vector(11701, 55.458843231201, 8165), CastPos = Vector(11701, 55.458843231201, 8165)},
        {From = Vector(11631, 55.45885848999, 8133),  To = Vector(11287, 58.037368774414, 7979), CastPos = Vector(11287, 58.037368774414, 7979)},
        {From = Vector(10545, 65.745803833008, 7913),  To = Vector(10555, 55.338600158691, 7537), CastPos = Vector(10555, 55.338600158691, 7537)},
        {From = Vector(10795, 55.354972839355, 7613),  To = Vector(10547, 65.771072387695, 7893), CastPos = Vector(10547, 65.771072387695, 7893)},
        {From = Vector(10729, 55.352409362793, 7307),  To = Vector(10785, 54.87170791626, 6937), CastPos = Vector(10785, 54.87170791626, 6937)},
        {From = Vector(10745, 54.871494293213, 6965),  To = Vector(10647, 55.350120544434, 7327), CastPos = Vector(10647, 55.350120544434, 7327)},
        {From = Vector(10099, 66.309921264648, 8443),  To = Vector(10419, 66.106910705566, 8249), CastPos = Vector(10419, 66.106910705566, 8249)},
        {From = Vector(9203, 63.777507781982, 3309),  To = Vector(9359, -63.260040283203, 3651), CastPos = Vector(9359, -63.260040283203, 3651)},
        {From = Vector(9327, -63.258842468262, 3675),  To = Vector(9185, 65.192367553711, 3329), CastPos = Vector(9185, 65.192367553711, 3329)},
        {From = Vector(10045, 55.140678405762, 6465),  To = Vector(10353, 54.869094848633, 6679), CastPos = Vector(10353, 54.869094848633, 6679)},
        {From = Vector(10441.002929688, 65.793014526367, 8315.2333984375),  To = Vector(10133, 64.52165222168, 8529), CastPos = Vector(10133, 64.52165222168, 8529)},
        {From = Vector(8323, 54.89501953125, 9137),  To = Vector(8207, 53.530456542969, 9493), CastPos = Vector(8207, 53.530456542969, 9493)},
        {From = Vector(8295, 53.530418395996, 9363),  To = Vector(8359, 54.895038604736, 8993), CastPos = Vector(8359, 54.895038604736, 8993)},
        {From = Vector(8495, 52.768348693848, 9763),  To = Vector(8401, 53.643203735352, 10125), CastPos = Vector(8401, 53.643203735352, 10125)},
        {From = Vector(8419, 53.59920501709, 9997),  To = Vector(8695, 51.417175292969, 9743), CastPos = Vector(8695, 51.417175292969, 9743)},
        {From = Vector(7145, 55.597702026367, 5965),  To = Vector(7413, -66.513969421387, 6229), CastPos = Vector(7413, -66.513969421387, 6229)},
        {From = Vector(6947, 56.01900100708, 8213),  To = Vector(6621, -62.816535949707, 8029), CastPos = Vector(6621, -62.816535949707, 8029)},
        {From = Vector(6397, 54.634998321533, 10813),  To = Vector(6121, 54.092365264893, 11065), CastPos = Vector(6121, 54.092365264893, 11065)},
        {From = Vector(6247, 54.6325340271, 11513),  To = Vector(6053, 39.563938140869, 11833), CastPos = Vector(6053, 39.563938140869, 11833)},
        {From = Vector(4627, 41.618049621582, 11897),  To = Vector(4541, 51.561706542969, 11531), CastPos = Vector(4541, 51.561706542969, 11531)},
        {From = Vector(5179, 53.036727905273, 10839),  To = Vector(4881, -63.11701965332, 10611), CastPos = Vector(4881, -63.11701965332, 10611)},
        {From = Vector(4897, -63.125648498535, 10613),  To = Vector(5177, 52.773872375488, 10863), CastPos = Vector(5177, 52.773872375488, 10863)},
        {From = Vector(11367, 50.348838806152, 9751),  To = Vector(11479, 106.51720428467, 10107), CastPos = Vector(11479, 106.51720428467, 10107)},
        {From = Vector(11489, 106.53769683838, 10093),  To = Vector(11403, 50.349449157715, 9727), CastPos = Vector(11403, 50.349449157715, 9727)},
        {From = Vector(12175, 106.80973052979, 9991),  To = Vector(12143, 50.354927062988, 9617), CastPos = Vector(12143, 50.354927062988, 9617)},
        {From = Vector(12155, 50.354919433594, 9623),  To = Vector(12123, 106.81489562988, 9995), CastPos = Vector(12123, 106.81489562988, 9995)},
        {From = Vector(9397, 52.484146118164, 12037),  To = Vector(9769, 106.21959686279, 12077), CastPos = Vector(9769, 106.21959686279, 12077)},
        {From = Vector(9745, 106.2202835083, 12063),  To = Vector(9373, 52.484580993652, 12003), CastPos = Vector(9373, 52.484580993652, 12003)},
        {From = Vector(9345, 52.689178466797, 12813),  To = Vector(9719, 106.20919799805, 12805), CastPos = Vector(9719, 106.20919799805, 12805)},
        {From = Vector(4171, 109.72004699707, 2839),  To = Vector(4489, 54.030017852783, 3041), CastPos = Vector(4489, 54.030017852783, 3041)},
        {From = Vector(4473, 54.04020690918, 3009),  To = Vector(4115, 110.06342315674, 2901), CastPos = Vector(4115, 110.06342315674, 2901)},
        {From = Vector(2669, 105.9382019043, 4281),  To = Vector(2759, 57.061370849609, 4647), CastPos = Vector(2759, 57.061370849609, 4647)},
        {From = Vector(2761, 57.062965393066, 4653),  To = Vector(2681, 106.2310256958, 4287), CastPos = Vector(2681, 106.2310256958, 4287)},
        {From = Vector(1623, 108.56233215332, 4487),  To = Vector(1573, 56.13228225708, 4859), CastPos = Vector(1573, 56.13228225708, 4859)},
        {From = Vector(1573, 56.048126220703, 4845),  To = Vector(1589, 108.56234741211, 4471), CastPos = Vector(1589, 108.56234741211, 4471)},
        {From = Vector(2355.4450683594, 60.167724609375, 6366.453125),  To = Vector(2731, 54.617771148682, 6355), CastPos = Vector(2731, 54.617771148682, 6355)},
        {From = Vector(2669, 54.488224029541, 6363),  To = Vector(2295, 60.163955688477, 6371), CastPos = Vector(2295, 60.163955688477, 6371)},
        {From = Vector(2068.5336914063, 54.921718597412, 8898.5322265625),  To = Vector(2457, 53.765918731689, 8967), CastPos = Vector(2457, 53.765918731689, 8967)},
        {From = Vector(2447, 53.763805389404, 8913),  To = Vector(2099, 54.922241210938, 8775), CastPos = Vector(2099, 54.922241210938, 8775)},
        {From = Vector(1589, 49.631057739258, 9661),  To = Vector(1297, 38.928337097168, 9895), CastPos = Vector(1297, 38.928337097168, 9895)},
        {From = Vector(1347, 39.538192749023, 9813),  To = Vector(1609, 50.499561309814, 9543), CastPos = Vector(1609, 50.499561309814, 9543)},
        {From = Vector(3997, -63.152000427246, 10213),  To = Vector(3627, -64.785446166992, 10159), CastPos = Vector(3627, -64.785446166992, 10159)},
        {From = Vector(3709, -63.07014465332, 10171),  To = Vector(4085, -63.139434814453, 10175), CastPos = Vector(4085, -63.139434814453, 10175)},
        {From = Vector(9695, 106.20919799805, 12813),  To = Vector(9353, 95.629013061523, 12965), CastPos = Vector(9353, 95.629013061523, 12965)},
        {From = Vector(5647, 55.136940002441, 9563),  To = Vector(5647, -65.224411010742, 9187), CastPos = Vector(5647, -65.224411010742, 9187)},
        {From = Vector(5895, 52.799312591553, 3389),  To = Vector(6339, 51.669734954834, 3633), CastPos = Vector(6339, 51.669734954834, 3633)},
        {From = Vector(6225, 51.669948577881, 3605),  To = Vector(5793, 53.080261230469, 3389), CastPos = Vector(5793, 53.080261230469, 3389)},
        {From = Vector(8201, 54.276405334473, 1893),  To = Vector(8333, 52.60326385498, 1407), CastPos = Vector(8333, 52.60326385498, 1407)},
        {From = Vector(8185, 52.598056793213, 1489),  To = Vector(8015, 54.276405334473, 1923), CastPos = Vector(8015, 54.276405334473, 1923)},
        {From = Vector(2351, 56.366249084473, 4743),  To = Vector(2355, 107.71157836914, 4239), CastPos = Vector(2355, 107.71157836914, 4239)},
        {From = Vector(2293, 109.00361633301, 4389),  To = Vector(2187, 56.207984924316, 4883), CastPos = Vector(2187, 56.207984924316, 4883)},
        {From = Vector(4271, 108.56426239014, 2065),  To = Vector(4775, 54.37939453125, 2033), CastPos = Vector(4775, 54.37939453125, 2033)},
        {From = Vector(4675, 54.971534729004, 2013),  To = Vector(4173, 108.41383361816, 1959), CastPos = Vector(4173, 108.41383361816, 1959)},
        {From = Vector(7769, 53.940235137939, 10925),  To = Vector(8257, 49.935401916504, 11049), CastPos = Vector(8257, 49.935401916504, 11049)},
        {From = Vector(8123, 49.935398101807, 11051),  To = Vector(7689, 53.834579467773, 10831), CastPos = Vector(7689, 53.834579467773, 10831)}
    }
}

function ReturnDrawPoint()
    local Spots     = JumpSpots[myPlayer.charName]
    local From      =  nil
    local To        =  nil
    local CastPos   =  nil   
    if Spots then
        local MaxDistance = 550
        for i, spot in ipairs(Spots) do
            if GetDistance(spot.CastPos) <= MaxDistance then
               From         = spot.From
               To           = spot.To
               CastPos      = spot.CastPos             
            end
        end
    end
    return From, To, CastPos
end


function OnDraw()
	if not LOADED then return end
    if myPlayer.dead then return end
    local qRange            =       275
    local wRange            =       260
    local eRange            =       385
    local rRange            =       900
    --[[menu draw]]
    local qDraw             =       menu.draw.Q
    local wDraw             =       menu.draw.W
    local eDraw             =       menu.draw.E
    local rDraw             =       menu.draw.R
    local targetDraw        =       menu.draw.target
    local targettext_		=		menu.draw.targettext
    local passive_          =       menu.draw.passivecount
    local spots_            =       menu.draw.spots
    local from_             =       menu.draw.from
    local CastPos_          =       menu.draw.castpos

    if qDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, qRange, ARGB(255, 255, 000, 000))
    end
    if wDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, wRange, ARGB(255, 000, 255, 000))
    end
    if eDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, eRange, ARGB(255, 251, 255, 000))
    end
    if rDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, rRange, ARGB(255, 255, 255, 000))
    end   
    if targetDraw and ValidTarget(Target) and TargetIsValid(Target) then
        for i=0, 3, 1 do
            DrawCircle2(Target.x, Target.y, Target.z, 80 + i , ARGB(255, 255, 000, 255))              
        end
        DrawCircle2(Target.x, Target.y, Target.z, 120 , ARGB(255, 255, 000, 000))
    end
    if targettext_ and ValidTarget(Target) and TargetIsValid(Target) and KillText ~= nil then
    	DrawText3D(KillText, Target.x, Target.y, Target.z, 16, ARGB(255,255,255,000), true)
    end
    if passive_ then
        DrawText3D("Passive Counter: "..tostring(PassiveIndicator), myPlayer.x - 50, myPlayer.y + 500, myPlayer.z, 16, ARGB(255,255,255,255))
    end
    if spots_ then
        local From, To, CastPos = ReturnDrawPoint()
        if from_ and To ~= nil then
            DrawCircle2(To.x, To.y, To.z, 100, ARGB(255, 255, 255, 000))            
        end
        if CastPos_ and CastPos ~= nil then
            DrawCircle2(CastPos.x, CastPos.y, CastPos.z, 120, ARGB(255, 000, 255, 000))
        end
    end
end