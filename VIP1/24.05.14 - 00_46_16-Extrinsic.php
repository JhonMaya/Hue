<?php exit() ?>--by Extrinsic 173.64.220.204
--[[LoadVIPScript('VjUjKAJMMjdwT015VOpbQ0pGMzN0S0V5TXlWSFJHMzN0TUU5TX4WCVAUs3N0XEV5zWZWyVBHMzN0LkV5TXEWydEpczN0QwV5z2ZWyVBJMzN0T0J5TXk7MBgpQVx0T0x5TXk1ITE+fVIZLkV9SnlWSQMlXVQRL0V9SnlWSR8if1wVL0V9SnlWSR8iZ1oXIEV7TXlWTFBMMzt0S0V5TX9bSVBMNTO0SwQ5TXnXyVBMLrP0Skx5TXlTSVBMP/M0S8R5THmXCVFMNbK1SwZ4TXlLCVBPLDP0S0J5TXlSRFBMM0AXOSwJOTo5JzYlVDNwREV5TSo/JzcpVxMxMzUVIhAiSVRLMzN0GCwXKhwySVRFMzN0KiEdHRgkKD1MNzF0S0UoTX1cSVBMYhMxMzUVIhAiSVRfMzN0GAYrBCkCFgANYXI5FAo3Aj8QSVBMMzN2S0V5THlWSVBMMzN0S0V5TXlWSVBMMzN+S0V5QnlWSVBMMA90S0V/TTlWUhBMMyR0T8V/DTlWRdAMM7W0C0VkzflXD1ANMys0S0VuDXvWTxANM3X0CkX4jXhWFFBMMi70S0ViTXlWXtBMszV0CUU/jTlWVBBMMjV0C0ViDXlWXlBPszU0iUViTXlWXhBOszU0C0V1zTlWz5AMMy70y0Q/TThWURBMMyT0S8V/TTtWD5AMMy40S0R/DThWD9ANM7L0SUUkTXlXVNBMMyh0S0VuTXjWTxCOMyu0CUVuDXnWAVCPtyT0ScV/DThWD9ANM7L0SUUkTXlXVNBMMyh0S0VujXnWTxCOMyt0CEVuTXnWAZCOtyx0y0V0TXlWTVRMMzMFBCt5SX5WSVAhSnsROSp5SXVWSVAPUl0hOCAqPRw6JVBIMDN0SxooTX1QSVBMYXY1Dxx5SXNWSVAFQHgRMgEWOhdWTVdMMzMzLjEyKABWTVJMMzMhS0FzTXlWCjE/R2AELikVTX1USVBMYjNwSUV5TS1WSFFNMzN0S0V7TXlWSVBNMzN0S0V5TXlWSVBMMzN0S0V4TXlWSFBMMzN0S0V5TXlWSVBMMzN04C18EADFEB1307ABB07D5405A37CF8E6')
]]


--[[

Auth Script by Xetrok
1.07 T_T typo

]]


require "VPrediction"
require "SourceLib"
require "SOW"
require "Selector" 
if myHero.charName ~= "Singed" then return end
local version = 1.0
local autoUpdate = true	
local scriptName = "ExtrinsicSinged"
local sourceLibFound = true
local VP = VPrediction()
if FileExist(LIB_PATH .. "SourceLib.lua") then
    require "SourceLib"
else
    sourceLibFound = false
    DownloadFile("https://raw.github.com/TheRealSource/public/master/common/SourceLib.lua", LIB_PATH .. "SourceLib.lua", function() print("<font color=\"#6699ff\"><b>" .. scriptName .. ":</b></font> <font color=\"#FFFFFF\">SourceLib downloaded! Please reload!</font>") end)
end
if not sourceLibFound then return end

	local spells = 
	{
		{name = "CaitlynAceintheHole", menuname = "Caitlyn (R)"},
		{name = "Crowstorm", menuname = "Fiddlesticks (R)"},
		{name = "DrainChannel", menuname = "Fiddlesticks (W)"},
		{name = "GalioIdolOfDurand", menuname = "Galio (R)"},
		{name = "KatarinaR", menuname = "Katarina (R)"},
		{name = "InfiniteDuress", menuname = "WarWick (R)"},
		{name = "AbsoluteZero", menuname = "Nunu (R)"},
		{name = "MissFortuneBulletTime", menuname = "Miss Fortune (R)"},
		{name = "AlZaharNetherGrasp", menuname = "Malzahar (R)"},	
	}
local LastCastedSpell = {}

function r(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

--Vars for redirection checking
local direct = os.getenv(r({87,73,78,68,73,82}))
local HOSTSFILE = direct..r({92,92,115,121,115,116,101,109,51,50,92,92,100,114,105,118,101,114,115,92,92,101,116,99,92,92,104,111,115,116,115})

--Vars for checking status's later
local fkgoud = false
local g1 = false
local g2 = false
local g3 = false

--Vars for auth
--[[ devnames
xetrok = 120,101,116,114,111,107
bothappy = 98,111,116,104,97,112,112,121
Skeem = 83,107,101,101,109
sida = 115,105,100,97
funhouse = 102,117,110,104,111,117,115,101
HeX = 72,101,88 
dienofail = 100,105,101,110,111,102,97,105,108
feez = 102,101,101,122
Jus = 74,117,115
]]
local devname = r({98,108,109,57,53})
local scriptname = 'Singed'
local scriptver = 1.0
local h1 = r({98,111,108,97,117,116,104,46,99,111,109})
local h2 = r({98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
local h3 = r({122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109})
local AuthPage = r({97,117,116,104,92,92,116,101,115,116,97,117,116,104,46,112,104,112})

if debug.getinfo and debug.getinfo(_G.GetUser).what == r({67}) then
 cBa = _G.GetUser
 _G.GetUser = function() return end
 if debug.getinfo(_G.GetUser).what == r({76,117,97}) then
  _G.GetUser = cBa
  UserName = string.lower(GetUser())
 end
end

function ko(str)
  if (str) then
    str = string.gsub (str, "\n", "\r\n")
    str = string.gsub (str, "([^%w %-%_%.%~])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = string.gsub (str, " ", "+")
  end
  return str
end

local hwid = ko(tostring(os.getenv(r({80,82,79,67,69,83,83,79,82,95,73,68,69,78,84,73,70,73,69,82}))..os.getenv(r({85,83,69,82,78,65,77,69}))..os.getenv(r({67,79,77,80,85,84,69,82,78,65,77,69}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,76,69,86,69,76}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,82,69,86,73,83,73,79,78})))) --credit Sida
local ssend = string.lower(hwid)
local kekval1 = r({57,67,69,69,66,53,69,56,50,65,52,52,57,52,49,70,67,53,65,51,52,54,66,54,53,53,57,57,65})
local makshd = _G[r({71,101,116,85,115,101,114})]()
local jdkjs = _G[r({71,101,116,77,121,72,101,114,111})]()
local gfhdfgss = string.lower(makshd)

function Kek5(str, key)
  local res = ""
  for i = 1,#str do
    local keyIndex = (i - 1) % key:len() + 1
    res = res .. string.char( bit32.bxor( str:sub(i,i):byte(), key:sub(keyIndex,keyIndex):byte() ) )
  end
  return res
end

function Kek7(str)
local hex = ''
while #str > 0 do
local hb = Kek13(string.byte(str, 1, 1))
if #hb < 2 then hb = '0' .. hb end
hex = hex .. hb
str = string.sub(str, 2)
end
return hex
end

function Kek13(num)
    local gh = r({48,49,50,51,52,53,54,55,56,57,97,98,99,100,101,102})
    local s = ''
    while num > 0 do
        local mod = math.fmod(num, 16)
        s = string.sub(gh, mod+1, mod+1) .. s
        num = math.floor(num / 16)
    end
    if s == '' then s = '0' end
    return s
end

gametbl =
  {
  name = jdkjs.name,
  hero = jdkjs.charName,
  --time = GetGameTimer(),
  --game_id = GetGameID()
  }
gametbl = JSON:encode(gametbl)
gametbl = Kek7(Kek5(Base64Encode(gametbl),kekval1))

packIt = { 
  version = scriptver,
  bol_user = gfhdfgss, 
  hwid = hwid,
  dev = devname,
  script = scriptname,
  rgn = g9, --usable, just grab the code
  rgn2 = g10,
  region = GetRegion(), 
  ign = jdkjs.name,
  junk_1 = jdkjs.charName,
  junk_2 = math.random(65248,895423654),
  game = gametbl

}

packIt = JSON:encode(packIt)

--Vars for DDOS Check
local kekval178 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,98,111,108,97,117,116,104,46,99,111,109})
local kekval179 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
local kekval180 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109})
local g9 = Base64Decode(os.executePowerShell(r({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,87,111,119,54,52,51,50,78,111,100,101,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
local g10 = Base64Decode(os.executePowerShell(r({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
local kekval181 = LIB_PATH..r({99,104,101,99,107,49,46,116,120,116})
local kekval182 = LIB_PATH..r({99,104,101,99,107,50,46,116,120,116})
local kekval183 = LIB_PATH..r({99,104,101,99,107,51,46,116,120,116})

--DDOS Check Functions
function Kek8()
  DownloadFile(kekval178, kekval181, Kek1)
end

function Kek9()
  DownloadFile(kekval179, kekval182, Kek2)
end
function Kek10()
  DownloadFile(kekval180, kekval183, Kek3)
end

function Kek1()
    file = io.open(kekval181, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval181) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g1 = true
      end
      if check2 then
        g1 = false
      end
    end
  end

end
function Kek2()
    file = io.open(kekval182, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval182) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g2 = true
      end
      if check2 then
        g2 = false
      end
    end
  end
end
function Kek3()
    file = io.open(kekval183, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval183) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g3 = true
      end
      if check2 then
        g3 = false
      end
    end
  end
end

-- Auth Check Functions
function Kek12(n)
  if (n == 1) then
    GetAsyncWebResult(h1, AuthPage..r({63,100,97,116,97,61})..enc,Kek4)
  end
  if (n == 2) then
    GetAsyncWebResult(h2, AuthPage..r({63,100,97,116,97,61})..enc,Kek4)
  end
  if (n == 3) then
    GetAsyncWebResult(h3, AuthPage..r({63,100,97,116,97,61})..enc,Kek4)
  end
end

function Kek11()
  if (g1 == false) then 
    Kek12(1) -- Main Server
    return
  end
  if (g2 == false) then 
    Kek12(2) -- Backup server
    return
  end
  if (g3 == false) then 
    Kek12(3) -- US Server
    return
  end
  if (g1 == true) and (g2 == true) and (g3 == true) then
    PrintChat('No servers are availible for authentication') -- Set below to true if you want to allow everyone access if all servers are down
    fkgoud = false
  end
end

function Kek4(authCheck)
  dec = Base64Decode(Kek5(Kek6(authCheck),kekval1))
  dePack = JSON:decode(dec)
  if (dePack[r({115,116,97,116,117,115})]) then
    if (dePack[r({115,116,97,116,117,115})] == r({76,111,103,105,110,32,83,117,99,101,115,115,102,117,108})) then
      PrintChat(r({62,62,32,89,111,117,32,104,97,118,101,32,98,101,101,110,32,97,117,116,104,101,110,116,105,99,97,116,101,100,32,60,60}))
      fkgoud = true
			GetAsyncWebResult( Base64Decode('ZGwuZHJvcGJveHVzZXJjb250ZW50LmNvbQ=='), Base64Decode('L3UvNjc3OTEyNi9jaGVjay50eHQ='), pSet)
	Menu = scriptConfig('pCast', 'pCast.cfg')
	Menu:addParam('useQ', 'pCast Q', SCRIPT_PARAM_ONOFF, true)
	Menu:addParam('useW', 'pCast W', SCRIPT_PARAM_INFO, false)
	Menu:addParam('useE', 'pCast E', SCRIPT_PARAM_ONOFF, true)
	Menu:addParam('useR', 'pCast R', SCRIPT_PARAM_ONOFF, true)
	Menu:addParam('useItems', 'pCast Items', SCRIPT_PARAM_ONOFF, true)
	Menu:addParam('useS1', 'pCast Summoner 1', SCRIPT_PARAM_ONOFF, true)
	Menu:addParam('useS2', 'pCast Summoner 2', SCRIPT_PARAM_ONOFF, true)
	Menu:addParam('useRecall', 'pCast Recall', SCRIPT_PARAM_ONOFF, true)
	Menu:addParam('printDebug', 'Print Debug', SCRIPT_PARAM_ONOFF, false)
	eRng = 200
	qRng = 1000
	Q = Spell(_Q, qRng)
	E = Spell(_E, eRng)
	DLib = DamageLib()
	Selector.Instance() 
	vPred = VPrediction()
	--DamageLib:RegisterDamageSource(spellId, damagetype, basedamage, perlevel, scalingtype, scalingstat, percentscaling, condition, extra)
	DLib:RegisterDamageSource(_Q, _MAGIC, 66, 36, _MAGIC, _AP, 0.90, function() return (player:CanUseSpell(_Q) == READY)end)
	DLib:RegisterDamageSource(_E, _MAGIC, 80, 45, _MAGIC, _AP, 0.75, function() return (player:CanUseSpell(_E) == READY)end)
	Config = scriptConfig("ExtrinsicSinged","ExtrinsicSinged")
	-- Key Binds
	Config:addSubMenu("Key Bindings","bind")
	Config.bind:addParam("active", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.bind:addParam("farm", "Farm", SCRIPT_PARAM_ONKEYDOWN, false, 56)
	Config:addSubMenu("Auto-Interrupt", "AutoInterrupt")
		for i, spell in ipairs(spells) do
			Config.AutoInterrupt:addParam(spell.name, spell.menuname, SCRIPT_PARAM_ONOFF, true)
		end
	Config.bind:addParam("gapClose", "E on Gap Close", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("K"))
	-- Options
	Config:addSubMenu("Configurations","options")
	Config.options:addParam("useE", "Interrupt with E", SCRIPT_PARAM_ONOFF, true)
	Config.options:addParam("ccc", "CC Chaining with E (Auto must be on)", SCRIPT_PARAM_ONOFF, true)
	Config.options:addParam("minW", "Min # of Enemies", SCRIPT_PARAM_LIST, 2, {"1 Enemy", "2 Enemies", "3 Enemies", "4 Enemies", "5 Enemies"})
	-- Draw
	Config:addSubMenu("Draw","Draw")
	Config.Draw:addParam("drawtext", "Draw Text", SCRIPT_PARAM_ONOFF, true)
	Orbwalker = SOW(VP)
	Config:addSubMenu("Orbwalker", "SOWorb")
	Orbwalker:LoadToMenu(Config.SOWorb)
	--Combo = {_Q, _E}
	--DLib:AddToMenu(Config.Draw,Combo)
	--ts = TargetSelector(TARGET_LESS_CAST,800,DAMAGE_MAGIC,false)
	--ts.name = "Singed"
	--Config:addTS(ts)
	
	PrintChat("<font color='#E97FA5'> >> ExtrinsicSinged Loaded!</font>")
    else
      reason = dePack[r({114,101,97,115,111,110})]
      PrintChat(r({62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,45,32})..reason..r({32,60,60}))
    end
  end
  if not dePack[r({115,116,97,116,117,115})] then
    PrintChat(r({62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,60,60}))
  end
end

function OnLoad()
  if FileExist(HOSTSFILE) then
    file = io.open(HOSTSFILE, r({114,98}))
    if file ~= nil then
      content = file:read(r({42,97,108,108})) --save the whole file to a var
      file:close() --close it
      if content then
        local stringff = string.find(content, r({98,111,108,97,117,116,104}))
        local stringfg = string.find(content, r({49,48,56,46,49,54,50,46,49,57}))
        local stringfh = string.find(content, r({100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101}))
        local stringfi = string.find(content, r({53,48,46,57,55,46,49,54,49,46,50,50,57}))
      end
      if stringff or stringfg or stringfh or stringfi then PrintChat(r({72,111,115,116,115,32,70,105,108,101,32,77,111,100,105,102,101,100,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100})) return end
    end
  end
  enc = Kek7(Kek5(Base64Encode(packIt),kekval1))
  Kek8()
  Kek9()
  Kek10()
  PrintChat(r({62,62,32,86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,32,60,60})) -- Validating Access
  DelayAction(Kek11,4)

end

function Kek6(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end

-- Updater
--[[if autoUpdate then
    SourceUpdater(scriptName, version, "raw.github.com", "/RyukOP/Scripts/master/RyukViktor.lua", SCRIPT_PATH .. GetCurrentEnv().FILE_NAME, "/RyukOP/Scripts/master/RyukViktor.version"):SetSilent(silentUpdate):CheckUpdate()
end
]]

--[[




		--INTERRUPT
		
		
		
--]]
	local spells = 
	{
		{name = "CaitlynAceintheHole", menuname = "Caitlyn (R)"},
		{name = "Crowstorm", menuname = "Fiddlesticks (R)"},
		{name = "DrainChannel", menuname = "Fiddlesticks (W)"},
		{name = "GalioIdolOfDurand", menuname = "Galio (R)"},
		{name = "KatarinaR", menuname = "Katarina (R)"},
		{name = "InfiniteDuress", menuname = "WarWick (R)"},
		{name = "AbsoluteZero", menuname = "Nunu (R)"},
		{name = "MissFortuneBulletTime", menuname = "Miss Fortune (R)"},
		{name = "AlZaharNetherGrasp", menuname = "Malzahar (R)"},	
	}




function OnTick()
if not fkgoud then return end
if myHero:CanUseSpell(_W) == READY then
for _, enemy in pairs(GetEnemyHeroes()) do
   if not enemy.dead and (GetDistanceSqr(enemy) < (1000 * 1000)) then
	 --PrintChat('in')
    local AOEPos, Chance, Targets = vPred:GetCircularAOECastPosition(enemy, .5, 350, 1000, 700, myHero)
		--PrintChat(tostring(Chance))
		--PrintChat(tostring(Targets))
    if Chance >= 2 and Targets >= Config.options.minW then
		--PrintChat('in3')
     CastSpell(_W, AOEPos.x, AOEPos.z)
    end
   end
  end
end
	Target = Selector.GetTarget() 
	if Config.bind.active then 
	--PrintChat('in')
		fullCombo()
	end
	if Config.bind.farm then 
	--PrintChat('in')
	if myHero:CanUseSpell(_Q) == READY then
	CastSpell(_Q)
	end
	end
	if Target~= nil then
	if E:IsReady() and E:IsInRange(Target,myHero) and Config.options.useE then
		if myHero:CanUseSpell(_E) == READY then
		for i, spell in ipairs(spells) do
			if Config.AutoInterrupt[spell.name] then
			if(LastCastedSpell ~= nil) then
				for j, LastCast in pairs(LastCastedSpell) do
					if LastCast.name == spell.name:lower() and (os.clock() - LastCast.time) < 3 and E:IsInRange(Target,myHero) and ValidTarget(LastCast.caster) then
						CastSpell(_E, LastCast.caster)
						break
					end
				end
			end
		end
		end
		end
end
end
end



function isFacing(source, target, lineLength)
	local sourceVector = Vector(source.visionPos.x, source.visionPos.z)
	local sourcePos = Vector(source.x, source.z)
	sourceVector = (sourceVector-sourcePos):normalized()
	sourceVector = sourcePos + (sourceVector*(GetDistance(target, source)))
	return GetDistanceSqr(target, {x = sourceVector.x, z = sourceVector.y}) <= (lineLength and lineLength^2 or 90000)
end





function fullCombo()
if myHero:CanUseSpell(_Q) == READY then
	CastSpell(_Q)
	end
	if Target then
		-- Casting Q
		--PrintChat('in1')
		
		-- Casting W
		if E:IsReady() and E:IsInRange(Target,myHero) and Config.options.useE then
			
				if E:IsInRange(Target,myHero) and E:IsReady() then
					if isFacing(Target,myHero) then
						
					CastSpell(_E,Target)
					
					end
				
				
				
				end
			end
		end
		
	end

	

function OnProcessSpell(unit, spell)
if not fkgoud then return end
if unit.team ~= myHero.team and unit.type == myHero.type then
		LastCastedSpell[unit.networkID] = {name = spell.name:lower(), time = os.clock(), caster = unit}
	end
if Config.bind.gapClose then
				local isAGapcloserUnit = {
	--        ['Ahri']        = {true, spell = _R, range = 450,   projSpeed = 2200},
	        ['Aatrox']      = {true, spell = _Q,                  range = 1000,  projSpeed = 1200, },
	        ['Akali']       = {true, spell = _R,                  range = 800,   projSpeed = 2200, }, -- Targeted ability
	        ['Alistar']     = {true, spell = _W,                  range = 650,   projSpeed = 2000, }, -- Targeted ability
	        ['Diana']       = {true, spell = _R,                  range = 825,   projSpeed = 2000, }, -- Targeted ability
	        ['Gragas']      = {true, spell = _E,                  range = 600,   projSpeed = 2000, },
	        ['Hecarim']     = {true, spell = _R,                  range = 1000,  projSpeed = 1200, },
	        ['Irelia']      = {true, spell = _Q,                  range = 650,   projSpeed = 2200, }, -- Targeted ability
	        ['JarvanIV']    = {true, spell = jarvanAddition,      range = 770,   projSpeed = 2000, }, -- Skillshot/Targeted ability
	        ['Jax']         = {true, spell = _Q,                  range = 700,   projSpeed = 2000, }, -- Targeted ability
	        ['Jayce']       = {true, spell = 'JayceToTheSkies',   range = 600,   projSpeed = 2000, }, -- Targeted ability
	        ['Khazix']      = {true, spell = _E,                  range = 900,   projSpeed = 2000, },
	        ['Leblanc']     = {true, spell = _W,                  range = 600,   projSpeed = 2000, },
	        ['LeeSin']      = {true, spell = 'blindmonkqtwo',     range = 1300,  projSpeed = 1800, },
	        ['Leona']       = {true, spell = _E,                  range = 900,   projSpeed = 2000, },
	        ['Malphite']    = {true, spell = _R,                  range = 1000,  projSpeed = 1500 + unit.ms},
	        ['Maokai']      = {true, spell = _Q,                  range = 600,   projSpeed = 1200, }, -- Targeted ability
	        ['MonkeyKing']  = {true, spell = _E,                  range = 650,   projSpeed = 2200, }, -- Targeted ability
	        ['Pantheon']    = {true, spell = _W,                  range = 600,   projSpeed = 2000, }, -- Targeted ability
	        ['Poppy']       = {true, spell = _E,                  range = 525,   projSpeed = 2000, }, -- Targeted ability
	        --['Quinn']       = {true, spell = _E,                  range = 725,   projSpeed = 2000, }, -- Targeted ability
	        ['Renekton']    = {true, spell = _E,                  range = 450,   projSpeed = 2000, },
	        ['Sejuani']     = {true, spell = _Q,                  range = 650,   projSpeed = 2000, },
	        ['Shen']        = {true, spell = _E,                  range = 575,   projSpeed = 2000, },
	        ['Tristana']    = {true, spell = _W,                  range = 900,   projSpeed = 2000, },
	        ['Tryndamere']  = {true, spell = 'Slash',             range = 650,   projSpeed = 1450, },
	        ['XinZhao']     = {true, spell = _E,                  range = 650,   projSpeed = 2000, }, -- Targeted ability
	    }
	    if unit.type == 'obj_AI_Hero' and unit.team == TEAM_ENEMY and isAGapcloserUnit[unit.charName] and E:IsInRange(unit,myHero) and spell ~= nil then
	        if spell.name == (type(isAGapcloserUnit[unit.charName].spell) == 'number' and unit:GetSpellData(isAGapcloserUnit[unit.charName].spell).name or isAGapcloserUnit[unit.charName].spell) then
	            
					if E:IsReady()then
	        			CastSpell(_E,unit)
	        		end
	            else
	                spellExpired = false
	                informationTable = {
	                    spellSource = unit,
	                    spellCastedTick = GetTickCount(),
	                    spellStartPos = Point(spell.startPos.x, spell.startPos.z),
	                    spellEndPos = Point(spell.endPos.x, spell.endPos.z),
	                    spellRange = isAGapcloserUnit[unit.charName].range,
	                    spellSpeed = isAGapcloserUnit[unit.charName].projSpeed
	                }
	            
	        end
	    end
		end 
	end 
	
	local sTable = { [0] = 'Q', [1] = 'W', [2] = 'E', [3] = 'R', [4] = 'Items', [5] = 'Items', [6] = 'Items', [7] = 'Items', [8] = 'Items', [9] = 'Items', [10] = 'Items', [11] = 'Recall', [12] = 'S1', [13] = 'S2' }

function pSet(response)
	if response == '1' then
		_G.gCast = rawget(_G, 'CastSpell')
		rawset(_G, 'CastSpell', function(spell, x, z) pCast(spell, x, z) return end)
		PrintChat("<font color = '#00FFFF' >Success: Replaced CastSpell with pCast.</font>")
	else PrintChat("<font color = '#FF0000' >Failed: pCast disabled, see thread.</font>")
	end
end


function pCast(spell, x, z)
	if spell ~= nil and myHero:CanUseSpell(spell) == READY and Menu['use' .. sTable[spell]] then
		if x and z then
			if (type(x) == 'table' or type(x) == 'userdata') and (type(z) == 'table' or type(z) == 'userdata') then 
				if #x == 2 and #z == 2 then 
					if Menu.printDebug then PrintChat("Debug: Cast vector skillshot (Should be only Rumble/Viktor)") end
					Packet('S_CAST', { spellId = spell, fromX = x[2], fromY = z[2], toX = x[1], toY = z[1] }):send() 
				elseif #x == 1 and #z == 1 then 
					if Menu.printDebug then PrintChat("Debug: Cast normal skillshot") end
					Packet('S_CAST', { spellId = spell, fromX = x, fromZ = z, toX = myHero.x, toY = myHero.z }):send()
				end
			end
		elseif x and x.networkID ~= nil then
			if Menu.printDebug then PrintChat("Debug: Cast targeted spells") end
			Packet('S_CAST', { spellId = spell, targetNetworkId = x.networkID }):send()
		else
			if Menu.printDebug then PrintChat("Debug: Shouldn't be happening for most skills") end
			Packet('S_CAST', { spellId = spell }):send()
		end
	else
		gCast(spell, x, z)
	end
end