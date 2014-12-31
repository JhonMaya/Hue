<?php exit() ?>--by UglyOldGuy 74.73.30.196
if myHero.charName ~= "Nami" then return end

local Libraries   = {
	["Prodiction"]	= "https://bitbucket.org/Klokje/public-klokjes-bol-scripts/raw/154ae5a9505b2af87c1a6049baa529b934a498a9/Common/Prodiction.lua",
	["SOW"]			= "https://raw.github.com/honda7/BoL/master/Common/SOW.lua",
	["VPrediction"] = "https://raw.github.com/honda7/BoL/master/Common/VPrediction.lua"
}

local Downloading, Count = false, 0

function AfterDownload()
	Count = Count - 1
	if Count == 0 then
		PrintChat("<font color='#99CC33'> Downloaded Required Libraries Please Double F9.</font>")
	else
		PrintChat("<font color='#FF0000'> Error Downloading Libraries!.</font>")
	end
end

for Name, Link in pairs(Libraries) do
	if FileExist(LIB_PATH..Name..".lua") then
		require(Name)
	else
		local LibraryPath = LIB_PATH..Name..".lua"
		PrintChat("<font color='#FF0000'> Downloading "..Name..".lua Please Wait...</font>")
		Downloading = true
		Count = Count + 1
		DownloadFile(Link, LibraryPath, AfterDownload())
	end
end

if Downloading then return end

local NamiVersion = 1.1
local NamiUpdate  = true
local UpdateHost  = "raw.github.com"
local VersionLink = "/UglyOldGuy/NintendoBoL/master/Support%20Bundle/Versions/Nami.ver"
local ScriptLink  = "/UglyOldGuy/NintendoBoL/master/Support%20Bundle/Nami%20-%20The%20Little%20Grown%20Mermaid.lua"
local ScriptPath  = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local DownLoad    = "https://"..UpdateHost..ScriptLink
local DownloadingFile = false


if NamiUpdate then
	local UpdateData = GetWebResult(UpdateHost, VersionLink)
	if UpdateData then
		if tonumber(UpdateData) > tonumber(NamiVersion) then
			PrintChat("<font color='#FF0000'> New Update Found Version: </font>"..tonumber(UpdateData))
			PrintChat("<font color='#FF0000'> Updating Please Wait...</font>")
			DownloadingFile = true
			DownloadFile(DownLoad, ScriptPath, function () DownloadingFile = false PrintChat("<font color='#99CC33'> Updated "..tonumber(NamiVersion).." > "..tonumber(UpdateData).." Sucessfully please double F9</font>") end)
		else
			PrintChat("<font color='#99CC33'> No Update Needed, Lastest Version Found.</font>")
		end
	else
		PrintChat("<font color='#FF0000'> Error Reading Update Version!</font>")
	end
end

if DownloadingFile then return end

function c(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

--Dev set vars
local devname = c({83,107,101,101,109})
local scriptname = 'nami'
local scriptver = 1.00

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
        PrintChat("<font color='#FF0000'> Validating Access Please Wait! </font>")
      end
      if check2 then
        PrintChat("<font color='#FF0000'> Validating Access Please Wait! </font>")
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
      PrintChat("<font color='#999966'> User Authenticated! Welcome Back </font>"..GetUser())
      NamiMenu()
      isuserauthed = true
    else
      reason = dePack[c({114,101,97,115,111,110})]
      PrintChat("<font color='#FF0000'> Error Authenticating User!! </font>")
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
  Variables()
  PrintChat("<font color='#0066FF'> >> Nami - The Little Grown Mermaid<<</font>")
end

function hex2string(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end

function Variables()
	Spells = {
		
		["Q"] = {key = _Q, name = "Aqua Prison",           range = 875,  ready = false, dmg = 0, data = myHero:GetSpellData(_Q), speed = 1750, delay = .5, width = 80, pos = nil},
		["W"] = {key = _W, name = "Ebb and Flow",          range = 725,  ready = false, dmg = 0, data = myHero:GetSpellData(_W)},
		["E"] = {key = _E, name = "Tidecaller's Blessing", range = 800,  ready = false, dmg = 0, data = myHero:GetSpellData(_E)},
		["R"] = {key = _R, name = "Tidal Wave",            range = 2550, ready = false, dmg = 0, data = myHero:GetSpellData(_R), speed = 1200, delay = 0.250, width = 325, pos = nil}
	}

	if VIP_USER then
		Prodict  = ProdictManager.GetInstance()
		ProdictQ = Prodict:AddProdictionObject(_Q, Spells.Q.range, Spells.Q.speed, Spells.Q.delay, Spells.Q.width, myHero)
		ProdictR = Prodict:AddProdictionObject(_R, Spells.R.range, Spells.R.speed, Spells.R.delay, Spells.R.width, myHero)
	end
	
	vPred = VPrediction()
	nSOW  = SOW(vPred)

	TargetSelector = TargetSelector(TARGET_LESS_CAST_PRIORITY, Spells.Q.range, DAMAGE_MAGIC)
	TargetSelector.name = "Nami"

	Buffs = { BUFF_STUN, BUFF_ROOT, BUFF_KNOCKUP, BUFF_SUPPRESS, BUFF_SLOW, BUFF_CHARM, BUFF_FEAR, BUFF_TAUNT }

	InterruptingSpells = {
		["AbsoluteZero"]				= true,
		["AlZaharNetherGrasp"]			= true,
		["CaitlynAceintheHole"]			= true,
		["Crowstorm"]					= true,
		["DrainChannel"]				= true,
		["FallenOne"]					= true,
		["GalioIdolOfDurand"]			= true,
		["InfiniteDuress"]				= true,
		["KatarinaR"]					= true,
		["MissFortuneBulletTime"]		= true,
		["Teleport"]					= true,
		["Pantheon_GrandSkyfall_Jump"]	= true,
		["ShenStandUnited"]				= true,
		["UrgotSwap2"]					= true
	}

	priorityTable = {
	    AP = {
	        "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
	        "Kassadin", "Katarina", "Kayle", "Kennen", "Nami", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
	        "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
	            },
	    Support = {
	        "Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",
	                },
	    Tank = {
	        "Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Nautilus", "Shen", "Singed", "Skarner", "Volibear",
	        "Warwick", "Yorick", "Zac",
	            },
	    AD_Carry = {
	        "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "Jinx", "KogMaw", "Lucian", "MasterYi", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
	        "Talon","Tryndamere", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Yasuo","Zed", 
	                },
	    Bruiser = {
	        "Aatrox", "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nocturne", "Olaf", "Poppy",
	        "Renekton", "Rengar", "Riven", "Rumble", "Shyvana", "Trundle", "Udyr", "Vi", "MonkeyKing", "XinZhao",
	            },
        }
	
	Items = {
		["BLACKFIRE"]	= { id = 3188, range = 750, ready = false, dmg = 0 },
		["BRK"]			= { id = 3153, range = 500, ready = false, dmg = 0 },
		["BWC"]			= { id = 3144, range = 450, ready = false, dmg = 0 },
		["DFG"]			= { id = 3128, range = 750, ready = false, dmg = 0 },
		["HXG"]			= { id = 3146, range = 700, ready = false, dmg = 0 },
		["ODYNVEIL"]	= { id = 3180, range = 525, ready = false, dmg = 0 },
		["DVN"]			= { id = 3131, range = 200, ready = false, dmg = 0 },
		["ENT"]			= { id = 3184, range = 350, ready = false, dmg = 0 },
		["HYDRA"]		= { id = 3074, range = 350, ready = false, dmg = 0 },
		["TIAMAT"]		= { id = 3077, range = 350, ready = false, dmg = 0 },
		["YGB"]			= { id = 3142, range = 350, ready = false, dmg = 0 }
	}

	local gameState = GetGame()
	if gameState.map.shortName == "twistedTreeline" then
		TTMAP = true
	else
		TTMAP = false
	end
	if heroManager.iCount < 10 then
        PrintChat(" >> Too few champions to arrange priority")
	elseif heroManager.iCount == 6 and TTMAP then
		ArrangeTTPrioritys()
    else
        ArrangePrioritys()
    end
end

function ArrangePrioritys()
    for i, enemy in pairs(GetEnemyHeroes()) do
        SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 2)
        SetPriority(priorityTable.Support, enemy, 3)
        SetPriority(priorityTable.Bruiser, enemy, 4)
        SetPriority(priorityTable.Tank, enemy, 5)
    end
end

function ArrangeTTPrioritys()
	for i, enemy in pairs(GetEnemyHeroes()) do
		SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 1)
        SetPriority(priorityTable.Support, enemy, 2)
        SetPriority(priorityTable.Bruiser, enemy, 2)
        SetPriority(priorityTable.Tank, enemy, 3)
	end
end

function SetPriority(table, hero, priority)
    for i=1, #table, 1 do
        if hero.charName:find(table[i]) ~= nil then
            TS_SetHeroPriority(priority, hero.charName)
        end
    end
end

function NamiMenu()
	NamiMenu = scriptConfig("Nami - The Little Grown Mermaid ", "Nami")
	
	NamiMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Skills Settings", "skills")
		NamiMenu.skills:addSubMenu(""..Spells.Q.name.." (Q)", "q")
			NamiMenu.skills.q:addParam("chainCC", "Auto Q Chain CC", SCRIPT_PARAM_ONOFF, true)
			NamiMenu.skills.q:addParam("autoQ", "Auto Q", SCRIPT_PARAM_ONOFF, true)
			NamiMenu.skills.q:addParam("interrupt", "Interrupt Spells with Q", SCRIPT_PARAM_ONOFF, true)
			NamiMenu.skills.q:addParam("minQ", "Min # of Enemies", SCRIPT_PARAM_LIST, 2, {"1 Enemy", "2 Enemies", "3 Enemies", "4 Enemies", "5 Enemies"})
			NamiMenu.skills.q:addParam("predType", "Prediction Use", SCRIPT_PARAM_LIST, 2, {"Prodiction", "VPrediction"})
      NamiMenu.skills.q:addParam("center", "Try to hit in Center", SCRIPT_PARAM_ONOFF, true)
		NamiMenu.skills:addSubMenu(""..Spells.W.name.." (W)", "w")
			NamiMenu.skills.w:addParam("packetCast", "Cast with Packets", SCRIPT_PARAM_ONOFF, true)
			NamiMenu.skills.w:addParam("autoW", "Auto Heal Allies", SCRIPT_PARAM_ONOFF, true)
			NamiMenu.skills.w:addParam("minHealth", "Heal Allies if Health < %", SCRIPT_PARAM_SLICE, 75, 0, 100, -1)
			NamiMenu.skills.w:addParam("minMana", "Heal Allies if Mana > %", SCRIPT_PARAM_SLICE, 60, 0, 100, -1)
		NamiMenu.skills:addSubMenu(""..Spells.E.name.." (E)", "e")
			NamiMenu.skills.e:addParam("packetCast", "Cast with Packets", SCRIPT_PARAM_ONOFF, true)
			NamiMenu.skills.e:addParam("autoE", "Auto E Allies", SCRIPT_PARAM_ONOFF, true)
			NamiMenu.skills.e:addParam("eSelf", "Dont Use E on Myself", SCRIPT_PARAM_ONOFF, false)
			NamiMenu.skills.e:addParam("minMana", "Auto E Allies if Mana > %", SCRIPT_PARAM_SLICE, 50, 0, 100, -1)
		NamiMenu.skills:addSubMenu(""..Spells.R.name.." (R)", "r")
			NamiMenu.skills.r:addParam("manualR", "Manual Ult Key (T)", SCRIPT_PARAM_ONKEYDOWN, false, 84)
			NamiMenu.skills.r:addParam("autoR", "Auto R", SCRIPT_PARAM_ONOFF, false)
			NamiMenu.skills.r:addParam("minR", "Min # of Enemies", SCRIPT_PARAM_LIST, 2, {"1 Enemy", "2 Enemies", "3 Enemies", "4 Enemies", "5 Enemies"})
			NamiMenu.skills.r:addParam("predType", "Prediction Use", SCRIPT_PARAM_LIST, 2, {"Prodiction", "VPrediction"})
		NamiMenu.skills:addSubMenu("Ignite", "ignite")
			NamiMenu.skills.ignite:addParam("auto", "Use Auto Ignite", SCRIPT_PARAM_ONOFF, true)

	NamiMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Combo Settings", "combo")
		NamiMenu.combo:addParam("comboKey", "Combo Key (X)", SCRIPT_PARAM_ONKEYDOWN, false, 88)
		NamiMenu.combo:addParam("comboE", "Use "..Spells.E.name.." (E) in Combo", SCRIPT_PARAM_ONOFF, false)
		NamiMenu.combo:addParam("comboItems", "Use Items With Combo", SCRIPT_PARAM_ONOFF, true)
		NamiMenu.combo:permaShow("comboKey") 

	NamiMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Harass Settings", "harass")
		NamiMenu.harass:addParam("harassKey", "Harass Hotkey (C)", SCRIPT_PARAM_ONKEYDOWN, false, 67)
		NamiMenu.harass:addParam("Q", "Use "..Spells.Q.name.." (Q)", SCRIPT_PARAM_ONOFF, true)
		NamiMenu.harass:addParam("W", "Use "..Spells.W.name.." (W)", SCRIPT_PARAM_ONOFF, false)
		NamiMenu.harass:addParam("E", "Use "..Spells.E.name.." (E)", SCRIPT_PARAM_ONOFF, true)
		NamiMenu.harass:permaShow("harassKey") 
			
	NamiMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Orbwalking Settings", "Orbwalking")
		nSOW:LoadToMenu(NamiMenu.Orbwalking)
	
	NamiMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Drawing Settings", "drawing")	
		NamiMenu.drawing:addParam("qDraw", "Draw "..Spells.Q.name.." (Q) Range", SCRIPT_PARAM_ONOFF, true)
		NamiMenu.drawing:addParam("wDraw", "Draw "..Spells.W.name.." (W) Range", SCRIPT_PARAM_ONOFF, false)
		NamiMenu.drawing:addParam("eDraw", "Draw "..Spells.E.name.." (E) Range", SCRIPT_PARAM_ONOFF, false)
    NamiMenu.drawing:addParam("counter", "Q Landed Counter", SCRIPT_PARAM_ONOFF, true)

	NamiMenu:addTS(TargetSelector)
end

function OnTick()
	if not isuserauthed then return end
	Checks()
	AutoSkills()

	ComboKey  = NamiMenu.combo.comboKey
	harassKey = NamiMenu.harass.harassKey
	
	if ComboKey then NamiCombo() end
	if harassKey then HarassCombo() end
	if NamiMenu.skills.ignite.auto then AutoIgnite() end
  if NamiMenu.skills.q.center then
    Spells.Q.width = 80
    Spells.Q.delay = .8
  else
    Spells.Q.width = 180
    Spells.Q.delay = .8
  end
end

function NamiCombo()
	if Target and Target.valid then
		CastQ(Target)
		CastW()
		if NamiMenu.combo.comboE then
			CastE()
		end
	end
end

function HarassCombo()
	if Target and Target.valid then
		if NamiMenu.harass.E then
			CastE(ETarget)
		end
		if NamiMenu.harass.Q then
			CastQ(Target)
		end
		if NamiMenu.harass.W then
			CastW()
		end
	end	
end

function AutoSkills()
	if NamiMenu.skills.q.autoQ and Spells.Q.ready then
		for _, enemy in pairs(GetEnemyHeroes()) do
			if not enemy.dead and (GetDistanceSqr(enemy) < (Spells.Q.range * Spells.Q.range)) then
				local AOEPos, Chance, Targets = vPred:GetCircularAOECastPosition(enemy, Spells.Q.delay, Spells.Q.width, Spells.Q.range, Spells.Q.speed, myHero)
				if Chance >= 2 and Targets >= NamiMenu.skills.q.minQ then
					CastSpell(_Q, AOEPos.x, AOEPos.z)
				end
			end
		end
	end
	if NamiMenu.skills.w.autoW and Spells.W.ready then
		for _, ally in pairs(GetAllyHeroes()) do
			local MenuHealth = NamiMenu.skills.w.minHealth / 100
			local MenuMana = NamiMenu.skills.w.minMana / 100
			if (ally.health <= (ally.maxHealth * MenuHealth)) and (myHero.mana >= (myHero.maxMana * MenuMana)) and (CountEnemyHeroInRange(Spells.W.range, ally) < 1) then
				CastW()
			elseif (ally.health <= (ally.maxHealth * MenuHealth)) and (myHero.mana >= (myHero.maxMana * MenuMana)) and (CountEnemyHeroInRange(Spells.W.range, ally) >= 1) then
				CastW()
			end
		end
	end
	if NamiMenu.skills.r.autoR or NamiMenu.skills.r.manualR and Spells.R.ready then
		for _, enemy in pairs(GetEnemyHeroes()) do
			if not enemy.dead and GetDistanceSqr(enemy) < Spells.R.range * Spells.R.range then
				local rAOEPos, rChance, rTargets = vPred:GetLineAOECastPosition(enemy, Spells.R.delay, Spells.R.width, Spells.R.range, Spells.R.speed, myHero)
				if rChance >= 2 and rTargets >= NamiMenu.skills.r.minR then
					CastSpell(_R, rAOEPos.x, rAOEPos.z)
				end
			end
		end
	end
end

function CastW()
	local WTarget = nil
	if Spells.W.ready then
		local MenuHealth = NamiMenu.skills.w.minHealth / 100
		for _, ally in pairs (GetAllyHeroes()) do
			if not ally.dead and GetDistanceSqr(ally) <= Spells.W.range * Spells.W.range then
				if (ally.health <= (ally.maxHealth * MenuHealth)) and (CountEnemyHeroInRange(Spells.W.range, ally) >= 1) then
					WTarget = ally
				end
			end
		end
		if WTarget == nil then
			for _, enemy in pairs(GetEnemyHeroes()) do
				if not enemy.dead and GetDistanceSqr(enemy) <= Spells.W.range * Spells.W.range then
					if (CountAllyHeroInRange(Spells.W.range, enemy) >= 1) or (CountEnemyHeroInRange(Spells.W.range, enemy) >= 1) then
						WTarget = enemy
					end
				end
			end
		end
		if Target and GetDistanceSqr(Target) <= Spells.W.range * Spells.W.range and WTarget == nil then
			WTarget = Target
		end
	end
	if WTarget ~= nil then
		if (GetDistanceSqr(WTarget) > (Spells.W.range * Spells.W.range)) or not Spells.W.ready then
			return false
		else
			if VIP_USER and NamiMenu.skills.w.packetCast then
				Packet("S_CAST", {spellId = _W, targetNetworkId = WTarget.networkID}):send()
				return true
			else
				CastSpell(_W, WTarget)
				return true
			end
		end
	end
end

function CastE()
	local ETarget = nil
	if Spells.E.ready then
		for _, ally in ipairs(GetAllyHeroes()) do
			if GetDistanceSqr(ally) <= Spells.E.range * Spells.E.range then
				if (ETarget == nil) or (ETarget.damage < ally.damage) then
					ETarget = ally
				end
			end
			if ETarget == nil or (myHero.damage > ETarget.damage) and not NamiMenu.skills.e.eSelf then
				ETarget = myHero
			end
		end
	end
	if ETarget ~= nil then
		if (GetDistanceSqr(ETarget) > (Spells.E.range * Spells.E.range)) or not Spells.E.ready then
			return false
		else
			if VIP_USER and NamiMenu.skills.e.packetCast then
				Packet("S_CAST", {spellId = _E, targetNetworkId = ETarget.networkID}):send()
				return true
			else
				CastSpell(_E, ETarget)
				return true
			end
		end
	end
end

function CountAllyHeroInRange(range, object)
    object = object or myHero
    range  = range * range
    local allyInRange = 0
    for i = 1, heroManager.iCount, 1 do
        local hero = heroManager:getHero(i)
        if hero.team == myHero.team and not hero.dead and GetDistanceSqr(object, hero) <= range then
            allyInRange = allyInRange + 1
        end
    end
    return allyInRange
end

function CastQ(unit)
	if (GetDistanceSqr(unit) > (Spells.Q.range * Spells.Q.range)) or (not Spells.Q.ready) or (not unit.valid) then
		return false
	else
		if VIP_USER then
			if NamiMenu.skills.q.predType == 1 then
				Spells.Q.pos = ProdictQ:GetPrediction(unit)
				if Spells.Q.pos and Spells.Q.pos ~= nil then
					CastSpell(_Q, Spells.Q.pos.x, Spells.Q.pos.z)
					return true
				end
			else
				local CastPos, HitChance, Position = vPred:GetCircularCastPosition(unit, Spells.Q.delay, Spells.Q.width, Spells.Q.speed)
				if HitChance >= 2 then
					CastSpell(_Q, CastPos.x, CastPos.z)
					return true
				end
			end
		else
			local QPrediction = TargetPrediction(Spells.Q.range, Spells.Q.speed, Spells.Q.delay, Spells.Q.width)
			Spells.Q.pos = QPrediction:GetPrediction(unit)
			if Spells.Q.pos and Spells.Q.pos ~= nil then
				CastSpell(_Q, Spells.Q.pos.x, Spells.Q.pos.z)
				return true
			end
		end
	end
	return false
end

function AutoIgnite()
	if ignitReady then
		for _, enemy in pairs(GetEnemyHeroes()) do
			if GetDistanceSqr(enemy) < 600 * 600 then
				local igniteDmg = getDmg("IGNITE", enemy, myHero)
				if enemy.health < igniteDmg then
					CastSpell(ignit, enemy)
				end
			end
		end
	end
end

function GetTarget()
	TargetSelector:update()
    if _G.MMA_Target and _G.MMA_Target.type == myHero.type then
    	return _G.MMA_Target
   	elseif _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair then
   		return _G.AutoCarry.Attack_Crosshair.target
   	elseif TargetSelector.target and not TargetSelector.target.dead and TargetSelector.target.type  == "obj_AI_Hero" then
    	return TargetSelector.target
    else
    	return nil
    end
end

function UseItems(enemy)
	for i, item in pairs(Items) do
		if GetInventoryItemIsCastable(item.id) and GetDistanceSqr(enemy) < item.range*item.range then
			CastItem(item.id, enemy)
		end
	end
end

function MoveToMouse()
	if GetDistance(mousePos) then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
    end        
end

if VIP_USER then
	function OnGainBuff(source, buff)
		if not isuserauthed then return end
		if NamiMenu.skills.q.chainCC then
			if source.team ~= myHero.team and source.type == "obj_AI_Hero" then
				for i = 1, #Buffs do
					local buffType = Buffs[i]
					if buff.type == buffType then
						CastQ(source)
					end
				end
			end
		end
	end
end

function OnProcessSpell(unit, spell)
	if not isuserauthed then return end
	if unit.team == myHero.team then
		if NamiMenu.skills.e.autoE then
			if spell.name:lower():find("attack") and GetDistanceSqr(unit) <= Spells.E.range * Spells.E.range then
				local MenuMana = NamiMenu.skills.e.minMana / 100
				if myHero.mana >= (myHero.maxMana * MenuMana) and spell.target.type == "obj_AI_Hero" then
					if unit == myHero and not NamiMenu.skills.e.eSelf then
						CastE(unit)
					elseif unit ~= myHero then
						CastE(unit)
					end
				end
			end
        end
    end
    if NamiMenu.skills.q.interrupt then
		if (GetDistanceSqr(unit) <= (Spells.Q.range * Spells.Q.range)) then
			if InterruptingSpells[spell.name] then
				CastSpell(_Q, unit.visionPos.x, unit.visionPos.z)
			end
		end
	end
end

function OnDraw()
	if not isuserauthed then return end
	if not myHero.dead then
		if Spells.Q.ready and NamiMenu.drawing.qDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.Q.range, 0x0099FF)
		end
		if Spells.W.ready and NamiMenu.drawing.wDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.W.range, 0x33CC33)
		end
		if Spells.E.ready and NamiMenu.drawing.eDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.E.range, 0x6699FF)
		end
	end
end

function Checks()
	-- Updates Targets --
	Target = GetTarget()

	-- Updates Items --
	for i, item in pairs(Items) do
		item.ready = GetInventoryItemIsCastable(item.id)
	end
	
	-- Updates Spell Info --
	for i, spell in pairs(Spells) do
		spell.ready = myHero:CanUseSpell(spell.key) == READY
	end
	
	-- Finds Ignite --
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		ignit = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignit = SUMMONER_2
	end

	ignitReady = (ignit ~= nil and myHero:CanUseSpell(ignit) == READY)
end