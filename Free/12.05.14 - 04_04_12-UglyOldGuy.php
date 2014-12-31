<?php exit() ?>--by UglyOldGuy 74.73.30.196
require "VPrediction"
require "Prodiction"
require "SOW"

if myHero.charName ~= "Morgana" then return end

function c(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

--Dev set vars
local devname = c({83,107,101,101,109})
local scriptname = 'morgana'
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
      MorganaMenu()
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
  PrintChat("<font color='#990099'> >> Morgana - The Fallen Angel<<</font>")
end

function hex2string(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end



function Variables()
	Spells = {
		
		["Q"] = {key = _Q, name = "Dark Binding",          range = 1300, ready = false, dmg = 0, data = myHero:GetSpellData(_Q), speed = 1200, delay = 0.1515, width =  70, pos = nil},
		["W"] = {key = _W, name = "Tormented Soil",        range =	900, ready = false, dmg = 0, data = myHero:GetSpellData(_W), speed =   20, delay = 0.6720, width = 280, pos = nil},
		["E"] = {key = _E, name = "Black Shield",          range =	750, ready = false,			 data = myHero:GetSpellData(_E)														 },
		["R"] = {key = _R, name = "Soul Shackles",         range =  625, ready = false, dmg = 0, data = myHero:GetSpellData(_R)														 }
	}

	if VIP_USER then
		Prodict  = ProdictManager.GetInstance()
		ProdictQ = Prodict:AddProdictionObject(_Q, Spells.Q.range, Spells.Q.speed, Spells.Q.delay, Spells.Q.width, myHero)
		ProdictW = Prodict:AddProdictionObject(_W, Spells.W.range, Spells.W.speed, Spells.W.delay, Spells.W.width, myHero)
	end
	
	vPred = VPrediction()
	mSOW  = SOW(vPred)

	TargetSelector = TargetSelector(TARGET_NEAR_MOUSE, Spells.Q.range, DAMAGE_MAGIC)
	TargetSelector.name = "Morgana"

	Buffs = { BUFF_STUN, BUFF_ROOT, BUFF_KNOCKUP, BUFF_SUPPRESS, BUFF_SLOW, BUFF_CHARM, BUFF_FEAR, BUFF_TAUNT }

	priorityTable = {
	    AP = {
	        "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
	        "Kassadin", "Katarina", "Kayle", "Kennen", "Morgana", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
	        "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
	            },
	    Support = {
	        "Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Morgana", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",
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

	JungleMobNames = {
        ["wolf8.1.1"] = true,
        ["wolf8.1.2"] = true,
        ["YoungLizard7.1.2"] = true,
        ["YoungLizard7.1.3"] = true,
        ["LesserWraith9.1.1"] = true,
        ["LesserWraith9.1.2"] = true,
        ["LesserWraith9.1.4"] = true,
        ["YoungLizard10.1.2"] = true,
        ["YoungLizard10.1.3"] = true,
        ["SmallGolem11.1.1"] = true,
        ["wolf2.1.1"] = true,
        ["wolf2.1.2"] = true,
        ["YoungLizard1.1.2"] = true,
        ["YoungLizard1.1.3"] = true,
        ["LesserWraith3.1.1"] = true,
        ["LesserWraith3.1.2"] = true,
        ["LesserWraith3.1.4"] = true,
        ["YoungLizard4.1.2"] = true,
        ["YoungLizard4.1.3"] = true,
        ["SmallGolem5.1.1"] = true,
	}

	FocusJungleNames = {
        ["Dragon6.1.1"] = true,
        ["Worm12.1.1"] = true,
        ["GiantWolf8.1.1"] = true,
        ["AncientGolem7.1.1"] = true,
        ["Wraith9.1.1"] = true,
        ["LizardElder10.1.1"] = true,
        ["Golem11.1.2"] = true,
        ["GiantWolf2.1.1"] = true,
        ["AncientGolem1.1.1"] = true,
        ["Wraith3.1.1"] = true,
        ["LizardElder4.1.1"] = true,
        ["Golem5.1.2"] = true,
		["GreatWraith13.1.1"] = true,
		["GreatWraith14.1.1"] = true,
	}
	
	for i = 0, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil then
			if FocusJungleNames[object.name] then
				table.insert(FocusJungleNames, object)
			elseif JungleMobNames[object.name] then
				table.insert(JungleMobNames, object)
			end
		end
	end

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

function MorganaMenu()
	MorganaMenu = scriptConfig("Morgana - The Fallen Angel", "Morgana")
	
	MorganaMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Skills Settings", "skills")
		MorganaMenu.skills:addSubMenu(""..Spells.Q.name.." (Q)", "q")
			MorganaMenu.skills.q:addParam("chainCC", "Auto Q Chain CC", SCRIPT_PARAM_ONOFF, true) -- Done
			MorganaMenu.skills.q:addParam("autoQ", "Auto Q", SCRIPT_PARAM_ONOFF, true) -- Done
			MorganaMenu.skills.q:addParam("maxRange", "Max. Range to Cast Q: ", SCRIPT_PARAM_SLICE, Spells.Q.range, 500, Spells.Q.range, 0) -- Done
			MorganaMenu.skills.q:addParam("predType", "Prediction Use", SCRIPT_PARAM_LIST, 2, {"Prodiction", "VPrediction"}) -- Done
			MorganaMenu.skills.q:addParam("packetCast", "Cast with Packets", SCRIPT_PARAM_ONOFF, true) -- Done
      MorganaMenu.skills.q:addParam("", "Don't Auto Q:", SCRIPT_PARAM_INFO, "")
        for _, enemy in pairs(GetEnemyHeroes()) do
          MorganaMenu.skills.q:addParam(enemy.hash,  enemy.charName, SCRIPT_PARAM_ONOFF, false)
        end
		MorganaMenu.skills:addSubMenu(""..Spells.W.name.." (W)", "w")
			MorganaMenu.skills.w:addParam("autoW", "Auto W at Stunned/Snared Target", SCRIPT_PARAM_ONOFF, true) -- Done
			MorganaMenu.skills.w:addParam("predType", "Prediction Use", SCRIPT_PARAM_LIST, 2, {"Prodiction", "VPrediction"}) -- Done
			MorganaMenu.skills.w:addParam("packetCast", "Cast with Packets", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.skills:addSubMenu(""..Spells.E.name.." (E)", "e")
			MorganaMenu.skills.e:addParam("packetCast", "Cast with Packets", SCRIPT_PARAM_ONOFF, true) -- Done
      MorganaMenu.skills.e:addParam("skills", "Auto Shield Skills Evadeee can't Dodge", SCRIPT_PARAM_ONOFF, true) -- Done
      MorganaMenu.skills.e:addParam("", "Don't Auto E:", SCRIPT_PARAM_INFO, "")
        for _, ally in pairs(GetAllies()) do
          MorganaMenu.skills.e:addParam(ally.hash, ally.charName, SCRIPT_PARAM_ONOFF, false)
        end
		MorganaMenu.skills:addSubMenu(""..Spells.R.name.." (R)", "r")
			MorganaMenu.skills.r:addParam("manualR", "Manual Ult Key (T)", SCRIPT_PARAM_ONKEYDOWN, false, 84) -- Done
			MorganaMenu.skills.r:addParam("autoR", "Auto R", SCRIPT_PARAM_ONOFF, true) -- Done
			MorganaMenu.skills.r:addParam("minR", "Min # of Enemies", SCRIPT_PARAM_LIST, 3, {"1 Enemy", "2 Enemies", "3 Enemies", "4 Enemies", "5 Enemies"}) -- Done

	MorganaMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Combo Settings", "combo")
		MorganaMenu.combo:addParam("comboKey", "Combo Key (X)", SCRIPT_PARAM_ONKEYDOWN, false, 32) -- Done
		MorganaMenu.combo:addParam("comboItems", "Use Items With Combo", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.combo:addParam("targetStunned", "Use "..Spells.W.name.." (W) only if Target Stunned", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.combo:permaShow("comboKey") 

	MorganaMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Harass Settings", "harass")
		MorganaMenu.harass:addParam("harassKey", "Harass Hotkey (C)", SCRIPT_PARAM_ONKEYDOWN, false, 67) -- Done
		MorganaMenu.harass:addParam("harassW", "Use "..Spells.W.name.." (W) to Harass", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.harass:addParam("targetStunned", "Use "..Spells.W.name.." (W) only if Target Stunned", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.harass:permaShow("harassKey") 
			
	MorganaMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Clear Settings", "jungle")
		MorganaMenu.jungle:addParam("jungleKey", "Jungle Clear Key (V)", SCRIPT_PARAM_ONKEYDOWN, false, 86) -- Done
		MorganaMenu.jungle:addParam("jungleQ", "Use "..Spells.Q.name.." (Q)", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.jungle:addParam("jungleW", "Use "..Spells.W.name.." (W)", SCRIPT_PARAM_ONOFF, true) -- Done

	MorganaMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Orbwalking Settings", "Orbwalking")
		mSOW:LoadToMenu(MorganaMenu.Orbwalking) -- Done

	MorganaMenu:addSubMenu("[Nintendo "..myHero.charName.."] - KillSteal Settings", "ks")
		MorganaMenu.ks:addParam("killSteal", "Use Smart Kill Steal", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.ks:addParam("autoIgnite", "Auto Ignite", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.ks:permaShow("killSteal")
			
	MorganaMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Drawing Settings", "drawing")	
		MorganaMenu.drawing:addParam("qDraw", "Draw "..Spells.Q.name.." (Q) Range", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.drawing:addParam("wDraw", "Draw "..Spells.W.name.." (W) Range", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.drawing:addParam("eDraw", "Draw "..Spells.E.name.." (E) Range", SCRIPT_PARAM_ONOFF, true) -- Done
		MorganaMenu.drawing:addParam("rDraw", "Draw "..Spells.R.name.." (R) Range", SCRIPT_PARAM_ONOFF, true) -- Done

	MorganaMenu:addTS(TargetSelector)
end

function OnTick()
	if not isuserauthed then return end
	Checks()
	AutoSkills()

	ComboKey  = MorganaMenu.combo.comboKey
	JungleKey = MorganaMenu.combo.jungleKey
	HarassKey = MorganaMenu.harass.harassKey	

	if ComboKey then MorganaCombo() end
	if JungleKey then JungleFarm() end
	if HarassKey then HarassCombo() end
	if MorganaMenu.ks.killSteal then KillSteal() end
	if MorganaMenu.ks.autoIgnite then AutoIgnite() end
  if MorganaMenu.skills.e.skills and _G.Evadeee_impossibleToEvade then
    CastSkill(_E, myHero)
  end
end

function MorganaCombo()
	if Target and Target.valid then
		if MorganaMenu.combo.comboItems then
			UseItems(Target)
		end
		CastSkill(_Q, Target)
		if (MorganaMenu.combo.targetStunned and not Target.canMove) or not MorganaMenu.combo.targetStunned then
			CastSkill(_W, Target)
		end
	end
end

function HarassCombo()
	if Target and Target.valid then
		CastSkill(_Q, Target)
		if MorganaMenu.harass.harassW then
			if (MorganaMenu.harass.targetStunned and not Target.canMove) or not MorganaMenu.harass.targetStunned then
				CastSkill(_W, Target)
			end
		end
	end
end

function AutoSkills()
	if MorganaMenu.skills.q.autoQ then
		for i, enemy in pairs(GetEnemyHeroes()) do
			if not MorganaMenu.skills.q[enemy.hash] and not enemy.dead then 
        CastSkill(_Q, enemy)
      end
		end
	end
	if MorganaMenu.skills.r.autoR or MorganaMenu.skills.r.manualR then
		if CountEnemyHeroInRange(Spells.R.range) >= MorganaMenu.skills.r.minR then
			CastSpell(_R)
		end
	end
	if MorganaMenu.skills.w.autoW then
		for i, enemy in pairs(GetEnemyHeroes()) do
			if not enemy.canMove then
				CastSkill(_W, enemy)
			end
		end
	end
end


function CastSkill(skill, target)
	for i, spell in pairs(Spells) do
		if spell.key == skill then
			if (GetDistanceSqr(target) > spell.range * spell.range) or not spell.ready then
				return false
			end
		end
	end
	if skill == _Q then
		if VIP_USER then
			if MorganaMenu.skills.q.predType == 1 then
				Spells.Q.pos = ProdictQ:GetPrediction(target)
				if Spells.Q.pos and Spells.Q.pos ~= nil then
					CastSpell(_Q, Spells.Q.pos.x, Spells.Q.pos.z)
          return true
				end
			else
				local CastPos, HitChance, Position = vPred:GetLineCastPosition(target, Spells.Q.delay, Spells.Q.width, MorganaMenu.skills.q.maxRange, Spells.Q.speed, myHero, true)
				if HitChance >= 2 then
					CastSpell(_Q, CastPos.x, CastPos.z)
					return true
				end
			end
		else
			local QPrediction = TargetPrediction(Spells.Q.range, Spells.Q.speed, Spells.Q.delay, Spells.Q.width)
			Spells.Q.pos = QPrediction:GetPrediction(target)
			if Spells.Q.pos and Spells.Q.pos ~= nil then
				CastSpell(_Q, Spells.Q.pos.x, Spells.Q.pos.z)
				return true
			end
		end
		return false
	elseif skill == _W then
		if VIP_USER then
			if MorganaMenu.skills.w.predType == 1 then
				Spells.E.pos = ProdictW:GetPrediction(target)
				if Spells.W.pos and Spells.W.pos ~= nil then
					CastSpell(_W, Spells.W.pos.x, Spells.W.pos.z)
					return true
				end
			else
				local CastPos, HitChance, nTargets = vPred:GetCircularAOECastPosition(target, Spells.W.delay, Spells.W.width, Spells.W.range, Spells.W.speed, myHero)
				if HitChance >= 2 then
					CastSpell(_W, CastPos.x, CastPos.z)
					return true
				end
			end
		else
			local WPrediction = TargetPrediction(Spells.W.range, Spells.W.speed, Spells.W.delay, Spells.W.width)
			Spells.W.pos = WPrediction:GetPrediction(target)
			if Spells.W.pos and Spells.W.pos ~= nil then
				CastSpell(_W, Spells.W.pos.x, Spells.W.pos.z)
				return true
			end
		end
		return false
	elseif skill == _E then
		if VIP_USER and MorganaMenu.skills.w.packetCast then
			Packet("S_CAST", {spellId = _E, targetNetworkId = target.networkID}):send()
			return true
		else
			CastSpell(_E, target)
			return true
		end
		return false
	end
end

function JungleFarm()
	local JungleMob = GetJungleMob()
	if JungleMob ~= nil then
		local JungleCombo = {}
		if MorganaMenu.jungle.jungleQ then
			CastSkill(_Q, JungleMob)
		end
		if MorganaMenu.jungle.jungleE then
			CastSkill(_E, JungleMob)
		end
	end
end

function KillSteal()
	for _, enemy in pairs(GetEnemyHeroes()) do
		Spells.Q.dmg = getDmg("Q", enemy, myHero)
		Spells.E.dmg = getDmg("E", enemy, myHero)
		if ValidTarget(enemy) and enemy.visible then
			if Spells.Q.ready then
				if enemy.health < Spells.E.dmg then
					CastSkill(_Q, enemy)
				end
			elseif Spells.E.ready then
				if enemy.health < Spells.E.dmg then
					CastSkill(_E, enemy)
				end
			elseif Spells.Q.ready and Spells.E.ready then
				if enemy.health < Spells.Q.dmg + Spells.E.dmg then
					CastSkill(_Q, enemy)
				end
			end
		end
	end
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

function GetCustomTarget()
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
		if MorganaMenu.skills.q.chainCC then
			if source.team ~= myHero.team and source.type == "obj_AI_Hero" then
				for i = 1, #Buffs do
					local buffType = Buffs[i]
					if buff.type == buffType then
						CastSkill(_Q, source)
					end
				end
			end
		end
	end
end

function GetAllies()
    if _allyHeroes then return _allyHeroes end
    _allyHeroes = {}
    for i = 1, heroManager.iCount do
        local hero = heroManager:GetHero(i)
        if hero.team == player.team then
            table.insert(_allyHeroes, hero)
        end
    end
    return setmetatable(_allyHeroes,{
        __newindex = function(self, key, value)
            error("Adding to AllyHeroes is not granted. Use table.copy.")
        end,
    })
end

function OnProcessSpell(unit, spell)
  if not isuserauthed then return end
  if (not spell.name:lower():find("attack")) and unit.type == myHero.type and unit.team ~= myHero.team and Spells.E.ready then
      for _, ally in pairs(GetAllies()) do
        if spell.target == ally and not MorganaMenu.skills.e[ally.hash] then
          CastSkill(_E, ally)
        end
      end
  end
end

function OnDraw()
	if not isuserauthed then return end

	if not myHero.dead then
		if MorganaMenu.drawing.qDraw and Spells.Q.ready then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.Q.range, 0x990099)
		end
		if MorganaMenu.drawing.wDraw and Spells.W.ready then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.W.range, ARGB(255, 51,204, 51))
		end
		if MorganaMenu.drawing.eDraw and Spells.E.ready then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.E.range, ARGB(255, 32,178,170))
		end
		if MorganaMenu.drawing.rDraw and Spells.R.ready then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.R.range, ARGB(255,128, 0 ,128))
		end
	end
end

function SpellToString(unit, spell)
	if spell.name == unit:GetSpellData(_Q).name then
		return "Q"
	elseif spell.name == unit:GetSpellData(_W).name then
		return "W"
	elseif spell.name == unit:GetSpellData(_E).name then
		return "E"
	elseif spell.name == unit:GetSpellData(_R).name then
		return "R"
	end
end

function Checks()
	-- Updates Targets --
	Target = GetCustomTarget()

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