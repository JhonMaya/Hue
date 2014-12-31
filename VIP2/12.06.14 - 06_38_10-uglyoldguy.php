<?php exit() ?>--by uglyoldguy 68.48.159.9
local version = "0.01"
if myHero.charName ~= 'Zac' then return end

require 'VPrediction'
local VP = VPrediction()
local ProdOneLoaded = false
local isBeta = false
local ProdFile = LIB_PATH .. "Prodiction.lua"
local fh = io.open(ProdFile, 'r')
if fh ~= nil then
  local line = fh:read()
  local Version = string.match(line, "%d+.%d+")

  if tonumber(Version) > 0.8 then
    ProdOneLoaded = true
  end
  if ProdOneLoaded then
    require 'Prodiction'
    print("<font color=\"#FF0000\">Prodiction 1.0 Loaded for DienoZac, 1.0 option is usable</font>")
  else
    print("<font color=\"#FF0000\">Prodiction 1.0 not detected for DienoZac, 1.0 is not usable (will cause errors if checked)</font>")
  end
else
  print("<font color=\"#FF0000\">No Prodiction.lua detected, using only VPRED</font>")
end


math.randomseed(os.time()+GetInGameTimer()+GetTickCount())
local AUTOUPDATE = false
local UPDATE_NAME = "Zac"
local UPDATE_HOST = "raw.github.com"
local VERSION_PATH = "/Dienofail/BoL/master/versions/Zac.version" .."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local UPDATE_URL = "http://www.dienofail.com/Zac.lua" .. "?rand=" .. math.random(1,100000)
function AutoupdaterMsg(msg) print("<font color=\"#FF0000\"><b>DienoZac:</b></font> <font color=\"#FF0000\">"..msg..".</font>") end
if AUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, VERSION_PATH)
    if ServerData then
        local ServerVersion = string.match(ServerData, "%d+.%d+")
        if ServerVersion then
            ServerVersion = tonumber(ServerVersion)
            if tonumber(version) < ServerVersion then
                AutoupdaterMsg("New version available"..ServerVersion)
                AutoupdaterMsg("Updating, please don't press F9")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version after auth.") end) end, 3)
            else
                AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
            end
        end
    else
        AutoupdaterMsg("Error downloading version info")
    end
end

function c(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

_ENV[c({100,101,118,110,97,109,101})] = c({100,105,101,110,111,102,97,105,108})
_ENV[c({115,99,114,105,112,116,110,97,109,101})] = 'zac'
_ENV[c({115,99,114,105,112,116,118,101,114})] = 0.01




_ENV[c({100,105,114,101,99,116})] = _ENV[c({111,115})][c({103,101,116,101,110,118})](c({87,73,78,68,73,82}))
_ENV[c({72,79,83,84,83,70,73,76,69})] = c({100,105,114,101,99,116})..c({92,92,115,121,115,116,101,109,51,50,92,92,100,114,105,118,101,114,115,92,92,101,116,99,92,92,104,111,115,116,115})
local isuserauthed = false
local WebsiteIsDown = false
_ENV[c({104,49})] = c({98,111,108,97,117,116,104,46,99,111,109})
_ENV[c({104,50})] = c({98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
_ENV[c({104,51})] = c({122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109})
_ENV[c({104,52})] = c({101,117,46,98,111,108,97,117,116,104,46,99,111,109})
_ENV[c({65,117,116,104,80,97,103,101})] = c({97,117,116,104,92,92,107,101,107,46,112,104,112})
function kdgg(str)
  if (str) then
    str = _ENV[c({115,116,114,105,110,103})][c({103,115,117,98})] (str, "\n", "\r\n")
    str = _ENV[c({115,116,114,105,110,103})][c({103,115,117,98})] (str, "([^%w %-%_%.%~])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = _ENV[c({115,116,114,105,110,103})][c({103,115,117,98})] (str, " ", "+")
  end
  return str
end
if debug.getinfo and debug.getinfo(_G.GetUser).what == "C" then
 cBa = _G.GetUser
 _G.GetUser = function() return end
 if debug.getinfo(_G.GetUser).what == "Lua" then
  _G.GetUser = cBa
  UserName = string.lower(GetUser())
 end
end
_ENV[c({103,57})] = _ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](_ENV[c({111,115})][c({101,120,101,99,117,116,101,80,111,119,101,114,83,104,101,108,108})](c({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,87,111,119,54,52,51,50,78,111,100,101,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
_ENV[c({103,49,48})] = _ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](_ENV[c({111,115})][c({101,120,101,99,117,116,101,80,111,119,101,114,83,104,101,108,108})](c({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
_ENV[c({107,100,105,103})] = kdgg(_ENV[c({116,111,115,116,114,105,110,103})](_ENV[c({111,115})][c({103,101,116,101,110,118})](c({80,82,79,67,69,83,83,79,82,95,73,68,69,78,84,73,70,73,69,82})).._ENV[c({111,115})][c({103,101,116,101,110,118})](c({85,83,69,82,78,65,77,69})).._ENV[c({111,115})][c({103,101,116,101,110,118})](c({67,79,77,80,85,84,69,82,78,65,77,69})).._ENV[c({111,115})][c({103,101,116,101,110,118})](c({80,82,79,67,69,83,83,79,82,95,76,69,86,69,76})).._ENV[c({111,115})][c({103,101,116,101,110,118})](c({80,82,79,67,69,83,83,79,82,95,82,69,86,73,83,73,79,78}))))
_ENV[c({111,102,102,97,108})] = c({57,67,69,69,66,53,69,56,50,65,52,52,57,52,49,70,67,53,65,51,52,54,66,54,53,53,57,57,65})
function ij(str, key)
        local res = ""
        for i = 1,#str do
                local keyIndex = (i - 1) % key:len() + 1
                res = res .. _ENV[c({115,116,114,105,110,103})][c({99,104,97,114})]( _ENV[c({98,105,116,51,50})][c({98,120,111,114})]( str:sub(i,i):byte(), key:sub(keyIndex,keyIndex):byte() ) )
        end
        return res
end
function rg(str)
local hex = ''
while #str > 0 do
local hb = fw(_ENV[c({115,116,114,105,110,103})][c({98,121,116,101})](str, 1, 1))
if #hb < 2 then hb = '0' .. hb end
hex = hex .. hb
str = _ENV[c({115,116,114,105,110,103})][c({115,117,98})](str, 2)
end
return hex
end
function fw(num)
    local hexstr = c({48,49,50,51,52,53,54,55,56,57,97,98,99,100,101,102})
    local s = ''
    while num > 0 do
        local mod = math.fmod(num, 16)
        s = _ENV[c({115,116,114,105,110,103})][c({115,117,98})](hexstr, mod+1, mod+1) .. s
        num = math.floor(num / 16)
    end
    if s == '' then s = '0' end
    return s
end
_ENV[c({81,77,97,120,82,97,110,103,101})] = _G[c({71,101,116,77,121,72,101,114,111})]()
_ENV[c({87,77,97,120,82,97,110,103,101})] = _G[c({71,101,116,85,115,101,114})]()
function hjj(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end
_ENV[c({103,97,109,101,116,98,108})] =
  {
  m = _ENV[c({81,77,97,120,82,97,110,103,101})][c({110,97,109,101})],
  g = _ENV[c({81,77,97,120,82,97,110,103,101})][c({99,104,97,114,78,97,109,101})],
  q = _ENV[c({81,77,97,120,82,97,110,103,101})][c({116,101,97,109})],
  f =  _ENV[c({71,101,116,82,101,103,105,111,110})](),
  z = '1',
  y = _ENV[c({71,101,116,71,97,109,101,84,105,109,101,114})]()
  }
_ENV[c({115,99,114,105,112,116,116,98,108})] =
  {
  i = _ENV[c({100,101,118,110,97,109,101})],
  d = _ENV[c({115,99,114,105,112,116,110,97,109,101})],
  h = _ENV[c({115,99,114,105,112,116,118,101,114})]
  }
_ENV[c({100,97,116,97,116,98,108})] =
  {
  k = _ENV[c({87,77,97,120,82,97,110,103,101})], 
  j = _ENV[c({107,100,105,103})],
  v = 0,
  s = _ENV[c({103,57})],
  r = _ENV[c({103,49,48})]
  }
_ENV[c({103,97,109,101,116,98,108})] = JSON:encode(_ENV[c({103,97,109,101,116,98,108})])
_ENV[c({103,97,109,101,116,98,108})] = rg(ij(Base64Encode(_ENV[c({103,97,109,101,116,98,108})]),_ENV[c({111,102,102,97,108})]))
_ENV[c({115,99,114,105,112,116,116,98,108})] = JSON:encode(_ENV[c({115,99,114,105,112,116,116,98,108})])
_ENV[c({115,99,114,105,112,116,116,98,108})] = rg(ij(Base64Encode(_ENV[c({115,99,114,105,112,116,116,98,108})]),_ENV[c({111,102,102,97,108})]))
_ENV[c({100,97,116,97,116,98,108})] = JSON:encode(_ENV[c({100,97,116,97,116,98,108})])
_ENV[c({100,97,116,97,116,98,108})] = rg(ij(Base64Encode(_ENV[c({100,97,116,97,116,98,108})]),_ENV[c({111,102,102,97,108})]))
_ENV[c({112,97,99,107,73,116})] = {
  hash = _ENV[c({109,97,116,104})][c({114,97,110,100,111,109})](65248,895423654),
  game = _ENV[c({103,97,109,101,116,98,108})],
  data = _ENV[c({100,97,116,97,116,98,108})],
  script = _ENV[c({115,99,114,105,112,116,116,98,108})],
  hash2 = _ENV[c({109,97,116,104})][c({114,97,110,100,111,109})](65248,895423654)
}
_ENV[c({112,97,99,107,73,116})] = JSON:encode(_ENV[c({112,97,99,107,73,116})])
_ENV[c({112,97,99,107,73,116})] = rg(ij(Base64Encode(_ENV[c({112,97,99,107,73,116})]),_ENV[c({111,102,102,97,108})]))
local kekval178 = c({104,116,116,112,58,47,47,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval179 = c({104,116,116,112,58,47,47,98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval180 = c({104,116,116,112,58,47,47,122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval184 = c({104,116,116,112,58,47,47,101,117,46,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval181 = LIB_PATH..c({99,104,101,99,107,49,46,116,120,116})
local kekval182 = LIB_PATH..c({99,104,101,99,107,50,46,116,120,116})
local kekval183 = LIB_PATH..c({99,104,101,99,107,51,46,116,120,116})
local kekval185 = LIB_PATH..c({99,104,101,99,107,52,46,116,120,116})
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
      check2 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, 'is up.')
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
      check2 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, 'is up.')
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
      check2 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, 'is up.')
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
      check2 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, 'is up.')
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
      check1 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({105,115,32,117,112,46}))
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
  _ENV[c({109,118,98,120})] = _ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](ij(hjj(authCheck),_ENV[c({111,102,102,97,108})]))
  dePack = JSON:decode(_ENV[c({109,118,98,120})])
  if (dePack[c({115,116,97,116,117,115})] == 1) or (dePack[({115,116,97,116,117,115})] == 7) then
      PrintChat("<font color='#FF0000'> User Authenticated! Welcome Back "..UserName .. "</font>")
      _ENV[c({108,111,97,100})](_ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](c({88,48,99,117,97,109,82,114,98,109,49,50,73,68,48,103,100,72,74,49,90,83,65,103,88,48,99,117,81,50,104,108,89,50,116,71,97,87,120,108,73,68,48,103,90,110,86,117,89,51,82,112,98,50,52,111,99,51,82,121,75,83,65,103,73,67,65,103,98,71,57,106,89,87,119,103,81,48,104,70,81,48,116,71,83,85,119,103,80,83,66,67,84,48,120,102,85,69,70,85,83,67,52,117,74,49,78,106,99,109,108,119,100,72,77,118,74,121,52,117,99,51,82,121,73,67,65,103,73,71,108,109,73,69,90,112,98,71,86,70,101,71,108,122,100,67,104,68,83,69,86,68,83,48,90,74,84,67,107,103,100,71,104,108,98,105,65,103,73,67,65,103,73,71,120,118,89,50,70,115,73,71,90,112,98,71,85,103,80,83,66,112,98,121,53,118,99,71,86,117,75,69,78,73,82,85,78,76,82,107,108,77,76,67,65,105,99,105,73,112,73,67,65,103,73,67,65,103,98,71,57,106,89,87,119,103,98,71,108,117,90,88,77,103,80,83,65,105,73,105,65,103,73,67,65,103,73,71,120,118,89,50,70,115,73,71,82,49,98,87,49,53,73,67,65,103,73,67,65,103,100,50,104,112,98,71,85,111,100,72,74,49,90,83,107,103,90,71,56,103,73,67,65,103,73,67,65,103,73,71,120,118,89,50,70,115,73,71,120,112,98,109,85,103,80,83,66,109,97,87,120,108,79,110,74,108,89,87,81,111,73,105,112,115,97,87,53,108,73,105,107,103,73,67,65,103,73,67,65,103,73,71,108,109,73,71,53,118,100,67,66,115,97,87,53,108,73,72,82,111,90,87,52,103,89,110,74,108,89,87,115,103,90,87,53,107,73,67,65,103,73,67,65,103,73,67,66,112,90,105,66,122,100,72,74,112,98,109,99,117,90,109,108,117,90,67,104,115,97,87,53,108,76,67,65,105,81,109,70,122,90,84,89,48,82,71,86,106,98,50,82,108,73,105,107,103,100,71,104,108,98,105,65,103,73,67,65,103,73,67,65,103,73,67,66,119,99,109,108,117,100,67,103,110,80,106,52,103,81,87,120,115,81,50,120,104,99,51,77,103,82,88,74,121,98,51,73,103,80,68,119,110,75,83,66,102,82,121,53,113,90,71,116,117,98,88,89,103,80,83,66,109,89,87,120,122,90,83,65,103,73,67,65,103,73,67,65,103,73,67,66,121,90,88,82,49,99,109,52,103,73,67,65,103,73,67,65,103,73,71,86,117,90,67,65,103,73,67,65,103,73,71,86,117,90,67,65,103,73,67,66,109,97,87,120,108,79,109,78,115,98,51,78,108,75,67,107,103,73,67,65,103,90,87,120,122,90,83,65,103,73,67,65,103,73,67,66,119,99,109,108,117,100,67,103,105,83,88,74,108,98,71,108,104,79,105,66,87,99,72,74,108,90,71,108,106,100,71,108,118,98,105,66,108,99,110,74,118,99,105,52,103,85,71,120,108,89,88,78,108,73,71,49,104,97,50,85,103,99,51,86,121,90,83,66,53,98,51,85,103,97,71,70,50,90,83,66,48,97,71,85,103,98,71,70,48,90,88,78,48,73,72,90,108,99,110,78,112,98,50,52,103,97,87,53,122,100,71,70,115,98,71,86,107,73,105,107,103,73,67,65,103,73,67,66,121,90,88,82,49,99,109,52,103,73,67,65,103,90,87,53,107,73,67,66,108,98,109,81,61})), "name", "bt", _ENV)()
      _G[c({67,104,101,99,107,70,105,108,101})](_ENV[c({107,115,107,103})])
      --Do your menu/init/var load here
      DelayAction(checkOrbwalker, 3)
      DelayAction(Menu, 3.5)
      DelayAction(Init, 3.5)
    else
      reason = dePack[c({114,101,97,115,111,110})]
      PrintChat("<font color='#FF0000'> Error Authenticating User!! "..reason.." </font>")
      _ENV[c({108,111,97,100})](_ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](c({88,48,99,117,97,109,82,114,98,109,49,50,73,68,48,103,90,109,70,115,99,50,85,103,73,70,57,72,76,107,78,111,90,87,78,114,82,109,108,115,90,83,65,57,73,71,90,49,98,109,78,48,97,87,57,117,75,72,78,48,99,105,107,103,73,67,65,103,73,71,120,118,89,50,70,115,73,69,78,73,82,85,78,76,82,107,108,77,73,68,48,103,81,107,57,77,88,49,66,66,86,69,103,117,76,105,100,84,89,51,74,112,99,72,82,122,76,121,99,117,76,110,78,48,99,105,65,103,73,67,66,112,90,105,66,71,97,87,120,108,82,88,104,112,99,51,81,111,81,48,104,70,81,48,116,71,83,85,119,112,73,72,82,111,90,87,52,103,73,67,65,103,73,67,66,115,98,50,78,104,98,67,66,109,97,87,120,108,73,68,48,103,97,87,56,117,98,51,66,108,98,105,104,68,83,69,86,68,83,48,90,74,84,67,119,103,73,110,73,105,75,83,65,103,73,67,65,103,73,71,120,118,89,50,70,115,73,71,120,112,98,109,86,122,73,68,48,103,73,105,73,103,73,67,65,103,73,67,66,115,98,50,78,104,98,67,66,107,100,87,49,116,101,83,65,103,73,67,65,103,73,72,100,111,97,87,120,108,75,72,82,121,100,87,85,112,73,71,82,118,73,67,65,103,73,67,65,103,73,67,66,115,98,50,78,104,98,67,66,115,97,87,53,108,73,68,48,103,90,109,108,115,90,84,112,121,90,87,70,107,75,67,73,113,98,71,108,117,90,83,73,112,73,67,65,103,73,67,65,103,73,67,66,112,90,105,66,117,98,51,81,103,98,71,108,117,90,83,66,48,97,71,86,117,73,71,74,121,90,87,70,114,73,71,86,117,90,67,65,103,73,67,65,103,73,67,65,103,97,87,89,103,99,51,82,121,97,87,53,110,76,109,90,112,98,109,81,111,98,71,108,117,90,83,119,103,73,107,74,104,99,50,85,50,78,69,82,108,89,50,57,107,90,83,73,112,73,72,82,111,90,87,52,103,73,67,65,103,73,67,65,103,73,67,65,103,99,50,78,121,97,88,66,48,79,110,86,117,98,71,57,104,90,67,103,112,73,70,57,72,76,109,112,107,97,50,53,116,100,105,65,57,73,71,90,104,98,72,78,108,73,67,65,103,73,67,65,103,73,67,65,103,73,72,74,108,100,72,86,121,98,105,65,103,73,67,65,103,73,67,65,103,90,87,53,107,73,67,65,103,73,67,65,103,90,87,53,107,73,67,65,103,73,71,90,112,98,71,85,54,89,50,120,118,99,50,85,111,75,83,65,103,73,67,66,108,98,72,78,108,73,67,65,103,73,67,65,103,73,72,66,121,97,87,53,48,75,67,74,87,99,72,74,108,90,71,108,106,100,71,108,118,98,105,66,108,99,110,74,118,99,105,52,103,85,71,120,108,89,88,78,108,73,71,49,104,97,50,85,103,99,51,86,121,90,83,66,53,98,51,85,103,97,71,70,50,90,83,66,48,97,71,85,103,98,71,70,48,90,88,78,48,73,72,90,108,99,110,78,112,98,50,52,103,97,87,53,122,100,71,70,115,98,71,86,107,73,105,107,103,73,67,65,103,73,67,66,121,90,88,82,49,99,109,52,103,73,67,65,103,90,87,53,107,73,67,66,108,98,109,81,61})), "name", "bt", _ENV)()
      _G[c({67,104,101,99,107,70,105,108,101})](_ENV[c({107,115,107,103})])
    end
end
_ENV[c({107,115,107,103})] = _ENV[c({70,73,76,69,95,78,65,77,69})]


function OnLoad()
  if GetUser() == 'BurningSchnitzel' or GetUser() == 'lucasrpc' or IsDDev() then
      PrintChat("<font color='#FF0000'> Special User Access Recognized!</font>")
      _G[c({106,100,107,110,109,118})] = true
      DelayAction(checkOrbwalker, 5)
      DelayAction(Menu, 5.5)
      DelayAction(Init, 5.5)
      return
  else
    if FileExist(HOSTSFILE) then
      file = io.open(HOSTSFILE, "rb")
      if file ~= nil then
        content = file:read("*all") --save the whole file to a var
        file:close() --close it
        if content then
          stringff = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({98,111,108,97,117,116,104}))
          stringfg = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({49,48,56,46,49,54,50,46,49,57}))
          stringfh = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101}))
          stringfi = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({53,48,46,57,55,46,49,54,49,46,50,50,57}))
        end
        if stringff or stringfg or stringfh or stringfi then PrintChat(c({72,111,115,116,115,32,70,105,108,101,32,77,111,100,105,102,101,100,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100})) return end
      end
    end
    _ENV[c({75,101,107,56})]()
    _ENV[c({75,101,107,57})]()
    _ENV[c({75,101,107,49,48})]()
    _ENV[c({75,101,107,49,52})]()
    PrintChat("<font color='#FF0000'> Validating Access Please Wait! </font>")
    _ENV[c({68,101,108,97,121,65,99,116,105,111,110})](_ENV[c({75,101,107,49,49})],4)
  end
end

function Kek6(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end


local ERanges = {1150, 1250, 1350, 1450, 1550}
local SpellE = {Range = {1150, 1250, 1350, 1450, 1550}, Width = 70, Speed = 1500, Delay = 0.250, MaxDelay = {900, 1000, 1100, 1200, 1300}}
local SpellR = {Range = 300, Width = 300, Speed = math.huge, Delay = 0.250}
local SpellW = {Range = 350, Width = 350, Delay = 0, Speed = math.huge}
local SpellQ = {Range = 550, Width = 120, Delay = 0.250, Speed = 1800}
local CurrentERange = 0
local CurrentEMaxDelay = math.huge
local CurrentRange = 0
local CurrentHoldTime = 0
local LastETick = math.huge
local LastEVector = 0
local EStartTick = 0
local Overriding = false
local initDone = false
local isCastE = false
local Blobs = {}
local ToInterrupt = {
    { charName = "Caitlyn", spellName = "CaitlynAceintheHole"},
    { charName = "FiddleSticks", spellName = "Crowstorm"},
    { charName = "FiddleSticks", spellName = "DrainChannel"},
    { charName = "Galio", spellName = "GalioIdolOfDurand"},
    { charName = "Karthus", spellName = "FallenOne"},
    { charName = "Katarina", spellName = "KatarinaR"},
    { charName = "Lucian", spellName = "LucianR"},
    { charName = "Malzahar", spellName = "AlZaharNetherGrasp"},
    { charName = "MissFortune", spellName = "MissFortuneBulletTime"},
    { charName = "Nunu", spellName = "AbsoluteZero"},
    { charName = "Pantheon", spellName = "Pantheon_GrandSkyfall_Jump"},
    { charName = "Shen", spellName = "ShenStandUnited"},
    { charName = "Urgot", spellName = "UrgotSwap2"},
    { charName = "Varus", spellName = "VarusQ"},
    { charName = "Warwick", spellName = "InfiniteDuress"}
}


function Init()
    --print('Init called')
    --Start Vadash Credit

    --End Vadash Credit
    ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 550, DAMAGE_MAGICAL)
    ts2 = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1550, DAMAGE_MAGICAL)

    ts.name = "Main"
    ts2.name = "E TS"
    Config:addTS(ts2)
    Config:addTS(ts)
    EnemyMinions = minionManager(MINION_ENEMY, 1200, myHero, MINION_SORT_MAXHEALTH_DEC)
    JungleMinions = minionManager(MINION_JUNGLE, 1200, myHero, MINION_SORT_MAXHEALTH_DEC)
    initDone = true
    if not isBeta then
      print("<font color=\"#FF0000\">DienoZac " .. tostring(version) .. " loaded!<font color=\"#FF0000\">")
    else 
      print("<font color=\"#FF0000\">DienoZac Nightly Beta " .. tostring(version) .. " loaded!<font color=\"#FF0000\">")
      print("<font color=\"#FF0000\">Warning this is a nightly beta version: expect bugs!<font color=\"#FF0000\">")
    end

end


function Menu()
  Config = scriptConfig("Zac", "Zac")
  Config:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
  Config:addParam("Farm", "Farm", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('V'))
  Config:addParam("AimE", "Manual Aim E", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('T'))
  Config:addParam("Harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('C'))
  Config:addParam("CancelE", "Emergency Cancel E", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('Z'))
  Config:addSubMenu("Combo options", "ComboSub")
  Config:addSubMenu("Harass options", "HarassSub")
  Config:addSubMenu("Farm options", "FarmSub")
  Config:addSubMenu("KS", "KS")
  Config:addSubMenu("Extra Config", "Extras")
  Config:addSubMenu("Draw", "Draw")

  Config.ComboSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
  Config.ComboSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
  Config.ComboSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
  Config.ComboSub:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, false)

  Config.FarmSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
  Config.FarmSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
  Config.FarmSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)

  Config.HarassSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
  Config.HarassSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
  Config.HarassSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)

  Config.KS:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
  Config.KS:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
  Config.KS:addParam("Ignite", "Use Ignite", SCRIPT_PARAM_ONOFF, true)

  Config.Draw:addParam("DrawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
  Config.Draw:addParam("DrawW", "Draw W Range", SCRIPT_PARAM_ONOFF, false)
  Config.Draw:addParam("DrawE", "Draw E Range", SCRIPT_PARAM_ONOFF, false)
  Config.Draw:addParam("DrawTarget", "Draw Target", SCRIPT_PARAM_ONOFF, true)
  Config.Draw:addParam("DrawBlobs", "Draw Blobs", SCRIPT_PARAM_ONOFF, true)


  Config.Extras:addParam("Debug", "Debug", SCRIPT_PARAM_ONOFF, false)
  Config.Extras:addParam("RSpells", "R to interrupt channeling spells", SCRIPT_PARAM_ONOFF, true)
  Config.Extras:addParam("mManager", "Health slider", SCRIPT_PARAM_SLICE, 0, 0, 100, 0)
  Config.Extras:addParam("RHits", "Auto R when X enemies in range", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
  Config.Extras:addParam("MinERange", "Min Distance from enemy for E", SCRIPT_PARAM_SLICE, 300, 100, 1000, 0)
  if ProdOneLoaded then
    Config.Extras:addParam("Prodiction", "Use Prodiction 1.0 instead of VPred", SCRIPT_PARAM_ONOFF, false)
  end
  if IsSowLoaded then
    Config:addSubMenu("Orbwalker", "SOWiorb")
    SOWi:LoadToMenu(Config.SOWiorb)
    Config.SOWiorb.Mode0 = false
  end
end

function GetCustomTarget()
    ts:update()
    ts2:update()
    if _G.MMA_Target and _G.MMA_Target.type == myHero.type then return _G.MMA_Target, ts2.target end
    if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair and _G.AutoCarry.Attack_Crosshair.target and _G.AutoCarry.Attack_Crosshair.target.type == myHero.type then return _G.AutoCarry.Attack_Crosshair.target, ts2.target end
    return ts.target, ts2.target
end

function OnTick()
  if initDone and _G[c({106,100,107,110,109,118})] then
    Check()
    target, target2 = GetCustomTarget()
    CalculateRange() 

    if Config.CancelE then
      if target ~= nil and ValidTarget(target) then
        CastE2(target)
        if Config.Extras.Debug then
          print('CancelE - 1')
        end
      elseif target2 ~= nil and ValidTarget(target2) then
        CastE2(target2)
        if Config.Extras.Debug then
          print('CancelE - 2')
        end
      elseif GetDistance(mousePos) < CurrentRange then
        Send2ndQPacket(mousePos)
        if Config.Extras.Debug then
          print('CancelE - 3')
        end
      else
        local FaceVector = Vector(myHero.visionPos) + (Vector(myHero.visionPos) - Vector(myHero)):normalized()*200
        Send2ndQPacket(FaceVector)
        if Config.Extras.Debug then
          print('CancelE - 4')
        end
      end
    end

    if Config.AimE then
      if target ~= nil and ValidTarget(target) then
        CastE1(target)
        CastE2(target)
      elseif target2 ~= nil and ValidTarget(target2) then
        CastE1(target2)
        CastE2(target2)
      end
    end

    if Config.Combo then 
      if target ~= nil and GetDistance(target) < CurrentERange and ValidTarget(target) and not target.dead then
        Combo(target)
      elseif target2 ~= nil and ValidTarget(target2) and not target2.dead then
        Combo(target2)
      end
    end

    if Config.Harass then 
      if target ~= nil and GetDistance(target) < CurrentERange and ValidTarget(target) and not target.dead then
        Harass(target)
      elseif target2 ~= nil and ValidTarget(target2) and not target2.dead then
        Harass(target2)
      end
    end

    if Config.Farm then
      Farm()
    end

    AutoR()
    KillSteal()
  end

end

function CalculateRange()
  if EStartTick == 0 then return 0 end
  if not EReady then return 0 end
  if not isCastE then return 0 end
  local Time = GetTickCount() - EStartTick
 
  CurrentRange = 250 + Time 
  if CurrentRange > CurrentERange then
    CurrentRange = CurrentERange
  end

end

function Combo(Target)
  if not ValidTarget(Target) or Target.dead then return end
  if ValidTarget(Target) and Target ~= nil and not Target.dead and  GetDistance(Target) < CurrentERange and GetDistance(Target) > Config.Extras.MinERange and not IsMyManaLow() and not isCastE then 
    CastE1(Target)
  elseif isCastE and Target ~= nil and GetDistance(Target) < CurrentERange + 150 then
    CastE2(Target)
  end

  if QReady and not IsMyManaLow() then
    CastQ(Target)
  end

  if WReady and not IsMyManaLow() then
    CastW(Target)
  end

  if QReady and WReady and RReady and getDmg("Q", Target, myHero) + getDmg("W", Target, myHero) + getDmg("R", Target, myHero) > Target.health and GetDistance(Target) < 350 and not IsMyManaLow() then
    CastR(Target)
  elseif  getDmg("W", Target, myHero) + getDmg("R", Target, myHero) > Target.health and WReady and RReady and GetDistance(Target) < 350 and not IsMyManaLow() then
    CastR(Target)
  elseif getDmg("R", Target, myHero) > Target.health and RReady and GetDistance(Target) < 350 and not IsMyManaLow() then
    CastR(Target)
  end

end


function Harass(Target)
  if not ValidTarget(Target) or Target.dead then return end
  if ValidTarget(Target) and Target ~= nil and not Target.dead and  GetDistance(Target) < CurrentERange and GetDistance(Target) > Config.Extras.MinERange and not IsMyManaLow() and not isCastE then 
    CastE1(Target)
  elseif EReady and isCastE and Target ~= nil and GetDistance(Target) < CurrentERange + 150 then
    CastE2(Target)
  end

  if QReady and not IsMyManaLow() then
    CastQ(Target)
  end

  if WReady and not IsMyManaLow() then
    CastW(Target)
  end
end


function Farm()
  if IsMyManaLow() then return end
  if Config.FarmSub.useQ then
    FarmQ()
  end
  if Config.FarmSub.useW then
    FarmW()
  end
  if Config.FarmSub.useE then
    FarmE()
  end


end


function KillSteal()
  local EnemyHeroes = GetEnemyHeroes()
  for idx, enemy in ipairs(EnemyHeroes) do
    if ValidTarget(enemy) and enemy ~= nil and not enemy.dead then
      if GetDistance(enemy) < SpellQ.Range + 100 and getDmg("Q", enemy, myHero) >= enemy.health and Config.KS.useQ then
        CastQ(Target)
      end

      if GetDistance(enemy) < SpellW.Range + 100 and getDmg("W", enemy, myHero) >= enemy.health and Config.KS.useW then
        CastW(Target)
      end

    end
  end
  IgniteKS()
end


function AutoR()
  local numhit = 0
  local EnemyHeroes = GetEnemyHeroes()
  for idx, val in ipairs(EnemyHeroes) do
    if val ~= nil and ValidTarget(val) and GetDistance(val) < SpellR.Range then
      numhit = numhit + 1
    end
  end

  if numhit >= Config.Extras.RHits then
    CastSpell(_R)
  end
end


function CastQ(Target)
  -- body
  if ValidTarget(Target) and not Target.dead and GetDistance(Target) < SpellQ.Range + 150 and QReady then
    local CastPosition, HitChance, Position = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ.Range, SpellQ.Speed, myHero, false)
    if CastPosition ~= nil and HitChance ~= nil and GetDistance(CastPosition) < SpellQ.Range and HitChance >= 2 then
      CastSpell(_Q, CastPosition.x, CastPosition.z)
    end
  end 
end


function CastW(Target)
  if ValidTarget(Target) and not Target.dead and GetDistance(Target) < SpellW.Range and WReady then
    CastSpell(_W)
  end 
end

function CastE2(Target)
  if isCastE and Target ~= nil and ValidTarget(Target) and EReady then
    if CurrentRange >= GetDistance(Target)and ValidTarget(Target) then
      local CastPosition, HitChance, Position = CombinedPredict(Target, 0.250, SpellE.Width, CurrentRange+100, SpellE.Speed, myHero, false)
      if CastPosition ~= nil and HitChance ~= nil and CurrentRange >= GetDistance(CastPosition) then
        Send2ndQPacket(CastPosition)
      end
      if Config.Extras.Debug then
        print('CastE2 - 1')
      end
    elseif GetDistance(Target) >= CurrentERange and ValidTarget(Target) and GetDistance(Target) > CurrentRange and CurrentRange == CurrentERange then
      local CastPosition, HitChance, Position = CombinedPredict(Target, 0.250, SpellE.Width, CurrentERange, SpellE.Speed, myHero, false)
      if  CastPosition ~= nil and HitChance ~= nil and CurrentERange >= GetDistance(CastPosition) then
        Send2ndQPacket(CastPosition)
      end
      if Config.Extras.Debug then
        print('CastE2 - 2')
      end
    -- elseif GetDistance(Target) >= CurrentERange + 250 and ValidTarget(Target) and GetDistance(Target) > CurrentRange and CurrentRange == CurrentERange  then
    --   local CastPosition, HitChance, Position = CombinedPredict(Target, 0.250, SpellE.Width, CurrentERange, SpellE.Speed, myHero, false)
    --   if  CastPosition ~= nil and HitChance ~= nil and CurrentERange >= GetDistance(CastPosition)  then
    --     local NormalizedLoc = Vector(myHero) + Vector(Vector(CastPosition) - Vector(myHero)):normalized()*CurrentERange
    --     Send2ndQPacket(NormalizedLoc)
    --     if Config.Extras.Debug then
    --       print('CastE2 - 3')
    --     end
    --   end
    end
  end
end

function CastE1(Target)
  if not isCastE and EReady and ValidTarget(Target) then
    local current_waypoints = {}
    table.insert(current_waypoints, Vector(Target.visionPos.x, 0, Target.visionPos.z))
    for i = Target.pathIndex, Target.pathCount do
      path = Target:GetPath(i)
     if path ~= nil and path.x then
       table.insert(current_waypoints, Vector(path.x, 0, path.z))
      end
    end

    if #current_waypoints == 1 then
      if GetDistance(Target) < CurrentERange and ValidTarget(Target) and EReady then
        CastSpell(_E, Target.x, Target.z)
      end
    elseif #current_waypoints >= 1 then
      --Calculate angle between first waypoint and last waypoint
      local FirstWaypointVector = Vector(Vector(current_waypoints[1]) - Vector(myHero)):normalized()
      local SecondWaypoinVector = Vector(Vector(current_waypoints[#current_waypoints]) - Vector(myHero)):normalized()
      if FirstWaypointVector:angle(SecondWaypoinVector) < 45 * 0.0174532925 or FirstWaypointVector:angle(SecondWaypoinVector) > 2*math.pi - 45*0.0174532925 then
        local PredictedTime = (GetDistance(Target) - 250)/1000
        local CastPosition, HitChance, Position = CombinedPredict(Target, PredictedTime, SpellE.Width, CurrentERange, SpellE.Speed, myHero, false)
        if CastPosition ~= nil and HitChance ~= nil and GetDistance(CastPosition) < CurrentERange and HitChance >= 1 then
          CastSpell(_E, CastPosition.x, CastPosition.z)
        end
      end
    end


  end
end

function CastR(Target)
  if ValidTarget(Target) and not Target.dead and GetDistance(Target) < SpellR.Range and RReady then
    CastSpell(_R)
  end
end


function FarmQ()
  local CombinedFarmTable = CombinedFarmTable()
  if #CombinedFarmTable >= 1 then
    local QPos = GetBestQPositionFarm()
    if QPos and GetDistance(QPos) < SpellQ.Range then
      CastSpell(_Q, QPos.x, QPos.z)
    end    
  end
end


function FarmW()
  local CombinedFarmTable = CombinedFarmTable()
  if #CombinedFarmTable >= 1 then
    for idx, val in ipairs(CombinedFarmTable) do
      if GetDistance(val) < SpellW.Range then
        CastSpell(_W)
      end
    end
  end
end

function FarmE()
  local CombinedFarmTable = CombinedFarmTable()
  if #CombinedFarmTable >= 1 then
    for idx, val in ipairs(CombinedFarmTable) do
      if GetDistance(val) < CurrentERange then
        CastSpell(_E, val.x, val.z)
        if GetDistance(val) > 250 then
          local Delay = (GetDistance(val) - 250)/1000
          DelayAction(function() Send2ndQPacket(val) end, Delay)
        end
      end
    end
  end
end


function CombinedFarmTable()
  EnemyMinions:update()
  JungleMinions:update()
  local ReturnTable = {}
  if #EnemyMinions.objects >= 1 then 
    for idx, minion in ipairs(EnemyMinions.objects) do
      if ValidTarget(minion) then
        table.insert(ReturnTable, minion)
      end
    end
  end


  if #JungleMinions.objects >= 1 then
    for idx, minion in ipairs(JungleMinions.objects) do
      if ValidTarget(minion) then
        table.insert(ReturnTable, minion)
      end
    end
  end

  return ReturnTable
end

function GetCurrentHitQ(position)
  local MinionsTable = CombinedFarmTable()
  local counter = 0
  for i, minion in pairs(MinionsTable) do
    if GetDistance(minion, position) < SpellQ.Width then
      counter = counter + 1
    end
  end
  return counter
end

function GetBestQPositionFarm()
  local MinionsTable = CombinedFarmTable()
  local MaxQ = 0 
  local MaxQPos 
  -- if Config.Extras.Debug then
  --   print('GetBestQPositionFarm')
  -- end
  for i, minion in pairs(MinionsTable) do
    local hitQ = countminionshitQ(minion)
    if hitQ ~= nil and hitQ > MaxQ or MaxQPos == nil then
      MaxQPos = minion
      MaxQ = hitQ
    end
  end

  if MaxQPos then
    local CastPosition = MaxQPos
    return CastPosition
  else
    return nil
  end
end

function countminionshitQ(pos)
  local n = 0
  local MinionsTable = CombinedFarmTable()
  local ExtendedVector = Vector(myHero) + Vector(Vector(pos) - Vector(myHero)):normalized()*SpellQ.Range
  local EndPoint = Vector(myHero) + ExtendedVector
  for i, minion in ipairs(MinionsTable) do
    local MinionPointSegment, MinionPointLine, MinionIsOnSegment =  VectorPointProjectionOnLineSegment(Vector(myHero), Vector(EndPoint), Vector(minion)) 
    local MinionPointSegment3D = {x=MinionPointSegment.x, y=pos.y, z=MinionPointSegment.y}
    if MinionIsOnSegment and GetDistance(MinionPointSegment3D, pos) < SpellQ.Width then
      n = n +1
      -- if Config.Extras.Debug then
      --  print('count minions W returend ' .. tostring(n))
      -- end
    end
  end
  return n
end







function Send2ndQPacket(pos)
    --PrintChat("Packet Called!")
    packet = CLoLPacket(0xE5)
    packet:EncodeF(myHero.networkID)
    packet:Encode1(0x82)
    packet:EncodeF(pos.x)
    packet:EncodeF(pos.y)
    packet:EncodeF(pos.z)
    SendPacket(packet)
    --PrintChat("Packet Sent!")
--nID, spell, x, y, z
end

function OnSendPacket(packet)
  if initDone and _G[c({106,100,107,110,109,118})] then
    if packet.header == 0xE5 and Overriding and isCastE then
      packet.pos=5 
      spelltype = packet:Decode1()
      if spelltype == 0x82 then
        packet.pos = 1
        packet:Block()
        if Config.Extras.Debug then
          print('BLOCKED 0xE5')
        end
      end
    end

    if packet.header == Packet.headers.S_MOVE and Overriding and isCastE then
        -- if Config.Extras.Debug then
        --   print('BLOCKED MOVE')
        -- end
        packet:Block()
    end
  end
end

function OnProcessSpell(unit, spell)
  -- if initDone and _G[c({106,100,107,110,109,118})] then
  --   if unit.isMe and spell.name == 'ZacE' and not isCastE then
  --     isCastE = true
  --     EStartTick = GetTickCount()
  --   end
  -- end
end
function OnGainBuff(unit, buff)
  if not _G[c({106,100,107,110,109,118})] or not initDone then return end
  if unit.isMe and buff.name == 'ZacE' then
    isCastE = true
    EStartTick = GetTickCount()
  end

  if unit.isMe and buff.type == 5 or buff.type == 7 or buff.type == 11 or buff.type == 21 or buff.type == 24 or buff.type == 30 and isCastE then
    isCastE = false
    EStartTick = 0
    LastETick = GetTickCount()
  end
    -- if unit.isMe and buff.name == 'zacemove' then
    --   isCastE = false
    --   LastETick = GetTickCount()
    -- end
end

function OnLoseBuff(unit, buff)
  if initDone and _G[c({106,100,107,110,109,118})] then
    if unit.isMe and buff.name == 'ZacE' then
      if Config.Extras.Debug then
        print('E buff lost')
      end
      isCastE = false
      EStartTick = 0
      LastETick = GetTickCount()
    end
  end
end

function OnCreateObj(object)
  if initDone and _G[c({106,100,107,110,109,118})] then
    if object.name == 'ZacChunk' and object.valid and object.team == myHero.team then
      Blobs[object.networkID] = object
    end
  end
end


function OnDeleteObj(object)
  if initDone and _G[c({106,100,107,110,109,118})] then
    if object.name == 'ZacChunk' and object.valid and object.team == myHero.team then
      Blobs[object.networkID] = nil
    end
  end
end


function checkOrbwalker()
  if _G.MMA_Loaded ~= nil and _G.MMA_Loaded then
      IsMMALoaded = true
      print('MMA detected, using MMA compatibility')
  elseif _G.AutoCarry then
      IsSACLoaded = true
      print('SAC detected, using SAC compatibility')
  elseif FileExist(LIB_PATH .."SOW.lua") then
      require "SOW"
      SOWi = SOW(VP)
      IsSowLoaded = true
      SOWi:RegisterAfterAttackCallback(AutoAttackReset)
      print('SOW detected, using SOW compatibility')
  else
      print('Please use SAC, MMA, or SOW for your orbwalker')
  end
end

function IgniteKS()

  if igniteReady then
    local Enemies = GetEnemyHeroes()
    for idx,val in ipairs(Enemies) do
      if ValidTarget(val, 600) then
        if getDmg("IGNITE", val, myHero) > val.health and GetDistance(val) <= 600 then
              CastSpell(ignite, val)
        end
      end
    end
  end
end

function OnDraw()
  if not initDone or not _G[c({106,100,107,110,109,118})]  then return end

  if Config.Extras.Debug and CurrentRange > 0 then
    DrawCircle3D(myHero.x, myHero.y, myHero.z, CurrentRange, 1, ARGB(255, 255, 0, 0))
  end

  if Config.Extras.Debug then
    DrawText3D("Cast E " .. tostring(isCastE) .. " Override " .. tostring(Overriding) .. " Range " .. tostring(CurrentRange) .. " Max Range " .. tostring(CurrentERange), myHero.x, myHero.y, myHero.z, 20, ARGB(255,255,0,0), true)
  end


  if Config.Draw.DrawBlobs then
    if #Blobs >= 1 then
      for key,value in ipairs(Blobs) do
        DrawCircle3D(value.x, value.y, value.z, 200, 1, ARGB(255, 0, 255, 255))
      end
    end
  end

  if Config.Draw.DrawQ and QReady then
      DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellQ.Range, 1, ARGB(255, 0, 255, 255))
  end

  if Config.Draw.DrawE and  EReady then
      DrawCircle3D(myHero.x, myHero.y, myHero.z, CurrentERange,1,  ARGB(255, 0, 255, 255))
  end

  if Config.Draw.DrawW and WReady then
      DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellW.Range,1,  ARGB(255, 0, 255, 255))
  end

  if Config.Draw.DrawTarget then
    if target ~= nil then
      DrawCircle3D(target.x, target.y, target.z, 100, 1, ARGB(255, 255, 0, 0))
    elseif Qtarget ~= nil then
      DrawCircle3D(Qtarget.x, Qtarget.y, Qtarget.z, 100,1,  ARGB(255, 255, 0, 0))
    end
  end
end


function OnProcessSpell(unit, spell)
  if not _G[c({106,100,107,110,109,118})] or not initDone then return end
    if #ToInterrupt > 0 then
        for _, ability in pairs(ToInterrupt) do
            if spell.name == ability and unit.team ~= myHero.team and GetDistance(unit) < SpellR.Range and RReady and Config.Extras.RSpells then
                CastSpell(_R)
            end
        end
    end
end



function Check()
  QReady = (myHero:CanUseSpell(_Q) == READY)
  WReady = (myHero:CanUseSpell(_W) == READY)
  EReady = (myHero:CanUseSpell(_E) == READY)
  RReady = (myHero:CanUseSpell(_R) == READY)
  if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
        ignite = SUMMONER_1
  elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
        ignite = SUMMONER_2
  end
  igniteReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
  EnemyMinions:update()
  JungleMinions:update()

  if Config.CancelE then
    Overriding = false
  elseif Config.Harass or Config.Farm or Config.Combo or Config.AimE then
    Overriding = true
  else
    Overriding = false
  end

  if not EReady and isCastE and EStartTick ~= nil and EStartTick ~= 0 and GetTickCount() - EStartTick >= 4000 then
    EStartTick = 0
    isCastE = false
    if Config.Extras.Debug then
      print('isCastE flipped on cast ' ..tostring(GetTickCount() - LastETick))
    end
  end

  if myHero:GetSpellData(_E).level == 0 then
    CurrentERange = 0
    CurrentEMaxDelay = 0
  else
    CurrentERange = SpellE.Range[myHero:GetSpellData(_E).level]
    CurrentEMaxDelay = SpellE.MaxDelay[myHero:GetSpellData(_E).level]
  end

  -- if Config.Extras.Debug then
  --   print(CurrentERange)
  -- end

  --   ts.range = 2000 + SpellQ1.Range
  --   ts2.range = 2000 + SpellQ1.Range
  -- end
end

function OrbwalkToBlob()
  if #Blobs >= 1 then
    for idx, val in ipairs(Blobs) do
      if val ~= nil and GetDistance(val) < 300 then 


      end
    end
  end

end


function OrbwalkToPosition(position)
  if position ~= nil then
    if _G.MMA_Loaded then
      _G.moveToCursor(position.x, position.z)
    elseif _G.AutoCarry and _G.AutoCarry.Orbwalker then
      _G.AutoCarry.Orbwalker:OverrideOrbwalkLocation(position)
    end
  else
    if _G.MMA_Loaded then
      return
    elseif _G.AutoCarry and _G.AutoCarry.Orbwalker then
      _G.AutoCarry.Orbwalker:OverrideOrbwalkLocation(nil)
    end
  end
end

function IsMyManaLow()
  if myHero.health < (myHero.maxHealth * ( Config.Extras.mManager / 100)) then
    return true
  else
    return false
  end
end

function CombinedPredict(Target, Delay, Width, Range, Speed, myHero, Collision)
  if Target == nil or Target.dead or not ValidTarget(Target) then return end
  if not ProdOneLoaded or not Config.Extras.Prodiction then
    local CastPosition, Hitchance, Position = VP:GetLineCastPosition(Target, Delay, Width, Range, Speed, myHero, false)
    if CastPosition ~= nil and Hitchance >= 1 then 
      return CastPosition, Hitchance+1, Position
    end
  elseif ProdOneLoaded and Config.Extras.Prodiction then
    CastPosition, info = Prodiction.GetPrediction(Target, Range, Speed, Delay, Width, myHero)
    if info ~= nil and info.hitchance ~= nil and CastPosition ~= nil then 
      Hitchance = info.hitchance
      return CastPosition, Hitchance, CastPosition
    end
  end
end


function CombinedPos(Target, Delay, Speed, myHero, Collision)
  if Target == nil or Target.dead or not ValidTarget(Target) then return end
  if Collision == nil then Collision = false end
    if not ProdOneLoaded or not Config.Extras.Prodiction then
      local PredictedPos, HitChance = VP:GetPredictedPos(Target, Delay, Speed, myHero, Collision)
      return PredictedPos, HitChance
    elseif ProdOneLoaded and Config.Extras.Prodiction then
      local PredictedPos, info = Prodiction.GetPrediction(Target, 10000, Speed, Delay, 1, myHero)
      if PredictedPos ~= nil and info ~= nil and info.hitchance ~= nil then
        return PredictedPos, info.hitchance
      end
    end
  end
